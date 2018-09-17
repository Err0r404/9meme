<?php

namespace JS\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            '@JSComment/Default/index.html.twig',
            []
        );
    }
}
