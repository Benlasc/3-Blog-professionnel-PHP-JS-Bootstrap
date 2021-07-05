<?php

namespace OCFram;

trait randomStrGenerator
{
    function randomStrGenerator($len_of_gen_str)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $var_size = strlen($chars);
        $res = "";
        for ($x = 0; $x < $len_of_gen_str; $x++) {
            $res .= $chars[rand(0, $var_size - 1)];
        }
        return $res;
    }
}
