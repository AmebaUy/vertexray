#!/bin/bash
###############################################################################
# Script: Limpieza Post-Actualización - Staging Vertex Ray
# Fecha: Mayo 2026
# Descripción: Elimina plugins/temas obsoletos después de actualizaciones
###############################################################################

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN}  Vertex Ray - Limpieza Post-Update${NC}"
echo -e "${GREEN}==========================================${NC}"
echo ""

# Check if running in staging
CURRENT_USER=$(whoami)
if [[ "$CURRENT_USER" != "wpe-user" ]]; then
    echo -e "${RED}ERROR: Este script debe ejecutarse en staging WP Engine${NC}"
    echo -e "${YELLOW}SSH: ssh vertexraystg@vertexraystg.ssh.wpengine.net${NC}"
    exit 1
fi

echo -e "${YELLOW}[1/5] Verificando plugins inactivos...${NC}"
INACTIVE_PLUGINS=$(wp plugin list --status=inactive --field=name)

if [ -z "$INACTIVE_PLUGINS" ]; then
    echo -e "${GREEN}✓ No hay plugins inactivos${NC}"
else
    echo -e "${YELLOW}Plugins inactivos encontrados:${NC}"
    echo "$INACTIVE_PLUGINS"
    
    # Delete Goolytics specifically
    if echo "$INACTIVE_PLUGINS" | grep -q "goolytics-simple-google-analytics"; then
        echo -e "${YELLOW}  Eliminando goolytics-simple-google-analytics...${NC}"
        wp plugin delete goolytics-simple-google-analytics
        echo -e "${GREEN}  ✓ Goolytics eliminado${NC}"
    fi
    
    # List others but don't auto-delete
    echo -e "${YELLOW}  Otros plugins inactivos (revisar manualmente):${NC}"
    echo "$INACTIVE_PLUGINS" | grep -v "goolytics-simple-google-analytics" || echo "  Ninguno"
fi

echo ""
echo -e "${YELLOW}[2/5] Verificando temas innecesarios...${NC}"

# Check for default WordPress themes
DEFAULT_THEMES=("twentytwentyfive" "twentytwentyfour" "twentytwentythree" "twentytwentytwo" "twentytwentyone" "twentytwenty" "twentynineteen")

for theme in "${DEFAULT_THEMES[@]}"; do
    if wp theme is-installed "$theme"; then
        # Check if it's active
        if wp theme list --status=active --field=name | grep -q "^${theme}$"; then
            echo -e "${YELLOW}  ⚠️  $theme está activo, no se eliminará${NC}"
        else
            echo -e "${YELLOW}  Eliminando $theme...${NC}"
            wp theme delete "$theme"
            echo -e "${GREEN}  ✓ $theme eliminado${NC}"
        fi
    fi
done

echo ""
echo -e "${YELLOW}[3/5] Limpiando transients expirados...${NC}"
DELETED_TRANSIENTS=$(wp transient delete --expired 2>&1 | grep -oP '\d+(?= transient)' || echo "0")
echo -e "${GREEN}✓ $DELETED_TRANSIENTS transients eliminados${NC}"

echo ""
echo -e "${YELLOW}[4/5] Limpiando cache de objetos...${NC}"
wp cache flush
echo -e "${GREEN}✓ Cache limpiado${NC}"

echo ""
echo -e "${YELLOW}[5/5] Verificación final - Plugins activos:${NC}"
wp plugin list --status=active --fields=name,version,update

echo ""
echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN}  ✓ Limpieza completada${NC}"
echo -e "${GREEN}==========================================${NC}"
echo ""
echo -e "${YELLOW}Siguiente paso:${NC} Verificar .htaccess security rules"
echo -e "${YELLOW}Comando:${NC} cat /home/wpe-user/sites/vertexraystg/.htaccess | head -50"
