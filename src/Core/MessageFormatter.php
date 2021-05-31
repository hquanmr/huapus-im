<?php

/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/14
 * Time: 18:19
 */

namespace hquanmr\HuapusIm\Core;

use GuzzleHttp\MessageFormatter as BaseMessageFormatter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MessageFormatter extends BaseMessageFormatter
{
    /**
     * @var bool
     */
    protected $hideAccessToken = true;

    public function __construct($template = BaseMessageFormatter::DEBUG)
    {
        parent::__construct($template);
    }


    public function format(
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $error = null
    ) {
        return parent::format($request, $response, $error);
    }
}
