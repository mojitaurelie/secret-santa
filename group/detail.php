<?php
session_start();

if (!isset($_SESSION["displayName"]) or !isset($_SESSION["email"])) {
    $_SESSION["error"] = "Votre session a expiré (ERR_SESSION_EXPIRED)";
    header('Location: /');
    exit(0);
}

if (!isset($_GET['id'])) {
    header('Location: /dashboard');
    exit(0);
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/DatabaseLoader.php");

$participants = [];

$db = new DatabaseLoader();
$group = $db->FindOne("SELECT * FROM `group` WHERE id = ?", [$_GET['id']]);

if ($group == null) {
    header('Location: /dashboard');
    exit(0);
}

$usrGroups = $db->Query("SELECT * FROM `groupuserlink` WHERE groupId = ?", [$_GET['id']]);

foreach ($usrGroups as $usrGroup) {
    $user = $db->FindOne("SELECT * FROM `user` WHERE id = ?", [$usrGroup['userId']]);
    $participants[] = $user;
}
$admin = $db->FindOne("SELECT * FROM `user` WHERE id = ?", [$group['administrator']]);
$count = $db->FindOne("SELECT count(*) FROM `tirage` WHERE groupId = ?", [$group['id']]);
$executed = $count[0] > 0;

$ROOT = $_SERVER['DOCUMENT_ROOT'];
$title = $group['name'];
?>
<?php ob_start(); ?>
<h1><?= $group['name'] ?></h1>
<div>
    <p>Participants :</p>
    <ul class="list-group" style="margin-top: 1rem;">
        <?php foreach ($participants as $participant): ?>
            <?php if ($_SESSION['id'] == $participant['id']): ?>
                <li class="list-group-item"><?= $participant['transDisplayName'] ?></li>
            <?php else: ?>
                <li class="list-group-item"><?= $participant['displayName'] ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <button class="btn btn-primary" style="margin-top: 1rem;" id="open-modal">Ajouter un participant</button>
    <?php if ($_SESSION['id'] == $group['administrator']): ?>
        <a class="btn btn-primary" style="margin-top: 1rem;" href="/group/execute.php?id=<?= $_GET['id'] ?>">Effectuer le tirage</a>
    <?php else: ?>
        <?php if (!$executed): ?>
            <span style="margin-top: 1rem;"><?= $admin["displayName"] ?> n'a pas encore effectué le tirage</span>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="modal" id="add-participant-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email">
                    <input type="number" style="display: none;" id="group-id" value="<?= $_GET['id'] ?>" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btn-add-user">Ajouter</button>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/public/javascripts/group.js"></script>
<?php $scripts = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
