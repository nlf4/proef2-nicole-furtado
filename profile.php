<?php
$form = true;
$login_form = false;
$eul_form = true;
require_once "lib/autoload.php";
$css = array( "style.css");
$viewService->basicHead($css, "Profile");

$data = array(
    "email" => $_SESSION['user_email'],
    "language" => $_SESSION['user_lang'],
    "eul" => $_SESSION['signed_agreement'],
    "firstname" => $_SESSION['user_firstname'],
    "lastname" => $_SESSION['user_name']);

$template = $viewService->loadTemplate("profile");
print $viewService->replaceContentOneRow($data, $template);
