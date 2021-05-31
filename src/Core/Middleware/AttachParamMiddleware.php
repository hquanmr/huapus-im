<?php

namespace hquanmr\HuapusIm\Core\Middleware;

use Leo108\SDK\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;

class AttachParamMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $attachParam;

    /**
     * CheckApiResponseMiddleware constructor.
     * @param callable|boolean $shouldCheck
     * @param callable         $responseParser
     */
    public function __construct(callable $attachParam = null)
    {

        $this->attachParam = $attachParam;
    }

    public function __invoke()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                $request = call_user_func($this->attachParam, $request);
                return $handler($request, $options);
            };
        };
    }
}
