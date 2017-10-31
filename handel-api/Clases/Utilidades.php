<?php
function GET($name, $def = '')
{
    if (isset($_GET[$name]))
        return $_GET[$name];
        else
            return $def;
}

function REQUEST($name, $def = '')
{
    if (isset($_REQUEST[$name]))
        return $_REQUEST[$name];
        else
            return $def;
}



