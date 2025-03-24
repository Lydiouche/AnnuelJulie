Create Table Client (
    Id_Client INT AUTO_INCREMENT PRIMARY KEY,
    NomC VARCHAR(50) NULL, 
    PrenomC VARCHAR(50) NULL,
    AgeC INT null, 
    Telephone INT null, 
    Mail VARCHAR(100) NULL, 
    Adresse varchar(150) null,
    Pass VARCHAR(100) NULL, 
    Mode_Payement VARCHAR(100) NULL, 
    Handicap boolean null, 
    Accompagnateur VARCHAR(50) NULL references Client (Id_Client)
);
Create Table Stock (
    Num_ligne INT AUTO_INCREMENT PRIMARY KEY,
    Quantite int,
    Id_Produit INT REFERENCES Produit(Id_Produit)
);
Create Table Objet_Perdu (
    Id_Objet_Perdu int AUTO_INCREMENT PRIMARY KEY,
    Description text null,
    Type_Objet varchar(50) null,
    Couleur varchar(50) null,
    Satut varchar(50) null,
    Id_Client int references Client (Id_Client)
);
Create Table Produit (
    Id_Produit int AUTO_INCREMENT PRIMARY KEY,
    NomP varchar(50) null,
    Categorie varchar(50) null,
    Quantite_Stock int null,
    Seuil_Alerte int null,
    Prix_Unitaire int null
);
Create Table Emplacement_Camping (
    Id_Place_Camping int AUTO_INCREMENT primary KEY,
    Tente boolean null,
    Duvet boolean null,
    Matelas boolean null,
    Electricite boolean null,
    Num_Emplacement int references Reservation (Num_Emplacement)
);
Create table Reservation (
    Id_Reservation int AUTO_INCREMENT primary KEY,
    Date_Resa date null,
    Nb_Place int null,
    Statut_Payement boolean null,
    Num_Emplacement int null,
    Num_LigneS int references Stock(Num_LigneS),
    Id_Client int references Client (Id_Client)
);
Create Table Acces (
    Id_Acces int AUTO_INCREMENT primary key,
    Zone varchar(50) null,
    Date_Entree date null,
    Date_Sortie date null
);
Create Table Employe (
    Id_Employe int AUTO_INCREMENT primary key,
    NomE varchar(50) null,
    PrenomE varchar(50) null,
    Role varchar(50) null,
    Acces varchar(50) null,
    Code_Barre int null
);
Create Table Planning (
    Id_Planning int AUTO_INCREMENT primary key,
    Debut_Jour date null,
    Debut_Heure int null,
    Fin_Jour date null,
    Fin_Heure int null
);
Create Table Ligne_Employe (
    Id_Ligne_Employe int AUTO_INCREMENT primary key,
    Id_Employe int references Employe (Id_Employe),
    Id_Planning int references Planning (Id_Planning)
);
Create Table Ligne_Groupe (
    Num_Groupe int AUTO_INCREMENT primary key,
    NomG varchar(50) null
);
Create Table Musicien (
    Id_Musicien int AUTO_INCREMENT primary key,
    NomM varchar(50) null,
    PrenomM varchar(50) null,
    Groupe varchar(50) null,
    Type_Instru varchar(50) null,
    Num_Scene int null,
    Horaire_Passage date null,
    Jour_Passage Date null,
    Budget int null
);
Create Table Ligne_Musicien (
    Id_Ligne_Musicien int AUTO_INCREMENT primary key,
    Id_Planning int references Planning (Id_Planning),
    Id_Musicien int references Musicien (Id_Musicien),
    Num_Groupe int references Ligne_Groupe (Num_Groupe)
);