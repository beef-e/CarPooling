<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

$conn = mysqli_connect('localhost', 'pool', '', 'poolCar');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $partenza = $_POST['departure'];
    $destinazione = $_POST['destination'];
    $data = $_POST['departure_date'];
    $ora = $_POST['departure_time'];
    $posti = $_POST['seats'];
    $prezzo = $_POST['price'];


    $query = "INSERT INTO Viaggi (partenza, prezzo, destinazione, data_viaggio, ora, posti, id_utente_offerente) VALUES ('$partenza', '$prezzo', '$destinazione', '$data', '$ora', '$posti', '{$_SESSION['id']}')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo $twig->render('success.html');
    } else {
        echo "Errore nella creazione del viaggio. Riprova." . mysqli_error($conn);
    }
} else {
    if ($_SESSION['ruolo'] != 'd') {
        header('Location: home_controller.php');
    } else {
        echo $twig->render('create_trip.html');
    }
}
