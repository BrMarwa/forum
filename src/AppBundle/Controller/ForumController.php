<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Theme;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ForumController extends Controller
{
    /**
     * @Route("/forum", name="accuiel")
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
     * @Template("@App/forum/view.html.twig")
     */
    public function addAction(Request $request)
    {
        $theme = new Theme();
        $theme->setIntitule('Matériel');

        
        $em = $this->getDoctrine()->getManager();
        $em->persist($theme);
        $em->flush();
        
        return $this->redirectToRoute('affichage', array('id' => $theme->getId()));
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
     * @Method("GET")
     * @Template("@App/forum/edit.html.twig")
     */
    public function editAction($id, Request $request)
    {
        // il faut ajouter un formulaire
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

      return $this->redirectToRoute('accuiel');


    }
}
