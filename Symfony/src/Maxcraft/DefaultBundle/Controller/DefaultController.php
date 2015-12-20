<?php

namespace Maxcraft\DefaultBundle\Controller;


use Maxcraft\DefaultBundle\Entity\Comment;
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


        $form = $this->createFormBuilder(array())->add('champs_formulaire', "froala")->getForm();

        return $this->render('MaxcraftDefaultBundle:Tests:froala.html.twig',array('form' => $form->createView()));

        /*return $this->render('MaxcraftDefaultBundle:Default:index.html.twig', array(
            'newslist' => $newslist,
            'totalpages' => $totalPages,
            'page' => $page,
            'album' => $album,
            'images' => $images,

        ));*/
    }


   /*public function addZoneAction(){
       $zone = new Zone();
       $zone->setName("Mannheim");
       $zone->setWorld("Event2");
       $zone->setPoints("45;67;90");
       $zone->setOwner('Crevebedaine');
       $zone->setParent(1);
       $zone->setTags('public;no-spawn-horse');
       $zone->setBuilders('Mwa;Twa');

       $em = $this->getDoctrine()->getManager();
       $em->persist($zone);
       $em->flush();

       $rep = new Response('coucou');
       return $rep;*/

    public function testRegexAction(){
        $json = '-world:id="67",name="maxcraft",groupnumber="35",';
        $regex = '#^-world:id="(.+)",name="(.+)",groupnumber="(.+)",$#';

        if (preg_match($regex, $json)){
            $text = preg_replace($regex,'$1,$2,$3' ,$json);
            $repContent = 'ca marche :  ' .$text;
            $rep = new Response(json_encode($repContent));
            return $rep;
        } else{
            return new Response(json_encode('rate'));
        }
    }

    public function testBoolAction(){
        $str = '';
        $bool = boolval($str);
        return $bool;
    }



}


