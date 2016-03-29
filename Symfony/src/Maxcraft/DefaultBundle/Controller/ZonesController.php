<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:27
 */

namespace Maxcraft\DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


class ZonesController extends Controller
{
    /**
     * @param $webZoneId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function parcelleAction($webZoneId){

        $user = $this->getUser();

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:WebZone');
        $wzone= $rep->findOneById($webZoneId);

        if($wzone == NULL)
        {
            throw $this->createNotFoundException('Cette parcelle n\'existe pas ! (id : '.$webZoneId.')');
        }

        //$mapUrl = $this->container->getParameter('map_url');
        //$mapUrl .= '?worldname='.$zone->getWorld().'&mapname=flat&zoom=4&x='.$zone->getCenter()['x'].'&y=64&z='.$zone->getCenter()['z'];

        //cuboiders

        $cuboiders = $this->getDoctrine()->getManager()
            ->createQuery('SELECT b FROM MaxcraftDefaultBundle:Builder b WHERE b.role = \'CUBO\' AND b.zone = '.$wzone->getId())
            ->getResult();

        $builders = $this->getDoctrine()->getManager()
            ->createQuery('SELECT b FROM MaxcraftDefaultBundle:Builder b WHERE b.role = \'BUILD\' AND b.zone = '.$wzone->getId())
            ->getResult();

        //zone filles
        $filles =  $this->getDoctrine()->getManager()
            ->createQuery('SELECT z FROM MaxcraftDefaultBundle:Zone z WHERE z.parent = '.$wzone->getId())
            ->getResult();


        //vente
        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:OnSaleZone');
        $vente = $rep->findOneByZone($wzone->getServZone());

        //TODO à faire !!!! (shops)
        /*//shops
        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Shop');
        $shops = $rep->findByZone($zone->getId());*/

        return $this->render('MaxcraftDefaultBundle:Zones:parcelle.html.twig', array(
            'zone' => $wzone,
            //'mapurl' => $mapUrl,
            'cuboiders' => $cuboiders,
            'builders' => $builders,
            'filles' => $filles,
            'vente' => $vente,
            //'shops' => $shops,
        ));
    }

    public function zoneAction($zone)
    {

        //En vente ?

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:OnSaleZone');
        $vente = $rep->findOneByZone($zone);


        return $this->render('MaxcraftDefaultBundle:Zones:zone.html.twig', array(
            'parcelle' => $zone,
            'vente' => $vente,
        ));
    }
}