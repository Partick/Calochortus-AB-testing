<?php
require_once __DIR__ . '/config.php';
unset($_SESSION['ab_test_authed']);
session_destroy();
header('Location: login.php');
exit;
