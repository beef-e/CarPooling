<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

if (isset($_SESSION['nome'])) {
    header('Location: home_controller.php');
} else {
    echo $twig->render('login.html');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $conn = mysqli_connect('localhost', 'pool', '', 'poolCar');

        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }

        $query = "SELECT * FROM Utenti WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo $row;
            assign_sess_values($row);
            header('Location: home_controller.php');
        } else {
            echo "email or password incorrect!";
        }
    }
}

function assign_sess_values($row)
{
    $_SESSION['id'] = $row['id'];
    $_SESSION['nome'] = $row['nome'];
    $_SESSION['cognome'] = $row['conome'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['ruolo'] = $row['ruolo'];
    $_SESSION['tel'] = $row['tel'];
    $_SESSION['carta_id'] = $row['carta_id'];
    $_SESSION['scad_id'] = $row['scad_id'];
    $_SESSION['data_nascita'] = $row['data_nascita'];
    $_SESSION['carta_credito'] = $row['carta_credito'];
}
