<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 03/04/16
 * Time: 19:09
 */

namespace Maxcraft\DefaultBundle\Websocket\Requests;


use NathemWS\NathemWSRequest;

abstract class MaxcraftRequest extends NathemWSRequest
{

    /**
     * @return mixed
     */
    public function getDoctrine() {


        return $this->client->getServer()->getContainer()->get('doctrine');
    }

    protected function arrayToString(Array $array){

        $result = "";

        foreach($array as $a){
            $result = $a.';';
        }

        strval($result);
        if ($result[strlen($result)-1]==';') $result[strlen($result)-1]=null;

        return $result;
    }

}