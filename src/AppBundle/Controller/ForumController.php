<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Theme; 
use AppBundle\Form\ThemeType;
use AppBundle\Form\ThemeEditType;
use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForumController extends Controller
{
    /**
     * @Route("/forum", name="accueil")
     * @Template("@App/forum/index.html.twig")
     */ 
    public function indexAction(Request $request)
    {
        $listThemes = $this->getDoctrine()
        ->getManager()
        ->getRepository('AppBundle:Theme')->findAll();
        //var_dump($listThemes);
        return array(
            'listThemes' => $listThemes
        );
    }

    /**
     * @Route("/forum/add", name="ajout")
     * @Template("@App/forum/add.html.twig")
     */
    public function addAction(Request $request)
    {
        /*
        $theme = new Theme();
        $theme->setIntitule('Matériel');

        
        $em = $this->getDoctrine()->getManager();
        $em->persist($theme);
        $em->flush();
        */
        
        $theme = new Theme();
        $form = $this->get('form.factory')->create(ThemeType::class, $theme);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();

        return $this->redirectToRoute('affichage', array('id' => $theme->getId()));
        }
        
        return array(
            'theme' => $theme,
            'form' => $form->createView(),
        );
         
    }

    /**
     * @Route("/forum/view/{id}", defaults={"id" = 0}, name="affichage")
     * @Method("GET")
     * @Template("@App/forum/view.html.twig")
     */
    public function viewAction($id)
    {
        
        $themes = $this->getDoctrine()
        ->getManager()
        ->getRepository('AppBundle:Theme')
         ->getThemeById($id)
       ;
        return array(
            'themes' => $themes
        );
        
        
    }

    /**
     * @Route("/forum/edit/{id}", defaults={"id" = 0}, name="modifier")
     * @Template("@App/forum/edit.html.twig")
     */
    public function editAction($id, Request $request)
    {/*
        // il faut ajouter un formulaire
        $themes = $this->getDoctrine()
        ->getManager()
        ->getRepository('AppBundle:Theme')
         ->getThemeById($id)
       ;
        return array(
            'themes' => $themes
        );
       */
      $theme = $this->getDoctrine()
        ->getManager()
        ->getRepository('AppBundle:Theme')
         ->find($id)
       ;
       
        $form = $this->get('form.factory')->create(ThemeEditType::class, $theme);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            //$em->persist($theme);
            $em->flush();

        return $this->redirectToRoute('affichage', array('id' => $theme->getId()));
        }
        
        return array(
            'theme' => $theme,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/forum/delete/{id}", defaults={"id" = 0}, name="supprimer")
     * @Method("GET")
     * @Template("@App/forum/delete.html.twig")
     */
    public function deleteAction($id, Request $request)
    {
        
        
        $em = $this->getDoctrine() ->getManager();
        $themes = $em->getRepository('AppBundle:Theme') ->find($id);
        
     if (null === $themes) {
        throw new NotFoundHttpException("Le thème d'id ".$id." n'existe pas.");
      }
      // On boucle sur les messages du thème pour les supprimer
     foreach ($themes->getMessages() as $message) {
        $themes->removeMessage($message);
      }
      $em->remove($themes);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "Le thème a bien été supprimée.");

      return $this->redirectToRoute('accueil');


    }

    /**
     * @Route("/forum/addMsg/{id}", name="ajoutMsg")
     * @Template("@App/forum/addMsg.html.twig")
     */
    public function addMsgAction($id, Request $request)
    {
        //on récupère le thème 
        $em = $this->getDoctrine()
                    ->getManager();
        $theme = $em->getRepository('AppBundle:Theme')->find($id);
        $msg = new Message();
        
        $form = $this->get('form.factory')->create(MessageType::class, $msg);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $msg->setTheme($theme);
            $em = $this->getDoctrine()->getManager();
            $em->persist($msg);
            $em->flush();
            
        return $this->redirectToRoute('affichage', array('id' => $theme->getId()));
        }
        
        return array(
            'msg' => $msg,
            'form' => $form->createView(),
        );
         
    }

    /**
     * @Route("/forum/viewMsg/{id}", defaults={"id" = 0}, name="affichageMsg")
     * @Method("GET")
     * @Template("@App/forum/viewMsg.html.twig")
     */
    public function viewMsgAction($id)
    {
        
        $msg = $this->getDoctrine()
        ->getManager()
        ->getRepository('AppBundle:Message')
         //->getMsgById($id)
         ->find($id);
       ;
       //var_dump($msg); die();
        return array(
            'msg' => $msg
        );
        
        
    }

}
