<?php

session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}




if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM reservation 
                            INNER JOIN client ON client.id_client = reservation.id_client
                            INNER JOIN chambre ON chambre.id_chambre = reservation.id_chambre
                            WHERE reservation.id_reservation=?');
    $stmt->execute([$id]);

    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);


} else {
    header('location:list.php');
    exit;
}

?>
<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">
    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="text-start">
        <a href="list.php" class="btn btn-success my-2">Retour</a>
    </div>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    les infos de la table Reservation_chambre
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="container">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Réservation</th>
                                    <th>Code Réservation</th>
                                    <th>Date & Heure de Réservation</th>
                                    <th>Date d'Arrivée</th>
                                    <th>Date de Départ</th>
                                    <th>Nombre de Jours</th>
                                    <th>Nombre d'Adultes/Enfants</th>
                                    <th>Montant Total</th>
                                    <th>État</th>
                                    <th>ID Client</th>
                                    <th>ID Chambre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les lignes de la table seront insérées ici dynamiquement -->
                                <!-- Exemple statique -->
                                <tr>
                                    <td><?= $reservation['id_reservation'] ?></td>
                                    <td><?= $reservation['code_reservation'] ?></td>
                                    <td><?= $reservation['date_heure_reservation'] ?></td>
                                    <td><?= $reservation['date_arrivee'] ?></td>
                                    <td><?= $reservation['date_depart'] ?></td>
                                    <td><?= $reservation['nbr_jours'] ?></td>
                                    <td><?= $reservation['nbr_adultes_enfants'] ?></td>
                                    <td><?= $reservation['montant_total'] ?></td>
                                    <td><?= $reservation['etat'] ?></td>
                                    <td><?= $reservation['id_client'] ?></td>
                                    <td><?= $reservation['id_chambre'] ?></td>
                                </tr>
                                <!-- Ajoutez plus de lignes ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    les infos de le client
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="container ">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Client</th>
                                    <th>Nom Complet</th>
                                    <th>Sexe</th>
                                    <th>Date de Naissance</th>
                                    <th>Âge</th>
                                    <th>Pays</th>
                                    <th>Ville</th>
                                    <th>Adresse</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Autres Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les lignes de la table seront insérées ici dynamiquement -->
                                <!-- Exemple statique -->
                                <tr>
                                    <td><?= $reservation['id_client'] ?></td>
                                    <td><?= $reservation['nom_complet'] ?></td>
                                    <td><?= $reservation['sexe'] ?></td>
                                    <td><?= $reservation['date_naissance'] ?></td>
                                    <td><?= $reservation['age'] ?></td>
                                    <td><?= $reservation['pays'] ?></td>
                                    <td><?= $reservation['ville'] ?></td>
                                    <td><?= $reservation['adresse'] ?></td>
                                    <td><?= $reservation['telephone'] ?></td>
                                    <td><?= $reservation['email'] ?></td>
                                    <td><?= $reservation['autres_details'] ?></td>
                                </tr>
                                <!-- Ajoutez plus de lignes ici -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    les infos du chambre
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="container">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Chambre</th>
                                    <th>Numéro de Chambre</th>
                                    <th>Nombre d'Adultes/Enfants</th>
                                    <th>Renfort de Chambre</th>
                                    <th>Étage de Chambre</th>
                                    <th>Nombre de Lits</th>
                                    <th>Photo</th>
                                    <th>ID Type Chambre</th>
                                    <th>ID Capacité</th>
                                    <th>ID Tarif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les lignes de la table seront insérées ici dynamiquement -->
                                <!-- Exemple statique -->
                                <tr>
                                    <td><?= $reservation['id_chambre'] ?></td>
                                    <td><?= $reservation['numero_chambre'] ?></td>
                                    <td><?= $reservation['nombre_adultes_enfants_ch'] ?></td>
                                    <td><?= ($reservation['renfort_chambre']==1)?"Oui":"Non" ?></td>
                                    <td><?= $reservation['etage_chambre'] ?></td>
                                    <td><?= $reservation['nbr_lits_chambre'] ?></td>
                                    <td><img src="../../assets/images/chambre/<?= $reservation['photo'] ?>" alt=""></td>
                                    <td><?= $reservation['id_type_ch'] ?></td>
                                    <td><?= $reservation['id_capacite'] ?></td>
                                    <td><?= $reservation['id_tarif'] ?></td>
                                </tr>
                                <!-- Ajoutez plus de lignes ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- /#page-content-wrapper -->
    <?php require_once "../inc/footer.php" ?>