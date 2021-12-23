<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

if (!isset($_GET["id"])) {
    header('Location: /');
    exit(0);
}

$db = new DatabaseLoader();
$group = $db->FindOne("SELECT * FROM `group` WHERE id = ?", [$_GET['id']]);

if ($group == null) {
    header('Location: /');
    exit(0);
}

$tirage = $db->FindOne("SELECT * FROM `tirage` WHERE groupId = ?", [$_GET['id']]);
if ($tirage != null) {
    $db->Execute("DELETE FROM `result` WHERE tirageId = ?", [$tirage['id']]);
    $db->Execute("DELETE FROM `tirage` WHERE id = ?", [$tirage['id']]);
}

$links = $db->Query("SELECT * FROM `groupuserlink` WHERE groupId = ?", [$_GET['id']]);
if (count($links) < 2) {
    header('Location: /');
    exit(0);
}

$santas = [];
$receivers = [];
foreach ($links as $link) {
    $santas[] = $link['userId'];
    $receivers[] = $link['userId'];
}

$running = true;

while ($running) {
    $done = true;
    shuffle($receivers);
    for ($i = 0; $i < count($santas); $i++) {
        if ($santas[$i] == $receivers[$i]) {
            $done = false;
        }
    }

    if ($done) {
        $running = false;
    }
}

$db->Execute("INSERT INTO `tirage` (groupId, number) VALUES (?,0)", [$_GET['id']]);
$tirageId = $db->FindOne("SELECT `id` FROM `tirage` WHERE `id` = LAST_INSERT_ID()", []);
for ($i = 0; $i < count($santas); $i++) {
    $db->Execute("INSERT INTO `result` (userId, tirageId, resultId) VALUES (?,?,?)", [$santas[$i], $tirageId[0], $receivers[$i]]);
}

header('Location: /dashboard/group.php');