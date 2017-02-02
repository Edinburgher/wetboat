<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location:admin");
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php
    include_once __DIR__ . "/php/blocks/head.php";
    ?>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/login.js"></script>
</head>

<body>
    <?php
    include_once __DIR__ . "/php/blocks/nav.php";
    ?>
    <form id="loginForm" class="form-signin" method="POST">
        <div class="alert alert-danger hidden" id="errorMessage">
            <p></p>
        </div>
        <div id="txtLogin">Bitte melden Sie sich an!</div>
        <input type="text" name="username" class="form-control input-lg" placeholder="Benutzername" required autofocus>
        <input type="password" name="password" class="form-control input-lg" placeholder="Passwort" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">
            Anmelden
        </button>

    </form>

    <?php
    include_once __DIR__ . "/php/blocks/footer.php";
    ?>
</body>

</html>