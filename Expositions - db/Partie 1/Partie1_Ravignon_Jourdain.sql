--login utilisé : jour0062

--b)

alter table PERSONNE add (
constraint PK_NUMPERS primary key (numpers)
);

--c)

alter table PAYS add (
constraint PK_CDPAYS primary key (cdpays)
);

--d)

select distinct PAYS
from PERSONNE
where PAYS not in (select nomAng
               from PAYS);
               
--pour enlever les espaces inutiles à la fin des nom francais et anglais
-->

update pays
set nomang = substr(nomang, 1, length(nomang)-1)
where substr(nomang,length(nomang), 1) = ' ';

update pays
set nomfr = substr(nomfr, 1, length(nomfr)-1)
where substr(nomfr,length(nomfr), 1) = ' ';
               
--update pays
--set nomAng = substr(nomAng, 1, length(nomAng)-1)
--where substr(nomAng, length(nomAng)-1, 1) = ' ';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'USA')
where pays = 'United States';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'KOR')
where pays = 'South Korea';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'NLD')
where pays = 'Netherlands';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'RUS')
where pays = 'Russian Federation';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'VNM')
where pays = 'Vietnam';


update personne
set pays = (select nomAng
            from pays
            where cdPays = 'PHL')
where pays = 'Philippines';

update personne
set pays = (select nomAng
            from pays
            where cdPays = 'GBR')
where pays = 'United Kingdom';

alter table personne add (
cdPays char(3),
constraint FK_CDPAYS foreign key (cdPays) references Pays(cdPays)
);

update Personne
set cdpays = (select cdpays
              from Pays
              where pays = nomAng);
              
alter table personne drop column pays;

delete from pays 
where nomFr = 'France ';

--La suppression de l'enregistrement contenant les informations sur la France dans la table Pays 
--est impossible car il existe un enregistrement de la table Personne qui utilise le code de la 
--France en clé étrangère.

--e)

--R1

SELECT COUNT(DISTINCT cdPays) AS Nombre_de_pays_différents
FROM PERSONNE;

--R2


SELECT PAYS.nomFr AS Pays, PERSONNE.region AS Région, COUNT(*) AS "Nombre de personnes"
FROM PERSONNE
JOIN PAYS ON PERSONNE.cdPays = PAYS.cdPays
WHERE PAYS.nomFr IN ('France', 'Belgique')
GROUP BY PAYS.nomFr, PERSONNE.region;

--R3

SELECT PAYS.nomFr AS Pays, COUNT(*) AS "Nombre de personnes"
FROM PERSONNE
JOIN PAYS ON PERSONNE.cdPays = PAYS.cdPays
GROUP BY PAYS.nomFr
HAVING COUNT(*) > 8;

--R4

SELECT nomPers, pnomPers
FROM PERSONNE
WHERE dateNais = (SELECT MIN(dateNais) FROM PERSONNE);

--R5

SELECT PAYS.nomFr as Pays, count(nomPers) as "Nombre de personnes"
FROM PAYS
JOIN PERSONNE ON (PAYS.cdPays = PERSONNE.cdPays)
GROUP BY PAYS.nomFr
HAVING COUNT(nomPers) = (SELECT MAX(count(nomPers))
                         FROM PERSONNE
                         GROUP BY cdpays);

--R6

SELECT PAYS.nomfr as "Pays", PERSONNE.nompers as Nom, PERSONNE.pnompers as Prenom, round((SYSDATE - PERSONNE.dateNais)/365.25) || ' ' || 'ans' as Age
FROM PERSONNE
JOIN PAYS ON (PAYS.cdpays = PERSONNE.cdpays)
WHERE PERSONNE.dateNais = (SELECT min(P2.dateNais)
                            FROM PERSONNE P2
                            WHERE P2.cdPays = PERSONNE.cdPays)
GROUP BY PAYS.nomfr, PERSONNE.nompers, PERSONNE.pnompers, PERSONNE.dateNais;

--R7

SELECT PAYS.nomfr as Pays, count(PERSONNE.nomPers) as "Nombre de personnes"
FROM PERSONNE
RIGHT JOIN PAYS ON (PAYS.cdpays = PERSONNE.cdpays)
WHERE PAYS.continent = 'Océanie' and upper(PAYS.nomFr) like '%A%E'
GROUP BY PAYS.nomfr;



