<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

echo $twig->render('mytrips.html', ['user' => $_SESSION]);

if ($_SESSION['ruolo'] == "d") {
    $conn = mysqli_connect('localhost', 'pool', '', 'poolCar');
    $query = "SELECT * FROM Viaggi WHERE id_utente_offerente = '{$_SESSION['id']}'";
    $result = mysqli_query($conn, $query);
    // mysqli_fetch_all restituisce un array di array associativi (quando MYSQLI_ASSOC viene passato come secondo argomento)

    // $trips è un array di array associativi (ogni array associativo rappresenta un viaggio, cioè una riga della tabella Viaggi)
    $trips = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo $twig->render('mytrips.html', ['user' => $_SESSION, 'trips' => $trips]);
} else {
    // TODO: If the user is not a driver, fetch all the bookings made by the user
}
