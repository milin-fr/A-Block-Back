# A Block - Projet de fin de formation
Program de formation : https://oclock.io/formations/developpeur-web#programme

C'est la partie serveur et admin du site A Block.
Serveur front : http://3.84.28.117/
Serveur back : http://3.81.147.17/A-Block-Back/public/

## Installation en local :
- "git clone git@github.com:milin-fr/A-Block-Back.git" (dans le dossier xampp\htdocs)
- "composer install" dans le dossier A-Block-Back
- Copier ".env" et renommer en ".env.local"
- Éditer ".env.local" : 
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
ou "db_user" = nom d'utilisateur mysql, "db_password" = mot de pass mysql, "db_name" = abloc
- "php bin/console doctrine:database:create" dans le dossier A-Block-Back
- "php bin/console doctrine:migrations:migrate" dans le dossier A-Block-Back
- "php bin/console doctrine:fixtures:load" dans le dossier A-Block-Back

Prérequis (sur Windows):
- XAMPP (Apache, MySQL)
- Git
- PHP 7.2.5
- Composer
