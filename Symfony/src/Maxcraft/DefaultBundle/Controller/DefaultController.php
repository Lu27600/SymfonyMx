<?php

namespace Maxcraft\DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $id = 1;

        $zone = $this->getDoctrine()->getManager()->find('MaxcraftDefaultBundle:Zone', $id);


        $repContent = array(
            "id" => $zone->getId(),
            "owner" => $zone->getOwner()
        ) ;
        $rep = new Response(json_encode($repContent));
        return $rep;

        //return $this->render('MaxcraftDefaultBundle:Default:index.html.twig');
    }




}


