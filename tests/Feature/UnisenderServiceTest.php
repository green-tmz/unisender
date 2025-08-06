<?php

namespace LaravelUnisender\Tests\Feature;

use LaravelUnisender\Services\UnisenderService;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnisenderServiceTest extends TestCase
{
    protected UnisenderService $unisender;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the config
        $this->app['config']->set('unisender.api_key', 'test_api_key');
        $this->app['config']->set('unisender.encoding', 'UTF-8');
        $this->app['config']->set('unisender.retry_count', 4);
        $this->app['config']->set('unisender.platform', 'Test Platform');
        $this->app['config']->set('unisender.lang', 'en');
        
        $this->unisender = app(UnisenderService::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            \LaravelUnisender\UnisenderServiceProvider::class,
        ];
    }

    /** @test */
    public function it_can_send_sms()
    {
        $params = [
            'phone' => '+380971234567',
            'text' => 'Test SMS from Laravel',
            'sender' => 'TestApp'
        ];

        $response = $this->unisender->sendSms($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_send_email()
    {
        $params = [
            'email' => 'test@example.com',
            'subject' => 'Test Email',
            'body' => 'This is a test email from Laravel',
            'sender' => 'noreply@example.com'
        ];

        $response = $this->unisender->sendEmail($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_get_lists()
    {
        $response = $this->unisender->getLists();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_create_list()
    {
        $params = [
            'title' => 'Test List',
            'description' => 'Test list description'
        ];

        $response = $this->unisender->createList($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_subscribe_contact()
    {
        $params = [
            'email' => 'test@example.com',
            'list_ids' => '1'
        ];

        $response = $this->unisender->subscribe($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_get_contact()
    {
        $params = [
            'email' => 'test@example.com'
        ];

        $response = $this->unisender->getContact($params);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_get_campaigns()
    {
        $response = $this->unisender->getCampaigns();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_get_fields()
    {
        $response = $this->unisender->getFields();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_get_currency_rates()
    {
        $response = $this->unisender->getCurrencyRates();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_parse_response()
    {
        $jsonResponse = '{"result": {"id": 123}, "success": true}';
        
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->unisender);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);
        
        $response = $method->invoke($this->unisender, $jsonResponse);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_check_success_status()
    {
        $successResponse = ['result' => ['id' => 123]];
        $errorResponse = ['error' => 'API Error'];

        $this->assertTrue($this->unisender->isSuccess($successResponse));
        $this->assertFalse($this->unisender->isSuccess($errorResponse));
    }

    /** @test */
    public function it_can_get_error_message()
    {
        $errorResponse = ['error' => 'API Error'];
        $messageResponse = ['message' => 'Custom message'];

        $this->assertEquals('API Error', $this->unisender->getErrorMessage($errorResponse));
        $this->assertEquals('Custom message', $this->unisender->getErrorMessage($messageResponse));
        $this->assertNull($this->unisender->getErrorMessage(['success' => true]));
    }

    /** @test */
    public function it_handles_false_response()
    {
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->unisender);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);
        
        $response = $method->invoke($this->unisender, false);

        $this->assertIsArray($response);
        $this->assertFalse($response['success']);
        $this->assertEquals('API request failed', $response['error']);
    }

    /** @test */
    public function it_handles_invalid_json_response()
    {
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->unisender);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);
        
        $response = $method->invoke($this->unisender, 'invalid json');

        $this->assertIsArray($response);
        $this->assertFalse($response['success']);
        $this->assertEquals('Invalid JSON response', $response['error']);
    }
} 