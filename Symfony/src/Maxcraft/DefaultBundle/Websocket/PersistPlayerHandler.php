<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 22:09
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Player;
use Symfony\Component\HttpFoundation\Response;

class PersistPlayerHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-player:id="(\d+)",uuid="(.+)",pseudo="(.+)",balance="(.+),actif="(.+)",vanished="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6',$data);
            list ($id,$uuid,$pseudo,$balance,$actif,$vanished) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($player = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Player')->find($id))){
                $player = new Player();
            }
            $player->setUuid($uuid);
            $player->setPseudo($pseudo);
            $player->setBalance(doubleval($balance));
            if ($actif == 'true'){$player->setActif(true);}elseif($actif == 'false'){$player->setActif(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
            if ($vanished == 'true') {$player->setVanished(true);} elseif ($vanished == 'false') {$player->setVanished(false);} else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}

            $em->persist($player);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $players = explode('-',$str);

            foreach ($players as $p){
                $regex = '/-?player:id="(\d+)",uuid="(.+)",pseudo="(.+)",balance="(.+),actif="(.+)",vanished="(.+)",?/';
                if ( !(preg_match($regex,$p))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3;$4;$5;$6',$p);
                list ($id,$uuid,$pseudo,$balance,$actif,$vanished) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($player = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Player')->find($id))){
                    $player = new Player();
                }
                $player->setUuid($uuid);
                $player->setPseudo($pseudo);
                $player->setBalance(doubleval($balance));
                if ($actif == 'true'){$player->setActif(true);}elseif($actif == 'false'){$player->setActif(false);}else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}
                if ($vanished == 'true') {$player->setVanished(true);} elseif ($vanished == 'false') {$player->setVanished(false);} else{return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));}


                $em->persist($player);
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