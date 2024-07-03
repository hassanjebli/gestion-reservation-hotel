<?php
require_once '../config/database.php';

$sqlState = $conn->prepare('SELECT * FROM reservation');
$sqlState->execute();

$reservations = $sqlState->fetchAll(PDO::FETCH_ASSOC);
$events = [];

foreach ($reservations as $reservation) {
    $events[] = [
        'title' => 'RES ' . $reservation['id_reservation'],
        'start' => $reservation['date_arrivee'],
        'end' => $reservation['date_depart'],
        'extendedProps' => [
            'code_reservation' => $reservation['code_reservation'],
            'date_heure_reservation' => $reservation['date_heure_reservation'],
            'nbr_jours' => $reservation['nbr_jours'],
            'nbr_adultes_enfants' => $reservation['nbr_adultes_enfants'],
            'montant_total' => $reservation['montant_total'],
            'etat' => $reservation['etat'],
            'id_client' => $reservation['id_client'],
            'id_chambre' => $reservation['id_chambre']
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($events);

