# 🚧 TO BE FINISHED - TAREAS PENDIENTES MAYO 2026

**Estado:** CÓDIGO GENERADO - PENDIENTE DE DEPLOY Y TESTING

---

## ✅ COMPLETADO (Generación de Código + Deploy a Staging)

### TAREA 1: Corrección de Títulos SEO
- ✅ Código generado en functions.php (líneas 18-121)
- ✅ Filtros implementados para eliminar "Engitech"
- ✅ Meta tags Open Graph personalizados
- ✅ **VERIFICADO en staging:** Títulos correctos sin "Engitech"

### TAREA 2A: Reemplazo de Plugin Goolytics
- ✅ Código de tracking GA/GTM en functions.php (líneas 124-216)
- ✅ Detección automática de entornos
- ✅ **VERIFICADO en staging:** Tracking funcionando
- ⚠️ IDs extraídos de header hardcodeado - requieren verificación con Andrés Bolani

### TAREA 2B: WhatsApp - DECISIÓN DE ARQUITECTURA
- ❌ **Código custom ELIMINADO** (reversión 26/05/2026)
- ✅ **MANTENER plugin Joinchat** (creame-whatsapp-me)
- **Razón:** Plugin ligero (6.54 KB), mantenido, configurable
- **Ahorro potencial:** Solo 3 KB - no justifica deuda técnica custom
- **Beneficios:** Updates automáticos, features avanzadas, sin mantenimiento custom
- ✅ **VERIFICADO en staging:** Plugin funcionando correctamente

### TAREA 3: Hardening de Seguridad
- ✅ Código PHP en functions.php (líneas 219-332)
- ✅ Reglas .htaccess generadas (90 líneas en archivo separado)
- ✅ Protección REST API selectiva (Zoho-friendly)
- ⏳ **Reglas .htaccess pendientes de deploy**

### ARCHIVOS FINALES
- ✅ functions.php (332 líneas - reducido de 420 tras eliminar WhatsApp custom)
- ✅ header.php limpio (backup guardado)
- ✅ .htaccess-security (pendiente de aplicar)
- ✅ vertexray-staging-tests.spec.js (22 tests Playwright - pendiente ejecución)
- ✅ add-security-rules-to-stg.sh
- ✅ remove-replaced-plugins.sh (modificar para solo Goolytics)
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
- [x] Código subido con ameba-deploy ✅ (26/05/2026 confirmado)
- [x] WhatsApp actualizado a 59892250103 ✅ (26/05/2026)
- [x] Código custom de WhatsApp eliminado, plugin Joinchat mantenido ✅
- [x] Verificación manual: títulos, tracking, WhatsApp ✅
- [ ] Reglas .htaccess agregadas vía script
- [ ] Tests Playwright ejecutados

### 3. Testing Automatizado
- [ ] Instalar Playwright: `npm install`
- [ ] Ejecutar suite: `npx playwright test vertexray-staging-tests.spec.js`
- [ ] Revisar screenshots generados
- [ ] Validar 22 tests (títulos, tracking, seguridad, regresión)

### 4. Verificación Final Pre-Producción
- [ ] Google Tag Assistant: Confirmar tracking
- [ ] Lighthouse: Verificar mejoras WPO
- [ ] Manual: Probar formularios Contact Form 7
- [ ] Confirmar IDs GA/GTM con Andrés Bolani

### 5. Limpieza de Plugins
- [ ] **Desactivar Goolytics** (ya reemplazado por código custom)
- [ ] **Mantener Joinchat** (decisión arquitectural confirmada)
- [ ] Re-test después de desactivar Goolytics
- [ ] ~~Eliminar plugins~~ (mantener instalados por seguridad, solo desactivar)

### 6. Deploy a Producción (Cuando se apruebe)
- [ ] Merge rama `chore/mantenimiento-mayo-2026` → `dev`
- [ ] Push a `origin/dev`
- [ ] Deploy con: `npm run push-theme-prod engitech-child`
- [ ] O usar Local by Flywheel "Push to Live"
- [ ] Aplicar reglas .htaccess en producción
- [ ] Desactivar Goolytics en producción
- [ ] Smoke test completo en vertexray.com

### 7. Post-Deploy
- [ ] Forzar reindexación en Google Search Console
- [ ] Verificar títulos en resultados de búsqueda (puede tardar días)
- [ ] Monitorear Analytics por 48h
- [ ] Confirmar WhatsApp funcionando en prod
- [ ] Backup final con UpdraftPlus

---

## 📋 NOTAS TÉCNICAS
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
