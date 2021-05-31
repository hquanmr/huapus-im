<?php

namespace hquanmr\HuapusIm;

use GuzzleHttp\ClientInterface;
use Leo108\SDK\SDK;
use Psr\Log\LoggerInterface;
use hquanmr\HuapusIm\Core\Exceptions\InvalidArgumentException;
use hquanmr\HuapusIm\Service\Group;

class Application  extends SDK
{

    /**
     * @var string
     */
    public  $AppID;

    /**
     * @var string
     */
    public $Identifier;

    /**
     * @var string
     */
    public $Secretkey;

    public  function __construct(array $config = [], LoggerInterface $logger = null, ClientInterface $httpClient = null)
    {
        parent::__construct($config, $httpClient, $logger);
        $this->parseConfig($config);
    }
    public function parseConfig(array $config)
    {
        if (!isset($config['AppID'])) {
            throw new InvalidArgumentException('缺少 AppID 参数');
        }
        $this->AppID =  $config['AppID'];

        if (!isset($config['Secretkey'])) {
            throw new InvalidArgumentException('缺少 Secretkey 参数');
        }
        $this->Secretkey =  $config['Secretkey'];

        if (!isset($config['Identifier'])) {
            throw new InvalidArgumentException('缺少 Identifier 参数');
        }
        $this->Identifier =  $config['Identifier'];
        $this->config = $config;
    }

    protected function getApiMap()
    {
        return [
            'group' => Group::class,
        ];
    }
}
