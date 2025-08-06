<?php

namespace LaravelUnisender\Http\Controllers;

use LaravelUnisender\Services\UnisenderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

class UnisenderController extends Controller
{
    protected UnisenderService $unisender;

    public function __construct(UnisenderService $unisender)
    {
        $this->unisender = $unisender;
    }

    /**
     * Send SMS
     */
    public function sendSms(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string',
                'text' => 'required|string|max:160',
                'sender' => 'nullable|string',
            ]);

            $params = [
                'phone' => $validated['phone'],
                'text' => $validated['text'],
                'sender' => $validated['sender'] ?: config('unisender.default_sms_sender'),
            ];

            $response = $this->unisender->sendSms($params);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'data' => $response,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send email
     */
    public function sendEmail(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'subject' => 'required|string|max:255',
                'body' => 'required_without:html_body|string',
                'html_body' => 'required_without:body|string',
                'sender' => 'nullable|email',
                'sender_name' => 'nullable|string',
            ]);

            $params = [
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'sender' => $validated['sender'] ?: config('unisender.default_email_sender'),
            ];

            if (isset($validated['body'])) {
                $params['body'] = $validated['body'];
            }

            if (isset($validated['html_body'])) {
                $params['body_html'] = $validated['html_body'];
            }

            if (isset($validated['sender_name'])) {
                $params['sender_name'] = $validated['sender_name'];
            }

            $response = $this->unisender->sendEmail($params);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully',
                    'data' => $response,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get contact lists
     */
    public function getLists(): JsonResponse
    {
        try {
            $response = $this->unisender->getLists();

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'data' => $response['result'] ?? [],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get lists',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create contact list
     */
    public function createList(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $response = $this->unisender->createList($validated);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'message' => 'List created successfully',
                    'data' => $response,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create list',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Subscribe contact
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'list_ids' => 'required|string',
                'tags' => 'nullable|string',
                'request_ip' => 'nullable|ip',
            ]);

            $response = $this->unisender->subscribe($validated);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contact subscribed successfully',
                    'data' => $response,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe contact',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get contact information
     */
    public function getContact(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $response = $this->unisender->getContact($validated);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'data' => $response['result'] ?? [],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get contact',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get campaigns
     */
    public function getCampaigns(Request $request): JsonResponse
    {
        try {
            $params = $request->only(['limit', 'offset', 'from', 'to']);
            
            $response = $this->unisender->getCampaigns($params);

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'data' => $response['result'] ?? [],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get campaigns',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user fields
     */
    public function getFields(): JsonResponse
    {
        try {
            $response = $this->unisender->getFields();

            if ($this->unisender->isSuccess($response)) {
                return response()->json([
                    'success' => true,
                    'data' => $response['result'] ?? [],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->unisender->getErrorMessage($response),
                    'data' => $response,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get fields',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 