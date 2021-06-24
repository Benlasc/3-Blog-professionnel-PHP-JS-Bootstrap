<?php

namespace App\Backend\Modules\Posts;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Post;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;

class PostsController extends BackController
{
    public function executeDelete(HTTPRequest $request)
    {
        $postId = $request->getData('id');

        $this->managers->getManagerOf('Post')->delete($postId);
        //$this->managers->getManagerOf('Comment')->deleteFromNews($postId);

        $this->app->user()->setFlash('L\'article a bien été supprimé !', "alert alert-success");

        $this->app->httpResponse()->redirect('.');
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $this->managers->getManagerOf('Comment')->delete($request->getData('id'));

        $this->app->user()->setFlash('Le commentaire a bien été supprimé !', "alert alert-success");

        $this->app->httpResponse()->redirect('.');
    }

    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des articles');

        $manager = $this->managers->getManagerOf('Post');

        if ($request->getExists("page") and $request->getData("page") == 1) {
            $this->app->httpResponse()->redirect("/admin/");
        }

        $usersManager = $this->managers->getManagerOf('user');

        $page = $request->getData("page") ?? 1;

        $listePosts = $manager->findPaginated(8, $page, $usersManager);

        $usersManager = $this->managers->getManagerOf('User');

        foreach ($listePosts->getCurrentPageResults() as $post) {
            $author = $usersManager->getUnique($post->idAuteur());
            $post->setAuteur($author->nom() . " " . $author->prenom() . " (pseudo : " . $author->pseudo() . ")");
        }

        $this->page->addVar('listePosts', $listePosts);

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

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comment'), $request);

        if ($formHandler->process()) {
            $this->app->user()->setFlash('Le commentaire a bien été modifié', "alert alert-success");

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }

    /**
     * Resize the $source image. The result will be $uploadfile
     * The php gd extension must be enabled
     * @param string $source : path to the source image
     * @param mixed $uploadfile : path where the resized image will be placed
     * @param int $width : width of the resized image
     * @param int $height : height of the resized image
     *
     * @return void
     */
    private function resize(string $source, $uploadfile, int $width, int $height): void
    {
        $source = imagecreatefromjpeg($source);
        $destination = imagecreatetruecolor($width, $height);

        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, $largeur_source, $hauteur_source);

        imagejpeg($destination, $uploadfile);
    }

    /**
     * @param Post|null $post=null
     *
     * @return array
     * Renvoie un tableau contenant toutes les instances Users.
     * Si l'instance $post est fournie, le tableau retourné aura comme premier élément l'auteur de l'instance $post
     */
    private function authorsList(?Post $post = null) : array
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
                    if ($_FILES['image']['error']==1) {
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
                $post -> setId($idPost);

                if ($_FILES['image']['error']) {
                    $authors = $this->authorsList($post);
                    $post->setAuteur($authors);
                }
            } elseif ($_FILES['image']['error']) {
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

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Post'), $request);

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
}
