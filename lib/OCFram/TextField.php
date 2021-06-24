<?php

namespace OCFram;

class TextField extends Field
{
    protected $cols;
    protected $rows;
    protected $heightBootstrap;

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= '<div class="alert alert-danger" role="alert">' . $this->errorMessage . '</div>';
        }

        $required = ($this->required()) ? 'required' : '' ;

        $widget .= '<div class="col-12"><label for="' . $this->name . '" floatingTextarea2" class="form-label">' . $this->label . '</label><textarea class="form-control" id="' . $this->name . '" name="'.$this->name().'" style="height: ' . $this->heightBootstrap . 'px" '.$required.' >';

        if (!empty($this->value)) {
            $widget .= htmlspecialchars($this->value) . '</textarea></div>';
        }

        return $widget . '</textarea></div>';
    }

    public function setCols($cols)
    {
        $cols = (int) $cols;

        if ($cols > 0) {
            $this->cols = $cols;
        }
    }

    public function setRows($rows)
    {
        $rows = (int) $rows;

        if ($rows > 0) {
            $this->rows = $rows;
        }
    }

    public function setHeightBootstrap(int $height)
    {
        if ($height > 0) {
            $this->heightBootstrap = $height;
        }
    }
}
