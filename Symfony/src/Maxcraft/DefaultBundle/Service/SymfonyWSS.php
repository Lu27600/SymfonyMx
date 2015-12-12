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
        $this->registerHandler("ZONES-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\ZonesInfosHandler');
        $this->registerHandler("MODERATIONS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\ModerationsInfosHandler');
        $this->registerHandler("JOBS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\JobsInfosHandler');
        $this->registerHandler("FACTIONS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\FactionsInfosHandler');
        $this->registerHandler("ALLONSALEZONE-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\AllOnSaleZoneInfosHandler');
        $this->registerHandler("ALLPERMS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\AllPermsInfosHandler');
        $this->registerHandler("PLAYERS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\PlayersInfosHandler');
        $this->registerHandler("ALLRENTZONE-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\AllRentZoneInfosHandler');
        $this->registerHandler("WORLDS-INFOS", 'Maxcraft\\DefaultBundle\\Websocket\\WorldInfosHandler');
        $this->registerHandler("ZONE-PERSIST",'Maxcraft\\DefaultBundle\\Websocket\\PersistZoneHandler');
        $this->registerHandler("FACTION-PERSIST",'Maxcraft\\DefaultBundle\\Websocket\\PersistFactionHandler');
        $this->registerHandler("JOB-PERSIST", 'Maxcraft\\DefaultBundle\\Websocket\\PersistJobHandler');
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