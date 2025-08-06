<?php

namespace LaravelUnisender\Services;

use Unisender\ApiWrapper\UnisenderApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UnisenderService
{
    protected UnisenderApi $api;
    protected string $apiKey;
    protected string $encoding;
    protected int $retryCount;
    protected ?int $timeout;
    protected bool $compression;
    protected string $platform;
    protected string $lang;

    public function __construct()
    {
        $this->apiKey = config('unisender.api_key');
        $this->encoding = config('unisender.encoding', 'UTF-8');
        $this->retryCount = config('unisender.retry_count', 4);
        $this->timeout = config('unisender.timeout');
        $this->compression = config('unisender.compression', false);
        $this->platform = config('unisender.platform', 'Laravel Unisender Service');
        $this->lang = config('unisender.lang', 'en');

        $this->api = new UnisenderApi(
            $this->apiKey,
            $this->encoding,
            $this->retryCount,
            $this->timeout,
            $this->compression,
            $this->platform
        );

        $this->api->setApiHostLanguage($this->lang);
    }

    /**
     * Get the underlying UnisenderApi instance
     */
    public function getApi(): UnisenderApi
    {
        return $this->api;
    }

    /**
     * Send SMS to one or several recipients
     */
    public function sendSms(array $params): array
    {
        try {
            $response = $this->api->sendSms($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender SMS sending failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send email without personalization
     */
    public function sendEmail(array $params): array
    {
        try {
            $response = $this->api->sendEmail($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender email sending failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get all available campaign lists
     */
    public function getLists(): array
    {
        try {
            $response = $this->api->getLists();
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get lists failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new contact list
     */
    public function createList(array $params): array
    {
        try {
            $response = $this->api->createList($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender create list failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Update campaign list properties
     */
    public function updateList(array $params): array
    {
        try {
            $response = $this->api->updateList($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender update list failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a list
     */
    public function deleteList(array $params): array
    {
        try {
            $response = $this->api->deleteList($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender delete list failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Exclude contact from lists
     */
    public function exclude(array $params): array
    {
        try {
            $response = $this->api->exclude($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender exclude contact failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Unsubscribe contact from lists
     */
    public function unsubscribe(array $params): array
    {
        try {
            $response = $this->api->unsubscribe($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender unsubscribe failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Bulk import contacts
     */
    public function importContacts(array $params): array
    {
        try {
            $response = $this->api->importContacts($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender import contacts failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get total contacts count
     */
    public function getTotalContactsCount(array $params = []): array
    {
        try {
            $response = $this->api->getTotalContactsCount($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get total contacts count failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get contact count in list
     */
    public function getContactCount(array $params): array
    {
        try {
            $response = $this->api->getContactCount($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get contact count failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create email message without sending
     */
    public function createEmailMessage(array $params): array
    {
        try {
            $response = $this->api->createEmailMessage($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender create email message failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create SMS message without sending
     */
    public function createSmsMessage(array $params): array
    {
        try {
            $response = $this->api->createSmsMessage($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender create SMS message failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create campaign
     */
    public function createCampaign(array $params): array
    {
        try {
            $response = $this->api->createCampaign($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender create campaign failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get campaigns list
     */
    public function getCampaigns(array $params = []): array
    {
        try {
            $response = $this->api->getCampaigns($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get campaigns failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get campaign status
     */
    public function getCampaignStatus(array $params): array
    {
        try {
            $response = $this->api->getCampaignStatus($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get campaign status failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get user fields
     */
    public function getFields(): array
    {
        try {
            $response = $this->api->getFields();
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get fields failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create user field
     */
    public function createField(array $params): array
    {
        try {
            $response = $this->api->createField($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender create field failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Update user field
     */
    public function updateField(array $params): array
    {
        try {
            $response = $this->api->updateField($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender update field failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Delete user field
     */
    public function deleteField(array $params): array
    {
        try {
            $response = $this->api->deleteField($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender delete field failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get all tags
     */
    public function getTags(): array
    {
        try {
            $response = $this->api->getTags();
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get tags failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete user tag
     */
    public function deleteTag(array $params): array
    {
        try {
            $response = $this->api->deleteTag($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender delete tag failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check if contact is in lists
     */
    public function isContactInLists(array $params): array
    {
        try {
            $response = $this->api->isContactInLists($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender check contact in lists failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get contact field values
     */
    public function getContactFieldValues(array $params): array
    {
        try {
            $response = $this->api->getContactFieldValues($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get contact field values failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get contact information
     */
    public function getContact(array $params): array
    {
        try {
            $response = $this->api->getContact($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get contact failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Subscribe contact with automatic IP detection
     */
    public function subscribe(array $params): array
    {
        try {
            $response = $this->api->subscribe($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender subscribe failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Export contacts (async)
     */
    public function taskExportContacts(array $params): array
    {
        try {
            $response = $this->api->taskExportContacts($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender export contacts failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get task result
     */
    public function getTaskResult(array $params): array
    {
        try {
            $response = $this->api->getTaskResult($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get task result failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get currency rates
     */
    public function getCurrencyRates(): array
    {
        try {
            $response = $this->api->getCurrencyRates();
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get currency rates failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Validate sender email
     */
    public function validateSender(array $params): array
    {
        try {
            $response = $this->api->validateSender($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender validate sender failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Set sender domain
     */
    public function setSenderDomain(array $params): array
    {
        try {
            $response = $this->api->setSenderDomain($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender set sender domain failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get sender domain list
     */
    public function getSenderDomainList(array $params = []): array
    {
        try {
            $response = $this->api->getSenderDomainList($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get sender domain list failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get checked email addresses
     */
    public function getCheckedEmail(array $params = []): array
    {
        try {
            $response = $this->api->getCheckedEmail($params);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            Log::error('Unisender get checked email failed', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Parse API response
     */
    protected function parseResponse($response): array
    {
        if ($response === false) {
            return [
                'success' => false,
                'error' => 'API request failed'
            ];
        }

        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response',
                'raw_response' => $response
            ];
        }

        return $decoded;
    }

    /**
     * Check if API response is successful
     */
    public function isSuccess(array $response): bool
    {
        return isset($response['result']) || 
               (isset($response['success']) && $response['success'] === true);
    }

    /**
     * Get error message from response
     */
    public function getErrorMessage(array $response): ?string
    {
        return $response['error'] ?? $response['message'] ?? null;
    }
} 