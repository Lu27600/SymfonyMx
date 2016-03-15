<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:29
 */

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\AlbumRepository;
use Maxcraft\DefaultBundle\Entity\News;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller{

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newsAction(){
        $news = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:News')->findAll();

        return $this->render('MaxcraftDefaultBundle:Admin:news.html.twig', array(
            'newslist' => $news
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param null $newsId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newNewsAction($newsId = null, Request $request){
        $em = $this->getDoctrine()->getManager();

        if ($newsId == null){
            $news = new News();
        }
        else{
            $news = $em->getRepository('MaxcraftDefaultBundle:News')->find($newsId);
        }

        //define("ID", $this->getUser()->getId());


        //form
        $form = $this->createFormBuilder($news)
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('display', 'choice', array('choices' => array('0' => 'Non', '1' => 'Oui')))
            ->add('album', 'entity', array(
                'class' => 'MaxcraftDefaultBundle:Album',

                'query_builder' => function(AlbumRepository $er) {

                    return $er->createQueryBuilder('a')->orderBy('a.name', 'ASC');

                },

                'choice_label' => 'name',
                'group_by' => 'album.user.username',
                'placeholder' => 'Aucun',
                'empty_data'  => null,
                'required' => false,
            ))
            ->add('Valider', new SubmitType())
            ->getForm();

        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isValid()){
                $news->setUser($this->getUser());
                $em->persist($news);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'La news a été enregistrée !');

                return $this->redirect($this->generateUrl('admin_news'));
            }
        }

        $validator = $this->get('validator');
        $errorList = $validator->validate($news);

        foreach($errorList as $error)
        {
            $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
        }

        return $this->render('MaxcraftDefaultBundle:Admin:newnews.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function infosAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('MaxcraftDefaultBundle:User')->findAll();

        $total=0;
        $circulation = 0;
        $totalUsers = count($users);
        $ttactivesUser = 0;
        $nbSleepingUsers = count($em->getRepository('MaxcraftDefaultBundle:Session')->getSleepingUsers());
        foreach ($users as $u) {
            $total = $total + $u->getPlayer()->getBalance();
            if ($u->getActif()){
                $circulation = $circulation+ $u->getPlayer()->getBalance();
                $ttactivesUser++;
            }
        }

        $moyPerUser = $total/$totalUsers;
        $moyPerActives = $circulation/$ttactivesUser;
        $inactifs = $totalUsers - $ttactivesUser;
        $percentActivesU = round(($ttactivesUser/$totalUsers)*100);
        $percentSleepingU = round(($nbSleepingUsers/$totalUsers)*100);


        return $this->render('MaxcraftDefaultBundle:Admin:infos.html.twig', array(
            'ttPOs' => $total,
            'circulation' => $circulation,
            'moyPerUsers' => $moyPerUser,
            'moyPerActives' => $moyPerActives,
            'totalUsers' => $totalUsers,
            'activeUsers' => $ttactivesUser,
            'inactivesUser' => $inactifs,
            'actifsPercent' =>$percentActivesU,
            'nbsleeping' => $nbSleepingUsers,
            'percentSleepingU' => $percentSleepingU
        ));

    }


}