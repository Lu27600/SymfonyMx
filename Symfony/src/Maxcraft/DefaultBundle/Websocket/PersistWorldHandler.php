<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 22:25
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\World;
use Symfony\Component\HttpFoundation\Response;

class PersistWorldHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-world:id="(\d+)",name="(.+)",groupnumber="(\d+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3',$data);
            list ($id,$name,$groupNumber) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($world = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:World')->find($id))){
                $world = new World();
            }
            $world->setName($name);
            $world->setGroupNumber(intval($groupNumber));

            $em->persist($world);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $worlds = explode('-',$str);

            foreach ($worlds as $w){
                $regex = '/-?world:id="(\d+)",name="(.+)",groupnumber="(\d+)",?/';
                if ( !(preg_match($regex,$w))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3',$w);
                list ($id,$name,$groupNumber) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($world = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:World')->find($id))){
                    $world = new World();
                }
                $world->setName($name);
                $world->setGroupNumber(intval($groupNumber));

                $em->persist($world);
                $em->flush();
            }
            return new Response(json_encode(array('error'=> false)));
        }
        else{
            //erreur

            return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
        }
    }

    protected function onResponseSent()
    {
        return;
    }
}