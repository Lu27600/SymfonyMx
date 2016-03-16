<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/01/16
 * Time: 21:25
 */

namespace Maxcraft\DefaultBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactionController extends Controller {

    public function infosAction($factionTag){

        if ($faction = $this->getDoctrine()->getRepository('MaxcraftDefaultBundle:Faction')->findOneByTag($factionTag)){

            $message = 'La Faction '. strtoupper($factionTag).' n\'a pas été trouvée';

            return $this->render('MaxcraftDefaultBundle:Others:error.html.twig', array(
                'content' => $message
            ));
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

        //WARS
        //TODO wars
    }
}