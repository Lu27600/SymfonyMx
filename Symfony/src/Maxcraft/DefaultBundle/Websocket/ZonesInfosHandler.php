<?php


namespace Maxcraft\DefaultBundle\Websocket;





use Symfony\Component\HttpFoundation\Response;

class ZonesInfosHandler extends MaxcraftHandler
{





    protected function handle($data)
    {
        if ( !($zones = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Zone')->findAll())) {
            $error1Content =  array(
                "error" => true,
                "errorMessage" => "Problèmes avec Doctrine, il se peut que l'entité demandée n'existe pas en DB"
            );
            $error1 = new Response(json_encode($error1Content));
            return $error1;
        }

        $repContent = null;

        foreach($zones as $zone){
            $repContent = $repContent . $zone->objectToString($zone);
        }

        $rep = new Response(json_encode($repContent));
        return $rep;
    }

    protected function onResponseSent()
    {
        return null;
    }
}