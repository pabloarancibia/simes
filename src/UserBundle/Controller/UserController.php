<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function indexAction()
    {
         $em = $this->getDoctrine()->getManager();
         
         $users = $em->getRepository('UserBundle:User')->findAll();

         return $this->render('UserBundle:User:index.html.twig', array ('users'=>$users));
    }
	
}
