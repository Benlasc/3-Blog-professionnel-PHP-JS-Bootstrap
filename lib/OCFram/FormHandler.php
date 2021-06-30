<?php

namespace OCFram;

class FormHandler
{
    protected $form;
    protected $manager;
    protected $request;

    public function __construct(Form $form, Manager $manager, HTTPRequest $request, User $user)
    {
        $this->setForm($form);
        $this->setManager($manager);
        $this->setRequest($request);
        $this->setUser($user);
    }

    public function process()
    {
        $requestToken = $this->request->postData('token');
        $userToken =  $this->user->getAttribute('token');
        if ($this->request->method() == 'POST' && $this->form->isValid()) {
            if ($requestToken==$userToken) {
                $this->manager->save($this->form->entity());
                return true;
            }
            $this->user->setFlash('Les tokens ne correspondent pas !', 'alert alert-danger');
            return false;
        }
        return false;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function setManager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function setRequest(HTTPRequest $request)
    {
        $this->request = $request;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
