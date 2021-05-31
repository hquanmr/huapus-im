<?php

namespace hquanmr\HuapusIm\Core;

use GuzzleHttp\Middleware;
use Leo108\SDK\AbstractApi;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

use hquanmr\HuapusIm\Core\Middleware\AttachParamMiddleware;
use hquanmr\HuapusIm\Core\Middleware\CheckApiResponseMiddleware;
use hquanmr\HuapusIm\Application;
use hquanmr\HuapusIm\Core\Sig\TLSSigAPIv2;

class BaseApi extends AbstractApi
{

    /**
     * @var application
     */
    protected $sdk;

    /**
     * @return application
     */
    protected function getSDK()
    {
        return $this->sdk;
    }


    protected function getFullApiUrl($api)
    {
        return 'https://console.tim.qq.com/' . ltrim($api, '/');
    }

    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public static function parseJson(ResponseInterface $response)
    {
        return \GuzzleHttp\json_decode($response->getBody(), true, 512, JSON_BIGINT_AS_STRING);
    }


    /**
     * @return array
     */
    protected function getHttpMiddleware()
    {
        return array_filter([
            $this->getCheckApiResponseMiddleware(),
            $this->getRetryMiddleware(),
            $this->getAttachMiddleware(),
            $this->getLogRequestMiddleware(),
        ]);
    }

    public function getAttachMiddleware()
    {
        $app = $this->sdk;
        return new AttachParamMiddleware(function (RequestInterface $request) use ($app) {
            return $this->attachParam($app, $request);
        });
    }
    /**
     * 在请求的 url 后加上请求参数 
     *
     * @param string           $app
     * @param RequestInterface $request
     * @param bool             $cache
     *
     * @return RequestInterface
     */
    protected  function attachParam($app, RequestInterface $request, $cache = true)
    {
       
        $query                 = \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery());
        $TLSSigAPIv2 = new TLSSigAPIv2($app->AppID, $app->Secretkey);

        $query['usersig'] = $TLSSigAPIv2->genUserSig($app->Identifier, 86400);
        $query['sdkappid'] =   $app->AppID;
        $query['identifier'] = $app->Identifier;
        $query['random'] = mt_rand(1, 9999);
        $query['contenttype'] = 'json';
       
        $uri             = $request->getUri()->withQuery(http_build_query($query));
        return $request->withUri($uri);
    }
    /**
     * @return CheckApiResponseMiddleware
     */
    protected function getCheckApiResponseMiddleware()
    {
        return new CheckApiResponseMiddleware(true, [static::class, 'parseJson']);
    }
    /**
     * @return callable
     */
    protected function getRetryMiddleware()
    {
        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null) {
            if ($retries >= $this->getSDK()->getConfig('api_retry', 3)) {
                return false;
            }
            
            if (!$response || $response->getStatusCode() != 200) {
                return true;
            }
            $ret = static::parseJson($response);
            if ($ret['ErrorCode'] != 0) {
                return true;
            }

            return false;
        });
    }
    /**
     * @return callable
     */
    protected function getLogRequestMiddleware()
    {
        $logger    = $this->getSDK()->getLogger();
     
        $formatter = new MessageFormatter($this->getSDK()->getConfig('log.format', MessageFormatter::DEBUG));
        $logLevel  = $this->getSDK()->getConfig('log.level', LogLevel::INFO);

        return Middleware::log($logger, $formatter, $logLevel);
    }
}
