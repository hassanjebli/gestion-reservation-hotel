<?php
session_start();
require_once "../../config/database.php";

$date_arrivee = $_GET['date_arrivee'] ?? '';
$date_depart = $_GET['date_depart'] ?? '';
$nbr_personnes = $_GET['nbr_personnes'] ?? '';
$user = $_GET['client'] ?? '';
$type_chambre = $_GET['type_chambre'] ?? '';

if (isset($_GET['recherche'])) {
    if (!empty($date_arrivee) && !empty($date_depart) && !empty($nbr_personnes) && !empty($user) && !empty($type_chambre)) {
        $sql = "SELECT * FROM chambre 
                INNER JOIN type_chambre ON type_chambre.id_type_ch = chambre.id_type_ch 
                WHERE chambre.id_type_ch = ? 
                AND chambre.id_chambre NOT IN ( SELECT id_chambre FROM reservation WHERE (date_arrivee <= ? AND date_depart >= ?) ) 
                AND chambre.nombre_adultes_enfants_ch >= ?";
        $params = [$type_chambre, $date_depart, $date_arrivee, $nbr_personnes];

        $statement = $conn->prepare($sql);
        $statement->execute($params);
        $chambres = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($statement->rowCount() == 0) {
            $_SESSION["message"] = "Cette Chambre n'est pas disponible maintenant.";
        }
    } else {
        $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Tous les champs sont obligatoires
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

if (isset($_POST['reserver'])) {
    $cli = $_POST['client1'];
    $cham = $_POST['chambre'];
    $date_arri = $_POST['date_arrivee'];
    $date_dep = $_POST['date_depart'];
    $nbr_per = $_POST['nbr_personnes'];

    $stmtCode = $conn->prepare("SELECT MAX(id_reservation) AS max_id FROM reservation");
    $stmtCode->execute();
    $result = $stmtCode->fetch(PDO::FETCH_OBJ);
    $next_id = $result->max_id + 1;
    $code_reservation = "RES" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

    $date_heure_reservation = date("Y-m-d H:i:s");

    $timestamp_arrivee = strtotime($date_arrivee);
    $timestamp_depart = strtotime($date_depart);
    $timestamp_now = time();

    $nbr_jours = floor(($timestamp_depart - $timestamp_arrivee) / (60 * 60 * 24));

    $s = $conn->prepare('SELECT * FROM chambre INNER JOIN tarif_chambre ON tarif_chambre.id_tarif = chambre.id_tarif WHERE id_chambre=?');
    $s->execute([$cham]);
    $tarif = $s->fetch(PDO::FETCH_OBJ);

    $prix_nuit = $tarif->prix_base_nuit;

    $montant_total = $prix_nuit * $nbr_jours;

    // if ($timestamp_arrivee > $timestamp_now && $timestamp_depart > $timestamp_now) {
    //     $etat = "Planifiée";
    // } elseif ($timestamp_arrivee <= $timestamp_now && $timestamp_depart >= $timestamp_now) {
    //     $etat = "En cours";
    // } elseif ($timestamp_arrivee < $timestamp_now && $timestamp_depart < $timestamp_now) {
    //     $etat = "Terminée";
    // }

    $statement = $conn->prepare("INSERT INTO reservation VALUES(NULL,?,?,?,?,?,?,?,NULL,?,?)");
    $statement->execute([
        $code_reservation,
        $date_heure_reservation,
        $date_arri,
        $date_dep,
        $nbr_jours,
        $nbr_per,
        $montant_total,
        $cli,
        $cham
    ]);

    $_SESSION['message'] = "L'insertion a été effectuée avec succès.";
    header("Location:list.php");
    exit;
}

// Session message handling
$showModal = false;
if (isset($_SESSION['message'])) {
    if ($_SESSION['message'] == "Cette Chambre n'est pas disponible maintenant.") {
        $showModal = true;
    }
}
unset($_SESSION['message']);
?>

<?php require_once "../inc/header.php" ?>
<!-- Sidebar -->
<?php require_once "../inc/sidebar.php" ?>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper" class="main-content w-100">

    <button class="btn btn-primary mb-4" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <h5 class="text-center">AJOUTER RESERVATION :</h5>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['alert'])): ?>
        <?= $_SESSION['alert'] ?>

    <?php endif; ?>
    <?php unset($_SESSION['alert']) ?>
    <div class="text-start">
        <a href="list.php" class="btn btn-success">Retour</a>
    </div>

    <form action="#zrour" method="get">

        <h6 class="form-label mt-3">Client:</h6>
        <select name="client" class="form-control mt-2">
            <option value="">Choisir un client...</option>
            <?php
            $stmtClients = $conn->prepare("SELECT * FROM client");
            $stmtClients->execute();
            $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id_client'] ?>" <?php echo ($user == $client['id_client']) ? "selected" : "" ?>>
                    <?= $client['nom_complet'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <h6 class="form-label mt-3">Date d’arrivée:</h6>
        <input type="date" class="form-control mt-2" name="date_arrivee" value="<?= $date_arrivee ?>">

        <h6 class="form-label mt-3">Date de départ:</h6>
        <input type="date" class="form-control mt-2" name="date_depart" value="<?= $date_depart ?>">

        <h6 for="" class="form-label mt-3">Type Chambre :</h6>
        <select name="type_chambre" class="form-control mt-2">
            <option value="">Choisir...</option>
            <?php
            // Fetch types of rooms from the database
            $stmtTypes = $conn->prepare("SELECT * FROM type_chambre");
            $stmtTypes->execute();
            $types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($types as $type): ?>
                <option value="<?= $type['id_type_ch'] ?>" <?php echo ($type_chambre == $type['id_type_ch']) ? "selected" : "" ?>>
                    <?= $type['type_chambre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <h6 for="" class="form-label mt-3">Nombre de personnes : </h6>
        <input type="number" class="form-control my-2 mx-1" id="roomQuantity" placeholder="Nombre des personnes"
            name="nbr_personnes" value="<?= $nbr_personnes ?>" min="1">

        <button class="btn btn-success w-100 mt-2" name="recherche" type="submit">Rechercher</button>
    </form>

    <?php if (isset($chambres) && !empty($chambres)): ?>
        <div class="row mt-5 " id="zrour">
            <?php foreach ($chambres as $chambre): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="../../assets/images/chambre/<?= $chambre->photo ?>" alt="chambre photo"
                            class="card-img img-fluid">
                        <div class="card-body">
                            <h5 class="card-title">Chambre N°<?= $chambre->numero_chambre ?></h5>
                            <p class="card-text"><strong>Type:</strong> <?= $chambre->type_chambre ?></p>
                            <p class="card-text"><strong>Capacité:</strong> <?= $chambre->nombre_adultes_enfants_ch ?> personnes
                            </p>
                            <p class="card-text"><strong>Etage :</strong> <?= $chambre->etage_chambre ?></p>
                            <form action="" method="post" style="border:none">
                                <button class="btn btn-primary w-100" type="submit" name="reserver">Réserver</button>
                                <input type="hidden" name="chambre" value="<?= $chambre->id_chambre ?>">
                                <input type="hidden" name="date_arrivee" value="<?= $date_arrivee ?>">
                                <input type="hidden" name="date_depart" value="<?= $date_depart ?>">
                                <input type="hidden" name="nbr_personnes" value="<?= $nbr_personnes ?>">
                                <input type="hidden" name="client1" value="<?= $user ?>">
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- /#page-content-wrapper -->

<!-- Modal HTML -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Chambre Non Disponible</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Cette Chambre n'est pas disponible maintenant.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php require_once "../inc/footer.php" ?>

<script>
    $(document).ready(function () {
        <?php if ($showModal): ?>
            $('#availabilityModal').modal('show');
        <?php endif; ?>
    });
</script>