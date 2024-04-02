<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

$conn = mysqli_connect('localhost', 'pool', '', 'poolCar');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $identita = $_POST['id_number'];
    $scadenza_id = $_POST['id_expiry'];
    $birthdate = $_POST['birthdate'];
    $credit_card = $_POST['credit_card'];
    $license = NULL;
    $license_expiry = NULL;

    if (isset($_POST['driver'])) {
        $ruolo = 'd';
        $license = $_POST['license_number'];
        $license_expiry = $_POST['license_expiry'];
    } else {
        $ruolo = 'b';
        $license = NULL;
        $license_expiry = '0000-01-01';
    }

    // echo $name . $surname . $username . $email . $password . $phone . $identita . $scadenza_id . $birthdate . $credit_card . $license . $license_expiry;
    // TODO check if the use already exists
    $query = "SELECT * FROM Utenti WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        renderTemplate('userExists.html');
        exit();
    }

    // TODO insert the user into the database

    $query = "INSERT INTO Utenti (nome, conome, email, password, tel, carta_id, scad_id, data_nascita, carta_credito, ruolo, patente_id, scad_patente) VALUES ('$name', '$surname', '$email', '$password', '$phone', '$identita', '$scadenza_id', '$birthdate', '$credit_card', '$ruolo', '$license', '$license_expiry')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "User created successfully!";
    } else {
        //renderTemplate('error.html');
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
        exit();
    }

    // TODO reload to the page with success message
} else {
    renderTemplate('signup.html');
}

function renderTemplate($template)
{
    global $twig;
    echo $twig->render($template);
}
