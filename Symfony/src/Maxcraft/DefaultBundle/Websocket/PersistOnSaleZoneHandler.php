<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 21:46
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\OnSaleZone;
use Symfony\Component\HttpFoundation\Response;

class PersistOnSaleZoneHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-onsalezone:id="(\d+)",zoneid="(\d+)",price="(.+)",forrent="(.+)",location="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5',$data);
            list ($id,$zoneid,$price,$forRent,$location) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($oSaleZ = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:OnSaleZone')->find($id))){
                $oSaleZ = new OnSaleZone();
            }
            $oSaleZ->setZoneId(intval($zoneid));
            $oSaleZ->setPrice(doubleval($price));
            if ($forRent == 'true'){$oSaleZ->setForRent(true);}elseif($forRent == 'false'){$oSaleZ->setForRent(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
            if ($location == 'null'){$oSaleZ->setLocation(null);}else{$oSaleZ->setLocation($location);}

            $em->persist($oSaleZ);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $oSaleZones = explode('-',$str);

            foreach ($oSaleZones as $osz){
                $regex = '/-?onsalezone:id="(\d+)",zoneid="(\d+)",price="(.+)",forrent="(.+)",location="(.+)",?/';
                if ( !(preg_match($regex,$osz))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3;$4;$5',$osz);
                list ($id,$zoneid,$price,$forRent,$location) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($oSaleZ = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:OnSaleZone')->find($id))){
                    $oSaleZ = new OnSaleZone();
                }
                $oSaleZ->setZoneId(intval($zoneid));
                $oSaleZ->setPrice(doubleval($price));
                if ($forRent == 'true'){$oSaleZ->setForRent(true);}elseif($forRent == 'false'){$oSaleZ->setForRent(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
                if ($location == 'null'){$oSaleZ->setLocation(null);}else{$oSaleZ->setLocation($location);}

                $em->persist($oSaleZ);
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