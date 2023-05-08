<?php

    error_reporting(E_ALL & ~E_NOTICE);  
    ini_set('display_errors', 0);

    require_once("./db.php");
    require_once("./api.php");

    new DB();

    $request = explode("?", $_SERVER["REQUEST_URI"])[0];
    $method = $_SERVER["REQUEST_METHOD"];

    function getIp() {
        $ip = "";
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    $isAuth = (bool) $_COOKIE["isAuth"];
    $token = "";

    if (!isset($_COOKIE["token"])) {
        $token = createUser($_SERVER['HTTP_USER_AGENT'], getIp());
    } else {
        $token = $_COOKIE["token"];
    }

    if ($method === "GET") {

        switch ($request) {
            case "/":
                require(__DIR__ . "/pages/index.php");
                break;

            case "/api/getEvents":
                getEventByEventName($_GET["startDate"] ?? "", $_GET["endDate"] ?? "", $_GET["eventName"] ?? "");
                break;

            case "/api/getEventsByUser":
                getEventByUser($_GET["startDate"] ?? "", $_GET["endDate"] ?? "", $_GET["eventName"] ?? "");
                break;

            case "/api/getEventsByStatusUser":
                getEventByStatusUser($_GET["startDate"] ?? "", $_GET["endDate"] ?? "", $_GET["eventName"] ?? "");
                break;
        }

    }

    if ($method === "POST") {

        switch ($request) {
            case "/api/setEvent":
                if (isset($_POST["eventname"])) {
                    captureEvent($_POST["eventname"], $token, $isAuth);
                }

                break;

            case "/api/userLogin":
                userLogin();
                break;

            case "/api/userLogout":
                userLogout();
                break;
        }

    }
