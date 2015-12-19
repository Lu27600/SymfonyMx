<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 20:04
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Moderation;
use Symfony\Component\HttpFoundation\Response;

class PersistModerationHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-moderation:id="(\d+)",uuid="(.+)",ismute="(.+)",muteend="(.+)",isjail="(.+)",jailend="(.+)",isban="(.+)",banend="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8',$data);
            list ($id,$uuid,$ismute, $muteend,$isjail,$jailend,$isban,$banend) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($moderation = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Moderation')->find($id))){
                $moderation = new Moderation();
            }
            $moderation->setUuid($uuid);
            if ($ismute == 'true'){$moderation->setIsmute(true);}elseif($ismute == 'false'){$moderation->setIsmute(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
            $moderation->setMuteend(intval($muteend));
            if ($isjail == 'true'){$moderation->setIsjail(true);}elseif($isjail == 'false'){$moderation->setIsjail(false);}else {return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
            $moderation->setJailend(intval($jailend));
            if ($isban == 'true'){$moderation->setIsban(true);}elseif($isban == 'false'){$moderation->setIsban(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
            $moderation->setBanend(intval($banend));

            $em->persist($moderation);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $moderations = explode('-',$str);

            foreach ($moderations as $m){
                $regex = '/-?moderation:id="(\d+)",uuid="(.+)",ismute="(.+)",muteend="(.+)",isjail="(.+)",jailend="(.+)",isban="(.+)",banend="(.+)",?/';
                if ( !(preg_match($regex,$m))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8',$m);
                list ($id,$uuid,$ismute, $muteend,$isjail,$jailend,$isban,$banend) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($moderation = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Moderation')->find($id))){
                    $moderation = new Moderation();
                }
                $moderation->setUuid($uuid);
                if ($ismute == 'true'){$moderation->setIsmute(true);}elseif($ismute == 'false'){$moderation->setIsmute(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
                $moderation->setMuteend(intval($muteend));
                if ($isjail == 'true'){$moderation->setIsjail(true);}elseif($isjail == 'false'){$moderation->setIsjail(false);}else {return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
                $moderation->setJailend(intval($jailend));
                if ($isban == 'true'){$moderation->setIsban(true);}elseif($isban == 'false'){$moderation->setIsban(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
                $moderation->setBanend(intval($banend));

                $em->persist($moderation);
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