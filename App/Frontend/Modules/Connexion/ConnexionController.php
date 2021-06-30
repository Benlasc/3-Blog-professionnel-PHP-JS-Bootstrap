<?php

namespace App\Frontend\Modules\Connexion;

use Entity\User;
use FormBuilder\ConnexionFormBuilder;
use FormBuilder\UserFormBuilder;
use \OCFram\BackController;
use OCFram\FormHandler;
use \OCFram\HTTPRequest;
use OCFram\Random_str_generator;
use OCFram\Resize;

class ConnexionController extends BackController
{
    use Resize;
    use Random_str_generator;

    public function executeAddUser(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Inscription');

        $this->addView();
    }

    public function executeConnectUser(HTTPRequest $request)
    {
        $valid=false;
        if ($request->postExists('pseudo')) {
            $valid = true;
            $pseudo = $request->postData('pseudo');
            $user = $this->managers->getManagerOf('User')->getWithPseudo($pseudo);
            if ($user) {
                $user_id = $user->id();
                $user_avatar = $user->avatar();
                $user_pseudo = $user->pseudo();
                $isAdmin = $user->admin();
            }
            if (!$user) {
                $valid = false;
                $this->app->user()->setFlash('Identifiants invalides', "alert alert-danger");
            } elseif (!password_verify($request->postData('password'), $user->password())) {
                $valid = false;
                $this->app->user()->setFlash('Identifiants invalides', "alert alert-danger");
            } elseif (!$user->confirmed()) {
                $valid = false;
                $this->app->user()->setFlash('Vous n\'avez pas encore cliqué sur le lien de validation que nous vous avons envoyé par mail', "alert alert-danger");
            }
            $user = new User(['pseudo' => $pseudo,
                              'password' => $request->postData('password')]);
        } else {
            $user = new User();
        }

        $userBuilder = new ConnexionFormBuilder($user);
        $userBuilder->build();

        $form = $userBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('User'), $request, $this->app->user());

        if ($valid && $form->isValid()) {
            $this->app->user()->setAttribute('user_id', $user_id);
            $this->app->user()->setAttribute('user_avatar', $user_avatar);
            $this->app->user()->setAttribute('user_pseudo', $user_pseudo);
            $this->app->user()->deleteAttribute('token');
            $this->app->user()->setAttribute('token', bin2hex(openssl_random_pseudo_bytes(6)));
            $this->app->user()->setFlash('Vous êtes connecté', "alert alert-success");
            if ($isAdmin) {
                $this->app->user()->setAdmin();
            }
            $this->app()->httpResponse()->redirect('/compte');
        }
        $this->page->addVar('form', $form->createView());

        $this->addView();
    }

    public function executeDeconnectUser(HTTPRequest $request)
    {
        $this->app->user()->deleteAttribute('user_id');
        $this->app->user()->deleteAttribute('token');
        $this->app->user()->deleteAttribute('admin');
        $this->app->httpResponse()->redirect('/');
    }

    public function executeSeeAccount(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Mon compte');

        $this->addView();
    }

    public function processForm(HTTPRequest $request)
    {
        $valid = false;
        if ($request->postExists('pseudo')) {
            $pseudo = $request->postData('pseudo');
            $mail = $request->postData('mail');
            $valid = true;
            if ($request->postData('password') != $request->postData('passwordCheck')) {
                $valid = false;
                $this->app->user()->setFlash('Vous avez indiqué deux mots de passe différents.', "alert alert-danger");
            }
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $valid = false;
                $this->app->user()->setFlash('Votre mail n\'est pas valide', "alert alert-danger");
            }
            if (!empty($_FILES['avatar'])) {
                if ($_FILES['avatar']['error']) {
                    $image = $_POST['oldValue'];
                    if ($_FILES['avatar']['error'] == 1) {
                        $this->app->user()->setFlash('L\'image ne doit pas dépasser 2 Mo !', 'alert alert-danger');
                    }
                    if ($_FILES['avatar']['error'] == 4 && !$this->app->user()->isAuthenticated()) {
                        $image = 'assets/img/avatars/commentaire.jpg';
                    }
                } else {
                    $uploaddir = __DIR__ . '/../../../../Web/assets/img/avatars/';
                    $uploadfile = $uploaddir . basename($_FILES['avatar']['name']);

                    $this->resize($_FILES['avatar']['tmp_name'], $uploadfile, 40, 40);

                    $image = 'assets/img/avatars/' . $_FILES['avatar']['name'];
                }
            }
                        
            $user = new User([
                'pseudo' => $pseudo,
                'nom' => $request->postData('nom'),
                'prenom' => $request->postData('prenom'),
                'avatar' => $image ?? null,
                'mail' => $mail,
                'password' => $request->postData('password'),
                'passwordCheck' => $request->postData('passwordCheck'),
                'pseudo' => $request->postData('pseudo'),
                'confirmationToken' => $this->random_str_generator(60),
            ]);
            if ($idUser = $this->app->user()->getAttribute('user_id')) {
                $user->setId($idUser);
                $user->setConfirmed(true);
                $userWithSamePseudo = $this->managers->getManagerOf('User')->getWithPseudo($pseudo);
                if ($userWithSamePseudo && $userWithSamePseudo->id() != $this->app->user()->getAttribute('user_id')) {
                    $valid = false;
                    $this->app->user()->setFlash('Ce pseudo est déjà pris', "alert alert-danger");
                }
                $userWithSameMail = $this->managers->getManagerOf('User')->getWithMail($mail);
                if ($userWithSameMail && $userWithSameMail->id() != $this->app->user()->getAttribute('user_id')) {
                    $valid = false;
                    $this->app->user()->setFlash('Ce mail est déjà pris', "alert alert-danger");
                }
            } else {
                if ($this->managers->getManagerOf('User')->getWithPseudo($pseudo)) {
                    $valid = false;
                    $this->app->user()->setFlash('Ce pseudo est déjà pris', "alert alert-danger");
                }
                if ($this->managers->getManagerOf('User')->getWithMail($mail)) {
                    $valid = false;
                    $this->app->user()->setFlash('Ce mail est déjà pris', "alert alert-danger");
                }
            }
        } else {
            if ($idUser = $this->app->user()->getAttribute('user_id')) {
                $user = $this->managers->getManagerOf('User')->getUnique($idUser);
                $user->setPassword(null);
            } else {
                $user = new User();
            }
        }

        $userBuilder = new UserFormBuilder($user);
        $userBuilder->build();

        $form = $userBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('User'), $request, $this->app->user());

        if ($valid && $formHandler->process()) {
            if ($user->isNew()) {
                $url='http://monsite.fr/activation-'.$user->newId.'-'.$user->confirmationToken();
                mail($user->mail(), "Confirmation de votre compte", "Afin de valider votre compte, veuillez cliquer sur le lien suivant : \n\n".$url);
                $this->app->user()->setFlash('Merci, un mail vous a été envoyé pour confirmer votre compte', "alert alert-success");
            } else {
                $this->app->user()->setAttribute('user_avatar', $user->avatar());
                $this->app->user()->setAttribute('user_pseudo', $user->pseudo());
                $this->app->user()->setFlash('Votre compte a bien été modifié !', 'alert alert-success');
            }
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeConfirmAccount(HTTPRequest $request)
    {
        $id=$request->getData('id');
        $token=$request->getData('token');
        $user = $this->managers->getManagerOf('User')->getUnique($id);
        if ($user) {
            if ($user->confirmationToken() == $token) {
                $user->setConfirmed(true);
                $user->setConfirmationToken(null);
                $this->managers->getManagerOf('User')->save($user, false);
                $this->app->user()->setFlash('Votre compte est maintenat activé', "alert alert-success");
                $this->app()->httpResponse()->redirect('/connexion');
            } else {
                $this->app->user()->setFlash('Ce lien d\'activation est invalide', "alert alert-danger");
                $this->app()->httpResponse()->redirect('/connexion');
            }
        } else {
            $this->app->user()->setFlash('Ce lien d\'activation est invalide', "alert alert-danger");
            $this->app()->httpResponse()->redirect('/connexion');
        }
    }

    public function executePasswordForget(HTTPRequest $request)
    {
        if ($request->postExists("email")) {
            $user = $this->managers->getManagerOf('User')->getWithMail($request->postData('email'));
            if ($user) {
                $token = $this->Random_str_generator(60);
                $user->setResetToken($token);
                $user->setResetAt(new \DateTime('NOW'));
                $this->managers->getManagerOf('User')->save($user, false);

                $url='http://monsite.fr/password-reset-'.$user->id().'-'.$user->resetToken();
                mail($user->mail(), "Récupération mot de passe", "Afin de définir un nouveau mot de passe, veuillez cliquer sur le lien suivant : \n\n".$url);
                $this->app()->user()->setFlash('Merci, un mail vous a été envoyé pour définir un nouveu mot de passe', "alert alert-success");
                $this->app()->httpResponse()->redirect('/connexion');
            } else {
                $this->app()->user()->setFlash('Ce mail ne correspond à aucun utilisateur', "alert alert-danger");
                $this->app()->httpResponse()->redirect('/password-forget');
            }
        }
     
        $this->page->addVar('title', 'Récupération du mot de passe');

        $this->addView();
    }

    public function executePasswordReset(HTTPRequest $request)
    {
        $userId = $request->getData('id');
        $token = $request->getData('token');
        $user = $this->managers->getManagerOf('User')->getUnique($userId);
        if ($user) {
            $now = new \DateTime('NOW');
            if ($user->resetToken() == $token && $now->diff($user->resetAt())->days < 2) {
                if ($request->postExists('password') && $request->postExists('password_confirm')) {
                    $password = $request->postData('password');
                    $password_confirm = $request->postData('password_confirm');
                    if ($password == $password_confirm) {
                        $user->setPassword($password);
                        $this->managers->getManagerOf('User')->save($user);

                        $this->app()->user()->setFlash('Votre mot de passe a été mis à jour', "alert alert-success");
                        $this->app()->user()->setAuthenticated((int) $user->id());
                        if ($user->admin() == 1) {
                            $this->app()->user()->setAdmin();
                        }
                        $this->app()->httpResponse()->redirect('/connexion');
                    } else {
                        $this->app()->user()->setFlash('Les mots de passe ne sont pas identiques', "alert alert-danger");
                        //$this->app()->httpResponse()->redirect('.');
                    }
                }
            } else {
                $this->app()->user()->setFlash('Le lien est invalide', "alert alert-danger");
                $this->app()->httpResponse()->redirect('/connexion');
            }
        } else {
            $this->app()->user()->setFlash('Le lien est invalide', "alert alert-danger");
            $this->app()->httpResponse()->redirect('/connexion');
        }
        $this->addView();
    }
}
