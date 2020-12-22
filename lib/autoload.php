<?php
//$_url = "http://localhost:8080";
//$_grant_type= 'password';
//$_client_id = 'q-sollicitatie-nifu';
//$_client_secret_pre = '5Wlu8Fq3wSBxIPa4vB9AOGPCyQ8QwVw0w5MjFzTXj8pdeDWziG';
//$_scope = 'sollicitatie-scope';
//$_token_url = 'https://apidev.questi.com/2.0/token/?';
//$_api_url = 'https://apidev.questi.com/2.0';


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
