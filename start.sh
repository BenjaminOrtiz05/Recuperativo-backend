#!/bin/bash

# Salir si ocurre un error
set -e

# Ejecutar migraciones forzadas
echo "🔁 Ejecutando migraciones..."
php artisan migrate --force

# Cachear configuración, rutas y vistas
echo "⚙️ Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar Apache en primer plano (no lo cambies)
echo "🚀 Iniciando servidor Apache..."
exec apache2-foreground
