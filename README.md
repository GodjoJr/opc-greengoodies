# GreenGoodies

Application e-commerce développée dans le cadre d'un projet de formation. GreenGoodies est une boutique en ligne construite avec le framework Symfony 6.4, permettant la navigation et l'achat de produits via une interface web complète.

---

## Stack technique

- **Framework** : Symfony 6.4 (LTS)
- **Langage** : PHP >= 8.1
- **Base de données** : PostgreSQL 16 (via Docker)
- **ORM** : Doctrine ORM 3
- **Templating** : Twig
- **Front-end** : SCSS, Symfony AssetMapper, Stimulus, Turbo
- **Authentification** : LexikJWTAuthenticationBundle

---

## Prérequis

- PHP >= 8.1
- Composer
- Node.js et npm
- Docker et Docker Compose
- Symfony CLI (recommandé)

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/GodjoJr/opc-greengoodies.git
cd opc-greengoodies
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configurer les variables d'environnement

Copier le fichier `.env` et l'adapter à votre environnement local :

```bash
cp .env .env.local
```

Renseigner notamment la variable `DATABASE_URL` avec les informations de connexion à votre base de données.

### 5. Démarrer la base de données

Démarrez votre serveur de base de données via votre méthode habituelle (MAMP, WAMP, XAMPP, Docker, serveur natif, etc.), puis assurez-vous que la variable `DATABASE_URL` dans votre fichier `.env.local` correspond bien à votre configuration locale.

### 6. Créer la base de données et appliquer les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 7. Charger les données de test (fixtures)

```bash
php bin/console doctrine:fixtures:load
```

Cette commande peuple la base de données avec un jeu de données de démonstration généré via FakerPHP.

### 8. Lancer le serveur de développement

```bash
symfony server:start
```

---

## Structure du projet

```
opc-greengoodies/
├── assets/          # Fichiers JavaScript et SCSS
├── config/          # Configuration Symfony (services, routes, packages)
├── migrations/      # Migrations Doctrine
├── public/          # Point d'entrée web (index.php)
├── src/             # Code source PHP (Controllers, Entities, Repositories...)
├── templates/       # Templates Twig
├── tests/           # Tests PHPUnit
├── translations/    # Fichiers de traduction
├── compose.yaml     # Configuration Docker Compose
└── composer.json    # Dépendances PHP
```

---

## Auteur

Projet réalisé dans le cadre d'une formation OpenClassroom.  
Dépôt : [github.com/GodjoJr/opc-greengoodies](https://github.com/GodjoJr/opc-greengoodies)