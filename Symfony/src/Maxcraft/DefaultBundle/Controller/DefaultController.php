<?php

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Zone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaxcraftDefaultBundle:Default:index.html.twig');
    }


   /*public function addZoneAction(){
       $zone = new Zone();
       $zone->setName("Mannheim");
       $zone->setWorld("Event2");
       $zone->setPoints("45;67;90");
       $zone->setOwner('Crevebedaine');
       $zone->setParent(1);
       $zone->setTags('public;no-spawn-horse');
       $zone->setBuilders('Mwa;Twa');

       $em = $this->getDoctrine()->getManager();
       $em->persist($zone);
       $em->flush();

       $rep = new Response('coucou');
       return $rep;*/

    public function testRegexAction(){
        $json = '-world:id="67",name="maxcraft",groupnumber="35",';
        $regex = '#^-world:id="(.+)",name="(.+)",groupnumber="(.+)",$#';

        if (preg_match($regex, $json)){
            $text = preg_replace($regex,'$1,$2,$3' ,$json);
            $repContent = 'ca marche :  ' .$text;
            $rep = new Response(json_encode($repContent));
            return $rep;
        } else{
            return new Response(json_encode('rate'));
        }
    }

    public function testBoolAction(){
        $str = '';
        $bool = boolval($str);
        return $bool;
    }



}


