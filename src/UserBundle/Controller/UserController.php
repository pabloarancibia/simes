<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
         $em = $this->getDoctrine()->getManager();
         
         //$users = $em->getRepository('UserBundle:User')->findAll();
		 $dql= "SELECT u FROM UserBundle:User u ORDER BY u.id DESC";
         $users = $em->createQuery($dql);
         
         $paginator = $this->get('knp_paginator');
         $pagination = $paginator->paginate(
             $users,
             $request->query->getInt('page',1),
             5
             
             );
         
         return $this->render('UserBundle:User:index.html.twig', array ('pagination'=>$pagination));


         //return $this->render('UserBundle:User:index.html.twig', array ('users'=>$users));
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
	
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('UserBundle:User')->find($id);
        
        if(!$user)
        {
            $messageException = 'Usuario no Existe.';
            throw $this->createNotFoundException($messageException) ;
        }
        
        $form = $this->createEditForm($user);
        
        return $this->render('UserBundle:User:edit.html.twig',array('user' => $user, 'form' => $form->createView()));

	}
	
	private function createEditForm (User $entity)
    {
        $form = $this->createForm(new UserType(),$entity, 
        array(
            'action' => $this->generateUrl('user_update',array(
                'id' => $entity->getId())), 'method' => 'PUT'));
        return $form;
    }
	public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
         if(!$user)
        {
            $messageException = 'Usuario no Existe.';
            throw $this->createNotFoundException($messageException) ;
        }
        //metodo p elaborar nuestro formulario con los datos del user correspondiente
        $form = $this->createEditForm($user);
        //procesamos el formulario con el obj request
        $form->handleRequest($request);

        //validamos el contenido del formulario
        //ifsubmitted verifica si el form se envio correctamente
        //isValid verifica si los datos q estamos enviando son correctos
        if($form->isSubmitted()&&$form->isValid())
        {
            //utilizamos flush para q guarde los datos que cambiamos
            $em->flush();

            $successMessage = 'Usuario modificado...';
            //mostramos el msj desde la variable
            $this->addFlash('mensaje', $successMessage);

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));

        }

        //si no se proceso el form
        return $this->render('PAUserBundle:User:edit.html.twig', array('user'=>$user, 'form'=>$form->createView()));

    }


}
