# Menma Shop

Menma Shop — plateforme e‑commerce Full‑Stack optimisée pour la conversion (lead generation). Conçue pour transformer le visiteur en acheteur en un minimum de clics via une stratégie de vente locale (commande via WhatsApp, preuves sociales, paiement à la livraison).

## Démarrage rapide (dev)

- Copier `.env.example` en `.env` et modifier les variables (notamment `WHATSAPP_NUMBER` et `DB` si besoin)
- Installer dépendances PHP/JS
  - `composer install`
  - `npm install`
- Générer la clé d'application : `php artisan key:generate`
- Créer la base locale et exécuter les migrations & seeders :
  - `touch database/database.sqlite`
  - `php artisan migrate --seed`
- Compiler les assets : `npm run build`
- Démarrer le serveur local : `php artisan serve`

### Docker (local development)

Une configuration Docker basique est fournie (`Dockerfile` + `docker-compose.yml`) pour lancer l'application avec PostgreSQL et Nginx.

- Build & start :

```bash
docker compose up --build -d
```

- L'application sera disponible sur : http://localhost:8080

- Exemple d'URL de connexion Postgres pour la variable d'environnement :

```
DATABASE_URL=postgres://postgres:postgres@db:5432/menma
```

- Après `docker compose up` exécuter migrations / seeders depuis le conteneur `app` :
  - `docker compose exec app php artisan migrate --seed`

## Déploiement sur Render (Postgres)

- Créez un service PostgreSQL dans Render et récupérez l'**external database URL** (format `postgresql://user:pass@host/database`).
- Dans l'onglet Environment de votre service Web, ajoutez :
  - `DATABASE_URL` = *external database url* (recommandé)
  - ou `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` si vous préférez les variables individuelles
  - `DB_SSLMODE=require` (si nécessaire)
  - `APP_KEY` (généré via `php artisan key:generate --show`)
  - `APP_ENV=production`, `APP_DEBUG=false`
- Le fichier `render.yaml` est fourni : il exécute `composer install` en build et lance `scripts/deploy.sh` puis `php artisan serve` au démarrage.
- `scripts/deploy.sh` exécutera les migrations et mettra en cache la configuration/routes. Vous pouvez aussi exécuter les commandes manuellement via le Dashboard Render (Console).
- Si vous utilisez Docker, le `Dockerfile` installe `pdo_pgsql` (libpq) — vérifiez que votre environnement de déploiement utilise bien ce Dockerfile ou une image PHP avec `pdo_pgsql` activé.

## Fonctionnalités initiales
- Catalogue produits (listing / fiche produit) optimisé mobile (formulaire compact, input téléphonique, bouton sticky)
- Formulaire de commande simplifié -> page de confirmation + bouton « Ouvrir WhatsApp » (deep link `whatsapp://` mobile → `api.whatsapp.com` fallback web) avec message pré‑rempli
- Modèles Eloquent : `Product`, `Order`, `Comment`
- Auth scafolding (Laravel Breeze - Blade) pour accès admin
- Seeders/factories pour données d'exemple

## Prochaines étapes
- Dashboard admin (CRUD produits, modération commentaires, suivi commandes)
- Intégration WhatsApp Business/API (optionnel)
- Conteneurisation Docker & devcontainer
- Déploiement continu sur Render + base PostgreSQL (Supabase)

Pour plus de détails techniques, voir `docs/architecture.md`.
