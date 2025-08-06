#!/bin/bash
# Script de instalación automática para ITAM System
# Ejecutar como root: sudo bash install.sh

set -e  # Salir si hay algún error

echo "🚀 Iniciando instalación de ITAM System..."

# Verificar que se ejecuta como root
if [[ $EUID -ne 0 ]]; then
   echo "❌ Este script debe ejecutarse como root (usa sudo)" 
   exit 1
fi

# Función para mostrar progreso
show_progress() {
    echo "📋 $1..."
}

# Función para verificar si un comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Detectar sistema operativo
if [[ -f /etc/debian_version ]]; then
    OS="debian"
    PKG_MANAGER="apt"
elif [[ -f /etc/redhat-release ]]; then
    OS="redhat"
    PKG_MANAGER="yum"
else
    echo "❌ Sistema operativo no compatible. Este script funciona con Debian/Ubuntu y RedHat/CentOS"
    exit 1
fi

# Variables de configuración
DOMAIN_NAME=""
GIT_REPO_URL=""
INSTALL_SSL=true
INSTALL_FIREWALL=true

# Solicitar información al usuario
echo "📝 Configuración inicial:"
read -p "🌐 Ingresa tu dominio (ej: itam.tuempresa.com): " DOMAIN_NAME
read -p "📦 URL del repositorio Git (opcional, presiona Enter para omitir): " GIT_REPO_URL
read -p "🔒 ¿Instalar SSL con Let's Encrypt? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    INSTALL_SSL=true
fi

read -p "🛡️  ¿Configurar firewall UFW? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    INSTALL_FIREWALL=true
fi

# 1. Actualizar sistema
show_progress "Actualizando sistema"
$PKG_MANAGER update && $PKG_MANAGER upgrade -y

# 2. Instalar dependencias del sistema
show_progress "Instalando dependencias del sistema"
if [[ "$OS" == "debian" ]]; then
    # Agregar repositorio PHP para Debian/Ubuntu
    apt install -y software-properties-common
    add-apt-repository ppa:ondrej/php -y
    apt update
    
    # Instalar paquetes para Debian/Ubuntu
    apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-xml \
                   php8.2-mbstring php8.2-zip php8.2-curl php8.2-bcmath \
                   php8.2-fileinfo php8.2-tokenizer php8.2-ctype php8.2-json \
                   nginx unzip git curl wget
    
    # Instalar Composer
    if ! command_exists composer; then
        cd /tmp
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    
    # Instalar Node.js
    if ! command_exists node; then
        curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
        apt install -y nodejs
    fi
    
    # Instalar Certbot si se requiere SSL
    if [[ "$INSTALL_SSL" == true ]]; then
        apt install -y certbot python3-certbot-nginx
    fi
    
    # Instalar UFW si se requiere firewall
    if [[ "$INSTALL_FIREWALL" == true ]]; then
        apt install -y ufw
    fi
    
elif [[ "$OS" == "redhat" ]]; then
    # Para CentOS/RHEL
    yum install -y epel-release
    yum install -y php php-fpm php-mysql php-xml php-mbstring php-zip \
                   php-curl php-bcmath php-fileinfo php-tokenizer \
                   php-ctype php-json nginx unzip git curl wget
    
    # Instalar Composer
    if ! command_exists composer; then
        cd /tmp
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    
    # Instalar Node.js
    if ! command_exists node; then
        curl -fsSL https://rpm.nodesource.com/setup_lts.x | bash -
        yum install -y nodejs
    fi
fi

# Verificar instalaciones
show_progress "Verificando instalaciones"
php -v || { echo "❌ Error: PHP no instalado correctamente"; exit 1; }
composer --version || { echo "❌ Error: Composer no instalado correctamente"; exit 1; }
node --version || { echo "❌ Error: Node.js no instalado correctamente"; exit 1; }
nginx -v || { echo "❌ Error: Nginx no instalado correctamente"; exit 1; }

# 3. Configurar directorio del proyecto
show_progress "Configurando directorio del proyecto"
cd /var/www/html
rm -rf itam  # Remover si existe

# Clonar o crear directorio
if [[ -n "$GIT_REPO_URL" ]]; then
    git clone "$GIT_REPO_URL" itam
else
    echo "⚠️  Sin repositorio Git especificado. Debes subir los archivos manualmente a /var/www/html/itam/"
    mkdir -p itam
    echo "📁 Directorio creado en /var/www/html/itam - sube tus archivos aquí"
    echo "📋 Después ejecuta: cd /var/www/html/itam && composer install && npm ci"
    exit 0
fi

cd itam

# 4. Instalar dependencias PHP
show_progress "Instalando dependencias PHP"
composer install --optimize-autoloader --no-dev --no-interaction

# 5. Instalar dependencias Node.js
show_progress "Instalando dependencias Node.js"
npm ci --only=production
chmod +x node_modules/.bin/vite 2>/dev/null || true

# 6. Configurar entorno
show_progress "Configurando entorno"
if [[ ! -f .env ]]; then
    cp .env.example .env
fi

# Configurar .env para producción
sed -i "s|APP_ENV=local|APP_ENV=production|g" .env
sed -i "s|APP_DEBUG=true|APP_DEBUG=false|g" .env
if [[ -n "$DOMAIN_NAME" ]]; then
    sed -i "s|APP_URL=http://localhost|APP_URL=https://$DOMAIN_NAME|g" .env
fi

php artisan key:generate --force

# 7. Configurar base de datos
show_progress "Configurando base de datos"
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

# 8. Compilar assets
show_progress "Compilando assets"
npm run build

# 9. Configurar permisos
show_progress "Configurando permisos"
chown -R www-data:www-data /var/www/html/itam
find /var/www/html/itam -type d -exec chmod 755 {} \;
find /var/www/html/itam -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 10. Configurar Nginx
show_progress "Configurando servidor web (Nginx)"
cat > /etc/nginx/sites-available/itam << EOF
server {
    listen 80;
    server_name $DOMAIN_NAME www.$DOMAIN_NAME;
    root /var/www/html/itam/public;
    index index.php index.html;

    # Logs
    access_log /var/log/nginx/itam_access.log;
    error_log /var/log/nginx/itam_error.log;

    # Configuración principal
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # Procesar PHP
    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeouts
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Negar acceso a archivos ocultos
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Negar acceso a archivos sensibles
    location ~* \.(env|log|htaccess)\$ {
        deny all;
    }

    # Cache para assets estáticos
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)\$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Habilitar sitio
ln -sf /etc/nginx/sites-available/itam /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Verificar configuración de Nginx
nginx -t || { echo "❌ Error en configuración de Nginx"; exit 1; }

# Iniciar servicios
systemctl start nginx
systemctl enable nginx
systemctl start php8.2-fpm
systemctl enable php8.2-fpm
systemctl reload nginx

# 11. Configurar SSL si se solicitó
if [[ "$INSTALL_SSL" == true ]] && [[ -n "$DOMAIN_NAME" ]]; then
    show_progress "Configurando SSL con Let's Encrypt"
    certbot --nginx -d "$DOMAIN_NAME" -d "www.$DOMAIN_NAME" --non-interactive --agree-tos --email "admin@$DOMAIN_NAME" || {
        echo "⚠️  SSL no pudo configurarse automáticamente. Ejecuta manualmente:"
        echo "certbot --nginx -d $DOMAIN_NAME"
    }
fi

# 12. Configurar firewall si se solicitó
if [[ "$INSTALL_FIREWALL" == true ]]; then
    show_progress "Configurando firewall UFW"
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow ssh
    ufw allow 'Nginx Full'
    ufw --force enable
fi

# 13. Optimizar Laravel
show_progress "Optimizando aplicación Laravel"
cd /var/www/html/itam
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 14. Configurar backup automático
show_progress "Configurando backup automático"
cat > /usr/local/bin/itam-backup.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/itam"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup base de datos
cp /var/www/html/itam/database/database.sqlite $BACKUP_DIR/database_$DATE.sqlite

# Backup archivos subidos
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C /var/www/html/itam storage/app/public

# Limpiar backups antiguos (mantener últimos 7 días)
find $BACKUP_DIR -name "*.sqlite" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completado: $DATE"
EOF

chmod +x /usr/local/bin/itam-backup.sh

# Configurar cron para backup diario
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/itam-backup.sh >> /var/log/itam-backup.log 2>&1") | crontab -

# 15. Verificación final
show_progress "Realizando verificación final"
systemctl is-active --quiet nginx || { echo "❌ Nginx no está ejecutándose"; exit 1; }
systemctl is-active --quiet php8.2-fpm || { echo "❌ PHP-FPM no está ejecutándose"; exit 1; }

# Verificar conectividad
sleep 2
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/ || echo "000")
if [[ "$HTTP_STATUS" == "200" ]] || [[ "$HTTP_STATUS" == "302" ]]; then
    echo "✅ Servidor web respondiendo correctamente"
else
    echo "⚠️  Servidor web no responde como se esperaba (HTTP: $HTTP_STATUS)"
fi

# Mostrar información final
echo ""
echo "🎉 ¡Instalación completada exitosamente!"
echo ""
echo "📊 Información del sistema:"
echo "  🌐 Dominio: ${DOMAIN_NAME:-"IP del servidor"}"
echo "  🌍 URL: ${INSTALL_SSL:+https://}${INSTALL_SSL:-http://}${DOMAIN_NAME:-$(curl -s ifconfig.me)}"
echo "  📁 Directorio: /var/www/html/itam"
echo "  🗄️  Base de datos: SQLite (/var/www/html/itam/database/database.sqlite)"
echo ""
echo "🔐 Credenciales por defecto:"
echo "  👤 Usuario: sistemas@bkb.mx"
echo "  🔑 Contraseña: password"
echo ""
echo "⚠️  IMPORTANTE:"
echo "  1. Cambia la contraseña por defecto inmediatamente"
echo "  2. Revisa la configuración en /var/www/html/itam/.env"
echo "  3. Los backups se ejecutan diariamente a las 2:00 AM"
echo ""
echo "📋 Comandos útiles:"
echo "  Ver logs: tail -f /var/www/html/itam/storage/logs/laravel.log"
echo "  Reiniciar servicios: systemctl restart nginx php8.2-fpm"
echo "  Backup manual: /usr/local/bin/itam-backup.sh"
echo ""
echo "🆘 Si hay problemas, revisa:"
echo "  - /var/log/nginx/itam_error.log"
echo "  - /var/www/html/itam/storage/logs/laravel.log"
echo ""

if [[ "$INSTALL_FIREWALL" == true ]]; then
    echo "🛡️  Firewall UFW activado - Solo puertos SSH, HTTP y HTTPS están abiertos"
fi

if [[ "$INSTALL_SSL" == true ]] && [[ -n "$DOMAIN_NAME" ]]; then
    echo "🔒 SSL configurado - El certificado se renovará automáticamente"
fi

echo "✨ ¡Disfruta tu nuevo sistema ITAM!"