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
$user = $db->FindOne("SELECT * FROM `user` WHERE id = ?", [$_SESSION['id']]);

$title = "Votre compte";
?>
<?php ob_start(); ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error'] ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['info'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['info'] ?>
    </div>
<?php endif; ?>
<div class="card" style="margin-top: 1rem;">
    <div class="card-body">
        <form method="post" action="/account/update-account.php">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
            </div>
            <div class="mb-3">
                <label for="displayName" class="form-label">Votre nom d'affichage (Publique)</label>
                <input type="text" class="form-control" id="displayName" name="displayName" value="<?= $user['displayName'] ?>">
            </div>
            <div class="mb-3">
                <label for="transDisplayName" class="form-label">Votre nom d'affichage (Privé)</label>
                <input type="text" class="form-control" id="transDisplayName" name="transDisplayName" title="For my trans peeps 🏳️‍⚧️" value="<?= $user['transDisplayName'] ?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>

<div class="card" style="margin-top: 1rem;">
    <div class="card-body">
        <form method="post" action="/account/update-password.php">
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password-verification" class="form-label">Vérification du mot de passe</label>
                <input type="password" class="form-control" id="password-verification" name="password-verification">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require($ROOT . '/template/default.php'); ?>
<?php unset($_SESSION['error']); ?>
<?php unset($_SESSION['info']); ?>
