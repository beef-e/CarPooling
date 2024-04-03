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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['ruolo'] === "d") {
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
    } else {
        $feedback = $_POST["feedback"];

        //Select utenti il cui ID è in viaggi, tra i viaggi i cui id sono tra le prenotazioni dell'utente attualmente loggato

        //Query fatta interamente da solo a mano sono troppo forte
        $query = "SELECT * FROM Utenti where id IN (SELECT id_utente_offerente FROM Viaggi where id in (SELECT codice_viaggio FROM Prenotazioni WHERE id_utente='{$_SESSION['id']}'))";
        $result = mysqli_query($conn, $query);
        //in teoria deve ritornare sempre un solo utente
        $driver = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $driver = $driver[0];

        if (is_numeric($driver["media_voto"])) {
            $media = $driver["media_voto"];
        } else {
            $media = 0;
        }

        $n_voti = $driver["n_voti"] + 1;

        if ($n_voti === 1) {
            $media = $feedback;
        } else {
            $media = ($media + $feedback) / $n_voti;
        }



        $query = "UPDATE Utenti SET media_voto = '{$media}' WHERE id = '{$driver['id']}'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "errore in updating media";
        }
        $query = "UPDATE Utenti SET n_voti='{$n_voti}' WHERE id = '{$driver['id']}'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "errore in updating n_voti";
        }
        $query = "INSERT INTO Feedback (voto, id_ut_act, id_ut_pass) VALUES ('{$feedback}', '{$_SESSION['id']}', '{$driver['id']}')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "errore in updating feedback table";
        }
    }
}
