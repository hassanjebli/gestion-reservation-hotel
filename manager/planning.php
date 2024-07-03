<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user"])) {
    header("location:../index.php");
}

$stmt = $conn->prepare("SELECT * FROM users_app WHERE id_user=?");
$stmt->execute([$_SESSION['user']['id']]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);

if ($manager['type'] != 'manager') {
    header('location:../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.11.0/locales/fr.js'></script>
    <style>
        #calendar {
            max-width: 700px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            font-size: 0.7em;
            background-color: #f8f9fa;
            /* Light gray background */
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .fc-toolbar-title {
            font-size: 1.2em !important;
            font-weight: bold;
            color: #333;
        }

        .fc-button-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }

        .fc-button-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .fc-daygrid-day-number {
            font-size: 1em;
            font-weight: bold;
            color: #333;
        }

        .fc-event {
            border: none;
            border-radius: 2px;
            font-size: 0.8em;
        }

        .fc-event-time {
            font-weight: bold;
        }

        .fc-day {
            background-color: #f0f0f0;
            /* White background for day cells */
        }

        .fc-day-today {
            background-color: #e6f2ff !important;
            /* Light blue background for today */
        }

        .fc-col-header-cell {
            background-color: #e9ecef;
            /* Light gray background for header cells */
        }

        @media (max-width: 768px) {
            #calendar {
                max-width: 100%;
                font-size: 0.8em;
            }

            .fc-toolbar-title {
                font-size: 1.2em !important;
            }

            .fc-button {
                padding: 0.2em 0.5em !important;
            }
        }
    </style>
</head>

<body>
    <!-- Barre latérale (unchanged) -->
    <div class="bg-dark border-right sidebar" id="sidebar-wrapper">
        <div class="sidebar-heading">
            <a href="index.php" style="color:white;"><?= $manager['nom'] ?> <?= $manager['prenom'] ?><br><span
                    style="color: tomato; font-size:18px;"><i class="fas fa-briefcase"></i> Manager</span></a>
            <span class="close-sidebar-btn" id="close-sidebar">&times;</span>
        </div>
        <div class="list-group list-group-flush my-3 ">
            <a href="reservations.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'reservations') == true) ? "active" : "" ?>">
                <i class="fas fa-calendar-alt"></i> Réservations
            </a>
            <a href="users/list.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'list') == true) ? "active" : "" ?>">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="planning.php"
                class="list-group-item  text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'planning') == true) ? "active" : "" ?>">
                <i class="fas fa-calendar-check"></i> Consultation de planning
            </a>

            <a href="../auth/logout.php" class="list-group-item  logout" style="color:red !important;">
                <i class="fas fa-power-off me-2"></i> Deconnexion
            </a>
        </div>
    </div>
    <!-- Contenu de la page -->
    <div id="page-content-wrapper" class="main-content">
        <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>
        <h3 class="text-center mb-4">Planning des Réservations</h3>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Bootstrap -->
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                height: 'auto',
                contentHeight: 500,
                aspectRatio: 1.5,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: 'get_events.php',
                eventClick: function (info) {
                    var eventObj = info.event;
                    document.getElementById('modalTitle').textContent = 'Réservation: ' + eventObj.title;
                    document.getElementById('modalBody').innerHTML = `
                        <p><strong>Date d'arrivée:</strong> ${new Date(eventObj.start).toLocaleString('fr-FR')}</p>
                        <p><strong>Date de départ:</strong> ${new Date(eventObj.end).toLocaleString('fr-FR')}</p>
                        <h6><strong style='color:#007bff;'>Détails:</strong></h6>
                        <ul class='list-group'>
                            <li class='list-group-item'><strong>Code :</strong> ${eventObj.extendedProps.code_reservation}</li>
                            <li class='list-group-item'><strong>Jour :</strong> ${eventObj.extendedProps.date_heure_reservation}</li>
                            <li class='list-group-item'><strong>Nombre de jours: </strong>${eventObj.extendedProps.nbr_jours}</li>
                            <li class='list-group-item'><strong>Nombre de personnes: </strong> ${eventObj.extendedProps.nbr_adultes_enfants}</li>
                            <li class='list-group-item'><strong>Montant Total: </strong> ${eventObj.extendedProps.montant_total}</li>
                            <li class='list-group-item'><strong>État: </strong> ${eventObj.extendedProps.etat}</li>
                            <li class='list-group-item'><strong>ID Client:</strong> ${eventObj.extendedProps.id_client}</li>
                            <li class='list-group-item'><strong>ID Chambre:</strong> ${eventObj.extendedProps.id_chambre}</li>
                        </ul>
                    `;
                    var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    myModal.show();
                },
                dayMaxEvents: true,
                eventColor: '#007bff',
                eventTextColor: '#fff',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>