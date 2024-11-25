# SAE 2.01
## LECOURT HUMBERT Tom / JOURDAIN Thomas

## Script pour lancer le serveur Web local

Dans un terminal, exécutez cette ligne pour lancer le serveur Web local :
composer start

C'est un raccourci à la longue commande documentée plus haut avec l'aide de
composer. De plus, ce script n'a pas de limite de temps d'exécution :
"Composer\\Config::disableProcessTimeout"

La page d'accueil du site est accessible via l'adresse localhost:8000/

## Scripts pour faciliter l'utilisation de PHP CS Fixer

Dans un terminal, vous pouvez maintenant exécuter ces deux lignes :
composer test:cs
composer fix:cs
Le premier va vérifier le code de PHP CS Fixer, et le second va le
corriger.

## Création de .mypdo.ini

Pour vous connecter à la base de données, il vous faudra créer un fichier .mypdo.ini contenant la base de
données, ici vous pouvez vous connecter sur votre propre base de données en y insérant les données de la base de données de M.Jonquet sur PhpMyAdmin via le fichier à télécharger sur le sujet de la SAE.

Le fichier doit contenir :

[mypdo]
dsn = 'mysql:host=mysql;dbname=<nom_base>;charset=utf8'
username = '<username>'
password = '<mot_de_passe>'

## Installer les dépendances

N'oubliez pas d'installer les dépendances du projet avec la commande "composer install".

## Ce que propose l'application

Cette application affiche des dizaines des séries télé, vous pouvez cliquer dessus pour accéder à des informations complémentaires sur la série et ses saisons. Chaque série et saison (sauf exceptions gérées par l'application) possède une affiche ou un poster. Vous pouvez cliquer sur les saisons pour y obtenir des informations complémentaires ainsi que la liste de tous ses épisodes qui possède un lien en arrière vers la série. En bas de page pour les informations sur les séries et les saisons, vous pouvez revenir à la page d'accueil. Sur la page d'accueil, vous pouvez ajouter une série via un formulaire, tandis que sur la page de la série vous pouvez la modifier ou la supprimer sans restrictions. De plus, vous pouvez filtrer la page d'accueil par genre.
Tous les programmes dans le dossier "public" sont les pages, dans le dossier "admin" il y a les formulaires, et dans le dossier "Entity" il y a les classes qui permettent de manipuler les données de la base de données. Enfin, cette application est sécurisée et gère les erreurs qui vous redirigeront vers la page d'accueil ou sur un page blanche en cas d'exception extrême.

## Pour lancer le projet

Pour se faire, rendez-vous  à la racine du projet et placer vous dans un terminal. Ensuite, utilisez la commande "composer start:linux" ou "composer start:windows", tout dépendant du système d'exploitation que vous utilisez. Enfin, rendez-vous dans votre navigateur, et tapez l'adresse suivante dans votre barre de lien : http://localhost:8000/. 

