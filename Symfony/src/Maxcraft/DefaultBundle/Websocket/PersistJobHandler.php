<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/12/15
 * Time: 19:39
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Jobs;
use Symfony\Component\HttpFoundation\Response;

class PersistJobHandler extends MaxcraftHandler
{

    protected function handle($data)

    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-jobs:id="(\d+)",metier="(.+)",xp="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3',$data);
            list ($id,$metier,$xp) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($job = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Jobs')->find($id))){
                $job = new Jobs();
            }
            $job->setMetier($metier);
            $job->setXp(doubleval($xp));

            $em->persist($job);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $jobs = explode('-',$str);

            foreach ($jobs as $j){
                $regex = '/-?jobs:id="(\d+)",metier="(.+)",xp="(.+)",?/';
                if ( !(preg_match($regex,$j))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3',$j);
                list ($id,$metier,$xp) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($job = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Jobs')->find($id))){
                    $job = new Jobs();
                }
                $job->setMetier($metier);
                $job->setXp(doubleval($xp));

                $em->persist($job);
                $em->flush();
            }
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