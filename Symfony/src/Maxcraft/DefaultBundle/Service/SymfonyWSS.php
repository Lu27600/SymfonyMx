<?php


namespace Maxcraft\DefaultBundle\Service;


use Doctrine\Bundle\DoctrineBundle\Registry;
use NathemWS\NathemWSS;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class SymfonyWSS extends NathemWSS
{
    private  $container;

    /**
     * @param $container
     * @param $key
     */
    public function __construct($container, $key)
    {
        parent::__construct("MaxcraftPhpServer", $key);
        $this->container = $container;

        //Handlers :
        $this->registerHandler("JOB-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\JobInfosHandler');
        $this->registerHandler("FACTION-INFOS", 'Maxcraft\\DefaultBundle\\WeSocket\\FactionInfosHandler');
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }



    public function start()
    {
        $runningServer = IoServer::factory(new HttpServer(new WsServer($this)), 7689);
        $runningServer->run();

    }


}