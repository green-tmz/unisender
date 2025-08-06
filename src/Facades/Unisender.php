<?php

namespace LaravelUnisender\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Unisender\ApiWrapper\UnisenderApi getApi()
 * @method static array sendSms(array $params)
 * @method static array sendEmail(array $params)
 * @method static array getLists()
 * @method static array createList(array $params)
 * @method static array updateList(array $params)
 * @method static array deleteList(array $params)
 * @method static array exclude(array $params)
 * @method static array unsubscribe(array $params)
 * @method static array importContacts(array $params)
 * @method static array getTotalContactsCount(array $params = [])
 * @method static array getContactCount(array $params)
 * @method static array createEmailMessage(array $params)
 * @method static array createSmsMessage(array $params)
 * @method static array createCampaign(array $params)
 * @method static array getCampaigns(array $params = [])
 * @method static array getCampaignStatus(array $params)
 * @method static array getFields()
 * @method static array createField(array $params)
 * @method static array updateField(array $params)
 * @method static array deleteField(array $params)
 * @method static array getTags()
 * @method static array deleteTag(array $params)
 * @method static array isContactInLists(array $params)
 * @method static array getContactFieldValues(array $params)
 * @method static array getContact(array $params)
 * @method static array subscribe(array $params)
 * @method static array taskExportContacts(array $params)
 * @method static array getTaskResult(array $params)
 * @method static array getCurrencyRates()
 * @method static array validateSender(array $params)
 * @method static array setSenderDomain(array $params)
 * @method static array getSenderDomainList(array $params = [])
 * @method static array getCheckedEmail(array $params = [])
 * @method static bool isSuccess(array $response)
 * @method static string|null getErrorMessage(array $response)
 *
 * @see \LaravelUnisender\Services\UnisenderService
 */
class Unisender extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'unisender';
    }
} 