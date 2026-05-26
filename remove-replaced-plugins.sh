#!/bin/bash

# Script para desactivar y eliminar plugins reemplazados por código personalizado
# Ejecutar SOLO después de verificar que el código nuevo funciona en staging

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}==================================================================${NC}"
echo -e "${YELLOW}Desactivación y eliminación de plugins reemplazados${NC}"
echo -e "${YELLOW}==================================================================${NC}"
echo ""

# Plugins a eliminar
PLUGINS=("goolytics-simple-google-analytics" "creame-whatsapp-me")

echo -e "${RED}⚠️  ADVERTENCIA IMPORTANTE:${NC}"
echo -e "${RED}   Este script eliminará permanentemente los siguientes plugins:${NC}"
echo -e "${RED}   1. Goolytics - Simple Google Analytics${NC}"
echo -e "${RED}   2. Joinchat (creame-whatsapp-me)${NC}"
echo ""
echo -e "${YELLOW}   Antes de continuar, asegúrate de:${NC}"
echo -e "${YELLOW}   - Haber verificado que el código nuevo funciona correctamente${NC}"
echo -e "${YELLOW}   - Estar en el entorno correcto (staging o producción)${NC}"
echo -e "${YELLOW}   - Tener un backup reciente${NC}"
echo ""
read -p "¿Deseas continuar? (escribe 'SI' para confirmar): " CONFIRM

if [ "$CONFIRM" != "SI" ]; then
    echo -e "${GREEN}Operación cancelada por seguridad${NC}"
    exit 0
fi

echo ""
echo -e "${YELLOW}📋 Generando reporte de plugins antes de eliminar...${NC}"

# Crear archivo de reporte
REPORT_FILE="plugin-removal-report-$(date +%Y%m%d-%H%M%S).txt"

echo "==================================================================" > $REPORT_FILE
echo "REPORTE DE ELIMINACIÓN DE PLUGINS - Vertex Ray" >> $REPORT_FILE
echo "Fecha: $(date)" >> $REPORT_FILE
echo "==================================================================" >> $REPORT_FILE
echo "" >> $REPORT_FILE

# Obtener información de los plugins antes de eliminarlos
for PLUGIN in "${PLUGINS[@]}"; do
    echo -e "${YELLOW}Obteniendo información de: $PLUGIN${NC}"
    
    echo "Plugin: $PLUGIN" >> $REPORT_FILE
    echo "---" >> $REPORT_FILE
    
    # Obtener versión y estado
    PLUGIN_INFO=$(wp plugin get $PLUGIN --format=json 2>/dev/null)
    
    if [ $? -eq 0 ]; then
        echo "$PLUGIN_INFO" | grep -E "name|version|status|title" >> $REPORT_FILE
        echo "" >> $REPORT_FILE
    else
        echo "Plugin no encontrado o WP-CLI no disponible" >> $REPORT_FILE
        echo "" >> $REPORT_FILE
    fi
done

echo "" >> $REPORT_FILE
echo "==================================================================" >> $REPORT_FILE
echo "RAZÓN DE ELIMINACIÓN" >> $REPORT_FILE
echo "==================================================================" >> $REPORT_FILE
echo "" >> $REPORT_FILE
echo "1. Goolytics: Reemplazado por código nativo en functions.php" >> $REPORT_FILE
echo "   - Tracking de GA4/GTM ahora se inserta vía wp_head()" >> $REPORT_FILE
echo "   - Solo se ejecuta en producción (no en local/staging)" >> $REPORT_FILE
echo "   - Reducción de ~30-50KB por carga" >> $REPORT_FILE
echo "" >> $REPORT_FILE
echo "2. Joinchat: Reemplazado por botón HTML/CSS personalizado" >> $REPORT_FILE
echo "   - Botón flotante en HTML puro con CSS inline" >> $REPORT_FILE
echo "   - Sin scripts JS innecesarios" >> $REPORT_FILE
echo "   - Reducción de ~20-30KB por carga" >> $REPORT_FILE
echo "" >> $REPORT_FILE
echo "Beneficio total: -50-80KB por carga, -2-4 peticiones HTTP" >> $REPORT_FILE
echo "" >> $REPORT_FILE

echo -e "${GREEN}✅ Reporte generado: $REPORT_FILE${NC}"
echo ""

# Desactivar plugins
echo -e "${YELLOW}📥 Paso 1: Desactivando plugins...${NC}"
for PLUGIN in "${PLUGINS[@]}"; do
    echo -e "  Desactivando: $PLUGIN"
    wp plugin deactivate $PLUGIN 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}  ✅ $PLUGIN desactivado${NC}"
        echo "✅ $PLUGIN desactivado exitosamente" >> $REPORT_FILE
    else
        echo -e "${RED}  ❌ Error al desactivar $PLUGIN${NC}"
        echo "❌ Error al desactivar $PLUGIN" >> $REPORT_FILE
    fi
done

echo ""
echo -e "${YELLOW}⏸️  Pausa de seguridad de 10 segundos...${NC}"
echo -e "${YELLOW}   (Verifica que el sitio sigue funcionando correctamente)${NC}"
sleep 10

echo ""
echo -e "${YELLOW}🗑️  Paso 2: Eliminando plugins permanentemente...${NC}"
read -p "¿Confirmas la eliminación permanente? (escribe 'ELIMINAR' para confirmar): " CONFIRM_DELETE

if [ "$CONFIRM_DELETE" != "ELIMINAR" ]; then
    echo -e "${YELLOW}⚠️  Eliminación cancelada. Los plugins están desactivados pero no eliminados.${NC}"
    echo "⚠️ Eliminación cancelada por el usuario" >> $REPORT_FILE
    exit 0
fi

for PLUGIN in "${PLUGINS[@]}"; do
    echo -e "  Eliminando: $PLUGIN"
    wp plugin delete $PLUGIN 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}  ✅ $PLUGIN eliminado${NC}"
        echo "✅ $PLUGIN eliminado exitosamente" >> $REPORT_FILE
    else
        echo -e "${RED}  ❌ Error al eliminar $PLUGIN${NC}"
        echo "❌ Error al eliminar $PLUGIN" >> $REPORT_FILE
    fi
done

echo "" >> $REPORT_FILE
echo "==================================================================" >> $REPORT_FILE
echo "VERIFICACIÓN POST-ELIMINACIÓN" >> $REPORT_FILE
echo "==================================================================" >> $REPORT_FILE
echo "" >> $REPORT_FILE

# Verificar que el sitio sigue funcionando
echo ""
echo -e "${YELLOW}🔍 Paso 3: Verificando funcionalidad del sitio...${NC}"

# Verificar que el sitio responde
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://vertexray.local/ 2>/dev/null || echo "000")

if [ "$HTTP_CODE" == "200" ] || [ "$HTTP_CODE" == "301" ]; then
    echo -e "${GREEN}✅ El sitio responde correctamente (HTTP $HTTP_CODE)${NC}"
    echo "✅ Sitio responde con HTTP $HTTP_CODE" >> $REPORT_FILE
else
    echo -e "${YELLOW}⚠️  El sitio responde con código HTTP $HTTP_CODE${NC}"
    echo "⚠️ Sitio responde con HTTP $HTTP_CODE" >> $REPORT_FILE
fi

# Listar plugins activos restantes
echo ""
echo -e "${YELLOW}📦 Plugins activos restantes:${NC}"
echo "" >> $REPORT_FILE
echo "PLUGINS ACTIVOS DESPUÉS DE LA ELIMINACIÓN:" >> $REPORT_FILE
wp plugin list --status=active --format=table | tee -a $REPORT_FILE

echo ""
echo -e "${GREEN}==================================================================${NC}"
echo -e "${GREEN}✅ Proceso completado${NC}"
echo -e "${GREEN}==================================================================${NC}"
echo ""
echo -e "${YELLOW}📋 Reporte guardado en: $REPORT_FILE${NC}"
echo ""
echo -e "${YELLOW}📝 Próximos pasos:${NC}"
echo -e "  1. Verificar que el tracking de GA/GTM funciona (usar Google Tag Assistant)"
echo -e "  2. Verificar que el botón de WhatsApp es visible y funcional"
echo -e "  3. Revisar los logs de errores en wp-content/debug.log"
echo -e "  4. Incluir el archivo $REPORT_FILE en el reporte de mantenimiento"
echo ""
