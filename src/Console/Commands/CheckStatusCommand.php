<?php

namespace LaravelUnisender\Console\Commands;

use LaravelUnisender\Services\UnisenderService;
use Illuminate\Console\Command;

class CheckStatusCommand extends Command
{
    protected $signature = 'unisender:status';

    protected $description = 'Check Unisender API connection status and configuration';

    /**
     * Execute the console command.
     */
    public function handle(UnisenderService $unisender): int
    {
        $this->info('Checking Unisender API status...');
        $this->newLine();

        // Check configuration
        $this->checkConfiguration();

        // Test API connection
        $this->testApiConnection($unisender);

        return 0;
    }

    /**
     * Check configuration settings
     */
    protected function checkConfiguration(): void
    {
        $this->info('📋 Configuration Check:');
        
        $config = [
            'API Key' => config('unisender.api_key') ? '✅ Set' : '❌ Not set',
            'Encoding' => config('unisender.encoding', 'UTF-8'),
            'Retry Count' => config('unisender.retry_count', 4),
            'Platform' => config('unisender.platform', 'Laravel Unisender Service'),
            'Language' => config('unisender.lang', 'en'),
            'Default SMS Sender' => config('unisender.default_sms_sender') ?: '❌ Not set',
            'Default Email Sender' => config('unisender.default_email_sender') ?: '❌ Not set',
        ];

        $this->table(['Setting', 'Value'], collect($config)->map(function ($value, $key) {
            return [$key, $value];
        })->toArray());

        $this->newLine();
    }

    /**
     * Test API connection
     */
    protected function testApiConnection(UnisenderService $unisender): void
    {
        $this->info('🔗 API Connection Test:');

        try {
            // Test with getCurrencyRates as it's lightweight
            $startTime = microtime(true);
            $response = $unisender->getCurrencyRates();
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);

            if ($unisender->isSuccess($response)) {
                $this->info("✅ API Connection: Successful");
                $this->info("⏱️  Response Time: {$responseTime}ms");
                
                if (isset($response['result'])) {
                    $this->info("📊 API Response: Valid JSON received");
                }
            } else {
                $errorMessage = $unisender->getErrorMessage($response);
                $this->error("❌ API Connection: Failed");
                $this->error("📝 Error: {$errorMessage}");
                
                if (str_contains($errorMessage, 'Invalid API key')) {
                    $this->warn("💡 Tip: Check your UNISENDER_API_KEY in .env file");
                }
            }
        } catch (\Exception $e) {
            $this->error("❌ API Connection: Exception occurred");
            $this->error("📝 Error: {$e->getMessage()}");
            
            if (str_contains($e->getMessage(), 'cURL error')) {
                $this->warn("💡 Tip: Check your internet connection");
            }
        }

        $this->newLine();
    }

    /**
     * Show detailed information
     */
    protected function showDetailedInfo(UnisenderService $unisender): void
    {
        $this->info('📊 Detailed Information:');

        // Test multiple API endpoints
        $endpoints = [
            'Currency Rates' => fn() => $unisender->getCurrencyRates(),
            'Contact Lists' => fn() => $unisender->getLists(),
            'User Fields' => fn() => $unisender->getFields(),
        ];

        $results = [];
        foreach ($endpoints as $name => $callback) {
            try {
                $startTime = microtime(true);
                $response = $callback();
                $endTime = microtime(true);
                $responseTime = round(($endTime - $startTime) * 1000, 2);

                $status = $unisender->isSuccess($response) ? '✅ Success' : '❌ Failed';
                $results[] = [$name, $status, "{$responseTime}ms"];
            } catch (\Exception $e) {
                $results[] = [$name, '❌ Exception', $e->getMessage()];
            }
        }

        $this->table(['Endpoint', 'Status', 'Response Time/Error'], $results);

        // Show API limits info
        $this->newLine();
        $this->info('📈 API Limits Information:');
        $this->line('• Rate limiting: ' . (config('unisender.enable_rate_limiting') ? 'Enabled' : 'Disabled'));
        $this->line('• Cache: ' . (config('unisender.enable_cache') ? 'Enabled' : 'Disabled'));
        $this->line('• Logging: ' . (config('unisender.enable_logging') ? 'Enabled' : 'Disabled'));

        $this->newLine();
    }
} 