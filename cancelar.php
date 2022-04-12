<?php

session_start();

unset($_SESSION['face_access_token']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
header("Location: login.php");

?>