<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction()
    {
         $em = $this->getDoctrine()->getManager();
         
         $users = $em->getRepository('UserBundle:User')->findAll();

         return $this->render('UserBundle:User:index.html.twig', array ('users'=>$users));
    }
	
	public function addAction()
	{
		//renderizamos
		$user = new User();
		$form = $this->createCreateForm($user);
		
		return $this->render('UserBundle:User:add.html.twig',array('form'=>$form->createView()));
	}
	
	public function createCreateForm(User $entity)
	{
		$form = $this->createForm(new UserType(),$entity,array(
		'action'=>$this->generateUrl('user_create'),
		'method'=>'POST'
		));
		
		return $form;
	}
	public function createAction (Request $request)
	{
		$user = new User();
		$form = createCreateForm($user);
		$form -> handleRequest($request);
		if ($form->isValid())
		{
			$em=$this->getDoctrine->getManager();
			$em->persist($user);
			$em->flush();
			
			return $this->redirectToRoute('user_index');
		}
			return $this->render('UserBundle:User:add.html.twig', array ('form'=>$form->createView()));
	}
}
