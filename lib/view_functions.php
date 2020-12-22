<?php

function LoadTemplate( $name )
{
    return file_get_contents("templates/$name.html");
}


