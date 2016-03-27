<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:25
 */

namespace Maxcraft\DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuideController extends Controller
{

    public function guideAction($page){

        $rep = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Page');
        $page = $rep->findOneByRoute($page);


        if($page == NULL)
        {
            throw $this->createNotFoundException('Cette page du guide n\'existe pas');
        }

        $sections = $this->getDoctrine()->getManager()->createQuery('SELECT s FROM MaxcraftDefaultBundle:PageSection s WHERE s.page = '.$page->getId().' AND s.display = 1 ORDER BY s.ordervalue ASC')->getResult();

        return $this->render('MaxcraftDefaultBundle:Guide:guide.html.twig', array(
            'page' => $page,
            'sections' => $sections,
        ));
    }
}