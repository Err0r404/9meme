<?php

namespace JS\MemeBundle\Controller;

use JS\MemeBundle\Entity\Meme;
use JS\MemeBundle\Entity\Score;
use JS\MemeBundle\Form\MemeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MemeController extends Controller {
    
    public function indexAction(Request $request){
        $user = $this->getUser();

        $paginator  = $this->get('knp_paginator');

        $pagination = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JSMemeBundle:Meme')
            ->getMemesPaginated($paginator, $request, $user->getId());
        
        return $this->render(
            '@JSMeme/Meme/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }
    
    public function viewAction($id) {
        $em = $this->getDoctrine()->getManager();

        $meme = $em
            ->getRepository('JSMemeBundle:Meme')
            ->getMeme($id)
        ;

        if(null === $meme || sizeof($meme) === 0){
            throw new NotFoundHttpException("Meme #$id not found");
        }

        $meme = $meme[0];

        $userScore = null;
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $userScore = $em
                ->getRepository('JSMemeBundle:Score')
                ->findOneBy(['user' => $this->getUser()->getId(), 'meme' => $meme->getId()])
            ;
        }

        $comments = $em
            ->getRepository('JSCommentBundle:Comment')
            ->findBy(['meme' => $meme]);
        
        return $this->render(
            '@JSMeme/Meme/view.html.twig',
            [
                'meme' => $meme,
                'comments' => $comments,
                'userScore' => $userScore,
            ]
        );
    }
    
    public function addAction(Request $request) {
        $meme = new Meme();
        $user = $this->getUser();

        $form = $this->createForm(MemeType::class, $meme);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // Link User to Meme
            $user->addMeme($meme);
            // $meme->setUser($this->getUser());

            // Create a score and link it to Meme and User
            $score = new Score();
            $score->setPoint(1);
            $score->setMeme($meme);
            $user->addScore($score);

            $em = $this->getDoctrine()->getManager();
            $em->persist($meme);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your meme has been saved');
    
            return $this->redirectToRoute('js_meme_view', ['id' => $meme->getId()]);
        }
    
        return $this->render(
            '@JSMeme/Meme/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    public function deleteAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $meme = $em->getRepository('JSMemeBundle:Meme')->find($id);

        if(null === $meme){
            throw new NotFoundHttpException("Meme #$id not found");
        }

        if($meme->getUser() !== $this->getUser()){
            throw new AccessDeniedHttpException();
        }

        // Empty form to benefits CSRF protection
        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->remove($meme);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your meme has been deleted');

            return $this->redirectToRoute('js_meme_home');
        }

        return $this->render(
            '@JSMeme/Meme/delete.html.twig',
            [
                'form' => $form->createView(),
                'meme' => $meme
            ]
        );
    }

    public function popularitiesAction(){
        $listPopularities = [
            [
                'id' => 1,
                'title' => 'Hot',
                'icon_class' => 'bx bx-hot',
            ],
            [
                'id' => 2,
                'title' => 'Trending',
                'icon_class' => 'bx bx-trending-up',
            ],
            [
                'id' => 3,
                'title' => 'Fresh',
                'icon_class' => 'bx bx-time',
            ],
        ];
    
        return $this->render(
            '@JSMeme/popularities.html.twig',
            ['listPopularities' => $listPopularities]
        );
    }

    public function randomMemeAction(){
        $memes = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JSMemeBundle:Meme')
            ->getMemesRandom(5)
        ;
        
        return $this->render(
            '@JSMeme/random.html.twig',
            ['memes' => $memes]
        );
    
    }
}
