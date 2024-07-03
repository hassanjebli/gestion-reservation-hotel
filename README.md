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

![login](https://github.com/hassanjebli/gestion-reservation-hotel/assets/151209380/a320ed96-59b5-4425-9dc7-1b3c256ebffa)


### manager
- Suivi des réservations
- Consultation de planning des réservations
- Gestion des comptes utilisateurs d’application :
  - Création/modification/suppression et affichage des comptes.
  - Activation et blocage des comptes des utilisateurs.

- ![manager](https://github.com/hassanjebli/gestion-reservation-hotel/assets/151209380/6b4078ae-a9ef-4b2b-9d5e-16a716560e4b)


### Réceptionniste
- Gestion des clients.
- Gestion des chambres
- Gestion de configuration :
  - Gestion des tarifs des chambres
  - Gestion des types des chambres.
  - Gestion des capacités des chambres.
- Gestion des réservations des chambres. 

- ![receptionist](https://github.com/hassanjebli/gestion-reservation-hotel/assets/151209380/1710f4ad-de40-4dd0-92cf-aba54d8ab23f)


### Caissier
- Gestion de paiement des réservations.

- ![caissier](https://github.com/hassanjebli/gestion-reservation-hotel/assets/151209380/416eb30d-6783-4e37-bf1f-5e622a47efb8)


### Étapes
1. Clonez le dépôt sur votre machine locale :
    ```bash
    git clone https://github.com/hassanjebli/gestion-reservation-hotel.git
    ```
2. Configurez votre serveur web pour pointer vers le répertoire du projet.
3. Importez le fichier `database.sql` dans votre base de données MySQL.
4. Configurez les paramètres de connexion à la base de données dans le fichier `config.php`.


## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.



## Contact

HASSAN AIT-JABLI - [hassanjebli2002@gmail.com](mailto:hassanjebli2002@gmail.com)

Project Link: [https://github.com/hassankebli/todo-list-app](https://github.com/hassankebli/todo-list-app)


