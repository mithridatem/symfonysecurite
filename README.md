**Récupérer le projet** :

**Prérequis** :
- php 8.2 + (avec sodium et Intl activés),
- Serveur Apache 2 +,
- Serveur MySQL / MariaDB,
- composer 2.7 +,
- symfony cli 5.7 +,
- openssl,
- Compte Email compatible (SMTP).

**Saisir les commandes et instructions suivantes** :

```sh
git clone https://github.com/mithridatem/symfonysecurite.git securite
cd securite
mkdir config/jwt

# Créer les clés public/private
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

# Ajouter les entrées suivantes dans le fichier .env
DATABASE_URL=
USER_EMAIL=
PWD_EMAIL=
SMTP_EMAIL=
PORT_SMTP_EMAIL=
JWT_SECRET_KEY=
JWT_PUBLIC_KEY=
JWT_PASSPHRASE=

# Ajouter les entrées suivantes dans le fichier .env.dev

# remplacer par votre configuration BDD
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"

# remplacer par votre configuration Email SMTP
USER_EMAIL="xxxxxx@xxxxx.xxx"
PWD_EMAIL="xxxxxxxxxxxxxx"
SMTP_EMAIL="xxxx.xxxx.xxx"
PORT_SMTP_EMAIL=000

# remplacer par votre passphrase openssl
JWT_PASSPHRASE=remplacer_par_votre_passphrase

composer install

symfony console d:d:c
symfony console d:m:m
symfony console d:f:l

```
