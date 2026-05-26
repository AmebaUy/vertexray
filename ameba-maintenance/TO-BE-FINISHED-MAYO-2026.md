# 🚧 TO BE FINISHED - TAREAS PENDIENTES MAYO 2026

**Estado:** CÓDIGO GENERADO - PENDIENTE DE DEPLOY Y TESTING

---

## ✅ COMPLETADO (Generación de Código)

### TAREA 1: Corrección de Títulos SEO
- ✅ Código generado en functions.php (líneas 18-110)
- ✅ Filtros implementados para eliminar "Engitech"
- ✅ Meta tags Open Graph personalizados

### TAREA 2A: Reemplazo de Plugin Goolytics
- ✅ Código de tracking GA/GTM en functions.php (líneas 113-185)
- ✅ Detección automática de entornos
- ⚠️ IDs extraídos de header hardcodeado - requieren verificación

### TAREA 2B: Reemplazo de Plugin Joinchat
- ✅ Botón HTML/CSS/SVG generado en functions.php (líneas 188-296)
- ⚠️ Número de WhatsApp es PLACEHOLDER - requiere actualización

### TAREA 3: Hardening de Seguridad
- ✅ Código PHP en functions.php (líneas 303-398)
- ✅ Reglas .htaccess generadas (90 líneas)
- ✅ Protección REST API selectiva (Zoho-friendly)

### ARCHIVOS CREADOS
- ✅ functions.php (420 líneas)
- ✅ header.php limpio (backup guardado)
- ✅ .htaccess-security
- ✅ vertexray-staging-tests.spec.js (22 tests Playwright)
- ✅ add-security-rules-to-stg.sh
- ✅ remove-replaced-plugins.sh
- ✅ INSTRUCCIONES-IMPLEMENTACION.md
- ✅ RESUMEN-IMPLEMENTACION.md

---

## ⏳ PENDIENTE DE FINALIZACIÓN

### 1. Configuración Pre-Deploy (CRÍTICO)

#### ⚠️ VERIFICACIONES PENDIENTES CON ANDRÉS BOLANI:
- [ ] **WhatsApp:** Confirmar que `59892250103` (Uruguay) es el número correcto
  - **Estado actual:** Actualizado a `59892250103` obtenido de producción
  - **Ubicación:** functions.php línea ~220
  - **Contacto:** Andrés Bolani (dev)
  
- [ ] **IDs de Tracking GA/GTM:** Confirmar `UA-2468946-1` y `GTM-NPL5XMZ`
  - **Estado actual:** Usando IDs extraídos de header.php hardcodeado
  - **Ubicación:** functions.php líneas ~157-158
  - **Ambigüedad detectada:** Site Kit plugin instalado (posibles IDs alternativos)
  - **Verificación necesaria:** Revisar Google Analytics/GTM admin console
  - **Contacto:** Andrés Bolani (dev)

### 2. Deploy a Staging
- [x] Código subido con ameba-deploy ✅ (26/05/2026 confirmado por usuario)
- [x] WhatsApp actualizado a 59892250103 ✅ (26/05/2026)
- [ ] Reglas .htaccess agregadas vía script
- [ ] Verificar archivos en servidor

### 3. Testing Automatizado en Staging
- [ ] Instalar Playwright si no está
- [ ] Ejecutar 22 tests: `npx playwright test vertexray-staging-tests.spec.js`
- [ ] Verificar todos pasan
- [ ] Revisar screenshots generados

### 4. Verificaciones Manuales en Staging
- [ ] Tracking con Google Tag Assistant
- [ ] Botón WhatsApp en desktop y móvil
- [ ] Títulos sin "Engitech"
- [ ] Formulario de contacto
- [ ] Integración Zoho (si aplica)

### 5. Eliminación de Plugins en Staging
- [ ] Desactivar Goolytics y Joinchat
- [ ] Verificar sitio funciona
- [ ] Eliminar plugins
- [ ] Re-ejecutar tests
- [ ] Generar reporte de eliminación

### 6. Deploy a Producción
- [ ] Backup completo
- [ ] Subir código
- [ ] Agregar .htaccess
- [ ] Desactivar/eliminar plugins
- [ ] Limpiar caché

### 7. Verificación Final en Producción
- [ ] Re-ejecutar tests Playwright
- [ ] Verificar GA en tiempo real
- [ ] Probar formularios
- [ ] Verificar WhatsApp
- [ ] Monitorear 48-72h

### 8. Documentación
- [ ] Actualizar agents.md
- [ ] Generar reporte conjugado Abril + Mayo
- [ ] Screenshots before/after
- [ ] Commit y PR

---

## ⚠️ BLOQUEADORES IDENTIFICADOS

1. **Número de WhatsApp:** PLACEHOLDER temporal (línea 228 functions.php)
2. **IDs de GA/GTM:** Extraídos de header hardcodeado, puede haber discrepancia con Site Kit
3. **Integración Zoho:** Credenciales pendientes
4. **Staging:** .htaccess aún no agregado

---

## 📊 BENEFICIOS ESPERADOS (Al completar)

- ⚡ -50-80KB por carga (-2 plugins)
- 🎯 Títulos SEO sin "Engitech"
- 🔒 Seguridad mejorada sin Wordfence

---

**Próxima acción:** Iniciar FASE 0 del proceso ameba-monthly-support-prompt.md
