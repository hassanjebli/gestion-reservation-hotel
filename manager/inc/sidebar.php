<!-- Sidebar -->
<div class="bg-dark border-right sidebar" id="sidebar-wrapper">
    <div class="sidebar-heading">

        <a href="../index.php" style="color:white;"><?= $manager['nom'] ?> <?= $manager['prenom'] ?><br><span
                style="color: tomato; font-size:18px;"><i class="fas fa-briefcase"></i> Manager</span></a>
        <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
    </div>
    <div class="list-group list-group-flush my-3 ">
        <a href="../reservations.php"
            class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'reservations') == true) ? "active" : "" ?>">
            <i class="fas fa-calendar-alt"></i> Réservations
        </a>
        <a href="../users/list.php"
            class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'users') == true) ? "active" : "" ?>">
            <i class="fas fa-users"></i> Utilisateurs
        </a>
        <a href="../planning.php"
            class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'planning') == true) ? "active" : "" ?>">
            <i class="fas fa-calendar-check"></i> Consultation de planning
        </a>

        <a href="../../auth/logout.php" class="list-group-item  logout ">
            <i class="fas fa-power-off me-2"></i> Deconnexion
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->