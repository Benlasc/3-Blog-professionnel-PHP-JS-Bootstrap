<?php

namespace App\Frontend\Modules\Posts;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

class PostsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        // $nombreNews = $this->app->config()->get('nombre_news');
        // $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        $this->page->addVar('title', 'Blog');

        // On récupère le manager des news.
        $manager = $this->managers->getManagerOf('Post');

        $page = $request->getData("page") ?? 1;
        
        $listePosts = $manager->findPaginated(8, $page);
        
        // $listePosts = $manager->getList();


        // On ajoute la variable $listePosts à la vue.
        $this->page->addVar('listePosts', $listePosts);

        $vue='@'. $this->module.'/'.$this->action;

        $this->page->addVar('vue', $vue);
    }

    public function executeShow(HTTPRequest $request)
    {
        $post = $this->managers->getManagerOf('Post')->getUnique($request->getData('id'));

        if (empty($post)) {
            $this->app->httpResponse()->redirect404();
        }

        if ($post->slug() !== $request->getData('slug')) {
            $url = $this->app->router()->generateUri("showPost", [$post->slug(), $post->id()]);
            $this->app->httpResponse()->redirect($url);
        }

        $auteur = $this->managers->getManagerOf('user')->getUnique($post->idAuteur())->pseudo();

        $this->page->addVar('auteur', $auteur);
        $this->page->addVar('post', $post);
        // $this->page->addVar('comments', $this->managers->getManagerOf('Comment')->getListOf($post->id()));

        $vue='@'. $this->module.'/'.$this->action;

        $this->page->addVar('vue', $vue);
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        // Si le formulaire a été envoyé.
        if ($request->method() == 'POST') {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu')
            ]);
        } else {
            $comment = new Comment;
        }

        $formBuilder = new CommentFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comment'), $request);

        if ($formHandler->process()) {
            $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

            $this->app->httpResponse()->redirect('news-' . $request->getData('news') . '.html');
        }

        // $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un commentaire');
    }
}
