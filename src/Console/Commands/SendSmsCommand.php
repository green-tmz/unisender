<?php

namespace LaravelUnisender\Console\Commands;

use LaravelUnisender\Services\UnisenderService;
use Illuminate\Console\Command;

class SendSmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'unisender:sms 
                            {phone : Phone number to send SMS to}
                            {text : SMS text content} 
                            {--list-id= : List ID to send to multiple contacts (optional)}';

    /**
     * The console command description.
     */
    protected $description = 'Send SMS via Unisender API';

    /**
     * Execute the console command.
     */
    public function handle(UnisenderService $unisender): int
    {
        $phone = $this->argument('phone');
        $text = $this->argument('text');
        $sender = $this->option('sender') ?: config('unisender.default_sms_sender');
        $listId = $this->option('list-id');

        if (!$sender) {
            $this->error('Sender name is required. Please provide --sender option or set UNISENDER_DEFAULT_SMS_SENDER in config.');
            return 1;
        }

        $params = [
            'phone' => $phone,
            'text' => $text,
            'sender' => $sender,
        ];

        if ($listId) {
            $params['list_ids'] = $listId;
        }

        $this->info('Sending SMS...');
        $this->line("Phone: {$phone}");
        $this->line("Sender: {$sender}");
        $this->line("Text: {$text}");

        try {
            $response = $unisender->sendSms($params);

            if ($unisender->isSuccess($response)) {
                $this->info('SMS sent successfully!');
                $this->table(['Field', 'Value'], [
                    ['Status', 'Success'],
                    ['Response', json_encode($response, JSON_PRETTY_PRINT)],
                ]);
                return 0;
            } else {
                $errorMessage = $unisender->getErrorMessage($response);
                $this->error("Failed to send SMS: {$errorMessage}");
                $this->table(['Field', 'Value'], [
                    ['Status', 'Failed'],
                    ['Error', $errorMessage],
                    ['Response', json_encode($response, JSON_PRETTY_PRINT)],
                ]);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Exception occurred: {$e->getMessage()}");
            return 1;
        }
    }
} 