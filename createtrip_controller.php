<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

if ($_SESSION['role'] != 'd') {
    header('Location: home_controller.php');
} else {
    echo $twig->render('create_trip.html');

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $partenza = $_POST['departure'];
        $destinazione = $_POST['destination'];
        $data = $_POST['departure_date'];
        $ora = $_POST['departure_time'];
        $posti = $_POST['seats'];
        $prezzo = $_POST['price'];
    }
}
