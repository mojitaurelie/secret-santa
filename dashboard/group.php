<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

$db = new DatabaseLoader();

$groups = [];
$groupsLink = $db->Query("SELECT * FROM `groupuserlink` WHERE userId = ?", [$_SESSION['id']]);
foreach ($groupsLink as $groupLink) {
    $group = $db->FindOne("SELECT * FROM `group` WHERE id = ?", [$groupLink['groupId']]);
    $count = $db->FindOne("SELECT count(*) FROM `groupuserlink` WHERE groupId = ?", [$groupLink['groupId']]);
    $group['count'] = $count[0];
    $groups[] = $group;
}

$title = "Vos tirages";
?>
<?php ob_start(); ?>
<a class="btn btn-primary" href="/group/create.php" style="margin-top: 1rem;">Créer un tirage</a>
<ul class="list-group" style="margin-top: 1rem;">
    <?php foreach ($groups as $group): ?>
        <li class="list-group-item"><a href="/group/detail.php?id=<?= $group['id'] ?>"><?= $group['name'] ?></a><span class="grayed"> · <?= $group['count'] ?> <?= $group['count'] > 1 ? 'participants' : 'participant' ?></span></li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
