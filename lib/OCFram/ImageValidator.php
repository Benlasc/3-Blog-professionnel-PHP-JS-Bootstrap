<?php

namespace OCFram;

use finfo;

class ImageValidator extends Validator
{
    protected $authorizedMimes;

    public function __construct($errorMessage, $authorizedMimes = ['image/jpeg'])
    {
        parent::__construct($errorMessage);

        $this->setAuthorizedMimes($authorizedMimes);
    }

    public function isValid($value)
    {
        $finfo = new finfo();
        $info = $finfo->file($value, FILEINFO_MIME_TYPE);
        return in_array($info, $this->authorizedMimes);
    }

    public function setAuthorizedMimes(array $authorizedMimes)
    {
        foreach ($authorizedMimes as $mime) {
            if (is_string($mime)) {
                $this->authorizedMimes[] = $mime;
            } else {
                throw new \RuntimeException('Le type d\'image n\'est pas une chaîne de caractères.');
            }
        }
    }
}
