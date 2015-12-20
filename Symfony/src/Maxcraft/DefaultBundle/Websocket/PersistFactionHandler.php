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
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8;$9;$10;$11;$12;$13;$14',$data);
            list ($id,$uuid,$name,$tag,$balance,$spawn,$jail,$owner,$heads,$members,$recruits,$enemies,$allies,$icon,$banner) = explode(';',$str);

            if ( !(preg_match('/\d+\.\d+/',$balance))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            list ($entier,$decimal) = explode('.',$balance);
            $balance1 = $entier.'.'.$decimal;
            doubleval($balance1);

            $em = $this->getDoctrine()->getManager();

            if (!($faction = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultManager:Faction')->find($id))){
                $faction = new Faction();
            }

            $faction->setUuid($uuid);
            $faction->setName($name);
            $faction->setTag($tag);
            $faction->setBalance($balance1);
            if ($spawn == 'null'){$faction->setSpawn(null);}else{$faction->setSpawn($spawn);}
            if ($jail == 'null'){$faction->setJail(null);}else{$faction->setJail($jail);}
            $faction->setOwner($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->findByName($owner));
            if ($heads == 'null'){$faction->setHeads(null);}else{$faction->setHeads($heads);}
            if ($members == 'null'){$faction->setMembers(null);}else{$faction->setMembers($members);}
            if ($recruits == 'null'){$faction->setRecruits(null);}else{$faction->setRecruits($recruits);}
            if ($enemies == 'null'){$faction->setEnemies(null);}else{$faction->setEnemies($enemies);}
            if ($allies == 'null'){$faction->setAllies(null);}else{$faction->setAllies($allies);}
            if ($icon == 'null'){$faction->setIcon(null);}else{$faction->setIcon($icon);}
            if ($banner == 'null'){$faction->setBanner(null);}else{$faction->setBanner($banner);}

            $em->persist($faction);
            $em->flush();

            return new Response(json_encode(array('error'=>false)));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets
            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $factions = explode('-',$str);

            foreach ($factions as $f){
                $regex = '/-?faction:id="(\d+)",uuid="(.+)",name="(.+)",tag="(.+)",balance="(.+)",spawn="(.+)",jail="(.+)",owner="(.+)",heads="(.+)",members="(.+)",recruits="(.+)",enemies="(.+)",allies="(.+)",icon="(.+)",banner="(.+)",?/';
                if ( !(preg_match($regex,$f))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8;$9;$10;$11;$12;$13;$14',$f);
                list ($id,$uuid,$name,$tag,$balance,$spawn,$jail,$owner,$heads,$members,$recruits,$enemies,$allies,$icon,$banner) = explode(';',$str);

                if ( !(preg_match('/\d+\.\d+/',$balance))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
                list ($entier,$decimal) = explode('.',$balance);
                $balance1 = $entier.'.'.$decimal;
                doubleval($balance1);

                $em = $this->getDoctrine()->getManager();

                if (!($faction = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Faction')->find($id))){
                    $faction = new Faction();
                }

                $faction->setUuid($uuid);
                $faction->setName($name);
                $faction->setTag($tag);
                $faction->setBalance($balance1);
                if ($spawn == 'null'){$faction->setSpawn(null);}else{$faction->setSpawn($spawn);}
                if ($jail == 'null'){$faction->setJail(null);}else{$faction->setJail($jail);}
                $faction->setOwner($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->findByName($owner));
                if ($heads == 'null'){$faction->setHeads(null);}else{$faction->setHeads($heads);}
                if ($members == 'null'){$faction->setMembers(null);}else{$faction->setMembers($members);}
                if ($recruits == 'null'){$faction->setRecruits(null);}else{$faction->setRecruits($recruits);}
                if ($enemies == 'null'){$faction->setEnemies(null);}else{$faction->setEnemies($enemies);}
                if ($allies == 'null'){$faction->setAllies(null);}else{$faction->setAllies($allies);}
                if ($icon == 'null'){$faction->setIcon(null);}else{$faction->setIcon($icon);}
                if ($banner == 'null'){$faction->setBanner(null);}else{$faction->setBanner($banner);}

                $em->persist($faction);
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