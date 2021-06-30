<?php

namespace OCFram;

class MinLengthValidator extends Validator
{
    protected $minLength;

    public function __construct($errorMessage, $minLength)
    {
        parent::__construct($errorMessage);

        $this->setMaxLength($minLength);
    }

    public function isValid($value)
    {
        return strlen($value) >= $this->minLength;
    }

    public function setMaxLength($minLength)
    {
        $minLength = (int) $minLength;

        if ($minLength > 0) {
            $this->minLength = $minLength;
        } else {
            throw new \RuntimeException('La longueur minimale doit être un nombre supérieur à 0');
        }
    }
}
