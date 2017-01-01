<?php
session_start();
session_destroy();
echo "Abmeldung erfolgreich";
header("Location: ../index.php");
