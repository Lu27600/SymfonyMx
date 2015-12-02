<?php

namespace Maxcraft\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaxcraftDefaultBundle:Default:index.html.twig');
    }


}
