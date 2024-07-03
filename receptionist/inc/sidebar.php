<!-- Sidebar -->
<div class="bg-dark border-right sidebar" id="sidebar-wrapper">
    <div class="sidebar-heading">
        <a href="../index.php" style="color:white;"><?= $reception['nom'] ?> <?= $reception['prenom'] ?><br>
            <span style="color: tomato; font-size:18px;"><i class="fas fa-concierge-bell"></i> Reception</span></a>
        <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
    </div>
    <div class="list-group list-group-flush my-3">
        <a href="../clients/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'clients') == true) ? "active" : "" ?>">
            <i class="fas fa-user-friends"></i> Clients
        </a>
        <a href="../rooms/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'rooms') == true) ? "active" : "" ?>">
            <i class="fas fa-bed"></i> Chambres
        </a>
        <a href="../tariffs/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'tariffs') == true) ? "active" : "" ?>">
            <i class="fas fa-dollar-sign"></i> Les Tariifs
        </a>
        <a href="../capacities/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'capacities') == true) ? "active" : "" ?>">
            <i class="fas fa-users"></i> Capacitie chambre
        </a>
        <a href="../types/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'types') == true) ? "active" : "" ?>">
            <i class="fas fa-th-list"></i> Type chambre
        </a>
        <a href="../reservations/list.php"
            class="list-group-item text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'reservations') == true) ? "active" : "" ?>">
            <i class="fas fa-calendar-alt"></i> Réservations
        </a>
        <a href="../../auth/logout.php" class="list-group-item logout">
            <i class="fas fa-power-off me-2"></i> Deconnexion
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->