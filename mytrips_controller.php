<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

$conn = mysqli_connect('localhost', 'pool', '', 'poolCar');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    if ($_SESSION['ruolo'] == "d") {
        $query = "SELECT * FROM Viaggi WHERE id_utente_offerente = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $query);
        // mysqli_fetch_all restituisce un array di array associativi (quando MYSQLI_ASSOC viene passato come secondo argomento)

        // $trips è un array di array associativi (ogni array associativo rappresenta un viaggio, cioè una riga della tabella Viaggi)
        $trips = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $query = "SELECT *, COUNT(*) as n_prenotazioni FROM Prenotazioni WHERE codice_viaggio IN (SELECT id FROM Viaggi WHERE id_utente_offerente = '{$_SESSION['id']}') group by codice_viaggio, id_utente";
        $result = mysqli_query($conn, $query);
        $bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo $twig->render('mytrips.html', ['user' => $_SESSION, 'trips' => $trips, 'bookings' => $bookings]);
    } else {
        // TODO: If the user is not a driver, fetch all the bookings made by the user and all the trips the user has booked

        $query = "SELECT codice_viaggio, stato, COUNT(*) as n_prenotazioni From Prenotazioni Where id_utente='{$_SESSION['id']}' group by codice_viaggio, id_utente";
        $result = mysqli_query($conn, $query);
        $bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);


        $query = "SELECT * FROM Viaggi WHERE id IN (SELECT codice_viaggio FROM Prenotazioni WHERE id_utente = '{$_SESSION['id']}')";
        $result = mysqli_query($conn, $query);
        $trips = mysqli_fetch_all($result, MYSQLI_ASSOC);


        echo $twig->render('mytrips.html', ['user' => $_SESSION, 'bookings' => $bookings, 'trips' => $trips]);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_viaggio = $_POST['id_viaggio'];
    $id_utente = $_POST['id_utente'];
    $azione = $_POST['action'];

    if ($azione == "ACC") {
        $query = "UPDATE Prenotazioni SET stato = 'a' WHERE codice_viaggio = '{$id_viaggio}' AND id_utente = '{$id_utente}'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Errore";
        }
    } else {
        $query = "UPDATE Prenotazioni SET stato = 'r' WHERE codice_viaggio = '{$id_viaggio}' AND id_utente = '{$id_utente}'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Errore";
        }
    }
}
