<?php

namespace JS\MemeBundle\Controller;

use JS\MemeBundle\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScoreController extends Controller
{
    /**
     * User vote +1 for a meme
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function incrementAction(Request $request)
    {
        // Check request is from AJAX
        if ($request->isXmlHttpRequest()) {
            // Get params from request
            $csrf_token = $request->get('csrf_token');
            $meme_id = $request->get('meme_id');

            // Check CSRF validity
            if($this->isCsrfTokenValid($meme_id, $csrf_token)){
                $em    = $this->getDoctrine()->getManager();

                // Current user
                $user = $this->getUser();

                // Current meme
                $meme = $em
                    ->getRepository('JSMemeBundle:Meme')
                    ->find($meme_id)
                ;

                if($meme == null){
                    return new Response("Meme #$meme_id not found", 400);
                }

                // Get score of the meme by current user
                $score = $em
                    ->getRepository('JSMemeBundle:Score')
                    ->findOneBy(['user' => $this->getUser()->getId(), 'meme' => $meme_id])
                ;

                // If user has already a score
                if($score !== null){
                    // User has already voted +1 so we remove previous vote
                    if($score->getPoint() == 1){
                        // Remove vote +1
                        $em->remove($score);

                        // Detach vote from user and meme
                        $user->removeScore($score);
                        $meme->removeScore($score);

                        $em->persist($user);
                        $em->persist($meme);
                        $em->flush();

                        return new JsonResponse(["ok" => true, "score" => "0"]);
                    }
                    // User has already voted -1 so we remove previous vote and add +1
                    else{
                        // Remove vote -1
                        $em->remove($score);

                        // Detach vote from user and meme
                        $user->removeScore($score);
                        $meme->removeScore($score);

                        // Create new vote +1
                        $newScore = new Score();
                        $newScore->setPoint(1);

                        // Attach new vote to user and meme
                        $user->addScore($newScore);
                        $meme->addScore($newScore);

                        $em->persist($newScore);
                        $em->persist($user);
                        $em->persist($meme);
                        $em->flush();

                        return new JsonResponse(["ok" => true, "score" => "1"]);
                    }
                }
                else{
                    // Create new vote +1
                    $newScore = new Score();
                    $newScore->setPoint(1);

                    // Attach new vote to user and meme
                    $user->addScore($newScore);
                    $meme->addScore($newScore);

                    $em->persist($newScore);
                    $em->persist($user);
                    $em->persist($meme);
                    $em->flush();

                    return new JsonResponse(["ok" => true, "score" => "1"]);
                }
            }
        }

        return new Response("", 400);
    }

    /**
     * User vote +1 for a meme
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function decrementAction(Request $request)
    {
        // Check request is from AJAX
        if ($request->isXmlHttpRequest()) {
            // Get params from request
            $csrf_token = $request->get('csrf_token');
            $meme_id = $request->get('meme_id');

            // Check CSRF validity
            if($this->isCsrfTokenValid($meme_id, $csrf_token)){
                $em    = $this->getDoctrine()->getManager();

                // Current user
                $user = $this->getUser();

                // Current meme
                $meme = $em
                    ->getRepository('JSMemeBundle:Meme')
                    ->find($meme_id)
                ;

                if($meme == null){
                    return new Response("Meme #$meme_id not found", 400);
                }

                // Get score of the meme by current user
                $score = $em
                    ->getRepository('JSMemeBundle:Score')
                    ->findOneBy(['user' => $this->getUser()->getId(), 'meme' => $meme_id])
                ;

                // If user has already a score
                if($score !== null){
                    // User has already voted -1 so we remove previous vote
                    if($score->getPoint() == -1){
                        // Remove vote +1
                        $em->remove($score);

                        // Detach vote from user and meme
                        $user->removeScore($score);
                        $meme->removeScore($score);

                        $em->persist($user);
                        $em->persist($meme);
                        $em->flush();

                        return new JsonResponse(["ok" => true, "score" => "0"]);
                    }
                    // User has already voted +1 so we remove previous vote and add -1
                    else{
                        // Remove vote +1
                        $em->remove($score);

                        // Detach vote from user and meme
                        $user->removeScore($score);
                        $meme->removeScore($score);

                        // Create new vote -1
                        $newScore = new Score();
                        $newScore->setPoint(-1);

                        // Attach new vote to user and meme
                        $user->addScore($newScore);
                        $meme->addScore($newScore);

                        $em->persist($newScore);
                        $em->persist($user);
                        $em->persist($meme);
                        $em->flush();

                        return new JsonResponse(["ok" => true, "score" => "-1"]);
                    }
                }
                else{
                    // Create new vote -1
                    $newScore = new Score();
                    $newScore->setPoint(-1);

                    // Attach new vote to user and meme
                    $user->addScore($newScore);
                    $meme->addScore($newScore);

                    $em->persist($newScore);
                    $em->persist($user);
                    $em->persist($meme);
                    $em->flush();

                    return new JsonResponse(["ok" => true, "score" => "-1"]);
                }
            }
        }

        return new Response("", 400);
    }
}
