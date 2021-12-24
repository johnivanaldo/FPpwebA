<?php
    session_start();
    session_unset();
    session_destroy();

    // Redirect ke index.php
    header("location: index.php");
    exit;
?>