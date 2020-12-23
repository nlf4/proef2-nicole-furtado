<?php
$form = true;
require_once "autoload.php";
$formname = $_POST["formname"];

// handle form according to type
switch ( $formname )
{
    case "login_form":

        if ($_POST['login_button'] == "Log in" )
        {
            $formHandler = $container->getFormHandler();
            $formHandler->processLogIn();
        }
        break;

    case "eul_form":

        if ($_POST['eul_button'] == "Akkoord")
        {
            $agreementHandler = $container->getAgreementHandler();
            $agreementHandler->processSignedAgreement();
        }
        break;

    default:
        // error message if no form is addressed
        print "ERROR - no form was submitted";
}

