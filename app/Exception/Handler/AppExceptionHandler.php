<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use Hyperf\Server\Exception\ServerException;
use App\Constants\HttpCode;
use Hyperf\Codec\Json;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 记录日志
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        // 格式化输出
        if ($throwable instanceof ServerException) {
            $data = [
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage()
            ];
        } else {
            $data = [
                'code' => 50015,
                'message' => '系统错误'
            ];
        }
        if ($throwable instanceof ModelNotFoundException) {
            $data['message'] = '未找到相关记录';
        }
        return $response->withStatus(HttpCode::BAD_REQUEST)
            ->withAddedHeader('content-type', 'application/json')
            ->withBody(new SwooleStream(Json::encode($data)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
