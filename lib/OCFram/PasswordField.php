<?php

namespace OCFram;

class PasswordField extends Field
{
    protected $maxLength;
    protected $minLength;
    protected $passwordForget;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= '<div class="alert alert-danger" role="alert">' . $this->errorMessage . '</div>';
        }

        $required = ($this->required()) ? 'required' : '';

        $widget .= '<div class="col-12"><label for=' . $this->name . ' class="form-label">' . $this->label . '</label><input type="password" class="form-control" id=' . $this->name . ' name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (!empty($this->minLength)) {
            $widget .= ' minLength="' . $this->minLength . '"';
        }

        if (!empty($this->maxLength)) {
            $widget .= ' maxlength="' . $this->maxLength . '"';
        }

        $widget .= $required . ' /></div>';


        if (isset($this->passwordForget) && $this->passwordForget == true) {
            $widget .= '<a class="passwordForget" href="/password-forget">(J\'ai oublié mon mot de passe)</a>';
        }

        return $widget;
    }





























    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;

        if ($maxLength > 0) {
            $this->maxLength = $maxLength;
        } else {
            throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }
    }

    public function setMinLength(int $minlength)
    {
        if ($minlength > 0) {
            $this->minlength = $minlength;
        } else {
            throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }
    }

    public function setPasswordForget(bool $passwordForget)
    {
        $this->passwordForget = $passwordForget;
    }
}
