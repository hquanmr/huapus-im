<?php
namespace hquanmr\HuapusIm\Service;

use hquanmr\HuapusIm\Core\BaseApi;

 class Group extends BaseApi
{
    const   DESTROY= 'v4/group_open_http_svc/destroy_group';
 
    public function destroy($groupId)
    {
        return static::parseJson($this->apiJson(self::DESTROY, ['GroupId' => $groupId]));
    }

}
