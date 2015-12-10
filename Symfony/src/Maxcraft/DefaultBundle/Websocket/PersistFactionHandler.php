<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/12/15
 * Time: 20:07
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Faction;
use Symfony\Component\HttpFoundation\Response;

class PersistFactionHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet
            $regex = '/-faction:id="(\d+)",uuid="(.+)",name="(.+)",tag="(.+)",balance="(.+)",spawn="(.+)",jail="(.+)",owner="(.+)",heads="(.+)",members="(.+)",recruits="(.+)",enemies="(.+)",allies="(.+)",icon="(.+)",banner="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$2;$3;$4;$5;$6;$7;$8;$9;$10;$11;$12;$13;$14',$data);
            list ($uuid,$name,$tag,$balance,$spawn,$jail,$owner,$heads,$members,$recruits,$enemies,$allies,$icon,$banner) = explode(';',$str);
            if ( !(preg_match('/\d+\.\d+/',$balance))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            list ($entier,$decimal) = explode('.',$balance);
            $balance1 = $entier.'.'.$decimal;
            doubleval($balance1);

            $em = $this->getDoctrine()->getManager();
            $faction = new Faction();
            $faction->setUuid($uuid);
            $faction->setName($name);
            $faction->setTag($tag);
            $faction->setBalance($balance1);
            $faction->setSpawn($spawn);
            $faction->setJail($jail);
            $faction->setOwner($owner);
            $faction->setHeads($heads);
            $faction->setMembers($members);
            $faction->setRecruits($recruits);
            $faction->setEnemies($enemies);
            $faction->setAllies($allies);
            $faction->setIcon($icon);
            $faction->setBanner($banner);

            $em->persist($faction);
            $em->flush();

            return new Response(json_encode(array('error'=>false)));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets
            if ( $data[0]!='-') return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $factions = explode('-',$str);

            foreach ($factions as $f){
                $regex = '/-?faction:id="(\d+)",uuid="(.+)",name="(.+)",tag="(.+)",balance="(.+)",spawn="(.+)",jail="(.+)",owner="(.+)",heads="(.+)",members="(.+)",recruits="(.+)",enemies="(.+)",allies="(.+)",icon="(.+)",banner="(.+)",?/';
                if ( !(preg_match($regex,$f))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $str = preg_replace($regex,'$2;$3;$4;$5;$6;$7;$8;$9;$10;$11;$12;$13;$14',$f);
                list ($uuid,$name,$tag,$balance,$spawn,$jail,$owner,$heads,$members,$recruits,$enemies,$allies,$icon,$banner) = explode(';',$str);
                if ( !(preg_match('/\d+\.\d+/',$balance))) return new Response(json_encode(array('error'=>'true','errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
                list ($entier,$decimal) = explode('.',$balance);
                $balance1 = $entier.'.'.$decimal;
                doubleval($balance1);

                $em = $this->getDoctrine()->getManager();
                $faction = new Faction();
                $faction->setUuid($uuid);
                $faction->setName($name);
                $faction->setTag($tag);
                $faction->setBalance($balance1);
                $faction->setSpawn($spawn);
                $faction->setJail($jail);
                $faction->setOwner($owner);
                $faction->setHeads($heads);
                $faction->setMembers($members);
                $faction->setRecruits($recruits);
                $faction->setEnemies($enemies);
                $faction->setAllies($allies);
                $faction->setIcon($icon);
                $faction->setBanner($banner);

                $em->persist($faction);
                $em->flush();
            }
            return new Response(json_encode(array('error'=> false)));
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