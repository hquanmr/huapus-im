<?php

namespace hquanmr\HuapusIm\Core\Exceptios;

class Code
{
    protected  $ERROR_CODE = [
        '80001' => '消息文本安全打击',
        '60002' => 'HTTP 解析错误 ，请检查 HTTP 请求 URL 格式',
        '60003' => 'HTTP 请求 JSON 解析错误，请检查 JSON 格式',
        '60004' => '请求 URL 或 JSON 包体中帐号或签名错误',
        '60005' => '请求 URL 或 JSON 包体中帐号或签名错误',
        '60006' => 'SDKAppID 失效，请核对 SDKAppID 有效性',
        '60007' => 'REST 接口调用频率超过限制，请降低请求频率',
        '60008' => '服务请求超时或 HTTP 请求格式错误，请检查并重试',
        '60009' => '请求资源错误，请检查请求 URL',
        '60010' => '请求需要 App 管理员权限',
        '60011' => 'SDKAppID 请求频率超限，请降低请求频率',
        '60012' => 'REST 接口需要带 SDKAppID，请检查请求 URL 中的 SDKAppID',
        '60013' => 'HTTP 响应包 JSON 解析错误',
        '60014' => '置换帐号超时',
        '60015' => '请求包体帐号类型错误，请确认帐号为字符串格式',
    ];

}
