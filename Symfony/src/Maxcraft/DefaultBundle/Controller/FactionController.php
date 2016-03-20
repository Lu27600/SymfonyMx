<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:25
 */

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Faction;
use Maxcraft\DefaultBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class FactionController extends Controller {

    public function infosAction($factionTag){

        $faction = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Faction')->findOneBy(array('tag' => strtoupper($factionTag)));
        if ($faction == null){

            $message = 'La Faction '. strtoupper($factionTag).' n\'a pas été trouvée';

            throw $this->createNotFoundException('La Faction '. strtoupper($factionTag).' n\'a pas été trouvée');

            /*return $this->render('MaxcraftDefaultBundle:Others:error.html.twig', array(
                'content' => $message
            ));*/
        }

        if(!($this->get('security.context')->isGranted('ROLE_USER'))) {

            $visitor = true;
            $ismine = false;
        }
        else {
            $visitor = false;
            if($faction == $this->getUser()->getFaction())
            {
                $ismine = true;

            }
            else
            {
                $ismine = false;
            }
        }

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User');
        $members= $rep->findByFaction($faction);

        //ALLIES ET ENEMIES
        $allies = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:FactionRole')->findAllies($faction);
        $enemies = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:FactionRole')->findEnemies($faction);

        if( !$ismine AND !$visitor AND $this->getUser()->getFaction())
        {
            $status = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:FactionRole')->findStateObject($faction, $this->getUser()->getFaction());
        }
        else
        {
            $status = null;
        }

        return $this->render('MaxcraftDefaultBundle:Faction:factioninfo.html.twig', array(
            'faction' => $faction,
            'ismine' => $ismine,
            'nbmembers' => count($members),
            'members' => $members,
            'visitor' => $visitor,
            'allies' => $allies,
            'enemies' => $enemies,
            'status' => $status,
            'tag' => strtoupper($faction->getTag())
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createFactionAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $faction = new Faction($this);

        if($this->getUser()->isFactionOwner())
        {
            return $this->render('MaxcraftDefaultBundle:Others:error.html.twig', array(
                'content' => 'Vous êtes déjà fondateur de faction !'
            ));
        }

        if($this->getUser()->getFaction())
        {
            $this->get('session')->getFlashBag()->add('alert', 'Si vous créez une faction vous quitterez votre faction actuelle.');
        }

        $prixFaction = $this->container->getParameter('prix_faction');

        //form
        $form = $this->createFormBuilder($faction)
            ->add('name', 'text')
            ->add('tag', 'text')
            ->add('icon', 'text', array('required' => false))
            ->add('Valider et payer !', new SubmitType())
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);


            $moneyvalid = true;


            if($this->getUser()->getBalance() <  $prixFaction)
            {
                $this->get('session')->getFlashBag()->add('alert', 'Vous devez disposer d\'au moins '.$prixFaction.' POs pour créer une faction !');
                $moneyvalid = false;
            }

            if($form->isValid() AND $moneyvalid)
            {
                $user = $this->getUser();
                $faction->setOwner($user);
                $user->setFactionRole(10);
                $user->setFaction($faction);
                $em->persist($user);
                $em->persist($faction);
                $em->flush();

                //maxcraft

                //TODO webSocket
                /*$this->get('minecraft')->loadFaction($faction->getId());
                $this->get('minecraft')->loadPlayer($user->getId());*/

                return $this->redirect($this->generateUrl('maxcraft_faction', array('factionTag' => $faction->getTag())));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($faction);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }

        }



        return $this->render('MaxcraftDefaultBundle:Faction:newfaction.html.twig', array(
            'form' => $form->createView(),
            'prixfaction' => $prixFaction
        ));
    }

    public function factionsListAction(){
        $factions = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Faction')->findBy(
            array(),
            array('tag' => 'desc')
        );

        $prixFaction = $this->container->getParameter('prix_faction');

        return $this->render('MaxcraftDefaultBundle:Faction:factionlist.html.twig', array(

            'factions' => $factions,
            'prix' => $prixFaction,
        ));
    }

    /**
     * @param $mpId
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function acceptrequestAction($mpId){

        $user = $this->getUser();

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:MP');
        $mp= $rep->findOneById($mpId);

        if($mp == null)
        {
            throw $this->createNotFoundException('Cette requete n\'existe pas !');
            /*return $this->render('MaxcraftDefaultBundle:error.html.twig', array(
                'content' => 'Cette requete n\'existe pas !'
            ));*/
        }
        if($mp->getTarget() != $this->getUser())
        {
            throw $this->createNotFoundException('Ce MP ne vous est pas adressé !');
            /*return $this->render('MaxcraftDefaultBundle:error.html.twig', array(
                'content' => 'Ce MP ne vous est pas adressé !'
            ));*/
        }

        if($user->getFaction() == null OR $user->getFaction()->getOwner() != $this->getUser())
        {

            throw $this->createNotFoundException('Vous n\'êtes pas fondateur de faction !');
        }

        if($user->getFaction() == $mp->getSender()->getFaction())
        {

            throw $this->createNotFoundException('Ce joueur est déjà dans votre faction !');
        }

        if($mp->getSender()->isFactionOwner())
        {
            throw $this->createNotFoundException('Vous ne pouvez pas recruter un chef de faction !');
        }

        $em = $this->getDoctrine()->getManager();

        $notif = new Notification($this);
        $notif->setContent('Vous avez été accepté dans la faction <strong>'.$user->getFaction()->getName().'</strong>. Félicitations ! Si vous êtiez dans une autre faction avant vous l\'avez quittée.');
        $notif->setType('FACTION');
        $notif->setUser($mp->getSender());
        $notif->setView(false);
        $em->persist($notif);

        $this->notifFaction($user->getFaction(), '<strong>'.$mp->getSender()->getUsername().'</strong> est entré dans votre faction ! Souhaitez lui la bienvenue.');

        $mp->getSender()->setFaction($user->getFaction());
        $mp->getSender()->setFactionRole(1);

        $em->remove($mp);

        $em->persist($mp->getSender());
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', '<strong>'.$mp->getSender()->getUsername().'</strong> à été accepté dans votre faction !');
        return $this->redirect($this->generateUrl('maxcraft_messages'));
    }

    public function notifFaction($faction, $message, $type = 'FACTION', $view = false)
    {
        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User');
        $members= $rep->findByFaction($faction);

        $em = $this->getDoctrine()->getManager();
        foreach($members as $member)
        {
            $notif = new Notification($this);
            $notif->setContent($message);
            $notif->setType($type);
            $notif->setUser($member);
            $notif->setView($view);
            $em->persist($notif);
        }

        $em->flush();
    }

    public function testAddAction($pseudo, $tag){
        $user = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->findOneByUsername($pseudo);
        $faction = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Faction')->findOneByTag(strtoupper($tag));

        if ($user == null || $faction == null){
            throw $this->createNotFoundException('user ou fac null');
        }

        $user->setFaction($faction);
        $this->getDoctrine()->getManager()->persist($user, $faction);
        $this->getDoctrine()->getManager()->flush();

        return $user->getUsername().' ajouté à '.$faction->getName();
    }
}