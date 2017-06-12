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
		$form = $this->createCreateForm($user);
		$form -> handleRequest($request);
		if ($form->isValid())
		{
			$em=$this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			
			 //obtenemos el password ingresado en el formulario:
            $password = $form->get('password')->getData();
            //traigo el encoder para codificar el password
            $encoder = $this->container->get('security.password_encoder');
            //codifico el pass
            $encoded = $encoder->encodePassword($user, $password);
            //almacenamos el password ya encriptado
            $user->setPassword($encoded);
			
			 // añadimos un msje que indique lo siguiente, primero creamos una variable traducible para después mostrar la variable
            $successMessage = ('Usuario creado con éxito..');
            //mostramos el msj desde la variable
            $this->addFlash('mensaje', $successMessage);


			
			return $this->redirectToRoute('user_index');
		}
			return $this->render('UserBundle:User:add.html.twig', array ('form'=>$form->createView()));
	}
}
