<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 05/12/15
 * Time: 18:46
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Symfony\Component\HttpFoundation\Response;

class WorldsInfosHandler extends MaxcraftHandler
{

    protected function handle($data)
    {
        if ( !($worlds = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:World')->findAll())){
            $error1Content =  array(
                "error" => true,
                "errorMessage" => "Problèmes avec Doctrine, il se peut que l'entité demandée n'existe pas en DB"
            );
            $error1 = new Response(json_encode($error1Content));
            return $error1;
        }

        $repContent = null;
        foreach ($worlds as $world) $repContent = $repContent.$world->objectToString($world);

        $rep = new Response(json_encode($repContent));
        return $rep;
    }

    protected function onResponseSent()
    {
        return null;
    }
}