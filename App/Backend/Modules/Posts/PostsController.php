<?php

namespace App\Backend\Modules\Posts;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Post;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
use OCFram\Resize;

class PostsController extends BackController
{
    use Resize;

    public function executeDelete(HTTPRequest $request)
    {
        $requestToken = $request->getData('token');
        $userToken =  $this->app()->user()->getAttribute('token');
        if ($requestToken == $userToken) {
            $postId = $request->getData('id');

            $this->managers->getManagerOf('Post')->delete($postId);
            $this->managers->getManagerOf('Comment')->deleteFromNews($postId);

            $this->app->user()->setFlash('L\'article a bien été supprimé !', "alert alert-success");

            $this->app->httpResponse()->redirect('.');
        } else {
            $this->app->user()->setFlash('Les tokens ne correspondent pas !', "alert alert-danger");
            $this->app->httpResponse()->redirect('.');
        }
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $requestToken = $request->getData('token');
        $userToken =  $this->app()->user()->getAttribute('token');

        if ($requestToken == $userToken) {
            $idComment = $request->getData('id');

            $idArticle = $this->managers->getManagerOf('Comment')->getIdArticle($idComment);

            $this->managers->getManagerOf('Comment')->deleteWithChildren($idComment);

            $this->app->user()->setFlash('Le commentaire a bien été supprimé !', "alert alert-success");

            if (preg_match('/admin\/comments/', $_SERVER['HTTP_REFERER'])) {
                $post = $this->managers->getManagerOf('Post')->getUnique($idArticle);

                $url = $this->app->router()->generateUri("invalidComments");

                $this->app->httpResponse()->redirect($url);
            }

            $post = $this->managers->getManagerOf('Post')->getUnique($idArticle);

            $url = $this->app->router()->generateUri("showPost", [$post->slug(), $idArticle]);

            $this->app->httpResponse()->redirect($url . '#form');
        } else {
            $this->app->user()->setFlash('Les tokens ne correspondent pas !', "alert alert-danger");
            if (preg_match('/\/blog\//', $_SERVER['HTTP_REFERER'])) {
                $this->app->httpResponse()->redirect($_SERVER['HTTP_REFERER'].'#form-comment');
            }
            $this->app->httpResponse()->redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des articles');

        $manager = $this->managers->getManagerOf('Post');

        $invalidComments = $this->managers->getManagerOf('Comment')->countInvalidComments();

        if ($request->getExists("page") and $request->getData("page") == 1) {
            $this->app->httpResponse()->redirect("/admin/");
        }

        $usersManager = $this->managers->getManagerOf('user');

        $page = $request->getData("page") ?? 1;

        $listePosts = $manager->findPaginated(8, $page, $usersManager);

        $usersManager = $this->managers->getManagerOf('User');

        foreach ($listePosts->getCurrentPageResults() as $post) {
            $author = $usersManager->getUnique($post->idAuteur());
            if ($author) {
                $post->setAuteur($author->nom() . " " . $author->prenom() . " (pseudo : " . $author->pseudo() . ")");
            }
            $postUri = $this->app()->router()->generateUri('showPost', [$post->slug(),$post->id()]);
            $postUri = '<a href="'.$postUri.'">'.$post->titre().'</a>';
            $post->postUri = $postUri;
        }

        $this->page->addVar('listePosts', $listePosts);
        $this->page->addVar('invalidComments', $invalidComments);

        $this->addView();
    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'un article');

        $this->addView();
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'un article');

        $this->addView();
    }

    public function executeUpdateComment(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Modification d\'un commentaire');

        if ($request->method() == 'POST') {
            $comment = new Comment([
                'id' => $request->getData('id'),
                'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu')
            ]);
        } else {
            $comment = $this->managers->getManagerOf('Comment')->get($request->getData('id'));
        }

        $formBuilder = new CommentFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comment'), $request, $this->app->user());

        if ($formHandler->process()) {
            $this->app->user()->setFlash('Le commentaire a bien été modifié', "alert alert-success");

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }

    /**
     * @param Post|null $post=null
     *
     * @return array
     * Renvoie un tableau contenant toutes les instances Users.
     * Si l'instance $post est fournie, le tableau retourné aura comme premier élément l'auteur de l'instance $post
     */
    private function authorsList(?Post $post = null): array
    {
        if ($post) {
            $usersManager = $this->managers->getManagerOf('User');
            $author = $usersManager->getUnique($post->idAuteur());
            $authors = [$author];
            foreach ($usersManager->getList() as $user) {
                if ($user != $author) {
                    $authors[] = $user;
                }
            }
            return $authors;
        } else {
            $usersManager = $this->managers->getManagerOf('User');
            $authors = $usersManager->getList();
            return $authors;
        }
    }

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            if (!empty($_FILES['image'])) {
                if ($_FILES['image']['error']) {
                    $image = $_POST['oldValue'];
                    if ($_FILES['image']['error'] == 1) {
                        $this->app->user()->setFlash('L\'image ne doit pas dépasser 2 Mo !', 'alert alert-danger');
                    }
                } else {
                    $uploaddir = __DIR__ . '/../../../../Web/assets/img/blog/';
                    $uploadfile = $uploaddir . basename($_FILES['image']['name']);

                    $this->resize($_FILES['image']['tmp_name'], $uploadfile, 640, 480);

                    $image = 'assets/img/blog/' . $_FILES['image']['name'];
                }
            }

            $post = new Post([
                'idAuteur' => $request->postData('auteur'),
                'titre' => $request->postData('titre'),
                'image' => $image ?? null,
                'chapo' => $request->postData('chapo'),
                'contenu' => $request->postData('contenu'),
                'slug' => $request->postData('slug')
            ]);

            if ($request->getExists('id')) {
                $idPost = $request->getData('id');
                $post->setId($idPost);
                $authors = $this->authorsList($post);
                $post->setAuteur($authors);

                // if ($_FILES['image']['error']) {
                //     $authors = $this->authorsList($post);
                //     $post->setAuteur($authors);
                // }
            } else {
                $authors = $this->authorsList();
                $post->setAuteur($authors);
            }
        } else {
            // L'identifiant de l'article est transmis si on veut la modifier
            if ($request->getExists('id')) {
                $post = $this->managers->getManagerOf('Post')->getUnique($request->getData('id'));
                $authors =  $this->authorsList($post);
                $post->setAuteur($authors);
            } else {
                $post = new Post;
                $authors = $this->authorsList();
                $post->setAuteur($authors);
            }
        }

        $formBuilder = new NewsFormBuilder($post);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Post'), $request, $this->app->user());

        if (isset($_FILES['image']) and $_FILES['image']['error'] == 1) {
            $this->page->addVar('form', $form->createView());
        } elseif ($formHandler->process()) {
            if ($post->isNew()) {
                $this->app->user()->setFlash('L\'article a bien été ajouté !', 'alert alert-success');
            } else {
                $this->app->user()->setFlash('L\'article a bien été modifié !', 'alert alert-success');
            }

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeSeeInvalidComments(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Validation des commentaires');
        $nbInvalidComments = $this->managers->getManagerOf('Comment')->countInvalidComments();

        $invalidComments = $this->managers->getManagerOf('Comment')->getListOfInvalid();

        foreach ($invalidComments as $comment) {
            $idArticle = $comment->idArticle();
            $idAuteur = $comment->idAuteur();

            $article = $this->managers->getManagerOf('Post')->getUnique($idArticle);
            $uriArticle = $this->app->router()->generateUri("showPost", [$article->slug(), $idArticle]);
            $urlArticle = '<a href="' . $uriArticle . '">' . $article->titre() . '</a>';

            $auteurComment = $this->managers->getManagerOf('User')->getUnique($idAuteur);
            if ($auteurComment) {
                $auteurComment = $auteurComment->nom() . ' ' . $auteurComment->prenom() . ' (pseudo : ' . $auteurComment->pseudo() . ')';
            }

            $comment->urlArticle = $urlArticle;
            $comment->auteurComment = $auteurComment;
        }

        $this->page->addVar('nbInvalidComments', $nbInvalidComments);
        $this->page->addVar('invalidComments', $invalidComments);
        $this->addView();
    }

    public function executeValidComment(HTTPRequest $request)
    {
        $commentId = $request->getData('id');

        $this->managers->getManagerOf('Comment')->validComment($commentId);

        $this->app->user()->setFlash('Le commentaire a bien été validé !', "alert alert-success");

        $this->app->httpResponse()->redirect('/admin/comments.html');
    }
}
