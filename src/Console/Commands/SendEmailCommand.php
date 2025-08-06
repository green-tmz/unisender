<?php

namespace LaravelUnisender\Console\Commands;

use LaravelUnisender\Services\UnisenderService;
use Illuminate\Console\Command;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'unisender:email 
                            {email : Email address to send to}
                            {subject : Email subject}
                            {--body= : Email body content}
                            {--html-body= : HTML email body content}
                            {--sender= : Sender email (optional)}
                            {--sender-name= : Sender name (optional)}
                            {--list-id= : List ID to send to multiple contacts (optional)}';

    /**
     * The console command description.
     */
    protected $description = 'Send email via Unisender API';

    /**
     * Execute the console command.
     */
    public function handle(UnisenderService $unisender): int
    {
        $email = $this->argument('email');
        $subject = $this->argument('subject');
        $body = $this->option('body');
        $htmlBody = $this->option('html-body');
        $sender = $this->option('sender') ?: config('unisender.default_email_sender');
        $senderName = $this->option('sender-name');
        $listId = $this->option('list-id');

        if (!$sender) {
            $this->error('Sender email is required. Please provide --sender option or set UNISENDER_DEFAULT_EMAIL_SENDER in config.');
            return 1;
        }

        if (!$body && !$htmlBody) {
            $this->error('Email body is required. Please provide --body or --html-body option.');
            return 1;
        }

        $params = [
            'email' => $email,
            'subject' => $subject,
            'sender' => $sender,
        ];

        if ($body) {
            $params['body'] = $body;
        }

        if ($htmlBody) {
            $params['body_html'] = $htmlBody;
        }

        if ($senderName) {
            $params['sender_name'] = $senderName;
        }

        if ($listId) {
            $params['list_ids'] = $listId;
        }

        $this->info('Sending email...');
        $this->line("Email: {$email}");
        $this->line("Subject: {$subject}");
        $this->line("Sender: {$sender}");

        try {
            $response = $unisender->sendEmail($params);

            if ($unisender->isSuccess($response)) {
                $this->info('Email sent successfully!');
                $this->table(['Field', 'Value'], [
                    ['Status', 'Success'],
                    ['Response', json_encode($response, JSON_PRETTY_PRINT)],
                ]);
                return 0;
            } else {
                $errorMessage = $unisender->getErrorMessage($response);
                $this->error("Failed to send email: {$errorMessage}");
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