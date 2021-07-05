<?php

namespace OCFram;

class MailField extends Field
{
    protected $maxLength;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= '<div class="alert alert-danger" role="alert">' . $this->errorMessage . '</div>';
        }

        $required = ($this->required()) ? 'required' : '';

        $widget .= '<div class="col-12"><label for=' . $this->name . ' class="form-label">' . $this->label 
        . '</label><input type="email" class="form-control" id='. $this->name . ' name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (!empty($this->maxLength)) {
            $widget .= ' maxlength="' . $this->maxLength . '"';
        }

        return $widget .= $required . ' /></div>';
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
}
