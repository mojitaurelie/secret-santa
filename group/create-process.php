<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_SESSION["CSRF"]) or !isset($_POST['CSRF'])) {
    header('Location: /');
    exit(0);
}

if ($_SESSION["CSRF"] != $_POST['CSRF']) {
    header('Location: /');
    exit(0);
}

if (!isset($_POST["name"])) {
    header('Location: /');
    exit(0);
}

$db = new DatabaseLoader();
$db->Execute("INSERT INTO `group` (name, administrator) VALUES (?,?)", [$_POST['name'], $_SESSION['id']]);
$result = $db->FindOne("SELECT `id` FROM `group` WHERE `id` = LAST_INSERT_ID()", []);
$db->Execute("INSERT INTO `groupuserlink` (userId, groupId) VALUES (?, ?)", [$_SESSION['id'], $result['id']]);
header('Location: /dashboard/group.php');