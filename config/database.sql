CREATE DATABASE php_project;
USE php_project;

CREATE TABLE client (
    id_client INT PRIMARY KEY,
    nom_complet VARCHAR(255),
    sexe VARCHAR(10),
    date_naissance DATE,
    age INT,
    pays VARCHAR(100),
    ville VARCHAR(100),
    adresse VARCHAR(255),
    telephone VARCHAR(20),
    email VARCHAR(100),
    autres_details TEXT
);

CREATE TABLE type_chambre (
    id_type_ch INT PRIMARY KEY,
    type_chambre VARCHAR(50),
    description_type TEXT,
    photo VARCHAR(250)
);

CREATE TABLE capacite_chambre (
    id_capacite INT PRIMARY KEY,
    titre_capacite VARCHAR(50),
    numero_capacite INT
);

CREATE TABLE tarif_chambre (
    id_tarif INT PRIMARY KEY,
    prix_base_nuit DECIMAL(10, 2),
    prix_base_passage DECIMAL(10, 2),
    n_prix_nuit DECIMAL(10, 2),
    n_prix_passage DECIMAL(10, 2)
);

CREATE TABLE users_app (
    id_user INT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    username VARCHAR(50),
    password VARCHAR(50),
    type VARCHAR(20),
    etat VARCHAR(20)
);

CREATE TABLE chambre (
    id_chambre INT PRIMARY KEY,
    numero_chambre VARCHAR(10),
    nombre_adultes_enfants_ch INT,
    renfort_chambre BOOLEAN,
    etage_chambre INT,
    nbr_lits_chambre INT,
    photo VARCHAR(250),
    id_type_ch INT,
    FOREIGN KEY (id_type_ch) REFERENCES type_chambre(id_type_ch),
    id_capacite INT,
    FOREIGN KEY (id_capacite) REFERENCES capacite_chambre(id_capacite),
    id_tarif INT,
    FOREIGN KEY (id_tarif) REFERENCES tarif_chambre(id_tarif)
);

CREATE TABLE reservation (
    id_reservation INT PRIMARY KEY,
    code_reservation VARCHAR(20),
    date_heure_reservation DATETIME,
    date_arrivee DATE,
    date_depart DATE,
    nbr_jours INT,
    nbr_adultes_enfants INT,
    montant_total DECIMAL(10, 2),
    etat VARCHAR(20),
    id_client INT,
    id_chambre INT,
    FOREIGN KEY (id_client) REFERENCES client(id_client),
    FOREIGN KEY (id_chambre) REFERENCES chambre(id_chambre)
);
