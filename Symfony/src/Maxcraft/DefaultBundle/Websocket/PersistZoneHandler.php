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
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8;$9',$data);
            list ($id,$name,$parent,$points,$owner,$world,$tags,$builders,$cuboiders) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();
            if ( !($zone = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Zone')->find($id))){
                $zone = new Zone();
            }

            if ($name == 'null'){$zone->setName(null);} else{$zone->setName($name);}
            if ($parent == 'null') {$zone->setParent(null);} else {$zone->setParent(intval($parent));}
            $zone->setPoints($points);
            if ($owner == 'null'){$zone->setOwner(null);}else {$zone->setOwner($owner);}
            $zone->setWorld($world);
            if($tags == 'null'){$zone->setTags(null);}else{$zone->setTags($tags);}
            if ($builders == 'null'){$zone->setBuilders(null);}else{$zone->setBuilders($builders);}
            if ($cuboiders == 'null'){$zone->setCuboiders(null);}else{$zone->setCuboiders($cuboiders);}

            $em->persist($zone);
            $em->flush();

            return new Response(json_encode(array('error'=>false)));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets
            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $zones = explode('-',$str);

            foreach ($zones as $z){
                $regex = '/-?zone:id="(\d+)",name="(.+)",parent="(\d+)",points="(.+)",owner="(.+)",world="(.+)",tags="(.+)",builders="(.+)",cuboiders="(.+)",?/';
                if ( !(preg_match($regex,$z))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3;$4;$5;$6;$7;$8;$9',$z);
                list ($id,$name,$parent,$points,$owner,$world,$tags,$builders,$cuboiders) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($zone = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Zone')->find($id))){
                    $zone = new Zone();
                }

                if ($name == 'null'){$zone->setName(null);} else{$zone->setName($name);}
                if ($parent == 'null') {$zone->setParent(null);} else {$zone->setParent(intval($parent));}
                $zone->setPoints($points);
                if ($owner == 'null'){$zone->setOwner(null);}else {$zone->setOwner($owner);}
                $zone->setWorld($world);
                if($tags == 'null'){$zone->setTags(null);}else{$zone->setTags($tags);}
                if ($builders == 'null'){$zone->setBuilders(null);}else{$zone->setBuilders($builders);}
                if ($cuboiders == 'null'){$zone->setCuboiders(null);}else{$zone->setCuboiders($cuboiders);}

                $em->persist($zone);
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