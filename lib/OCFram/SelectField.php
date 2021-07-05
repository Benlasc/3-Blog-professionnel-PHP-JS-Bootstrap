<?php

namespace OCFram;

class SelectField extends Field
{
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->value = $value;
        }
    }

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= '<div class="alert alert-danger" role="alert">' . $this->errorMessage . '</div>';
        }

        $widget .= '<div class="col-12"><label class="form-label" for=' . $this->name 
        . ' >Auteur</label><select class="form-select form-select-sm" id ="' . $this->name . '" name="' 
        . $this->name . '" aria-label=".form-select-sm example">';

        foreach ($this->value as $user) {
            if ($user) {
                $widget .= '<option value="' . $user->id() . '">' . htmlspecialchars($user->nom()) . ' '
                 . htmlspecialchars($user->prenom()) . ' (pseudo : ' . htmlspecialchars($user->pseudo())
                 . ') </option>';
            } else {
                $widget .= '<option value="">Un ancien utilisateur du site</option>';
            }
        }
        return $widget .= '</select></div>';
    }
}
