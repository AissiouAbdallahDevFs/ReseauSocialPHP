<?php
session_start();
require_once 'config.php';
include 'lib/database.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = htmlspecialchars($_POST('email'));
    $password = htmlspecialchars($_POST('passeword'));
    $check = connectToDatabase()->prepare('SELECT pseudo , email , password FROM user WHERE email = ?');
    $check->execute(array($email));
    $date = $check->fetch();
    $row = $check->rowCount();
    if ($row == 1) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $password = hash('sha256', $password);
            if ($date['password'] === $password) {
                $_SESSION['user'] = $data['pseudo'];
                header('Location:landing.php');
            } else header('Location:index.php?login_err=password');
        } else header('Location:index.php?login_err=email');
    } else header('Location:index.php?login_err=already');
} else header('Location:index.php');
