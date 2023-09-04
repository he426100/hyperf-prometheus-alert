<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Context\Context;
use Hyperf\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Hyperf\Config\config;

class IpWhiteListMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 获取配置文件中的白名单IP列表
        $whiteList = (array)config('ip_whitelist');

        // 获取客户端IP
        $clientIp = $request->getServerParams()['remote_addr'] ?? '';

        // 判断客户端IP是否在白名单中
        if (!empty($whiteList) && !in_array($clientIp, $whiteList)) {
            // 如果不在白名单中，则返回403 Forbidden响应
            $response = Context::get(ResponseInterface::class);
            $response = $response->withStatus(403)->withAddedHeader('content-type', 'application/json')->withBody(new SwooleStream(Json::encode([
                'code' => 403,
                'message' => 'forbid!'
            ])));
            return $response;
        }

        // 如果在白名单中，则继续处理请求
        return $handler->handle($request);
    }
}
