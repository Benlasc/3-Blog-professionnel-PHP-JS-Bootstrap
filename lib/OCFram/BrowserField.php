<?php

namespace OCFram;

class BrowserField extends Field
{
    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= '<div class="alert alert-danger" role="alert">' . $this->errorMessage . '</div>';
        }

        $widget .= '<input name="oldValue" type="hidden" value="' . $this->value . '">';

        $widget .= '<div class="custom-file col-12"><label for="' . $this->name . '" class="form-label avatar">'.$this->label;

        if (empty($this->value)) {
            if (!preg_match('/\/admin\//', $_SERVER['REDIRECT_URL'])) {
                $widget .= ' (actuel : <img src="/assets/img/avatars/commentaire.jpg" alt="">)';
            }
        } else {
            if (!preg_match('/\/admin\//', $_SERVER['REDIRECT_URL'])) {
                $widget .= ' (actuel : <img src="'.htmlspecialchars($this->value).'" alt="">)';
            } else {
                $widget .= ' (actuelle : <img src="/'.htmlspecialchars($this->value).'" alt="">)';
            }
        }

        $widget .= '</label><input type="file" class="form-control" id ="' . $this->name . '" name="' . $this->name . '" accept=".png, .jpg, .jpeg">';

        return $widget;
    }
}
