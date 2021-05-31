<?php

namespace hquanmr\HuapusIm\Core\Middleware;

use Leo108\SDK\Middleware\MiddlewareInterface;
use Leo108\QQExmail\Core\Exceptions\ApiException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CheckApiResponseMiddleware implements MiddlewareInterface
{
    /**
     * @var callable|bool
     */
    protected $shouldCheck;

    /**
     * @var callable
     */
    protected $responseParser;

    /**
     * CheckApiResponseMiddleware constructor.
     * @param callable|boolean $shouldCheck
     * @param callable         $responseParser
     */
    public function __construct($shouldCheck, callable $responseParser)
    {


        $this->shouldCheck    = $shouldCheck;
        $this->responseParser = $responseParser;
    }

    public function __invoke()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (is_bool($this->shouldCheck)) {
                    $shouldCheck = $this->shouldCheck;
                } else {
                    $shouldCheck = call_user_func($this->shouldCheck, $request);
                }
                if (!$shouldCheck) {
                    return $handler($request, $options);
                }

                return $handler($request, $options)->then(
                    function (ResponseInterface $response) {
                        $ret = call_user_func($this->responseParser, $response);
                        var_dump($ret);
                        exit();
                        if (!$ret) {
                            throw new ApiException('decode failed, response:' . $response->getBody());
                        }
                        if ($ret['errcode'] != 0) {
                            throw new ApiException($ret['errmsg'], $ret['errcode']);
                        }

                        return $response;
                    },
                    function ($reason) {
                        throw new ApiException($reason);
                    }
                );
            };
        };
    }
}
