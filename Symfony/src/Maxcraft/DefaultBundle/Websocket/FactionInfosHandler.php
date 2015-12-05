<?php


namespace Maxcraft\DefaultBundle\Websocket;


class FactionInfosHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        if ( !(isset($data['Faction-Id']) || $data['Faction-Id'] > 1)){
            $error1Content = array(
                "error" => true,
            );
        }
        // TODO: Implement handle() method.
    }

    protected function onResponseSent()
    {
        // TODO: Implement onResponseSent() method.
    }
}