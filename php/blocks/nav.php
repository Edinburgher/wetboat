
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <img alt="Brand" src="img/Logo_website_new.png" />
            </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li
                    <?php
                    $filename = basename($_SERVER['PHP_SELF']);
                    if ($filename === "index.php") {
                        echo " class='active'";
                    }
                    ?>>
                    <a href="index.php">Home</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<li><a id='txtSession'>Herzlich Willkommen, " . htmlspecialchars($_SESSION['username']) . "!</a></li>";
                    echo "<li";
                    if ($filename === "admin.php")
                        echo " class='active'";

                    echo "><a href='admin.php' id='btnLogin'>Admin</a></li>";
                    echo "<li><a href='php/logout.php' id='btnLogout'><span class='glyphicon glyphicon-log-out'></span> Abmelden</a></li>";
                } else {
                    echo "<li";
                    if ($filename === "login.php")
                        echo " class='active'";
                    echo "><a href='login.php' id='btnLogin'><span class='glyphicon glyphicon-log-in'></span> Anmelden</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</nav>