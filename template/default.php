<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="/public/stylesheets/style.css" rel="stylesheet">
    <?= $stylesheets ?? '' ?>
    <title>Secret Santa - <?= $title ?? 'Untitled page' ?></title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="/public/images/santa-hat.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            Secret Santa
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard/group.php">Tirage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard/account.php">Compte</a>
                </li>
            </ul>
            <?php if (isset($_SESSION["displayName"])): ?>
                <span class="navbar-text">
                    <?= $_SESSION["displayName"] ?>
                    <a href="/account/logout.php">Déconnexion</a>
                </span>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">
    <?= $content ?? '' ?>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<?= $scripts ?? '' ?>
</html>