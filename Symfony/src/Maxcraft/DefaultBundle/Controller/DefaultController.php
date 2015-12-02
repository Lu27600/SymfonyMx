<?php

namespace Maxcraft\DefaultBundle\Controller;

use NathemWS\PingRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WebSocket\ConnectionException;

class DefaultController extends Controller
{
    public function indexAction()
    {

        try {
            $this->get('WSC')->sendRequest(new PingRequest(), 'toto');
        } catch(ConnectionException $e){
            return new Response($e->getMessage());
        }

        return $this->render('MaxcraftDefaultBundle:Default:index.html.twig');
    }



}


