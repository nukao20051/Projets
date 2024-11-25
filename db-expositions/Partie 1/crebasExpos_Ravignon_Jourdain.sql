--Login utilisé : jour0062

/*==============================================================*/
/* Nom de SGBD :  ORACLE Version 11g                            */
/* Date de création :  13/05/2024 15:49:07                      */
/*==============================================================*/


drop table ACHAT cascade constraints;

drop table DEPARTEMENT cascade constraints;

drop table EXPO cascade constraints;

drop table GENRE cascade constraints;

drop table LIEU cascade constraints;

drop table TYPELIEU cascade constraints;

/*==============================================================*/
/* Table : ACHAT                                                */
/*==============================================================*/
create table ACHAT
(
   dateAchat            DATE                 not null,
   numPers              INTEGER              not null,
   numExpo              INTEGER              not null,
   EXP_numLieu          INTEGER              not null,
   numLieu              INTEGER              not null,
   nbBil                INTEGER              not null,
   nbilTR               INTEGER              not null,
   constraint PK_ACHAT primary key (dateAchat, numPers, numExpo, EXP_numLieu, numLieu)
);

/*==============================================================*/
/* Table : DEPARTEMENT                                          */
/*==============================================================*/
create table DEPARTEMENT
(
   numDpt               INTEGER              not null,
   cdPays               CHAR(3)          not null,
   nomDpt               VARCHAR2(50)         not null,
   constraint PK_DEPARTEMENT primary key (numDpt)
);

/*==============================================================*/
/* Table : EXPO                                                 */
/*==============================================================*/
create table EXPO
(
   numExpo              INTEGER              not null,
   numLieu              INTEGER              not null,
   numGenre             INTEGER              not null,
   titreExpo            VARCHAR2(30)         not null,
   dateDeb              DATE                 not null,
   dateFin              DATE                 not null,
   resume               VARCHAR2(100),
   tarif                NUMBER(4,2)          default 0,
   tarifR               NUMBER(4,2)          default 0,
   choix                INTEGER,
   dureeEv              GENERATED ALWAYS AS (dateFin-dateDeb) VIRTUAL,
      constraint CKC_CHOIX_EXPO check (choix is null or (choix in (NULL,1))),
   constraint PK_EXPO primary key (numExpo, numLieu),
   constraint CKC_TARIF_EXPO CHECK(tarif > tarifR)
);

/*==============================================================*/
/* Table : GENRE                                                */
/*==============================================================*/
create table GENRE
(
   numGenre             INTEGER              not null,
   libGenre             VARCHAR2(50)         not null,
   constraint PK_GENRE primary key (numGenre)
);

/*==============================================================*/
/* Table : LIEU                                                 */
/*==============================================================*/
create table LIEU
(
   numLieu              INTEGER              not null,
   numtpLieu            INTEGER              not null,
   numDpt               INTEGER              not null,
   nomLieu              VARCHAR2(50)         not null,
   missions             VARCHAR2(30),
   arrondissement       INTEGER              not null
      constraint CKC_ARRONDISSEMENT_LIEU check (arrondissement between 1 and 20),
   villeLieu            VARCHAR2(50)         not null,
   constraint PK_LIEU primary key (numLieu)
);

/*==============================================================*/
/* Table : TYPELIEU                                             */
/*==============================================================*/
create table TYPELIEU
(
   numtpLieu            INTEGER              not null,
   libTpLieu            VARCHAR2(50)         not null,
   constraint PK_TYPELIEU primary key (numtpLieu)
);

alter table ACHAT
   add constraint FK_ACHAT_ASSOCIATI_EXPO foreign key (numExpo, EXP_numLieu)
      references EXPO (numExpo, numLieu);

alter table ACHAT
   add constraint FK_ACHAT_ASSOCIATI_PERSONNE foreign key (numPers)
      references PERSONNE (numPers);

alter table ACHAT
   add constraint FK_ACHAT_ASSOCIATI_LIEU foreign key (numLieu)
      references LIEU (numLieu);

alter table DEPARTEMENT
   add constraint FK_DEPARTEM_POSSEDER_PAYS foreign key (cdPays)
      references PAYS (cdPays);

alter table EXPO
   add constraint FK_EXPO_ASSOCIATI_LIEU foreign key (numLieu)
      references LIEU (numLieu)
	ON DELETE CASCADE;

alter table EXPO
   add constraint FK_EXPO_ASSOCIATI_GENRE foreign key (numGenre)
      references GENRE (numGenre)
	ON DELETE SET NULL;

alter table EXPO
	add constraint CKC_DATE_EXPO check (datefin >= datedeb);

alter table LIEU
   add constraint FK_LIEU_ASSOCIATI_DEPARTEM foreign key (numDpt)
      references DEPARTEMENT (numDpt)
	ON DELETE SET NULL;

alter table LIEU
   add constraint FK_LIEU_ASSOCIATI_TYPELIEU foreign key (numtpLieu)
      references TYPELIEU (numtpLieu)
	ON DELETE SET NULL;


/*==============================================================*/
/* Table : OEUVRE                                                 */
/*==============================================================*/
create table OEUVRE
(
   numEvr              	INTEGER              not null,
   numArt               INTEGER              not null,
   numTpEvr             INTEGER              not null,
   titre                VARCHAR2(50)         not null,
   anneeCr            	INTEGER              null,
   constraint PK_OEUVRE primary key (numEvr)
);


/*==============================================================*/
/* Table : TYPEOEUVRE                                                 */
/*==============================================================*/
create table TYPEOEUVRE
(
   numTpEvr             INTEGER              not null,
   libTpEvr             VARCHAR2(30)         not null,
   constraint PK_TYPEOEUVRE primary key (numTpEvr)
);

/*==============================================================*/
/* Table : ARTISTE                                                */
/*==============================================================*/

CREATE TABLE ARTISTE
(
    numArt          INTEGER       NOT NULL,
    cdPays          CHAR(3)  	  ,
    nomArt	    VARCHAR2(30)      NOT NULL,
    pnomArt         VARCHAR2(30)  ,
    constraint PK_ARTISTE primary key (numArt)
);


drop table artiste cascade constraints;

INSERT INTO ARTISTE (numArt, nomArt, pnomArt)
SELECT cdArt, nom, prnm
FROM TESTSAELD.ARTISTE_IMPORT;



-- Tentative pour insérer les données de clés étrangères (cdPays) dans la table Artiste

ALTER TABLE ARTISTE ADD
pays varchar2(50);

--La Russie ne comporte pas les mêmes nom dans les tables Artiste et Pays et le pays Baviere n'est pas référencé dans la table Pays (Les artistes en Bavière n'auront donc pas de clés étrangère)
--Modification des noms de la Russie dans Artiste

update Artiste
set pays = (select upper(nomfr)
            from pays
            where cdpays = 'RUS')
where pays = 'RUSSIE';

--Reprise d'insertion des clés étrangères

UPDATE ARTISTE
SET pays = (SELECT pays
            FROM TESTSAELD.ARTISTE_IMPORT
            WHERE TESTSAELD.ARTISTE_IMPORT.cdart = artiste.numart);

UPDATE ARTISTE
SET ARTISTE.cdpays = (select Pays.cdpays
                      from Pays
                      where upper(Artiste.pays) = upper(Pays.nomfr));

--Suppression de la colonne pays dans Artiste

alter table artiste drop column pays;


--Import contraintes de clés étrangères

alter table OEUVRE
   add constraint FK_LIEU_ASSOCIATI_TYPEEVR foreign key (numTpEvr)
      references TYPEOEUVRE (numTpEvr);

alter table OEUVRE
   add constraint FK_LIEU_ASSOCIATI_NUMART foreign key (numArt)
      references ARTISTE (numArt);

--Création indexs

CREATE INDEX FK_lieu_numDpt ON LIEU(numDpt);

CREATE INDEX FK_lieu_numTpLieu ON LIEU(numTpLieu);



CREATE INDEX FK_personne_numPays ON PERSONNE(cdPays);

CREATE INDEX FK_expo_numLieu ON EXPO(numLieu);

CREATE INDEX FK_expo_numGenre ON EXPO(numGenre);

CREATE INDEX FK_personne_Pays ON Personne(cdPays);

CREATE INDEX FK_achat_numLieuA ON ACHAT(numLieu);

CREATE INDEX FK_achat_numExpo ON ACHAT(numExpo);

CREATE INDEX FK_achat_numPers ON ACHAT(numPers);




CREATE INDEX IDX_lieu_nomLieu ON LIEU(nomLieu);

CREATE INDEX IDX_personnes_nom_prenom on PERSONNE(nomPers, pnomPers);



CREATE INDEX IDX_oeuvre_titre ON OEUVRE(titre);


-- constraint CKC_TARIF_EXPO CHECK(tarif > tarifR) --> contrainte ajoutée de notre choix à la table EXPO
