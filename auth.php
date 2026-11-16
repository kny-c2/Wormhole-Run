<?php
// Checks CURRENT session user to see if it is set, redirect to main page if not
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}