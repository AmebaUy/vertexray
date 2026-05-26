#!/bin/bash

# Script para agregar reglas de seguridad al .htaccess de STAGING en WP Engine
# Uso: bash add-security-rules-to-stg.sh

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}==================================================================${NC}"
echo -e "${YELLOW}Agregando reglas de seguridad al .htaccess de STAGING${NC}"
echo -e "${YELLOW}==================================================================${NC}"
echo ""

# Configuración SSH de staging
STG_SSH="vertexraystg@vertexraystg.ssh.wpengine.net"
STG_PATH="/sites/vertexraystg"
HTACCESS_PATH="$STG_PATH/.htaccess"
BACKUP_PATH="$STG_PATH/.htaccess.backup-$(date +%Y%m%d-%H%M%S)"
SECURITY_RULES_FILE="wp-content/themes/engitech-child/.htaccess-security-minimal"

echo -e "${YELLOW}📋 Configuración:${NC}"
echo "  SSH: $STG_SSH"
echo "  Path: $STG_PATH"
echo "  .htaccess: $HTACCESS_PATH"
echo ""

# Paso 1: Verificar conexión SSH
echo -e "${YELLOW}🔍 Paso 1: Verificando conexión SSH...${NC}"
if ssh -o ConnectTimeout=10 "$STG_SSH" "echo 'Conexión exitosa'" &>/dev/null; then
    echo -e "${GREEN}✅ Conexión SSH exitosa${NC}"
else
    echo -e "${RED}❌ Error: No se pudo conectar vía SSH${NC}"
    echo -e "${RED}   Verifica que tengas configurada la clave SSH para WP Engine${NC}"
    echo -e "${RED}   Más info: https://wpengine.com/support/ssh-gateway/${NC}"
    exit 1
fi
echo ""

# Paso 2: Hacer backup del .htaccess actual
echo -e "${YELLOW}💾 Paso 2: Haciendo backup del .htaccess actual...${NC}"
if ssh "$STG_SSH" "cp $HTACCESS_PATH $BACKUP_PATH 2>/dev/null"; then
    echo -e "${GREEN}✅ Backup creado: $(basename $BACKUP_PATH)${NC}"
else
    echo -e "${YELLOW}⚠️  No se pudo crear backup (el archivo podría no existir aún)${NC}"
fi
echo ""

# Paso 3: Verificar si las reglas ya existen
echo -e "${YELLOW}🔍 Paso 3: Verificando si las reglas ya existen...${NC}"
ALREADY_EXISTS=$(ssh "$STG_SSH" "grep -c 'VERTEX RAY - Security Rules' $HTACCESS_PATH 2>/dev/null || echo 0")

if [ "$ALREADY_EXISTS" -gt 0 ]; then
    echo -e "${YELLOW}⚠️  Las reglas de seguridad ya están presentes en el .htaccess${NC}"
    echo -e "${YELLOW}   ¿Deseas reemplazarlas? (y/n)${NC}"
    read -r RESPONSE
    if [[ ! "$RESPONSE" =~ ^[Yy]$ ]]; then
        echo -e "${GREEN}Operación cancelada${NC}"
        exit 0
    fi
    # Remover las reglas antiguas
    echo -e "${YELLOW}🗑️  Removiendo reglas antiguas...${NC}"
    ssh "$STG_SSH" "sed -i '/# VERTEX RAY - Security Rules/,/# END Security Rules/d' $HTACCESS_PATH"
fi
echo ""

# Paso 4: Agregar las nuevas reglas de seguridad
echo -e "${YELLOW}📝 Paso 4: Agregando reglas de seguridad al .htaccess...${NC}"

# Crear un archivo temporal con las reglas
TEMP_RULES="/tmp/vertexray-security-rules-$$.htaccess"

cat > "$TEMP_RULES" << 'EOF'

# ============================================================================
# VERTEX RAY - Security Rules (Added May 2026)
# ============================================================================

# Block xmlrpc.php
<Files xmlrpc.php>
    Order Deny,Allow
    Deny from all
</Files>

# Protect wp-config.php
<Files wp-config.php>
    Order Allow,Deny
    Deny from all
</Files>

# Protect sensitive files
<FilesMatch "^(readme\.html|readme\.txt|license\.txt|wp-config-sample\.php|debug\.log|error_log)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Disable directory listing
Options -Indexes

# Protect .htaccess and hidden files
<FilesMatch "^\.">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Prevent PHP execution in uploads
<Directory "wp-content/uploads">
    <FilesMatch "\.php$">
        Order Allow,Deny
        Deny from all
    </FilesMatch>
</Directory>

# Limit HTTP methods
<LimitExcept GET POST HEAD>
    Order Allow,Deny
    Deny from all
</LimitExcept>

# Block common vulnerability scans
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
    RewriteRule ^(readme|changelog|install|upgrade)\.php$ - [F,L]
    RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2})
    RewriteRule ^(.*)$ - [F,L]
    RewriteCond %{QUERY_STRING} (union.*select|insert.*into|delete.*from|drop.*table) [NC]
    RewriteRule ^(.*)$ - [F,L]
    RewriteCond %{QUERY_STRING} \.\.\/ [NC,OR]
    RewriteCond %{QUERY_STRING} \.(bash|git|hg|log|svn|swp|cvs) [NC]
    RewriteRule ^(.*)$ - [F,L]
    RewriteCond %{HTTP_USER_AGENT} (libwww-perl|wget|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC]
    RewriteRule ^(.*)$ - [F,L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

ServerSignature Off

# ============================================================================
# END Security Rules
# ============================================================================
EOF

# Subir el archivo temporal al servidor y agregarlo al .htaccess
scp "$TEMP_RULES" "$STG_SSH:$STG_PATH/security-rules-temp.txt" &>/dev/null

if [ $? -eq 0 ]; then
    ssh "$STG_SSH" "cat $STG_PATH/security-rules-temp.txt >> $HTACCESS_PATH && rm $STG_PATH/security-rules-temp.txt"
    echo -e "${GREEN}✅ Reglas de seguridad agregadas correctamente${NC}"
else
    echo -e "${RED}❌ Error al subir las reglas${NC}"
    rm "$TEMP_RULES"
    exit 1
fi

# Limpiar archivo temporal local
rm "$TEMP_RULES"
echo ""

# Paso 5: Verificar que el sitio sigue funcionando
echo -e "${YELLOW}🌐 Paso 5: Verificando que el sitio sigue accesible...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://vertexraystg.wpenginepowered.com/)

if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✅ El sitio responde correctamente (HTTP $HTTP_CODE)${NC}"
elif [ "$HTTP_CODE" == "401" ]; then
    echo -e "${GREEN}✅ El sitio responde con protección por contraseña (HTTP $HTTP_CODE)${NC}"
    echo -e "${GREEN}   Esto es normal para staging con auth básica${NC}"
else
    echo -e "${YELLOW}⚠️  El sitio responde con código HTTP $HTTP_CODE${NC}"
    echo -e "${YELLOW}   Verifica manualmente: https://vertexraystg.wpenginepowered.com/${NC}"
fi
echo ""

# Paso 6: Mostrar instrucciones para rollback si es necesario
echo -e "${YELLOW}🔄 Rollback (si algo sale mal):${NC}"
echo "  ssh $STG_SSH"
echo "  cp $BACKUP_PATH $HTACCESS_PATH"
echo ""

echo -e "${GREEN}==================================================================${NC}"
echo -e "${GREEN}✅ Proceso completado exitosamente${NC}"
echo -e "${GREEN}==================================================================${NC}"
echo ""
echo -e "${YELLOW}📋 Próximos pasos:${NC}"
echo "  1. Verificar manualmente el sitio de staging"
echo "  2. Intentar acceder a https://vertexraystg.wpenginepowered.com/xmlrpc.php (debe dar 403)"
echo "  3. Si todo funciona correctamente, repetir el proceso en producción"
echo ""
