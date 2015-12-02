<?php

namespace Maxcraft\DefaultBundle\Controller;

use NathemWS\PingRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('WSC')->sendRequest(new PingRequest());

        return $this->render('MaxcraftDefaultBundle:Default:index.html.twig');
    }



}


