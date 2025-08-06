<?php

namespace App\Console\Commands\Unisender;

use App\Services\UnisenderService;
use Illuminate\Console\Command;

class GetListsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'unisender:lists {--format=table : Output format (table, json)}';

    /**
     * The console command description.
     */
    protected $description = 'Get all available contact lists from Unisender';

    /**
     * Execute the console command.
     */
    public function handle(UnisenderService $unisender): int
    {
        $this->info('Fetching contact lists...');

        try {
            $response = $unisender->getLists();

            if ($unisender->isSuccess($response)) {
                $lists = $response['result'] ?? [];
                
                if (empty($lists)) {
                    $this->info('No contact lists found.');
                    return 0;
                }

                $format = $this->option('format');

                if ($format === 'json') {
                    $this->line(json_encode($response, JSON_PRETTY_PRINT));
                } else {
                    $this->info('Contact Lists:');
                    $this->table(
                        ['ID', 'Title', 'Description', 'Created'],
                        collect($lists)->map(function ($list) {
                            return [
                                $list['id'] ?? 'N/A',
                                $list['title'] ?? 'N/A',
                                $list['description'] ?? 'N/A',
                                $list['created'] ?? 'N/A',
                            ];
                        })->toArray()
                    );
                }

                return 0;
            } else {
                $errorMessage = $unisender->getErrorMessage($response);
                $this->error("Failed to get lists: {$errorMessage}");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Exception occurred: {$e->getMessage()}");
            return 1;
        }
    }
} 