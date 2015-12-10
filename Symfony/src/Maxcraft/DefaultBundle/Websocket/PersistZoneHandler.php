<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 05/12/15
 * Time: 19:13
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Zone;
use Symfony\Component\HttpFoundation\Response;

class PersistZoneHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet
            $regex = '/-zone:id="(\d+)",name="(.+)",parent="(\d+)",points="(.+)",owner="(.+)",world="(.+)",tags="(.+)",builders="(.+)",cuboiders="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$2;$3;$4;$5;$6;$7;$8;$9',$data);
            list ($name,$parent,$points,$owner,$world,$tags,$builders,$cuboiders) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();
            $zone = new Zone();
            $zone->setName($name);
            $zone->setParent($parent);
            $zone->setPoints($points);
            $zone->setOwner($owner);
            $zone->setWorld($world);
            $zone->setTags($tags);
            $zone->setBuilders($builders);
            $zone->setCuboiders($cuboiders);

            $em->persist($zone);
            $em->flush();

            return new Response(json_encode('error=false'));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets
            if ( $data[0]!='-') return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $zones = explode('-',$str);

            foreach ($zones as $z){
                $regex = '/-?zone:id="(\d+)",name="(.+)",parent="(\d+)",points="(.+)",owner="(.+)",world="(.+)",tags="(.+)",builders="(.+)",cuboiders="(.+)",?/';
                if ( !(preg_match($regex,$z))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$2;$3;$4;$5;$6;$7;$8;$9',$z);
                list ($name,$parent,$points,$owner,$world,$tags,$builders,$cuboiders) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();
                $zone = new Zone();
                $zone->setName($name);
                $zone->setParent($parent);
                $zone->setPoints($points);
                $zone->setOwner($owner);
                $zone->setWorld($world);
                $zone->setTags($tags);
                $zone->setBuilders($builders);
                $zone->setCuboiders($cuboiders);

                $em->persist($zone);
                $em->flush();
            }
        }
        else{
            //erreur

            return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
        }


    }

    protected function onResponseSent()
    {
        return;
    }
}