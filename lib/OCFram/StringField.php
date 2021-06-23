<?php

namespace OCFram;

class StringField extends Field
{
    protected $maxLength;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        $widget .='<div class="col-12"><label for=' . $this->name . ' class="form-label">'. $this->label .'</label><input type="text"class="form-control" id=' . $this->name . ' name="' . $this->name . '"';

        if (!empty($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (!empty($this->maxLength)) {
            $widget .= ' maxlength="' . $this->maxLength . '"';
        }

        return $widget .= ' /></div>';
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
