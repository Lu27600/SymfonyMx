<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:26
 */

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Album;
use Maxcraft\DefaultBundle\Form\AlbumType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function myalbumsAction(Request $request){ //TODO fnr quand registerAction(), et images faites

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $albums = $em->getRepository('MaxcraftDefaultBundle:Album')->findAlbums($user, true);

        $album = new Album();
        $albumForm = $this->get('form.factory')->create(new AlbumType(), $album);

        if ($request->isMethod('POST')){
            $albumForm->handleRequest($request);

            if ($albumForm->isValid()){
                $album->setUser($user);

                $em->persist($album);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', "Album '".$album->getName()."' créé !");

                return $this->redirect($this->generateUrl('maxcraft_album_edit', array('albumId' => $album->getId())));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($album);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }
        }

        return $this->render('MaxcraftDefaultBundle:User:myalbums.html.twig', array(
            'albums' => $albums,
            'form' => $albumForm->createView()
        ));
    }

    /**
     * @param $albumId
     * @param Request $request
     * @return Response
     * @Security("has_role('ROLE_USER')")
     */
    public function editAlbumAction($albumId, Request $request){
        $em = $this->getDoctrine()->getManager();

        if ( !($album = $em->getRepository('MaxcraftDefaultBundle:Album')->findOneById($albumId))) throw $this->createNotFoundException('Cet album n\'existe pas !');
        if($album->getUser() != $this->getUser() AND $this->getUser()->getRole() != 'ROLE_ADMIN')
        {
            throw $this->createNotFoundException('Vous n\'avez pas la permission d\'éditer cet album.');
        }

        $albumForm = $this->createFormBuilder($album)
            ->add('name', 'text')
            ->add('description', 'textarea')
            ->add('display', 'choice', array(
                'choices' => array('1' => 'Oui', '0' => 'Non'),
            ))
            ->add('Enregistrer', new SubmitType())
            ->getForm();

        if ($request->isMethod('POST')){
            $albumForm->handleRequest($request);

            if ($albumForm->isValid()){

                $em->persist($album);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', "Album '".$album->getName()."' modifié !");

                return $this->redirect($this->generateUrl('maxcraft_album_edit', array('albumId' => $album->getId())));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($album);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }
        }

        return $this->render('MaxcraftDefaultBundle:User:albumedit.html.twig', array(
            'album' => $album,
            'form' =>$albumForm->createView()
        ));
    }

    public function profilAction($pseudo){
        $em = $this->getDoctrine()->getManager();

        if ( !($user = $em->getRepository('MaxcraftDefaultBundle:User')->findOneByUsername($pseudo))) throw $this->createNotFoundException('Joueur introuvable avec le pseudo donné !');
        if($user == NULL) {
            throw $this->createNotFoundException('Le joueur "'.$pseudo.'" n\'est pas inscrit !');
        }

        if(!($this->get('security.context')->isGranted('ROLE_USER'))) {
            $visitor = true;
            $myprofil = false;
        }
        else {
            $visitor = false;

            if($user == $this->getUser()) {
                $myprofil = true;
            }
            else {
                $myprofil = false;
            }
        }

        //ALBUMS
        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Album');
        $albums = $rep->findAlbums($user);

        //Zones
        $zones = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->findWebZones($user);

        //argent
        $balance = $user->getPlayer()->getBalance();

        //Gametime
        $GT = $user->getPlayer()->getGametime();
        $mGT = $GT%60;
        $hGT = floor($GT/60);

        //last co
        //TODO dernière connexion

        return $this->render('MaxcraftDefaultBundle:User:profil.html.twig', array(
            'user' => $user,
            'zones' => $zones,
            'balance' => $balance,
            'mGT' => $mGT,
            'hGT' => $hGT
        ));
    }
}