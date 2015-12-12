<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/12/15
 * Time: 22:03
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Maxcraft\DefaultBundle\Entity\Perms;
use Symfony\Component\HttpFoundation\Response;

class PersistPermsHandler extends MaxcraftHandler
{

    protected function handle($data)
    {

        json_decode($data);
        strval($data);

        if ( (substr_count($data, '-'))==1){
            //Un objet

            $regex = '/-perms:id="(\d+)",groupname="(.+)",perms="(.+)",?/';
            if ( !(preg_match($regex,$data))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

            $str = preg_replace($regex,'$1;$2;$3',$data);
            list ($id,$groupName,$perms) = explode(';',$str);

            $em = $this->getDoctrine()->getManager();

            if (!($perm = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Perms')->find($id))){
                $perm = new Perms();
            }
            $perm->setGroupName($groupName);
            $perm->setPerms($perms);

            $em->persist($perm);
            $em->flush();

            return new Response(json_encode(array('error'=>false )));
        }
        elseif (substr_count($data, '-')>1){
            //Plusieurs objets

            if ( $data[0]!='-') return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));
            $str = $data[0]=null;
            $permissions = explode('-',$str);

            foreach ($permissions as $p){
                $regex = '/-?perms:id="(\d+)",groupname="(.+)",perms="(.+)",?/';
                if ( !(preg_match($regex,$p))) return new Response(json_encode(array('error'=>true,'errorMessage'=>'La chaine de caractère envoyée ne match pas avec le pattern ')));

                $string = preg_replace($regex,'$1;$2;$3',$p);
                list ($id,$groupName,$perms) = explode(';',$string);

                $em = $this->getDoctrine()->getManager();

                if (!($perm = $this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:Perms')->find($id))){
                    $perm = new Perms();
                }
                $perm->setGroupName($groupName);
                $perm->setPerms($perms);

                $em->persist($perm);
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