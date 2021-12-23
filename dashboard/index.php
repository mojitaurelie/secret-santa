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
$groupsLink = $db->Query("SELECT * FROM `groupuserlink` WHERE userId = ? ORDER BY id DESC", [$_SESSION['id']]);
foreach ($groupsLink as $groupLink) {
    $group = $db->FindOne("SELECT * FROM `group` WHERE id = ?", [$groupLink['groupId']]);
    $count = $db->FindOne("SELECT count(*) FROM `groupuserlink` WHERE groupId = ?", [$groupLink['groupId']]);
    $group['count'] = $count[0];
    $tirage = $db->FindOne("SELECT * FROM `tirage` WHERE groupId = ?", [$groupLink['groupId']]);
    if ($tirage != null) {
        $result = $db->FindOne("SELECT * FROM `result` WHERE tirageId = ? AND userId = ?", [$tirage['id'], $_SESSION['id']]);
        $tirage['result'] = $result;
    }
    $group['tirage'] = $tirage;
    $groups[] = $group;
}

function GetRecipientName($id): ?string {
    $db = new DatabaseLoader();
    $name = $db->FindOne("SELECT displayName FROM `user` WHERE id = ?", [$id]);
    if ($name != null) {
        return $name[0];
    }
    return "???";
}

$title = $_SESSION["displayName"];
?>
<?php ob_start(); ?>
<?php foreach ($groups as $group): ?>
    <div class="card" style="margin-top: 1rem;">
        <div class="card-body">
            <h5 class="card-title"><?= $group['name'] ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?= $group['count'] ?> <?= $group['count'] > 1 ? 'participants' : 'participant' ?></h6>
            <p class="card-text">
                <?php if (isset($group['tirage']) and isset($group['tirage']['result'])): ?>
                Vous êtes le père noël suprise de <b><?= GetRecipientName($group['tirage']['result']['resultId']) ?></b>
                <?php else: ?>
                Le tirage n'a pas encore été éffectué
                <?php endif; ?>
            </p>
            <a href="/group/detail.php?id=<?= $group['id'] ?>" style="color: gray" class="card-link"><i class="bi bi-gear-fill"></i></a>
        </div>
    </div>
<?php endforeach; ?>
<?php if (count($groups) == 0): ?>
    Vous n'êtes ajouté sur aucun tirage 💔
<?php endif; ?>
<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
