<?php
session_start();

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_SESSION["CSRF"]) or !isset($_POST['CSRF'])) {
    $_SESSION["error"] = "La requête a été modifié avant la réception (ERR_CSRF_NOT_FOUND)";
    header('Location: /');
    exit(0);
}

if ($_SESSION["CSRF"] != $_POST['CSRF']) {
    $_SESSION["error"] = "La requête a été modifié avant la réception (ERR_CSRF_INVALID)";
    header('Location: /');
    exit(0);
}

if (!isset($_POST["email"]) or !isset($_POST['password'])) {
    $_SESSION["error"] = "Un champ est manquant (ERR_MISSING_FIELD)";
    header('Location: /');
    exit(0);
}

$db = new DatabaseLoader();

$user = $db->FindOne("SELECT password, transDisplayName, id FROM `user` WHERE email = ?", [$_POST["email"]]);

if ($user != null) {
    if (password_verify($_POST["password"], $user["password"])) {
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["displayName"] = $user["transDisplayName"];
        $_SESSION["id"] = $user["id"];
        header('Location: /dashboard');
        exit(0);
    }
}
$_SESSION["error"] = "Mot de passe incorrect (ERR_PASSWORD_INCORRECT)";
header('Location: /');