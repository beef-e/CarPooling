<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $identita = $_POST['id_number'];
    $scadenza_id = $_POST['id_expiry'];
    $birthdate = $_POST['birthdate'];
    $credit_card = $_POST['credit_card'];

    if (isset($_POST['license_number'])) {
        $role = 'driver';
        $license = $_POST['license_number'];
        $license_expiry = $_POST['license_expiry'];
    } else {
        $role = 'basic';
    }

    // echo $name . $surname . $username . $email . $password . $phone . $identita . $scadenza_id . $birthdate . $credit_card . $license . $license_expiry;
    // TODO check if the use already exists

    // TODO insert the user into the database

    // TODO reload to the page with success message
} else {
    echo $twig->render('signup.html');
}
