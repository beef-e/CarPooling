<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

// If the user is logged in, render the home page with the user's name

/*
if (isset($_SESSION['user'])) {
    echo $twig->render('home.html', ['user' => $_SESSION['user']]);
} else {
    header('Location: /');
}
*/

echo $twig->render('home.html');
