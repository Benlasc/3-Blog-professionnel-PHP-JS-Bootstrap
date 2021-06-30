<?php

namespace OCFram;

trait Random_str_generator
{
    function random_str_generator($len_of_gen_str)
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
