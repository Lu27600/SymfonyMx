<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 03/04/16
 * Time: 13:41
 */

namespace Maxcraft\DefaultBundle\Websocket;


class JobInfosHandler extends MaxcraftHandler
{


    protected function handle($data)
    {
        // TODO: Implement handle() method.
        $userUuid = $data['userUuid'];
    }

    protected function onResponseSent()
    {
        // TODO: Implement onResponseSent() method.
    }
}