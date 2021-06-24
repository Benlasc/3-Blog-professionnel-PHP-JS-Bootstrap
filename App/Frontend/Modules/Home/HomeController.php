<?php

namespace App\Frontend\Modules\Home;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

class HomeController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        // $nombreNews = $this->app->config()->get('nombre_news');
        // $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        // $this->page->addVar('title', 'Blog');

        // On récupère le manager des articles.
        // $manager = $this->managers->getManagerOf('Post');

        // $page = $request->getData("page") ?? 1;
        
        // $listePosts = $manager->findPaginated(8, $page);
        
        // $listePosts = $manager->getList();


        // On ajoute la variable $listePosts à la vue.
        // $this->page->addVar('listePosts', $listePosts);

        $this->addView();
    }
}
