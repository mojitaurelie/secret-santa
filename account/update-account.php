<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_POST['email']) or !isset($_POST['displayName']) or !isset($_POST['transDisplayName'])) {
    $_SESSION["error"] = "Un champ est manquant (ERR_MISSING_FIELD)";
    header('Location: /dashboard/account.php');
    exit(0);
}

$db = new DatabaseLoader();

$db->Execute("UPDATE `user` SET email = ?, displayName = ?, transDisplayName = ? WHERE id = ?", [$_POST['email'], $_POST['displayName'], $_POST['transDisplayName'], $_SESSION['id']]);

$_SESSION['info'] = "Votre compte a été mis à jour !";
header('Location: /dashboard/account.php');