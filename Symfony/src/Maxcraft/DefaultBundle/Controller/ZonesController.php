<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:27
 */

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\AlbumRepository;
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

    /**
     * @param $webZoneId
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editParcelleAction($webZoneId, Request $request){

        $user = $this->getUser();

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:WebZone');
        $wzone= $rep->findOneById($webZoneId);

        if($wzone == NULL)
        {
            throw $this->createNotFoundException('Cette parcelle n\'existe pas ! (id : '.$webZoneId.')');
        }

        if(!($user->getRole() == 'ROLE_ADMIN' OR $wzone->getServZone()->getOwner() == $user))
        {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette parcelle !');
        }

        define("ID", $this->getUser()->getId());

        //FORM
        $form = $this->createFormBuilder($wzone)
            ->add('name', 'text')
            ->add('description', 'froala', array('required' => false))
            ->add('album', 'entity', array(
                'class' => 'MaxcraftDefaultBundle:Album',
                'query_builder' => function(AlbumRepository $er, $id = ID) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.id', 'ASC')
                        ->where('a.user = '.$id);
                },
                'choice_label' => 'name',
                'group_by' => 'album.user',
                'placeholder' => 'Aucun',
                'empty_data'  => null,
                'required' => false,
            ))
            ->getForm();

        //POST FORM

        //recuperation form

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);


            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $szone= $wzone->getServZone();
                $szone->setName($request->request->get('name'));
                $em->persist($wzone, $szone);


                $em->flush();

                //maxcraft
                //TODO WS
                //$this->get('minecraft')->reloadZone($zone->getId());


                $this->get('session')->getFlashBag()->add('info', 'Les informations de la parcelle ont été modifiées.');
                return $this->redirect($this->generateUrl('parcelle', array('webZoneId' => $wzone->getId())));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($wzone);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }

        }


        return $this->render('MaxcraftDefaultBundle:Zones:editparcelle.html.twig', array(
            'form' => $form->createView(),
            'zone' => $wzone,
        ));

    }
}