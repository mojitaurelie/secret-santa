<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_POST['password']) or !isset($_POST['password-verification'])) {
    $_SESSION["error"] = "Un champ est manquant (ERR_MISSING_FIELD)";
    header('Location: /dashboard/account.php');
    exit(0);
}

if ($_POST["password"] != $_POST['password-verification']) {
    $_SESSION["error"] = "Les mots de passes sont differents (ERR_MISSING_FIELD)";
    header('Location: /dashboard/account.php');
    exit(0);
}

$db = new DatabaseLoader();

$OPTIONS = [
    'cost' => 12,
];
$PASS = password_hash($_POST["password"], PASSWORD_BCRYPT, $OPTIONS);

$db->Execute("UPDATE `user` SET password = ? WHERE id = ?", [$PASS, $_SESSION['id']]);

$_SESSION['info'] = "Votre mot de passe a été mis à jour !";
header('Location: /dashboard/account.php');