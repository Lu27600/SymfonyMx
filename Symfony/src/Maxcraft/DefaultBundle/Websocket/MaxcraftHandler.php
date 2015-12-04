<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 03/12/15
 * Time: 20:50
 */

namespace Maxcraft\DefaultBundle\Websocket;


use Doctrine\Bundle\DoctrineBundle\Registry;
use NathemWS\NathemWSHandler;


abstract class MaxcraftHandler extends NathemWSHandler
{

    /**
     * @return Registry
     */
    public function getDoctrine() {


        return $this->client->getServer()->getContainer()->get('doctrine');
    }

}