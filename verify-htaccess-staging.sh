#!/bin/bash
###############################################################################
# Script: Verificar y Aplicar .htaccess Security Rules - Staging
# Fecha: Mayo 2026
# Descripción: Verifica si las reglas de seguridad están completas en staging
#              y las aplica si faltan
###############################################################################

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN}  .htaccess Security Verification${NC}"
echo -e "${GREEN}==========================================${NC}"
echo ""

# Check environment
CURRENT_USER=$(whoami)
if [[ "$CURRENT_USER" != "wpe-user" ]]; then
    echo -e "${RED}ERROR: Este script debe ejecutarse en staging WP Engine${NC}"
    exit 1
fi

HTACCESS_PATH="/home/wpe-user/sites/vertexraystg/.htaccess"

echo -e "${YELLOW}[1/4] Verificando archivo .htaccess...${NC}"

if [ ! -f "$HTACCESS_PATH" ]; then
    echo -e "${RED}✗ .htaccess no encontrado en $HTACCESS_PATH${NC}"
    exit 1
fi

LINE_COUNT=$(wc -l < "$HTACCESS_PATH")
echo -e "${YELLOW}  Líneas actuales: $LINE_COUNT${NC}"

if [ "$LINE_COUNT" -lt 100 ]; then
    echo -e "${RED}  ⚠️  ALERTA: .htaccess solo tiene $LINE_COUNT líneas${NC}"
    echo -e "${RED}  Esperado: ~154 líneas (con security rules)${NC}"
    echo -e "${YELLOW}  Las reglas de seguridad parecen INCOMPLETAS${NC}"
else
    echo -e "${GREEN}  ✓ .htaccess parece completo ($LINE_COUNT líneas)${NC}"
fi

echo ""
echo -e "${YELLOW}[2/4] Buscando marcador de security rules...${NC}"

if grep -q "VERTEX RAY - REGLAS DE SEGURIDAD" "$HTACCESS_PATH"; then
    MARKER_LINE=$(grep -n "VERTEX RAY - REGLAS DE SEGURIDAD" "$HTACCESS_PATH" | cut -d: -f1)
    echo -e "${GREEN}  ✓ Marcador encontrado en línea $MARKER_LINE${NC}"
    
    # Check if rules are complete after marker
    LINES_AFTER_MARKER=$((LINE_COUNT - MARKER_LINE))
    echo -e "${YELLOW}  Líneas después del marcador: $LINES_AFTER_MARKER${NC}"
    
    if [ "$LINES_AFTER_MARKER" -lt 80 ]; then
        echo -e "${RED}  ⚠️  PROBLEMA: Solo hay $LINES_AFTER_MARKER líneas después del marcador${NC}"
        echo -e "${RED}  Esperado: ~90 líneas de security rules${NC}"
        RULES_INCOMPLETE=true
    else
        echo -e "${GREEN}  ✓ Security rules parecen completas${NC}"
        RULES_INCOMPLETE=false
    fi
else
    echo -e "${RED}  ✗ Marcador 'VERTEX RAY - REGLAS DE SEGURIDAD' NO encontrado${NC}"
    RULES_INCOMPLETE=true
fi

echo ""
echo -e "${YELLOW}[3/4] Verificando reglas críticas específicas...${NC}"

CRITICAL_RULES=(
    "xmlrpc.php"
    "wp-config.php"
    "Options -Indexes"
    "FilesMatch.*\.php"
    "X-Frame-Options"
)

MISSING_RULES=()

for rule in "${CRITICAL_RULES[@]}"; do
    if grep -q "$rule" "$HTACCESS_PATH"; then
        echo -e "${GREEN}  ✓ $rule${NC}"
    else
        echo -e "${RED}  ✗ $rule FALTANTE${NC}"
        MISSING_RULES+=("$rule")
    fi
done

echo ""
echo -e "${YELLOW}[4/4] Resultado del diagnóstico:${NC}"
echo -e "${YELLOW}================================${NC}"

if [ "$RULES_INCOMPLETE" = true ] || [ ${#MISSING_RULES[@]} -gt 0 ]; then
    echo -e "${RED}⚠️  .htaccess NECESITA ACTUALIZARSE${NC}"
    echo ""
    echo -e "${YELLOW}Reglas faltantes:${NC}"
    if [ ${#MISSING_RULES[@]} -gt 0 ]; then
        for rule in "${MISSING_RULES[@]}"; do
            echo "  - $rule"
        done
    fi
    echo ""
    echo -e "${YELLOW}ACCIÓN REQUERIDA:${NC}"
    echo "1. Hacer backup del .htaccess actual:"
    echo "   cp $HTACCESS_PATH ${HTACCESS_PATH}.backup-$(date +%Y%m%d)"
    echo ""
    echo "2. Desde tu máquina local, copiar el .htaccess completo:"
    echo "   scp c:/Users/mvall/Local\\ Sites/vertexray/app/public/.htaccess vertexraystg@vertexraystg.ssh.wpengine.net:/home/wpe-user/sites/vertexraystg/"
    echo ""
    echo "3. O aplicar manualmente desde local:"
    echo "   cat wp-content/themes/engitech-child/.htaccess-security >> /home/wpe-user/sites/vertexraystg/.htaccess"
else
    echo -e "${GREEN}✓ .htaccess está completo y las reglas de seguridad están aplicadas${NC}"
fi

echo ""
echo -e "${YELLOW}Para revisar el contenido completo:${NC}"
echo "cat $HTACCESS_PATH"
