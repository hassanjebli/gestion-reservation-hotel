# Gestion de Réservation des Chambres d'Hôtel

Ce projet est une application web de gestion de réservation des chambres d'hôtel, développée en PHP avec MySQL comme système de gestion de bases de données, utilisant l'API PDO.


## Introduction

L'objectif de ce projet est de créer un système d'information pour les hôtels afin d'informatiser le service de réservation de leurs chambres. Cette application permet une gestion efficace des réservations, des clients, des chambres, et bien plus encore.

## Fonctionnalités

### Gestion des Clients
- Afficher la liste des clients
- Ajouter un nouveau client
- Modifier les informations du client
- Supprimer un client
- Consulter toutes les informations du client
- Rechercher un client

### Gestion des Chambres
- Afficher la liste des chambres
- Ajouter une nouvelle chambre
- Modifier les informations de la chambre
- Supprimer une chambre
- Consulter toutes les informations de la chambre
- Consulter l’historique de la chambre
- Rechercher une chambre

### Gestion de Configuration
- Gestion des types des chambres
- Gestion des capacités des chambres
- Gestion des tarifs des chambres
- Gestion des comptes utilisateurs d’application

### Gestion des Réservations
- Afficher la liste des réservations
- Ajouter une nouvelle réservation de chambre
- Modifier les informations de la réservation
- Supprimer une réservation
- Consulter toutes les informations de la réservation de chambre
- Rechercher une réservation

## Technologies Utilisées
- PHP
- MySQL
- HTML
- CSS
- JavaScript


### Prérequis
- Serveur web (ex: Apache)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

## Tableaux de Bord
L'application propose trois tableaux de bord différents selon le rôle de l'utilisateur :

### Admin
- Gestion complète des clients, chambres, réservations, et configuration.
- Accès à toutes les fonctionnalités de l'application.

### Réceptionniste
- Gestion des clients et des réservations.
- Accès limité à la gestion des chambres et des configurations.

### Caissier
- Gestion des paiements et des factures.
- Consultation des réservations pour traitement des paiements.

### Étapes
1. Clonez le dépôt sur votre machine locale :
    ```bash
    git clone https://github.com/hassanjebli/gestion-reservation-hotel.git
    ```
2. Configurez votre serveur web pour pointer vers le répertoire du projet.
3. Importez le fichier `database.sql` dans votre base de données MySQL.
4. Configurez les paramètres de connexion à la base de données dans le fichier `config.php`.

## Utilisation
1. Accédez à l'application via votre navigateur web.
2. Utilisez le menu de navigation pour accéder aux différentes sections de l'application (clients, chambres, réservations, etc.).
3. Suivez les instructions à l'écran pour gérer les réservations de chambres.

## Contributeurs
- [Hassan AIT-JABLI](https://github.com/hassanjebli)


