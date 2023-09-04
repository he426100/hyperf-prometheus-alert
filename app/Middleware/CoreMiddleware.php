<?php
declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Contracts\Arrayable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Codec\Json;
use App\Constants\HttpCode;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    /**
     * Handle the response when cannot found any routes.
     */
    protected function handleNotFound(ServerRequestInterface $request): mixed
    {
        // 重写路由找不到的处理逻辑
        return $this->response()->withStatus(HttpCode::NOT_FOUND)->withAddedHeader('content-type', 'application/json')->withBody(new SwooleStream(Json::encode([
            'code' => 50015,
            'message' => 'not found'
        ])));;
    }

    /**
     * Handle the response when the routes found but doesn't match any available methods.
     */
    protected function handleMethodNotAllowed(array $methods, ServerRequestInterface $request): mixed
    {
        // 重写 HTTP 方法不允许的处理逻辑
        return $this->response()->withStatus(HttpCode::METHOD_NOT_ALLOWED)->withAddedHeader('content-type', 'application/json')->withBody(new SwooleStream(Json::encode([
            'code' => 50015,
            'message' => 'Allow: ' . implode(', ', $methods)
        ])));;
    }
}