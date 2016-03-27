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
use Maxcraft\DefaultBundle\Entity\Page;
use Maxcraft\DefaultBundle\Entity\PageSection;
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminMenuAction(){

        $nbbugs = count($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Bug')->findBy(
            array('fixed' => false)
        ));

        $nbShopsDemand = count($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:WebZone')->findBy(
            array('shopDemand' => true)
        ));

        return $this->render('MaxcraftDefaultBundle:Admin:menu.html.twig', array(
            'nbbugs' => $nbbugs,
            'nbShopsDemand' => $nbShopsDemand,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function usersAction(){

        $users = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->findBy(
            array(),
            array('id' => 'desc')
        );

        return $this->render('MaxcraftDefaultBundle:Admin:users.html.twig', array(
            'users' => $users,

        ));
    }

    /**
     * @param $userId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editUserAction($userId, Request $request){

        $em = $this->getDoctrine()->getManager();


        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User');
        $user= $rep->findOneById($userId);

        if($user == null)
        {
            throw $this->createNotFoundException('Ce joueur n\'est pas inscrit.');
        }

        //form
        $form = $this->createFormBuilder($user)
            ->add('email', 'text')
            ->add('password', 'password', array('required' => false))
            ->add('role', 'choice', array('choices' => array('ROLE_USER' => 'Joueur', 'ROLE_MODO' => 'Modo', 'ROLE_ADMIN' => 'Admin')))
            ->add('naissance', 'text', array('required' => false))
            ->add('loisirs', 'text', array('required' => false))
            ->add('fromwhere', 'text', array('required' => false))
            ->add('activite', 'text', array('required' => false))
            ->add('profil', 'froala', array('required' => false))
            ->add('ip', 'text', array('required' => false))
            ->add('banned', 'choice', array('choices' => array('1' => 'Oui', '0' => 'Non')))
            ->getForm();

        //traitement
        $password = $user->getPassword();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);



            if($form->isValid())
            {

                if($user->getPassword() == '' OR $user->getPassword() == null)
                {
                    $user->setPassword($password);
                }
                else
                {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                    $user->setPassword($newpassword);
                }

                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Les paramètres du joueur ont été modifiés !');

                return $this->redirect($this->generateUrl('admin_users'));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($user);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }

        }



        return $this->render('MaxcraftDefaultBundle:Admin:edituser.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function guideAction(){

        $pages = $this->getDoctrine()->getManager()->createQuery('SELECT p FROM MaxcraftDefaultBundle:Page p ORDER BY p.ordervalue ASC')->getResult();
        return $this->render('MaxcraftDefaultBundle:Admin:guide.html.twig', array(
            'pages' => $pages,
        ));
    }

    /**
     * @param null $pageId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editpageAction($pageId = null, Request $request){

        $em = $this->getDoctrine()->getManager();

        if($pageId == null)
        {
            $page = new Page();
        }
        else
        {
            $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Page');
            $page= $rep->findOneById($pageId);
        }

        if($page == null)
        {
            throw $this->createNotFoundException('Cette page n\'existe pas !');
        }

        //form
        $form = $this->createFormBuilder($page)
            ->add('title', 'text')
            ->add('route', 'text')
            ->add('ordervalue', 'integer')
            ->add('display', 'choice', array('choices' => array('1' => 'Oui', '0' => 'Non')))
            ->getForm();

        //traitement
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isValid())
            {



                $em->persist($page);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'La page a été editée !');

                return $this->redirect($this->generateUrl('admin_guide'));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($page);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }

        }



        return $this->render('MaxcraftDefaultBundle:Admin:editpage.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function sectionsAction($page){

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Page');
        $page = $rep->findOneByRoute($page);



        if($page == NULL)
        {
            throw $this->createNotFoundException('Cette page du guide n\'existe pas');
        }


        $sections = $this->getDoctrine()->getManager()->createQuery('SELECT s FROM MaxcraftDefaultBundle:PageSection s WHERE s.page = '.$page->getId().' ORDER BY s.ordervalue ASC')->getResult();

        return $this->render('MaxcraftDefaultBundle:Admin:sections.html.twig', array(
            'page' => $page,
            'sections' => $sections,
        ));
    }

    /**
     * @param null $sectionId
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editsectionAction($sectionId = null, Request $request){

        $em = $this->getDoctrine()->getManager();

        if($sectionId == null)
        {
            $section = new PageSection();
        }
        else
        {
            $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:PageSection');
            $section= $rep->findOneById($sectionId);
        }

        if($section == null)
        {
            throw $this->createNotFoundException('Cette section n\'existe pas !');
        }

        //form
        $form = $this->createFormBuilder($section)
            ->add('title', 'text')
            ->add('content', 'froala')
            ->add('ordervalue', 'integer')
            ->add('page', 'entity', array(
                'class' => 'MaxcraftDefaultBundle:Page',
                'property' => 'title'
            ))
            ->add('display', 'choice', array('choices' => array('1' => 'Oui', '0' => 'Non')))
            ->getForm();

        //traitement

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);



            if($form->isValid())
            {



                $em->persist($section);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'La section a été editée !');

                return $this->redirect($this->generateUrl('admin_editsection', array('sectionId' =>  $section->getId())));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($section);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());
            }

        }



        return $this->render('MaxcraftDefaultBundle:Admin:editsection.html.twig', array(
            'form' => $form->createView(),
            'section' => $section,
        ));
    }
}