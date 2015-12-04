<?php


namespace Maxcraft\DefaultBundle\Websocket;






class ZoneInfosHandler extends MaxcraftHandler
{



    protected function handle($data)
    {
        if ( !(isset($data['Zone-Id']) || $data['Zone-Id'] > 1) ) {
            return array(
            "error" => true,
            "errorMessage" => "Vous devez envoyer un nombre supérieur ou égal à 1"
        );
        }

        if ( !($zone = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Zone')->find($data))) {
            return array(
                "error" => true,
                "errorMessage" => "Problèmes avec Doctrine, il se peut que l'entité demandée n'existe pas en DB"
            );
        }

        return array(
            "id" => $zone->getId(),
            "name" => $zone->getName(),
            "parent" => $zone->getParent(),
            "points" => $zone->getPoints(),
            "owner" => $zone->getOwner(),
            "world" => $zone->getWorld(),
            "tags" => $zone->getTags(),
            "builders" => $zone->getBuilders(),
            "cuboiders" => $zone->getCuboiders(),
        );

    }

    protected function onResponseSent()
    {
        return null;
    }
}