<?php

namespace JS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function profileAction($usernameCanonical)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('JSUserBundle:User')
            ->findOneBy([
                'usernameCanonical' => $usernameCanonical
            ])
        ;

        if(null === $user){
            throw new NotFoundHttpException("User not found");
        }

        $memes = $em
            ->getRepository('JSMemeBundle:Meme')
            ->getMemesCreatedByUser($user->getId())
        ;

        $upvotes = $em
            ->getRepository('JSMemeBundle:Meme')
            ->getMemesUpvotedByUser($user->getId())
        ;

        return $this->render(
            '@JSUser/Profile/show.html.twig',
            [
                'user' => $user,
                'memes' => $memes,
                'upvotes' => $upvotes,
            ]
        );
    }
}
