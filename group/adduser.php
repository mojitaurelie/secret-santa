<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");
header('Content-Type: application/json');

if (!isset($_POST["email"]) or !isset($_POST['id'])) {
    echo json_encode(["error" => "Champs manquant"]);
    exit(0);
}

$db = new DatabaseLoader();

$user = $db->FindOne("SELECT id FROM `user` WHERE email = ?", [$_POST["email"]]);

if ($user != null) {
    $count = $db->FindOne("SELECT count(*) FROM `groupuserlink` WHERE userId = ? AND groupId = ?", [$user['id'], $_POST['id']]);
    if (intval($count[0]) == 0) {
        $db->Execute("INSERT INTO groupuserlink (userId, groupId) VALUES (?,?)", [$user['id'], $_POST['id']]);
        echo json_encode(["status" => "created"]);
    } else {
        echo json_encode(["status" => "already added"]);
    }
} else {
    echo json_encode(["status" => "user not found"]);
}
