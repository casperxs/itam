# INSTALL.md

Guía de instalación para el Sistema ITAM (IT Asset Management) en servidor remoto.

## 🚀 Instalación Rápida (Comandos Paso a Paso)

### Script de Instalación Automática

```bash
#!/bin/bash
# Script de instalación automática para ITAM System
# Ejecutar como root: sudo bash install.sh

set -e  # Salir si hay algún error

echo "🚀 Iniciando instalación de ITAM System..."

# 1. Actualizar sistema
echo "📦 Actualizando sistema..."
apt update && apt upgrade -y

# 2. Instalar dependencias del sistema
echo "🔧 Instalando dependencias..."
apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-xml php8.2-mbstring php8.2-zip php8.2-curl php8.2-bcmath php8.2-fileinfo php8.2-tokenizer php8.2-ctype php8.2-json composer nodejs npm nginx certbot python3-certbot-nginx unzip git curl

# 3. Crear directorio y clonar proyecto
echo "📁 Configurando directorio del proyecto..."
cd /var/www/html
rm -rf itam  # Remover si existe
git clone https://github.com/tu-usuario/itam.git itam
cd itam

# 4. Instalar dependencias PHP
echo "🎼 Instalando dependencias PHP..."
composer install --optimize-autoloader --no-dev --no-interaction

# 5. Instalar dependencias Node.js
echo "📦 Instalando dependencias Node.js..."
npm ci --only=production
chmod +x node_modules/.bin/vite

# 6. Configurar entorno
echo "⚙️ Configurando entorno..."
cp .env.example .env
php artisan key:generate --force

# 7. Configurar base de datos
echo "🗄️ Configurando base de datos..."
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

# 8. Compilar assets
echo "🎨 Compilando assets..."
npm run build

# 9. Configurar permisos
echo "🔐 Configurando permisos..."
chown -R www-data:www-data /var/www/html/itam
find /var/www/html/itam -type d -exec chmod 755 {} \;
find /var/www/html/itam -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 10. Configurar Nginx
echo "🌍 Configurando servidor web..."
cat > /etc/nginx/sites-available/itam << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/html/itam/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

ln -sf /etc/nginx/sites-available/itam /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# 11. Optimizar Laravel
echo "🚀 Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Instalación completada!"
echo "🌐 Accede a tu servidor en http://$(curl -s ifconfig.me)"
echo "👤 Usuario: sistemas@bkb.mx"
echo "🔑 Contraseña: password"
echo ""
echo "⚠️  IMPORTANTE: Cambia la contraseña por defecto inmediatamente"
```

### Comandos Manuales Paso a Paso

Si prefieres instalación manual, sigue estos comandos en orden:

#### Paso 1: Preparar el Servidor
```bash
# Conectar al servidor
ssh root@tu-servidor.com

# Actualizar sistema
apt update && apt upgrade -y

# Instalar dependencias
apt install -y software-properties-common curl wget unzip git
```

#### Paso 2: Instalar PHP 8.2
```bash
# Agregar repositorio PHP
add-apt-repository ppa:ondrej/php -y
apt update

# Instalar PHP y extensiones
apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-xml php8.2-mbstring php8.2-zip php8.2-curl php8.2-bcmath php8.2-fileinfo php8.2-tokenizer php8.2-ctype php8.2-json

# Verificar instalación
php -v
php -m | grep -E "(sqlite|mbstring|xml|zip)"
```

#### Paso 3: Instalar Composer
```bash
# Descargar e instalar Composer
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Verificar instalación
composer --version
```

#### Paso 4: Instalar Node.js y NPM
```bash
# Instalar Node.js 18 LTS
curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
apt install -y nodejs

# Verificar instalación
node --version
npm --version
```

#### Paso 5: Instalar Servidor Web (Nginx)
```bash
# Instalar Nginx
apt install -y nginx

# Iniciar y habilitar
systemctl start nginx
systemctl enable nginx

# Verificar estado
systemctl status nginx
```

#### Paso 6: Clonar/Subir el Proyecto
```bash
# Opción A: Clonar desde Git
cd /var/www/html
git clone https://github.com/tu-usuario/itam.git itam
cd itam

# Opción B: Subir archivos (si tienes el proyecto local)
# scp -r /ruta/local/itam root@servidor:/var/www/html/
```

#### Paso 7: Instalar Dependencias del Proyecto
```bash
cd /var/www/html/itam

# Instalar dependencias PHP
composer install --optimize-autoloader --no-dev --no-interaction

# Instalar dependencias Node.js
npm ci --only=production

# Dar permisos a Vite
chmod +x node_modules/.bin/vite
```

#### Paso 8: Configurar Entorno
```bash
# Copiar configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate --force

# Editar configuración (opcional - ya viene configurada para producción)
nano .env
```

#### Paso 9: Configurar Base de Datos
```bash
# Crear archivo SQLite
touch database/database.sqlite

# Ejecutar migraciones
php artisan migrate --force

# Sembrar datos iniciales
php artisan db:seed --force

# Verificar que se crearon las tablas
php artisan tinker --execute="echo 'Tablas: ' . implode(', ', array_keys(DB::select('SELECT name FROM sqlite_schema WHERE type=\"table\"')));"
```

#### Paso 10: Compilar Assets
```bash
# Compilar para producción
npm run build

# Verificar que se generaron
ls -la public/build/
```

#### Paso 11: Configurar Permisos
```bash
# Propietario correcto
chown -R www-data:www-data /var/www/html/itam

# Permisos de directorios (755)
find /var/www/html/itam -type d -exec chmod 755 {} \;

# Permisos de archivos (644)
find /var/www/html/itam -type f -exec chmod 644 {} \;

# Permisos especiales para storage y cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/
```

#### Paso 12: Configurar Nginx
```bash
# Crear configuración del sitio
cat > /etc/nginx/sites-available/itam << 'EOF'
server {
    listen 80;
    server_name tu-dominio.com www.tu-dominio.com;
    root /var/www/html/itam/public;
    index index.php index.html;

    # Logs
    access_log /var/log/nginx/itam_access.log;
    error_log /var/log/nginx/itam_error.log;

    # Configuración principal
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Procesar PHP
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
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
    location ~* \.(env|log|htaccess)$ {
        deny all;
    }

    # Cache para assets estáticos
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

# Habilitar sitio
ln -sf /etc/nginx/sites-available/itam /etc/nginx/sites-enabled/

# Deshabilitar sitio por defecto
rm -f /etc/nginx/sites-enabled/default

# Verificar configuración
nginx -t

# Recargar Nginx
systemctl reload nginx
```

#### Paso 13: Optimizar Laravel
```bash
cd /var/www/html/itam

# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Verificar configuración
php artisan about
```

#### Paso 14: Configurar SSL (Opcional pero Recomendado)
```bash
# Instalar Certbot
apt install -y certbot python3-certbot-nginx

# Obtener certificado SSL
certbot --nginx -d tu-dominio.com -d www.tu-dominio.com

# Verificar renovación automática
certbot renew --dry-run
```

#### Paso 15: Configurar Firewall
```bash
# Instalar UFW
apt install -y ufw

# Configurar reglas básicas
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'

# Habilitar firewall
ufw --force enable

# Verificar estado
ufw status
```

#### Paso 16: Configurar Monitoreo y Backup
```bash
# Crear script de backup
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
```

#### Paso 17: Verificación Final
```bash
# Verificar servicios
systemctl status nginx
systemctl status php8.2-fpm

# Verificar configuración Laravel
cd /var/www/html/itam
php artisan about

# Verificar conectividad
curl -I http://localhost

# Ver logs en tiempo real (opcional)
tail -f storage/logs/laravel.log
```

## Requisitos del Servidor

### Requisitos Mínimos
- **PHP**: 8.2 o superior
- **Composer**: 2.0 o superior
- **Node.js**: 18.0 o superior
- **NPM**: 9.0 o superior
- **Base de datos**: SQLite (incluida) o MySQL/PostgreSQL
- **Servidor web**: Apache/Nginx con mod_rewrite
- **Memoria**: 512MB RAM mínimo (recomendado 1GB+)
- **Espacio**: 500MB de disco libre

### Extensiones PHP Requeridas
```bash
php -m | grep -E "(openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath|fileinfo|zip)"
```

Extensiones necesarias:
- openssl
- pdo_sqlite (o pdo_mysql/pdo_pgsql)
- mbstring
- tokenizer
- xml
- ctype
- json
- bcmath
- fileinfo
- zip

## Pasos de Instalación

### 1. Clonar o Subir el Proyecto

**Opción A: Git Clone (recomendado)**
```bash
cd /var/www/html
git clone [URL_DEL_REPOSITORIO] itam
cd itam
```

**Opción B: Subir archivos comprimidos**
```bash
# Comprimir localmente (excluir node_modules y vendor)
tar -czf itam.tar.gz --exclude=node_modules --exclude=vendor .

# En el servidor
cd /var/www/html
tar -xzf itam.tar.gz
mv [directorio_extraido] itam
cd itam
```

### 2. Instalar Dependencias

```bash
# Instalar dependencias PHP
composer install --optimize-autoloader --no-dev

# Instalar dependencias Node.js
npm ci --only=production

# Dar permisos a binarios
chmod +x node_modules/.bin/vite
```

### 3. Configuración del Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Editar configuración
nano .env
```

**Configuración recomendada para producción (.env):**
```env
APP_NAME="ITAM System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de datos SQLite (recomendado para simplicidad)
DB_CONNECTION=sqlite
# Para MySQL, descomentar y configurar:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=itam_production
# DB_USERNAME=itam_user
# DB_PASSWORD=contraseña_segura

# Configuración de sesiones y cache
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Email (opcional - configurar según proveedor)
MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@dominio.com
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"

# Configuración adicional
LOG_CHANNEL=single
LOG_LEVEL=error
```

### 4. Configurar Base de Datos

**Para SQLite (recomendado):**
```bash
# Crear archivo de base de datos
touch database/database.sqlite

# Ejecutar migraciones
php artisan migrate --force

# Sembrar datos iniciales
php artisan db:seed --force
```

**Para MySQL:**
```bash
# Crear base de datos
mysql -u root -p -e "CREATE DATABASE itam_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p -e "CREATE USER 'itam_user'@'localhost' IDENTIFIED BY 'contraseña_segura';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON itam_production.* TO 'itam_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Ejecutar migraciones
php artisan migrate --force

# Sembrar datos iniciales
php artisan db:seed --force
```

### 5. Configurar Permisos

```bash
# Propietario correcto
chown -R www-data:www-data /var/www/html/itam

# Permisos de directorios
find /var/www/html/itam -type d -exec chmod 755 {} \;

# Permisos de archivos
find /var/www/html/itam -type f -exec chmod 644 {} \;

# Permisos especiales para storage y bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Asegurar que www-data pueda escribir
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Compilar Assets

```bash
# Compilar assets para producción
npm run build

# Verificar que se generaron los archivos
ls -la public/build/
```

### 7. Configurar Servidor Web

**Apache (.htaccess ya incluido):**
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    DocumentRoot /var/www/html/itam/public
    
    <Directory /var/www/html/itam/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/itam_error.log
    CustomLog ${APACHE_LOG_DIR}/itam_access.log combined
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/html/itam/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 8. Configurar SSL (Recomendado)

```bash
# Con Certbot (Let's Encrypt)
apt install certbot python3-certbot-apache  # Para Apache
# o
apt install certbot python3-certbot-nginx   # Para Nginx

# Obtener certificado
certbot --apache -d tu-dominio.com  # Apache
# o
certbot --nginx -d tu-dominio.com   # Nginx
```

### 9. Configurar Cron Jobs (Opcional)

```bash
# Editar crontab para www-data
crontab -u www-data -e

# Agregar líneas:
# Ejecutar scheduler de Laravel cada minuto
* * * * * cd /var/www/html/itam && php artisan schedule:run >> /dev/null 2>&1

# Limpiar logs antiguos (semanal)
0 2 * * 0 cd /var/www/html/itam && php artisan log:clear --keep=30

# Verificar garantías próximas a vencer (diario a las 8 AM)
0 8 * * * cd /var/www/html/itam && php artisan check:warranties
```

### 10. Configurar Queue Workers (Opcional)

```bash
# Crear servicio systemd
nano /etc/systemd/system/itam-worker.service
```

```ini
[Unit]
Description=ITAM Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/itam
ExecStart=/usr/bin/php artisan queue:work --verbose --tries=3 --timeout=90
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
# Habilitar y iniciar servicio
systemctl enable itam-worker
systemctl start itam-worker
```

## Post-Instalación

### 1. Verificar Instalación

```bash
# Comprobar configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar estado
php artisan about
```

### 2. Acceso por Defecto

- **URL**: https://tu-dominio.com
- **Usuario**: sistemas@bkb.mx
- **Contraseña**: password

**⚠️ IMPORTANTE: Cambiar la contraseña por defecto inmediatamente después del primer acceso.**

### 3. Crear Usuario Administrador

```bash
# Opción 1: Via artisan tinker
php artisan tinker
>>> $user = App\Models\User::where('email', 'sistemas@bkb.mx')->first();
>>> $user->password = Hash::make('nueva_contraseña_segura');
>>> $user->save();
>>> exit

# Opción 2: Crear nuevo usuario
php artisan tinker
>>> App\Models\User::create([
...     'name' => 'Tu Nombre',
...     'email' => 'tu-email@dominio.com',
...     'password' => Hash::make('contraseña_segura'),
...     'role' => 'admin',
...     'department' => 'TI',
...     'position' => 'Administrador',
...     'employee_id' => 'ADM002'
... ]);
>>> exit
```

## Mantenimiento

### Actualizaciones
```bash
# Hacer backup de la base de datos
cp database/database.sqlite database/backup_$(date +%Y%m%d).sqlite

# Actualizar código
git pull origin main

# Actualizar dependencias
composer install --optimize-autoloader --no-dev
npm ci --only=production

# Ejecutar migraciones si las hay
php artisan migrate --force

# Recompilar assets
npm run build

# Limpiar caché
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Monitoreo
```bash
# Ver logs de errores
tail -f storage/logs/laravel.log

# Monitorear uso de espacio
df -h
du -sh storage/

# Verificar procesos
ps aux | grep php
systemctl status itam-worker
```

### Backup Automático
```bash
# Crear script de backup
nano /usr/local/bin/itam-backup.sh
```

```bash
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
```

```bash
chmod +x /usr/local/bin/itam-backup.sh

# Agregar a crontab (backup diario a las 2 AM)
crontab -e
# 0 2 * * * /usr/local/bin/itam-backup.sh
```

## Solución de Problemas Comunes

### Error 500 - Internal Server Error
```bash
# Verificar logs
tail -f storage/logs/laravel.log
tail -f /var/log/apache2/error.log  # o nginx/error.log

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Base de datos bloqueada (SQLite)
```bash
# Verificar procesos que usan la BD
lsof database/database.sqlite

# Reiniciar servidor web
systemctl restart apache2  # o nginx
```

### Assets no cargan
```bash
# Verificar archivos compilados
ls -la public/build/

# Recompilar
npm run build

# Verificar permisos
chown -R www-data:www-data public/build/
```

### Memory Limit
```bash
# Aumentar en php.ini
memory_limit = 512M

# O usar temporalmente
php -d memory_limit=512M artisan migrate
```

## Configuración Adicional para Producción

### 1. Configurar Firewall
```bash
# UFW básico
ufw allow 22      # SSH
ufw allow 80      # HTTP
ufw allow 443     # HTTPS
ufw enable
```

### 2. Fail2Ban para SSH
```bash
apt install fail2ban
systemctl enable fail2ban
systemctl start fail2ban
```

### 3. Monitoreo con Logrotate
```bash
nano /etc/logrotate.d/itam
```

```
/var/www/html/itam/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    notifempty
    create 644 www-data www-data
    postrotate
        /bin/kill -USR1 `cat /var/run/php/php8.2-fpm.pid 2> /dev/null` 2> /dev/null || true
    endscript
}
```

---

## Soporte

Para problemas de instalación o configuración:

1. Revisar logs en `storage/logs/laravel.log`
2. Verificar configuración con `php artisan about`
3. Consultar documentación en `CLAUDE.md`
4. Verificar permisos de archivos y directorios

**Recuerda**: Siempre hacer backup antes de realizar cambios en producción.