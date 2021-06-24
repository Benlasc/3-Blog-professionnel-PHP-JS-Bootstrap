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

        // On récupère le manager des articles.
        $manager = $this->managers->getManagerOf('Post');

        if ($request->getExists("page") and $request->getData("page")==1) {
            $this->app->httpResponse()->redirect("/blog");
        }

        $page = $request->getData("page") ?? 1;

        $listePosts = $manager->findPaginated(8, $page);
        
        // On ajoute la variable $listePosts à la vue.
        $this->page->addVar('listePosts', $listePosts);

        $this->addView();
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

        $auteur = $this->managers->getManagerOf('User')->getUnique($post->idAuteur())->pseudo();

        // Commentaires

        $comments = $this->managers->getManagerOf('Comment')->getListOfValid($post->id());

        $comments_by_id = [];
        foreach ($comments as $comment) {
            $commentator = $this->managers->getManagerOf('User')->getUnique($comment->idAuteur());
            $comment->setAuteur($commentator);
            $comments_by_id [$comment->id()] = $comment;
        }

        foreach ($comments as $k => $comment) {
            if ($comment->idParent() != 0) {
                $comments_by_id [$comment->idParent()] -> addChildren($comment);
                unset($comments[$k]);
            }
        }

        if ($request->method() == 'POST') {
            $parent_id = ($request->postExists('parent_id')) ? $request->postData('parent_id') : 0 ;

            if ($parent_id !=0) {
                if ($this->managers->getManagerOf('Comment')->commentExist($parent_id) == false) {
                    throw new \Exception('Ce parent n\'existe pas');
                }
            }

            $comment = new Comment([
                'idAuteur' => 5, //à modifier plus tard
                'idArticle' => $request->getData('id'),
                'idParent' => $parent_id,
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
            $this->app->user()->setFlash('Votre commentaire est en attente de validation, merci !', "alert alert-success");
            $url = $this->app->router()->generateUri("showPost", [$post->slug(), $post->id()]);
            $this->app->httpResponse()->redirect($url.'#form');
        }
        
        $this->page->addVar('auteur', $auteur);
        $this->page->addVar('comments', $comments);
        $this->page->addVar('post', $post);
        $this->page->addVar('form', $form->createView());

        $this->addView();
    }
}
//     public function executeInsertComment(HTTPRequest $request)
//     {
//         // Si le formulaire a été envoyé.
//         if ($request->method() == 'POST') {
//             $comment = new Comment([
//                 'news' => $request->getData('news'),
//                 'auteur' => $request->postData('auteur'),
//                 'contenu' => $request->postData('contenu')
//             ]);
//         } else {
//             $comment = new Comment;
//         }

//         $formBuilder = new CommentFormBuilder($comment);
//         $formBuilder->build();

//         $form = $formBuilder->form();

//         $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comment'), $request);

//         if ($formHandler->process()) {
//             $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !', "alert alert-success");
//         }

//         // $this->page->addVar('comment', $comment);
//         $this->page->addVar('form', $form->createView());
//         $this->page->addVar('title', 'Ajout d\'un commentaire');
//     }
// }
