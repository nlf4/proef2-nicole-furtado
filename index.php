<?php
$form = true;
$login_form = true;
require_once "lib/autoload.php";

$css = array( "style.css");
$viewService->basicHead($css, "Log in");
print $viewService->loadTemplate("login");
