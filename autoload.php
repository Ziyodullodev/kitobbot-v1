<?php

spl_autoload_register(function ($function_name)
{
    include 'handlers/'. $function_name .'.php';
});


?>