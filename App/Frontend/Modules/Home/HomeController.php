<?php

namespace App\Frontend\Modules\Home;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class HomeController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            if (($name = $request->postData('name')) &&
                ($mail = $request->postData('email')) &&
                $message = $request->postData('message')
            ) {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $phone = $request->postData('message') ?? null;

                    $file = 'assets/img/mail/logo.png';
                    $logo = base64_encode(file_get_contents($file));

                    // $file = 'assets/img/mail/img_21.jpg';
                    // $image = base64_encode(file_get_contents($file));

                    $image = "https://samples.mailbakery.com/mail/86/9ab6f4/r546367-73600181/images/img_21.jpg";

                    $to = 'blascaze@aol.com';
                    $subject = 'test mail html';
                    $from = $mail;
                    
                    // Pour envoyer du courrier HTML, l'en-tête Content-type doit être défini.
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    
                    // Créer les en-têtes de courriel
                    $headers .= 'From: '.$from."\r\n".
                                'Reply-To: '.$from."\r\n" .
                                'X-Mailer: PHP/' . phpversion();

                    $content = $this->app()->renderer()->render('mail', ['name'=> $name, 'mail'=> $mail, 'message'=> $message, 'phone'=> $phone, 'logo'=> $logo, 'image'=> $image]);




                    mail($to, $subject, $content, $headers);
                    $this->app->user()->setFlash('Votre message a bien été envoyé.', 'alert alert-success');
                    $this->app()->httpResponse()->redirect('/#contact');
                } else {
                    $this->app->user()->setFlash('Le formulaire est invalide', 'alert alert-danger');
                }
            } else {
                $this->app->user()->setFlash('Le formulaire est invalide', 'alert alert-danger');
            }
        }
        $this->addView();
    }
}
