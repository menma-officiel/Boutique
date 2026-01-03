# Architecture & Infrastructure

Résumé technique du projet Menma Shop.

## Technologies
- Framework : Laravel (PHP 8.2) — architecture MVC
- Auth : Laravel Breeze (Blade)
- Base de données locale : SQLite (dev), PostgreSQL (production - Supabase)
- Conteneurisation : Docker (à ajouter)
- CI/CD : Render (déploiement automatique depuis GitHub)

## Composants principaux
- `Product` : fiches produits (nom, slug, description, price, stock, image, is_active)
- `Order` : commandes passées via formulaire simplifié (enregistrement + redirection WhatsApp)
- `Comment` : preuve sociale (avis) avec modération

## Redirection WhatsApp
Lorsqu'un visiteur passe commande, le système crée une `Order` puis redirige vers `wa.me` avec un message pré-rempli incluant les informations du client et du produit. Le numéro WhatsApp administrateur est configuré via `WHATSAPP_NUMBER` dans `.env`.

## Sécurité
- CSRF activé par défaut (middleware Laravel)
- Utilisation de l'ORM Eloquent pour prévenir les injections SQL
- `APP_KEY` utilisé pour cryptographie des données sensibles

## Notes de déploiement
- En production, utiliser PostgreSQL (Supabase) avec un connection pooler
- Activer SSL/TLS et variables d'environnement sécurisées dans Render
- Planifier une rotation d'`APP_KEY` si vous utilisez des données chiffrées
