<?php

declare(strict_types=1);

namespace App\Foundation\Traits;

use App\Constants\HttpCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Codec\Json;
use Hyperf\Context\Context;
use Hyperf\Contract\Arrayable;
use Hyperf\Contract\Jsonable;
use Psr\Http\Message\ResponseInterface;

trait ResponseTrait
{
    private $errorCode = 50015;
    private $errorMsg = '操作失败';
    private $httpCodeKey = 'RESPONSE-HTTP-CODE';
    private $headersKey = 'RESPONSE-HEADERS';

    /**
     * 成功响应
     * @param mixed $data
     * @return ResponseInterface
     */
    public function success($data = [], $msg = null, $code = 20000, $header = []): ResponseInterface
    {
        return $this->addHttpHeaders($header)->respond([
            'code' => $code,
            'message' => $msg ?? '操作成功',
            'result' => $data
        ]);
    }

    /**
     * 错误返回
     * @param string $msg     错误信息
     * @param int    $code    错误业务码
     * @param array  $data        额外返回的数据
     * @return ResponseInterface
     */
    public function fail(string $msg = null, int $code = 50015, $header = []): ResponseInterface
    {
        $httpCode = $this->getHttpCode();
        return $this->addHttpHeaders($header)->setHttpCode($httpCode == HttpCode::OK ? HttpCode::BAD_REQUEST : $httpCode)
            ->respond([
                'code' => $code ?? $this->errorCode,
                'message' => $msg ?? $this->errorMsg
            ]);
    }

    /**
     * 统一返回
     * @param mixed $data string|array
     * @param bool $status 
     * @param int $code 
     * @return ResponseInterface 
     */
    public function respondJson($data, bool $status = false, int $code = 50015)
    {
        if (!$status && is_string($data)) {
            return $this->fail($data, $code);
        }
        return $this->success($data);
    }

    /**
     * 设置http返回码
     * @param int $code    http返回码
     * @return $this
     */
    final public function setHttpCode(int $code = HttpCode::OK): self
    {
        Context::set($this->httpCodeKey, $code);
        return $this;
    }

    /**
     * 设置返回头部header值
     * @param string $key
     * @param        $value
     * @return $this
     */
    public function addHttpHeader(string $key, $value): self
    {
        $headers = $this->getHttpHeaders();
        $headers += [$key => $value];
        return $this->addHttpHeaders($headers);
    }

    /**
     * 批量设置头部返回
     * @param array $headers    header数组：[key1 => value1, key2 => value2]
     * @return $this
     */
    public function addHttpHeaders(array $headers = []): self
    {
        Context::set($this->headersKey, $headers);
        return $this;
    }

    /**
     * 获取http返回码
     * @return int http返回码
     */
    private function getHttpCode(): int
    {
        return Context::has($this->httpCodeKey) ? Context::get($this->httpCodeKey) : HttpCode::OK;
    }

    /**
     * 获取头部返回
     * @return array 
     */
    private function getHttpHeaders(): array
    {
        return Context::has($this->headersKey) ? Context::get($this->headersKey) : [];
    }

    /**
     * @param null|array|Arrayable|Jsonable|string $response
     * @return ResponseInterface
     */
    private function respond($response): ResponseInterface
    {
        if (is_string($response)) {
            return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream($response));
        }

        if (is_array($response) || $response instanceof Arrayable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream(Json::encode($response)));
        }

        if ($response instanceof Jsonable) {
            return $this->response()
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream((string)$response));
        }

        return $this->response()->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream((string)$response));
    }

    /**
     * @return mixed|ResponseInterface|null
     */
    protected function response(): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        foreach ($this->getHttpHeaders() as $key => $value) {
            $response = $response->withHeader($key, $value);
        }
        return $response->withStatus($this->getHttpCode());
    }
}
