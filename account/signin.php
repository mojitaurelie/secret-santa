<?php
session_start();

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_SESSION["CSRF"]) or !isset($_POST['CSRF'])) {
    $_SESSION["error"] = "La requête a été modifié avant la réception (ERR_CSRF_NOT_FOUND)";
    header('Location: /signup.php');
    exit(0);
}

if ($_SESSION["CSRF"] != $_POST['CSRF']) {
    $_SESSION["error"] = "La requête a été modifié avant la réception (ERR_CSRF_INVALID)";
    header('Location: /signup.php');
    exit(0);
}

if (!isset($_POST["email"]) or !isset($_POST['password']) or !isset($_POST['displayName'])) {
    $_SESSION["error"] = "Un champ est manquant (ERR_MISSING_FIELD)";
    header('Location: /signup.php');
    exit(0);
}

if ($_POST["password"] != $_POST['password-verification']) {
    $_SESSION["error"] = "Les mots de passes sont differents (ERR_MISSING_FIELD)";
    header('Location: /signup.php');
    exit(0);
}

$db = new DatabaseLoader();

$user = $db->FindOne("SELECT password, displayName FROM `user` WHERE email = ?", [$_POST["email"]]);

if ($user == null) {
    $OPTIONS = [
        'cost' => 12,
    ];
    $PASS = password_hash($_POST["password"], PASSWORD_BCRYPT, $OPTIONS);
    $params = [
        $_POST["email"],
        $PASS,
        $_POST["displayName"],
        $_POST["displayName"]
    ];
    $db->Execute("INSERT INTO `user` (email, password, displayName, transDisplayName) VALUES (?,?,?,?)", $params);
    header('Location: /');
    exit(0);
}

$_SESSION["error"] = "L'adresse email exist deja (ERR_USER_EXIST)";
header('Location: /signup.php');