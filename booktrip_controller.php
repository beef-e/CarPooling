<?php
session_start();
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');

$twig = new \Twig\Environment($loader);

$conn = mysqli_connect('localhost', 'pool', '', 'poolCar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codice_viaggio = $_POST['codice_viaggio'];

    $data = date('Y-m-d');

    $query = "INSERT INTO Prenotazioni (id_utente, codice_viaggio, data_prenotazione, stato) VALUES ('{$_SESSION['id']}', '$codice_viaggio', '{$data}', 's')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $query = "UPDATE Viaggi SET posti = posti - 1 WHERE id = '$codice_viaggio'";
        $result2 = mysqli_query($conn, $query);
        if (!$result2) {
            echo "Errore nella prenotazione del viaggio. Riprova." . mysqli_error($conn);
        } else {
            echo $twig->render('success.html');
        }
    } else {
        echo "Errore nella prenotazione del viaggio. Riprova." . mysqli_error($conn);
    }
} else {
    $query = "SELECT * FROM Viaggi WHERE posti > 0 AND id_utente_offerente != {$_SESSION['id']}";
    $result = mysqli_query($conn, $query);
    $trips = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo $twig->render('booktrip.html', ['user' => $_SESSION, 'trips' => $trips]);
}
