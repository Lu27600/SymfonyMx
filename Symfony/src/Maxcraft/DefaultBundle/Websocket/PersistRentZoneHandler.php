<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 22:16
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\RentZone;
use Symfony\Component\HttpFoundation\Response;

class PersistRentZoneHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-rentzone:id="(\d+)",zone="(\d+)",tenant="(.+)",price="(.+)",lastpay="(.+)",location="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6',$data);
            list ($id,$zoneId,$tenant,$price,$lastpay,$location) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($rentZone = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:RentZone')->find($id))){
                $rentZone = new RentZone();
            }
            $rentZone->setZone($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Zone')->find($zoneId));
            $rentZone->setTenant($tenant);
            $rentZone->setPrice(doubleval($price));
            if ($lastpay=='null'){$rentZone->setLastpay(null);}else{$rentZone->setLastpay($lastpay);}
            $rentZone->setLocation($location);

            $em->persist($rentZone);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $rZones = explode('-',$str);

            foreach ($rZones as $rz){
                $regex = '/-?rentzone:id="(\d+)",zone="(\d+)",tenant="(.+)",price="(.+)",lastpay="(.+)",location="(.+)",?/';
                if ( !(preg_match($regex,$rz))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3;$4;$5;$6',$rz);
                list ($id,$zoneid,$tenant,$price,$lastpay,$location) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($rentZone = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:RentZone')->find($id))){
                    $rentZone = new RentZone();
                }
                $rentZone->setZone($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Zone')->find($zoneid));
                $rentZone->setTenant($tenant);
                $rentZone->setPrice(doubleval($price));
                if ($lastpay=='null'){$rentZone->setLastpay(null);}else{$rentZone->setLastpay($lastpay);}
                $rentZone->setLocation($location);

                $em->persist($rentZone);
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
        return ;
    }
}