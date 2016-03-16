<?php

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Builder;
use Maxcraft\DefaultBundle\Entity\Comment;
use Maxcraft\DefaultBundle\Entity\Image;
use Maxcraft\DefaultBundle\Entity\Player;
use Maxcraft\DefaultBundle\Entity\User;
use Maxcraft\DefaultBundle\Entity\Zone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class DefaultController extends Controller
{
    public function indexAction($page, Request $request) //TODO fnr
    {
        //Récupération Album principal
        $albumid = $this->container->getParameter('index_album');
        $album = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Album')->findOneById($albumid);
        if ($album != null) {
            $images = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Album')->findImages($album);
        }
        else {
            $images = null;
        }

        //news par page
        $parpage = $this->container->getParameter('news_par_page');

        //Récupération des news
        $repNews = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:News');
        $news = $repNews->findByPage($page,$parpage);

        $totalNews = $repNews->countDisplay();
        $totalPages = ceil(($totalNews)/($parpage));
        $newslist = array();
        $commentFormList = array();
        foreach ($news as $new){
            $newslist[$new->getId()]['news'] = $new;
            $newslist[$new->getId()]['comments'] = $repNews->getComments($new->getId());
            $newslist[$new->getId()]['nbcomments'] = count($newslist[$new->getId()]['comments']);

            //préparation ajout commentaire
            $comment = new Comment();
            $comment->setNews($new);
            $newslist[$new->getId()]['comment'] = $comment;

            $newslist[$new->getId()]['form'] = $this->createFormBuilder($comment)
                ->add('content', 'froala', array('required' => true))
                ->add('news', 'hidden', array(
                    'data' => $new->getId()
                ))
                ->getForm();

            $newslist[$new->getId()]['commentform'] =  $newslist[$new->getId()]['form']
                ->createView();
            $commentFormList[$new->getId()] = $newslist[$new->getId()]['form']
                ->createView();
            $commentFormList[$new->getId()] = $newslist[$new->getId()]['form']->createView();
        }

        if ($request->isMethod('POST')){
            foreach ($newslist as $new)
            {
                $form = $new['form'];
                $form->handleRequest($request);

                if($form->isValid() AND $new['news']->getId() == $new['comment']->getNews()->getId())
                {

                    $new['comment']->setNews($new['news']);
                    $new['comment']->setUser($this->getUser()->getId());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($new['comment']);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('info', 'Votre commentaire pour la news  "'.$new['news']->getTitle().'" à été posté.');

                    return $this->redirect($this->generateUrl('maxcraft_default_blog', array(
                        'page' => $page,
                    )));
                }

            }
        }



        return $this->render('MaxcraftDefaultBundle:Default:index.html.twig', array(
            'newslist' => $newslist,
            'totalpages' => $totalPages,
            'page' => $page,
            'album' => $album,
            'images' => $images,
            'commentformList' => $commentFormList

        ));


    }



    public function registerAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        //TODO Aller chercher Règlement

        $annees = array();

        for($i=2016;$i>=1940;$i--)
        {
            $annees[$i] = $i;

        }

        $user = new User($this, null);
        $userForm = $this->createFormBuilder($user)
            ->add('username', new TextType())
            ->add('email', new TextType())
            ->add('password', new RepeatedType(), array(
                'type' => new PasswordType(),
                'invalid_message' => 'Vous avez mal recopié votre mot de passe !'
            ))
            ->add('naissance', new ChoiceType(), array(
                'choices' => $annees,
                'empty_value' => 'Choisisez',
                'empty_data' => null
            ))
            ->add('activite', new TextType(), array(
                'required' => false
            ))
            ->add('loisirs', new TextType(), array(
                'required' => false
            ))
            ->add('fromwhere', new TextareaType())
            ->add('Valider', new SubmitType())
            ->getForm();

        if ($request->isMethod('POST')){
            $userForm->handleRequest($request);

            //Test si dejà visité
            $havevisited = $em->getRepository('MaxcraftDefaultBundle:Player')->haveVisited($user->getUsername());
            if ($havevisited != true) 	$this->get('session')->getFlashBag()->add('alert', 'Vous devez vous connecter en jeu à maxcraft.fr pour pouvoir vous inscrire !');

            if ($userForm->isValid() && $havevisited == true ){

                $player = $em->getRepository('MaxcraftDefaultBundle:Player')->findOneByPseudo($user->getUsername());
                $user->setPlayer(($player));
                $user->setUuid($player->getUuid());
                $user->setSpleeping(false);

                $user->cryptePassword($user->getPassword()); //cryptage md5
                $user->setIp($this->container->get('request')->getClientIp());

                $user->setRole('ROLE_USER');

                $em->persist($user, $player);
                $em->flush();

                //TODO gérer permissions sur serveur + forum (dire qu'il est inscris)
                //new registeredPlayer($user->getPlayer())

                $this->get('session')->getFlashBag()->add('info', 'Votre inscription est terminée ! Vous pouvez à présent vous connecter.');

                return $this->redirect($this->generateUrl('maxcraft_homepage'));
            }

            $validator = $this->get('validator');
            $errorList = $validator->validate($user);

            foreach($errorList as $error)
            {
                $this->get('session')->getFlashBag()->add('alert', $error->getMessage());

            }
        }

        return $this->render('MaxcraftDefaultBundle:Default:register.html.twig', array(
            'form' => $userForm->createView()
        ));
    }

    /**
     * @param $albumId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadImageAction($albumId, Request $request){

        $file = $request->files->get('file');
        if($file == NULL)
        {
            throw $this->createNotFoundException('Une erreur s\'est produite !');
        }

        $em = $this->getDoctrine()->getManager();
        if ( !($album= $em->getRepository('MaxcraftDefaultBundle:Album')->findOneById($albumId))) throw $this->createNotFoundException('Cet Album n\'existe pas!');

        $image = new Image();

        $image->setFile($file);

        $image->upload();

        if (count($album->getImages()) == 0) $album->setAlbumimage($image);
        $album->addImage($image);
        $image->setAlbum($album);


        $em->persist($image);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', "Image(s) ajoutée(s) avec succès !");

        return $this->render('MaxcraftDefaultBundle:Default:response.html.twig', array(
            'display' => $file,
        ));

    }

    public function changeAlbumImageAction($albumid, $imageid){
        $this->islogged();

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Album');
        $album = $rep->find($albumid);

        if($album == NULL)
        {
            throw $this->createNotFoundException('L\'album '.$albumid.' n\'existe pas !');
        }

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Image');
        $image = $rep->find($imageid);

        if($image == NULL)
        {
            throw $this->createNotFoundException('Cette image n\'existe pas !');
        }

        $album->setAlbumimage($image);

        $em = $this->getDoctrine()->getManager();
        $em->persist($album);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'L\'image à été changée !');

        return $this->redirect($this->generateUrl('maxcraft_album_edit', array(
            'albumId' => $albumid,
        )));
    }

    public function removeImageAction($imageid){
        $this->islogged();

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Image');
        $image = $rep->find($imageid);

        if($image == NULL)
        {
            throw $this->createNotFoundException('Cette image n\'existe pas !');
        }
        $image->remove();
        $em = $this->getDoctrine()->getManager();
        if($image->getAlbum()->getAlbumimage() == $image)
        {
            $album = $image->getAlbum();
            $album->setAlbumimage(NULL);
            $em->persist($album);
        }

        $em->remove($image);

        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'L\'image à été supprimée !');

        return $this->redirect($this->generateUrl('maxcraft_album_edit', array(
            'albumId' => $image->getAlbum()->getId()
        )));
    }



    public function islogged()
    {
        if(!($this->get('security.context')->isGranted('ROLE_USER')))
        {
            throw $this->createNotFoundException('Vous devez vous connecter pour acceder à cette page !');
        }
        else
        {
            if($this->getUser()->getBanned())
            {
                throw $this->createNotFoundException('Vous êtes banni de ce site ! Contactez un admin en cas de problème.');
            }
        }
    }

    public function isAdmin()
    {
        if(!($this->get('security.context')->isGranted('ROLE_ADMIN')))
        {
            throw $this->createNotFoundException('Vous devez être admin pour acceder à cette page !');
        }
        else
        {
            if($this->getUser()->getBanned())
            {
                throw $this->createNotFoundException('Vous êtes banni de ce site ! Contactez un admin en cas de problème.');
            }
        }
    }

    public function isModo()
    {
        if(!($this->get('security.context')->isGranted('ROLE_MODO')))
        {
            throw $this->createNotFoundException('Vous devez être modo pour acceder à cette page !');
        }
        else
        {
            if($this->getUser()->getBanned())
            {
                throw $this->createNotFoundException('Vous êtes banni de ce site ! Contactez un admin en cas de problème.');
            }
        }
    }

    public function menuAction(){
        $pages = $this->getDoctrine()->getManager()->createQuery('SELECT p FROM MaxcraftDefaultBundle:Page p WHERE p.display = 1 ORDER BY p.ordervalue ASC')->getResult();

        if(($this->get('security.context')->isGranted('ROLE_USER')))
        {
            $nbnotif =  count($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Notification')->findBy(
              array('user' => $this->getUser(),
                  'view' => false)
            ));
                /*->createQuery('SELECT count(n.id) FROM MaxcraftDefaultBundle:Notification n WHERE n.user = '.$this->getUser().' AND n.view = 0')
                ->getSingleScalarResult();*/

            $nbmp =  count($this->getDoctrine()->getManager()->getRepository('MaxcraftDefaultBundle:MP')->findBy(
                array('target' => $this->getUser(),
                    'view'=>false)
            ));
                /*->createQuery('SELECT count(m.id) FROM MaxcraftDefaultBundle:MP m WHERE m.target = '.$this->getUser().' AND m.view = 0')
                ->getSingleScalarResult();*/

            $alert = $nbmp + $nbnotif;


            //sites de classement

            $voteforus = $this->container->getParameter('voteforus');


            return $this->render('MaxcraftDefaultBundle:Others:menu.html.twig', array(
                'nbnotif' => $nbnotif,
                'nbmp' => $nbmp,
                'alert' => $alert,
                'pages' => $pages,
                'voteforus' => $voteforus
            ));

        }
        else
        {
            return $this->render('MaxcraftDefaultBundle:Others:menu.html.twig', array(
                'pages' => $pages
            ));
        }
    }

    public function wrapAction(){
        $lastregistered = $this->getDoctrine()->get
    }
}


