<?php
session_start();
session_unset();
session_destroy();
header("Location: /UrbanJ/login.php"); // Redirige al login o donde prefieras
exit();
