<?php
ini_set("error_reporting", E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$_application_folder = "/questi-proef2";
$_root_folder = $_SERVER['DOCUMENT_ROOT'];
date_default_timezone_set('Europe/Amsterdam');

//load Services
require_once $_root_folder . "/services/Container.php";
require_once $_root_folder . "/services/DatabaseService.php";
require_once $_root_folder . "/services/ViewService.php";
require_once $_root_folder . "/services/FormHandler.php";
require_once $_root_folder . "/services/AgreementHandler.php";

//database functions
require_once $_root_folder . "/lib/password.php";

session_start();
//$_SESSION["head_printed"] = false;

$container = new Container($connectionData);
$viewService = $container->getViewService();
