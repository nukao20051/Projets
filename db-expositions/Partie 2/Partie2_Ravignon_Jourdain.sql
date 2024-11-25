--login utilisé : jour0062

--4)

--a)

INSERT INTO Typelieu VALUES (1, 'Musée municipal');
INSERT INTO Typelieu VALUES (2, 'Musée National');
INSERT INTO Typelieu VALUES (3, 'Musée Privé');
INSERT INTO Typelieu VALUES (4, 'Musée Départemental');
INSERT INTO Typelieu VALUES (5, 'Galerie d’art');
INSERT INTO Typelieu VALUES (6, 'Châteaux');
INSERT INTO Typelieu VALUES (7, 'Institutions culturelles');

INSERT INTO Departement VALUES (75, 'FRA', 'Paris');
INSERT INTO Departement VALUES (77, 'FRA', 'Seine-et-Marne');
INSERT INTO Departement VALUES (78, 'FRA', 'Yvelines');
INSERT INTO Departement VALUES (91, 'FRA', 'Essonne');
INSERT INTO Departement VALUES (92, 'FRA', 'Hauts-de-Seine');
INSERT INTO Departement VALUES (93, 'FRA', 'Seine-Saint-Denis');
INSERT INTO Departement VALUES (94, 'FRA', 'Val-de-Marne');
INSERT INTO Departement VALUES (95, 'FRA', 'Val-d’Oise');

INSERT INTO Genre VALUES (1, 'Architecture/Design/Mode');
INSERT INTO Genre VALUES (2, 'Art Contemporain');
INSERT INTO Genre VALUES (3, 'Beaux-Arts');
INSERT INTO Genre VALUES (4, 'Châteaux/Monuments');
INSERT INTO Genre VALUES (5, 'Galeries');
INSERT INTO Genre VALUES (6, 'Histoire/Civilisations');
INSERT INTO Genre VALUES (7, 'Instituts culturels');
INSERT INTO Genre VALUES (8, 'Jeunes Publics');
INSERT INTO Genre VALUES (9, 'Photographie');
INSERT INTO Genre VALUES (10, 'Salons');
INSERT INTO Genre VALUES (11, 'Sciences et Techniques');

--b)

alter table lieu drop constraint SYS_C00364516;


alter table lieu drop column missions;
alter table lieu add missions varchar2(200);


alter table lieu drop column nomlieu;
alter table lieu add nomlieu varchar2(100);

--c)

--modif table expo
alter table expo modify resume varchar2(150);
alter table expo modify titreexpo varchar(150);

--insertions table expo
insert into expo (numexpo, numlieu, numgenre, titreexpo, datedeb, datefin, resume, tarif, tarifr, choix)
select numexpo, numlieu, numgenre, titreexpo, datedeb, datefin, resume, tarif, tarifreduit, choix
from testsaeld.expo_import
where numlieu in (select numlieu
                  from lieu);
       
--insertion table typeoeuvre           
insert into typeoeuvre (numtpevr, libtpevr)
select numtpevr, libtpevr
from testsaeld.typeoeuvre_import;

--insertions table oeuvre
insert into oeuvre (numevr, numart, numtpevr, titre, anneecr) 
select numevr, numart, numtpevr, titre, anneecr
from testsaeld.oeuvre_import
where numtpevr in (select numtpevr
                   from typeoeuvre);

--d)

create table presentation (
    numLieu integer,
    numExpo integer,
    numEvr integer,
    constraint PK_PRESENTATION primary key (numLieu, numExpo, numEvr)
);

alter table presentation add 
    constraint FK_NUMLIEU_NUMEXPO_PRES foreign key (numlieu, numexpo) references Expo (numlieu, numexpo);

alter table presentation add 
    constraint FK_NUMEVR_PRESENTATION foreign key (numEvr) references Oeuvre (numEvr);



--Lors de la partie 1, nous avions oubli� de rajouter le champ modeReglt, nous avons donc du le rajouter ici pour la requ�te 9
-- AJOUT DU CHAMP MODEREGLT DANS LA TABLE ACHAT

--alter table achat add modeReglt varchar2(30);
alter table achat add constraint CK_PAIEMENT check (modereglt in ('CB','ESP','CHQ'));

--e)

--Utilisation d'un générateur de données appelé Mockaroo pour générer les données, nous avons par la suite modifiés certaines données car des numéros de ville n'existait pas par exemple


insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('23/04/2024', 'dd/mm/yyyy'), 40, 5, 235, 547, 346, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('23/02/2024', 'dd/mm/yyyy'), 79, 3, 107, 871, 94, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('01/03/2024', 'dd/mm/yyyy'), 126, 8, 23, 896, 515, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('27/03/2024', 'dd/mm/yyyy'), 73, 3, 235, 277, 508, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('25/04/2024', 'dd/mm/yyyy'), 168, 1, 20, 788, 43, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('03/03/2024', 'dd/mm/yyyy'), 100, 2, 183, 382, 640, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('30/03/2024', 'dd/mm/yyyy'), 48, 2, 46, 824, 625, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('20/05/2024', 'dd/mm/yyyy'), 164, 1, 16, 845, 623, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('19/05/2024', 'dd/mm/yyyy'), 129, 1, 98, 27, 430, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('02/03/2024', 'dd/mm/yyyy'), 198, 2, 82, 834, 602, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('21/03/2024', 'dd/mm/yyyy'), 51, 2, 8, 20, 326, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('30/09/2024', 'dd/mm/yyyy'), 98, 4, 173, 515, 38, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('20/04/2024', 'dd/mm/yyyy'), 164, 2, 97, 5, 682, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('10/03/2024', 'dd/mm/yyyy'), 20, 3, 219, 206, 279, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('18/05/2024', 'dd/mm/yyyy'), 182, 1, 24, 237, 673, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('14/04/2024', 'dd/mm/yyyy'), 28, 5, 235, 845, 820, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('22/04/2024', 'dd/mm/yyyy'), 103, 4, 224, 173, 865, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('16/03/2024', 'dd/mm/yyyy'), 82, 2, 192, 22, 259, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('12/04/2024', 'dd/mm/yyyy'), 136, 6, 23, 508, 626, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('24/04/2024', 'dd/mm/yyyy'), 195, 5, 28, 51, 305, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('24/04/2024', 'dd/mm/yyyy'), 3, 7, 23, 449, 219, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('18/04/2024', 'dd/mm/yyyy'), 40, 4, 173, 776, 727, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('06/05/2024', 'dd/mm/yyyy'), 143, 3, 101, 248, 456, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('28/04/2024', 'dd/mm/yyyy'), 117, 1, 224, 219, 840, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('24/02/2024', 'dd/mm/yyyy'), 91, 2, 51, 842, 732, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('28/04/2024', 'dd/mm/yyyy'), 138, 1, 236, 958, 518, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('05/05/2024', 'dd/mm/yyyy'), 26, 3, 219, 587, 308, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('17/05/2024', 'dd/mm/yyyy'), 30, 2, 104, 69, 544, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('14/03/2024', 'dd/mm/yyyy'), 189, 5, 235, 972, 817, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('23/04/2024', 'dd/mm/yyyy'), 24, 2, 229, 42, 699, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('30/03/2024', 'dd/mm/yyyy'), 48, 1, 23, 450, 600, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('25/02/2024', 'dd/mm/yyyy'), 48, 2, 23, 230, 700, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('20/03/2024', 'dd/mm/yyyy'), 48, 3, 23, 1000, 820, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('15/04/2024', 'dd/mm/yyyy'), 48, 4, 23, 705, 450, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('10/03/2024', 'dd/mm/yyyy'), 48, 5, 23, 650, 720, 'ESP');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('05/04/2024', 'dd/mm/yyyy'), 48, 6, 23, 440, 602, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('30/04/2024', 'dd/mm/yyyy'), 48, 7, 23, 300, 700, 'CB');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('30/03/2024', 'dd/mm/yyyy'), 48, 8, 23, 2000, 803, 'CHQ');
insert into Achat (dateAchat, numPers, numExpo, numLieu, nbBil, nbBilTr, modeReglt) values (to_date('05/05/2024', 'dd/mm/yyyy'), 26, 1, 151, 600, 700, 'CB');


--R1

--Solution 1 : Utilisation de NOT LIKE pour exclure Paris


SELECT DISTINCT p.nomPers, p.pnomPers
FROM PERSONNE p
JOIN ACHAT a ON p.numPers = a.numPers
JOIN LIEU l ON a.numLieu = l.numLieu
WHERE l.villeLieu NOT LIKE 'Paris%' and l.arrondissement is NULL;



--Solution 2 : Utilisation d'une sous-requête pour exclure Paris


SELECT DISTINCT p.nomPers, p.pnomPers
FROM PERSONNE p
WHERE p.numPers IN (
    SELECT a.numPers
    FROM ACHAT a
    JOIN LIEU l ON a.numLieu = l.numLieu
    WHERE l.villeLieu NOT LIKE 'Paris%' and l.arrondissement is NULL
);


--R2

SELECT e.titreExpo, g.libGenre, l.nomLieu, 
       NVL(SUM(a.nbBil), 0) AS nbBil, 
       NVL(SUM(a.nbBilTr), 0) AS nbBilTr, 
       e.tarif, e.tarifR, 
       NVL(SUM(a.nbBil * e.tarif + a.nbBilTr * e.tarifR), 0) AS recette
FROM EXPO e
JOIN GENRE g ON e.numGenre = g.numGenre
JOIN LIEU l ON e.numLieu = l.numLieu
LEFT JOIN ACHAT a ON e.numLieu = a.numLieu AND e.numExpo = a.numExpo
WHERE e.choix = 1 AND (e.numGenre = 1 OR e.numGenre = 2)
GROUP BY e.titreExpo, g.libGenre, l.nomLieu, e.tarif, e.tarifR
ORDER BY g.libGenre;




--R3


SELECT e.titreExpo, l.nomLieu, NVL(o.titre, 'Aucune œuvre enregistrée') AS titre
FROM EXPO e
JOIN LIEU l ON e.numLieu = l.numLieu
JOIN GENRE g ON e.numGenre = g.numGenre
LEFT JOIN OEUVRE o ON e.numExpo = o.numExpo
WHERE upper(l.nomLieu) LIKE upper('Musée%') AND upper(g.libGenre) = upper('Beaux-Arts')
ORDER BY l.nomLieu, e.titreExpo;

select titreexpo, nomlieu
from Expo e
join Lieu l on (l.numlieu = e.numlieu)
join Genre g on (g.numgenre = e.numgenre)
join Presentation p on (p.numlieu = l.numlieu)
left join Oeuvre o on (o.numevr = p.numevr)
where upper(l.nomlieu) like upper('Musée%') and upper(g.libgenre) = upper('Beaux-Arts');

--R4
--Solution 1 : Utilisation de NOT EXISTS

select titre
from Oeuvre o
where not exists (select null
                  from Presentation p
                  where o.numevr = p.numevr);

--Solution 2 : Utilisation de LEFT JOIN et IS NULL

select titre
from Oeuvre o
left join Presentation p on (p.numevr = o.numevr)
where p.numexpo is null;

-- 5. Liste des pays avec le nombre total de personnes
-- utilisant la fonction d'agrégat COUNT et une sous-requête pour obtenir la population totale par pays
SELECT p.nomFr AS Pays, 
       (SELECT COUNT(*) 
        FROM PERSONNE pers 
        WHERE pers.cdPays = p.cdPays) AS Nombre_de_personnes
FROM PAYS p
ORDER BY Nombre_de_personnes DESC;

-- Cette requête liste les pays avec le nombre total de personnes associées à chaque pays.


-- 6. Nombre de billets vendus par lieu pour des dates d'achat entre deux dates spécifiques
-- Utilisation de COUNT, BETWEEN, et une jointure externe LEFT JOIN
SELECT l.nomLieu, 
       COUNT(a.numPers) AS Total_Billets_Vendus
FROM LIEU l
LEFT JOIN ACHAT a ON l.numLieu = a.numLieu
WHERE a.dateAchat BETWEEN TO_DATE('2024-01-01', 'YYYY-MM-DD') AND TO_DATE('2024-12-31', 'YYYY-MM-DD')
GROUP BY l.nomLieu
ORDER BY Total_Billets_Vendus DESC;

-- Cette requête liste le nombre de billets vendus par lieu pour des dates d'achat en 2024, incluant les lieux sans achats.



-- 7. Liste des genres avec le nombre total d'expositions, seulement pour les genres ayant plus de 5 expositions
-- Utilisation de COUNT et HAVING pour filtrer les groupes
SELECT g.libGenre, COUNT(e.numExpo) AS Nombre_Expositions
FROM GENRE g
JOIN EXPO e ON g.numGenre = e.numGenre
GROUP BY g.libGenre
HAVING COUNT(e.numExpo) > 5
ORDER BY Nombre_Expositions DESC;

-- Cette requête liste les genres avec le nombre total d'expositions, mais seulement pour les genres ayant plus de 5 expositions.



-- 8. Moyenne des billets vendus par exposition, en excluant les expositions sans ventes
-- Utilisation de AVG, IS NOT NULL, et une sous-requête
SELECT e.titreExpo, 
       (SELECT AVG(a.nbBil) 
        FROM ACHAT a 
        WHERE a.numExpo = e.numExpo 
        AND a.nbBil IS NOT NULL) AS Moyenne_Billets_Vendus
FROM EXPO e
WHERE e.numExpo IN (SELECT numExpo FROM ACHAT WHERE nbBil IS NOT NULL)
ORDER BY Moyenne_Billets_Vendus DESC;

-- Cette requête liste les expositions avec la moyenne des billets vendus par exposition, en excluant celles sans ventes


-- 9. Liste des personnes ayant acheté des billets mais pas en mode 'Carte de Crédit'
-- Utilisation de l'opérateur MINUS
SELECT DISTINCT p.nomPers, p.pnomPers
FROM PERSONNE p
JOIN ACHAT a ON p.numPers = a.numPers
MINUS
SELECT DISTINCT p.nomPers, p.pnomPers
FROM PERSONNE p
JOIN ACHAT a ON p.numPers = a.numPers
WHERE a.modeReglt <> 'CB';

-- Cette requête liste les personnes ayant acheté des billets mais jamais en mode 'Carte de Crédit'.


-- 10. Liste des personnes ayant acheté des billets pour toutes les expositions dans un lieu spécifique, en l'occurence, le centre Pompidou (de num�ro de lieu 23) qui est mis � l'honneur
-- Utilisation de la division relationnelle
SELECT p.nomPers, p.pnomPers
FROM PERSONNE p
WHERE NOT EXISTS (
    SELECT e.numExpo
    FROM EXPO e
    WHERE e.numLieu = 23
    MINUS
    SELECT a.numExpo
    FROM ACHAT a
    WHERE a.numPers = p.numPers
);

-- Cette requête liste les personnes ayant acheté des billets pour toutes les expositions dans un lieu spécifique (numLieu = 1).

--c)

create sequence numPers_seq 
    start with 201
    increment by 1;
    
select numpers_seq.nextval from dual;
 
    
insert into Personne (numPers, pnomPers, nomPers, dateNais, ville, region, cdPays)
    select numPers_seq.nextval, pnomPers, nomPers, dateNais, ville, region ,cdPays
    from testsaeld.personne_import;

select pnomPers, nomPers, extract(year from datenais) as Annee_Nais
from Personne
where extract(year from dateNais) < 1960 ;

create view Chinois as
    select numPers, pnomPers, nomPers, ville, region, cdPays
    from Personne
    where cdPays = 'CHN';

drop view chinois;

select *
from chinois;

  
insert into Chinois values (205, 'Jacky', 'CHAN', 'Hong Kong', 'Victoria Peak', 'CHN');


update Personne
set datenais = to_date('07/04/1954', 'dd/mm/yyyy')
where nompers = 'CHAN' and pnompers = 'Jacky';

--d)
grant insert, update, delete, select on Lieu to ravi0023;

grant select, delete, update (nbBil, nbBilTR, dateAchat , modeReglt) on Achat to ravi0023;


create view EXPO_PARIS_MARS2024 as
    select numLieu, numExpo, titreExpo, dateDeb
    from Expo
    where to_char(dateDeb, 'mm/yyyy') = '03/2024'
    or to_char(dateDeb, 'mm/yyyy') = '03/2024'
    and numlieu in (select numlieu
                    from Lieu
                    where numDpt in (select numDpt
                                     from Departement
                                     where upper(nomDpt) = 'PARIS'));
                                     
select * from EXPO_PARIS_MARS2024;

grant insert, select, update on EXPO_PARIS_MARS2024 to ravi0023;

grant select, delete on Expo to ravi0023;

grant delete, select on Achat to ravi0023;



--6)

--a)
    
create view VUE_SAE_BDD as 
    select g.numgenre, g.libgenre, d.numdpt, d.nomdpt, l.numlieu, l.arrondissement, l.villelieu, l.nomlieu, e.numexpo, e.datedeb, e.datefin, e.tarif, e.tarifr, e.dureeev, e.titreexpo
    from genre g
    join expo e on (e.numgenre = g.numgenre)
    join lieu l on (l.numlieu = e.numlieu)
    join departement d on (d.numdpt = l.numdpt);
    

