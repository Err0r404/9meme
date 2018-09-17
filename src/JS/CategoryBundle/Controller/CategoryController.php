<?php

namespace JS\CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function widgetCategoriesAction(){
        $categories = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JSCategoryBundle:Category')
            ->findAllOrdered()
        ;

        return $this->render(
            '@JSCategory/widget_categories.html.twig',
            ['categories' => $categories]
        );
    }
}
