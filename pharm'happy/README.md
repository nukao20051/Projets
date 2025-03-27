# Projet de Gestion d'un Stock Pharmaceutique

## Membres du Groupe
- **Valentin FORTIER** - fort0050
- **Elias RICHARME** - rich0190
- **Thomas PILARZ** - pila0010
- **Thomas JOURDAIN** - jour0062
- **Lenny LACROIX** - lacr0034
- **Gatien GENEVOIS** - gene0024


## Description du Projet
Le projet vise à développer une application web pour des commandes de médicaments. Elle permet de suivre les quantités disponibles, contrôler les dates d’expiration et gérer les commandes, qu’il s’agisse d’approvisionnements ou de demandes de clients. L’objectif est de garantir la disponibilité des produits et éviter les ruptures de stock et les pertes.

## Mise en Place
Ce projet utilise le framework Symfony pour le développement backend.
Voici les principales étapes d'installation et de configuration du projet.

### Connexion à la vm
Pour accéder au site héberger sur notre machine virtuelle vous devez
être connecté au vpn du département informatique du l'iut

Notre vm est une machine OpenNebula qui est hébergé au département informatique de l'iut

Voici le lien : http://10.31.33.116:8000/

### Connexion avec hébergement en local

Pour héberger notre projet il est nécessaire d'avoir installé :

- [PHP](https://www.php.net/)

Pour s'assurer du bon fonctionnement du projet, vous devrez installer les extensions PHP suivantes :

`intl`, `xml`, `curl`, `dom`, `mysql`, `sqlite3`, `mbstring`


- [Composer](https://getcomposer.org/download/)

- [Symfony](https://symfony.com/download)

Par la suite, vous pouvez cloner le dépôt du projet :
```shell
git clone https://iut-info.univ-reims.fr/gitlab/gene0024/sae3-01
```

Une fois télécharger vous aurez besoin d'installer le projet :
```shell
composer install
```

Vous devez maintenant créez votre fichier de configuration de base de données :

```shell
cp .env .env.local
```

Changer cette ligne :

```json
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.2.25-MariaDB&charset=utf8mb4"
```

Vous pouvez finalement générer la base de donnée et ses données :

```shell
composer db
```

### Récupérer une clé API Gemini pour PharmBot

Si vous voulez configurer le Chatbot il est possible d'utiliser la clé API déjà présente dans le dépôt ou suivre les directives ci-dessous.

Pour récupérer une clé API pour l'API Gemini Generative Language et l'ajouter au bot, suivez ces étapes :

1. **Accéder à la console Google Cloud:** Ouvrez votre navigateur Web et accédez à la [console Google Cloud](https://console.cloud.google.com/). Assurez-vous d’être connecté au compte Google associé à votre projet Google Cloud.

2. **Sélectionner votre projet:** Dans le menu déroulant en haut de l’écran, sélectionnez le projet Google Cloud pour lequel vous souhaitez créer une clé API. Si vous n'avez pas encore de projet, vous devrez en créer un.

3. **Ouvrir la page « Identifiants »:** Dans le menu de navigation à gauche, sous « API et services », cliquez sur « Identifiants ».

4. **Créer des identifiants:** Cliquez sur le bouton « Créer des identifiants » en haut de la page.

5. **Choisir le type de clé:** Un menu déroulant s’affiche. Sélectionnez « Clé API ».

6. **Votre clé API est affichée:** Une fenêtre contextuelle s’affiche contenant votre nouvelle clé API.  **IMPORTANT : Copiez cette clé immédiatement. Une fois cette fenêtre fermée, vous ne pourrez plus la visualiser directement.** Google vous la montrera une seule fois.

7. **(Facultatif) Restreindre la clé API:** Pour des raisons de sécurité, il est fortement recommandé de restreindre votre clé API. Dans la page « Identifiants », cliquez sur le nom de votre nouvelle clé. Vous pouvez restreindre son utilisation par :

    * **Applications:** Limitez l'utilisation de la clé à des adresses IP spécifiques, des applications Android ou des identifiants iOS.
    * **API:** Restreignez l'utilisation de la clé à l'API Gemini uniquement. Ceci est crucial pour éviter toute utilisation abusive.

8. **Stocker votre clé en toute sécurité:** Enregistrez votre clé API dans src/controller/ChatBotController.php à la place de GEMINI_API_KEY, l'idéal est d'utiliser un gestionnaire de secrets ou des variables d'environnement.

**Remarques importantes :**

*   Ne pas inclure la clé API directement dans le code source.
*   Surveiller l'utilisation de votre clé API.
*   Régénérer votre clé API périodiquement.


## Fonctionnalité

### Authentification
Vous pouvez vous connecter à ces quatres utilisateurs qui ont tous des rôles différents

Pour se connecter comme Admin :
- **E-Mail : admin@example.com**
- **Mot de passe : test**

Pour se connecter comme Gestionnaire :
- **E-Mail : manager@example.com**
- **Mot de passe : test**

Pour se connecter comme User :
- **E-Mail : user@example.com**
- **Mot de passe : test**

Pour se connecter comme User avec une pharmacie (donne l'accès à tous les médicaments) :
- **E-Mail : user-mo@example.com**
- **Mot de passe : test**

### Fonctionnalité des utilisateurs

[**Fonctionnalité** : accès]

- **Creation de compte** : bouton dans la navbar (/person/create)
- **Voir son profil** : bouton dans la nav bar une fois connecter (/person/{id})
- **Gérer les adresses de livraison** : bouton dans le profil (/address)
- **Voir son panier** : bouton dans la navbar (/cart)
- **Utiliser le chatbot** : bouton en bas à droite du site (/chatbot)
- **Commander** : commander depuis le panier

### Fonctionnalité des administrateur

- **Visibilité sur le stock des médicaments** : accueil du site (/)
- **Consultation des commandes** : bouton dans la navbar (/order)
- **Back-office** : bouton dans la navbar (/admin)
- **Voir la liste des utilisateurs** : bouton dans la navbar (/person)