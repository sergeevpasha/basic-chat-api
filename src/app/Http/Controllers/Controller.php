<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Basic Chat API',
    description: 'A simple chat API with user management and messaging functionality',
)]
#[OA\Server(
    url: 'http://localhost:97',
    description: 'Local development server'
)]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
