<?php

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Comment;
use Maxcraft\DefaultBundle\Entity\News;
use Maxcraft\DefaultBundle\Entity\Player;
use Maxcraft\DefaultBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
        foreach ($news as $new){
            $newslist[$new->getId()]['news'] = $new;
            $newslist[$new->getId()]['comments'] = $repNews->getComments($new->getId());
            $newslist[$new->getId()]['nbcomments'] = count($newslist[$new->getId()]['comments']);

            //préparation ajout commentaire
            $comment = new Comment();
            $comment->setNews($new->getId());
            $newslist[$new->getId()]['comment'] = $comment;
            $newslist[$new->getId()]['form'] = $this->createFormBuilder($comment)
                ->add('content', 'textarea', array('required' => true))
                ->add('newsid', 'hidden', array('data' => $new->getId()) )
                ->getForm();
            $newslist[$new->getId()]['commentform'] =  $newslist[$new->getId()]['form']
                ->createView();
        }

        if ($request->isMethod('POST')){
            foreach ($newslist as $new)
            {
                $form = $new['form'];
                $form->bind($request);

                if($form->isValid() AND $new['news']->getId() == $new['comment']->getNews())
                {

                    $new['comment']->setNews($new['news']->getId());
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

        ));
    }


    public function userCreateAction(){

        $em = $this->getDoctrine()->getManager();

        $player = new Player();
        $player->setUuid(uniqid('test'));
        $player->setPseudo('Crevebedaine');
        $player->setBalance('1000');
        $player->setActif(true);
        $player->setVanished(false);

        $em->persist($player);
        $em->flush();

        $user = new User($this, $player);
        $user->setUuid($player->getUuid());
        $user->setUsername($player->getPseudo());
        $user->setEmail('mail@maxcraft.fr');
        $user->setPassword('motdepasse');
        $user->cryptePassword($user->getPassword());
        $user->setRole('ROLE_ADMIN');
        $user->setNaissance('3050');
        $user->setIp($this->container->get('request')->getClientIp());
        $user->setGametime('4678975');
        $user->setSpleeping(false);
        $user->setLoisirs('loisirs');

        $em->persist($user);
        $em->flush();

        return array('OK');

    }

    public function commentCreateAction( $user,  $newsId){
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setUser($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:User')->find($user));
        $comment->setNews($this->getDoctrine()->getRepository('MaxcraftDefaultBundle:News')->find($newsId));
        $comment->setContent("Coucou, ceci <strong>est</strong> un commentaire !");

        $em->persist($comment);
        $em->flush();
    }



}


