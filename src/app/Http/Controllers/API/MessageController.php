<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Events\NewMessageEvent;
use App\Http\Requests\CreateMessageRequest;
use App\Services\MessageService;
use Illuminate\Support\Facades\Broadcast;

#[OA\Tag(name: 'Messages', description: 'Message management endpoints')]
class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    protected MessageService $messageService;

    /**
     * MessageController constructor.
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Get all messages with their associated users.
     *
     * @return JsonResponse Response with all messages
     */
    #[OA\Get(
        path: '/api/messages',
        description: 'Retrieves all messages with their associated users',
        summary: 'Get all messages',
        tags: ['Messages'],
    )]
    #[OA\Parameter(
        name: 'login',
        description: 'User login for authentication',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 200,
        description: 'List of all messages',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'messages',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'content', type: 'string'),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'user', type: 'object', properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'login', type: 'string'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ]),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Login is required',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ]
        )
    )]
    public function index(): JsonResponse
    {
        $messages = $this->messageService->getAllMessages();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Create a new message.
     *
     * @param CreateMessageRequest $request The HTTP request containing login and content
     * @return JsonResponse Response with the created message
     */
    #[OA\Post(
        path: '/api/messages',
        description: 'Creates a new message for the authenticated user',
        summary: 'Create a new message',
        tags: ['Messages'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['login', 'message'],
            properties: [
                new OA\Property(property: 'login', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Message created successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'content', type: 'string'),
                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'user', properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'login', type: 'string'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                    ], type: 'object'),
                ], type: 'object'),
                new OA\Property(property: 'status', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Login is required',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'errors', type: 'object'),
            ]
        )
    )]
    public function store(CreateMessageRequest $request): JsonResponse
    {
        $message = $this->messageService->createMessage(
            $request->login,
            $request->message
        );

        return response()->json([
            'message' => $message,
            'status' => 'Message created successfully',
        ], 201);
    }
}
