<?php
$form = true;
require_once "autoload.php";

$formname = $_POST["formname"];

switch ( $formname )
{
    case "login_form":

        if ($_POST['login_button'] == "Log in" )
        {
//            print "Login form was submitted";
            $formHandler = $container->getFormHandler();
            $formHandler->processLogIn();
        }
        break;
    case "eul_form":

        break;
    default:
        // error message if no form is addressed
        print "ERROR - no form was submitted";
}

