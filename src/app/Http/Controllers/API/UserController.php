<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;

#[OA\Tag(name: 'Users', description: 'User management endpoints')]
class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create or retrieve a user by login.
     *
     * @param Request $request The HTTP request containing login
     * @return JsonResponse Response with user data and creation status
     */
    #[OA\Post(
        path: '/api/users',
        description: 'Creates a new user if the login does not exist, otherwise returns the existing user',
        summary: 'Create or retrieve a user by login',
        tags: ['Users'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['login'],
            properties: [
                new OA\Property(property: 'login', type: 'string', maxLength: 255),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'User created or retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'user', properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'login', type: 'string'),
                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                ], type: 'object'),
                new OA\Property(property: 'isNewUser', type: 'boolean'),
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
    public function createOrRetrieve(CreateUserRequest $request): JsonResponse
    {
        $result = $this->userService->createOrRetrieveUser($request->login);
        $user = $result['user'];
        $isNewUser = $result['isNewUser'];

        return response()->json([
            'user' => $user,
            'isNewUser' => $isNewUser,
            'message' => $isNewUser ? 'User created successfully' : 'User retrieved successfully',
        ]);
    }
}
