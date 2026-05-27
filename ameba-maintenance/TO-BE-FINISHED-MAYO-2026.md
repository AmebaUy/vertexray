# 🚧 TO BE FINISHED - TAREAS PENDIENTES MAYO 2026

**Estado:** Sesión 27/05/2026 — Auditoría completa ejecutada | Pendiente: deploy prod + limpieza BD prod (manual) + Playwright (próximo mantenimiento)

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

### TAREA 4: CF7 — Mensajes en Español
- ✅ Filtro `gettext` en functions.php (commit `c3f9511`) — 21 mensajes traducidos
- ✅ `_messages` post meta actualizado en DB local via WP-CLI (10 formularios)
- ✅ `_messages` post meta actualizado en DB staging via WP-CLI SSH
- ⚠️ Verificar encoding en staging (acentos en mensajes) si no se ven correctamente

### TAREA 5: Plugin wp-migrate-db-pro-compatibility eliminado
- ✅ `wp-content/mu-plugins/wp-migrate-db-pro-compatibility.php` eliminado (local + staging)

### TAREA 6: Performance & Cleanup (sesión 27/05/2026)
- ✅ `robots.txt` físico creado (virtual → archivo real con reglas de seguridad)
- ✅ `WP_POST_REVISIONS = 5` en wp-config.php
- ✅ functions.php TAREA 4: comments/pingbacks deshabilitados (código PHP + admin UI)
- ✅ functions.php TAREA 4: emojis WP deshabilitados (~10 KB JS+CSS ahorrados)
- ✅ functions.php TAREA 4: oEmbed discovery links deshabilitados
- ✅ functions.php TAREA 4: preconnect hints GTM/GA (solo producción)
- ✅ Google Site Kit eliminado local (duplicaba GA — `analytics` + `analytics-4` activos)
- ✅ BD local limpiada: 189 spam, 189 commentmeta huérfanos, 8167 postmeta huérfanos, 30 tablas optimizadas
- ✅ Transients local: 10 eliminados
- ✅ `default_comment_status = closed` + `default_ping_status = closed` en DB local
- ✅ Staging: transients (3), comment_status, ping_status actualizados via SSH
- ✅ `ameba-maintenance/backups/db-cleanup.php` creado (WP eval-file para BD local)
- ✅ Tests Playwright: e2e-flows.spec.js (Fix Iteration 5), forms.spec.js, security.spec.js, playwright.config.js actualizados

---

## ⏳ PENDIENTE DE FINALIZACIÓN

### 1. Pendiente para correr manualmente en PROD

> Ver **PASO 4** en la sección "Deploy a Producción" más abajo — tiene el comando completo.

### 2. Configuración Pre-Deploy (CRÍTICO)

#### ⚠️ VERIFICACIONES PENDIENTES CON ANDRÉS BOLANI:
- [ ] **IDs de Tracking GA/GTM:** Confirmar `UA-2468946-1` y `GTM-NPL5XMZ`
  - **Estado actual:** Usando IDs extraídos de header.php hardcodeado
  - **Ubicación:** functions.php líneas ~157-158
  - **Nota:** Site Kit eliminado local — ya no hay duplicación. IDs quedan en functions.php únicamente.
  - **Contacto:** Andrés Bolani (dev)

### 3. Deploy a Staging
- [x] Código subido con ameba-deploy ✅ (26/05/2026 confirmado)
- [x] WhatsApp actualizado a 59892250103 ✅
- [x] Código custom de WhatsApp eliminado, plugin Joinchat mantenido ✅
- [x] Verificación manual: títulos, tracking, WhatsApp ✅
- [x] Transients + comment/ping_status cerrados via SSH ✅ (27/05/2026)
- [ ] Staging BD queries + optimize pendiente (comandos en sección 1 arriba)
- [ ] Tests Playwright (postergar a próximo mantenimiento)

### 4. POSTPONED (próximo mantenimiento)
- [ ] Playwright run completo (e2e-flows, forms, security)
- [ ] Lighthouse CLI en producción
- [ ] Akismet verificación en producción
- [ ] GA/GTM IDs confirmación con Andrés Bolani
- [ ] Image thumbnails audit
- [ ] JSON-LD schema audit
- [ ] `fetchpriority="high"` en hero image
- [ ] Speculation Rules API snippet

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

> ⚠️ **PRINCIPIO FUNDAMENTAL:** Nunca pisar prod con stg ni con local en bloque.
> Solo operaciones scoped: theme via rsync, .htaccess via append SSH, plugins via WP-CLI SSH.

#### PASO 0 — Prerequisitos (verificar antes de empezar)
- [x] Tests Playwright pasando en staging (críticos: forms, security, e2e) — ✅ RUN 4 en curso 26/05/2026 (objetivo: 0 fallos)
- [x] QA visual en producción — ✅ OK manual 26/05/2026
- [ ] QA funcional (formularios, WhatsApp, tracking) — pendiente
- [ ] IDs GA/GTM confirmados con Andrés Bolani
- [ ] .htaccess staging verificado (security rules presentes)

> ℹ️ **PASOs 1-7 diferidos a FASE 9** del ciclo de mantenimiento mensual.
> No se despliega a prod hasta completar QA funcional + tests Playwright.
> Este bloque queda como insumo/referencia para ese momento.

#### PASO 1 — Backup de producción

```bash
# SSH a producción
ssh vertexray@vertexray.ssh.wpengine.net

# Exportar DB (guarda en /sites/vertexray/)
wp db export ~/backup-prod-pre-mayo2026-$(date +%Y%m%d).sql --path=/sites/vertexray

# Verificar que se creó
ls -lh ~/backup-prod-pre-mayo2026*.sql
exit
```

> Alternativamente desde wp-admin: **UpdraftPlus → Backup Now** (incluye archivos + DB)

#### PASO 2 — Deploy del child theme (SCOPED, solo engitech-child)

```bash
# Desde local, en el directorio ameba-deploy
cd "c:\Users\mvall\Local Sites\vertexray\app\public\ameba-deploy"

# Pushea SOLO wp-content/themes/engitech-child/ via rsync
# No toca DB, plugins, uploads ni otros temas
npm run push-theme-prod engitech-child
```

**Qué hace exactamente:**
- `rsync -avz --delete` de `local/themes/engitech-child/` → `prod/themes/engitech-child/`
- Excluye `.git`, `node_modules`, `*.log`
- Solo archivos del child theme: `functions.php`, `header.php`, `style.css`, etc.
- El flag `--delete` elimina en prod archivos que ya no existen en local (dentro del theme)

#### PASO 3 — Seguridad en WP Engine (Nginx)

> ⚠️ **WP Engine usa Nginx, no Apache.** Las directivas Apache del `.htaccess`
> (`<Files>`, `Order Deny,Allow`, `Header always set`, `LimitExcept`) **no son
> procesadas por Nginx** y no tienen efecto. El hardening real ya está cubierto
> por `functions.php` a nivel PHP, que SÍ funciona en WP Engine.

**Cobertura real de seguridad (PHP-level — funciona en WP Engine):**

| Protección | Implementación | Estado |
|------------|---------------|--------|
| xmlrpc deshabilitado | `add_filter('xmlrpc_enabled', '__return_false')` | ✅ functions.php línea 224 |
| REST API /users bloqueado | `add_filter('rest_endpoints', ...)` | ✅ functions.php línea 269 |
| Security headers | `add_action('send_headers', ...)` | ✅ functions.php línea 314 |
| DISALLOW_FILE_EDIT | `define('DISALLOW_FILE_EDIT', true)` | ✅ functions.php línea 319 |
| Author archives bloqueados | `template_redirect` hook | ✅ functions.php línea 340 |
| Versión WP oculta | Filtros generator meta + RSS | ✅ functions.php |
| Errores login genéricos | Filter `login_errors` | ✅ functions.php |

**Lo único que sí va en .htaccess para WP Engine (ya están en el archivo base):**
- Las reglas de `RewriteRule` de WordPress (BEGIN WordPress / END WordPress)
- El bloque de redirección HTTPS (si aplica)
- El bloque `AMEBA_UPLOADS_FALLBACK` (solo en local)

**No aplicar el bloque de Apache security rules** — no tiene efecto en Nginx.
WP Engine tiene su propio WAF y bloqueos a nivel plataforma (xmlrpc, login brute force, etc.)

> Si en el futuro se necesita hardening adicional a nivel servidor:
> usar el panel de WP Engine → Security → Access Rules, o contactar soporte WPE.

#### PASO 3B — Solo si el .htaccess de prod NO tiene las reglas WordPress básicas

```bash
# SSH a producción
ssh vertexray@vertexray.ssh.wpengine.net

# Verificar estado actual
wc -l ~/sites/vertexray/.htaccess
grep -c "BEGIN WordPress" ~/sites/vertexray/.htaccess 2>/dev/null || echo "Bloque WordPress NO presente"

# Si todo está bien (tiene BEGIN WordPress), no tocar nada
# El .htaccess en WP Engine solo necesita el bloque WordPress estándar
exit
```



#### PASO 4 — Limpieza post-deploy en producción (27/05/2026) ✅ EJECUTADO

> ✅ Completado el 27/05/2026. Resultados:
> - 3 transients eliminados
> - `default_ping_status` actualizado a `closed` (prod lo tenía open)
> - 189 comentarios spam eliminados (IDs 9–197)
> - 0 commentmeta huérfanos
> - 8167 postmeta huérfanos eliminados
> - 30 tablas optimizadas
> - 10 opciones Site Kit eliminadas de wp_options
> - Goolytics/WPMDB: ya limpios (0 filas)

**Nota:** rsync `--delete` del `push-code-prod` elimina los archivos del plugin pero NO ejecuta el uninstall hook. Siempre limpiar wp_options/wp_usermeta manualmente después de eliminar plugins vía rsync.

**Comando de referencia para próximas veces (heredoc — evita que el shell local procese las comillas):**

```bash
ssh -o StrictHostKeyChecking=no vertexray@vertexray.ssh.wpengine.net << 'ENDSSH'
wp transient delete --all
wp option update default_comment_status closed
wp option update default_ping_status closed
SPAM_IDS=$(wp comment list --status=spam --format=ids)
[ -n "$SPAM_IDS" ] && wp comment delete $SPAM_IDS --force || echo "No spam comments"
wp db query "DELETE FROM wp_commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM wp_comments)"
wp db query "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts)"
wp db optimize
wp cache flush
ENDSSH
```

**Cleanup de opciones huérfanas por plugin eliminado vía rsync:**
```bash
ssh -o StrictHostKeyChecking=no vertexray@vertexray.ssh.wpengine.net << 'ENDSSH'
wp db query "DELETE FROM wp_options WHERE option_name LIKE 'PLUGIN_PREFIX%'"
wp db query "DELETE FROM wp_usermeta WHERE meta_key LIKE 'PLUGIN_PREFIX%'"
ENDSSH
```

#### PASO 6 — Verificación de estabilidad post-actualización mayor (WP 7.0 + PHP 8.4)

> Ejecutar **siempre** después de updates mayores (WP Core, Meta Box, Elementor, Kirki).
> El script y el protocolo QA fueron generados el 26/05/2026 para la actualización WP 7.0.

##### 6A — Script automático `verify-env.php`

El script vive en la raíz del proyecto (`verify-env.php`) y se ejecuta via WP-CLI.
Cubre: CPT ot_portfolio + postmeta integridad, compatibilidad PHP 8.4 en plugins sin actualizar,
error logs recientes, estado OAuth de Zoho Campaigns.

```bash
# Subir a staging
scp "c:/Users/mvall/Local Sites/vertexray/app/public/verify-env.php" \
    vertexraystg@vertexraystg.ssh.wpengine.net:~/sites/vertexraystg/

# Ejecutar en staging
ssh vertexraystg@vertexraystg.ssh.wpengine.net \
  "cd ~/sites/vertexraystg && wp eval-file verify-env.php"

# Ejecutar en producción (solo si staging fue limpio)
scp "c:/Users/mvall/Local Sites/vertexray/app/public/verify-env.php" \
    vertexray@vertexray.ssh.wpengine.net:~/sites/vertexray/

ssh vertexray@vertexray.ssh.wpengine.net \
  "cd ~/sites/vertexray && wp eval-file verify-env.php"

# BORRAR el script al terminar (no dejar expuesto)
ssh vertexraystg@vertexraystg.ssh.wpengine.net "rm ~/sites/vertexraystg/verify-env.php"
ssh vertexray@vertexray.ssh.wpengine.net "rm ~/sites/vertexray/verify-env.php"
```

**Output esperado (sin issues):**
- `✓ CPT 'ot_portfolio' registrado correctamente`
- `✓ Meta Box 5.12.0+ (actualizado correctamente)`
- `✓ Sin postmeta huérfanos detectados`
- `✓ soo-demo-importer — sin patrones deprecated`
- `✓ ot_portfolio — sin patrones deprecated`
- `✓ access_token: SET (xxx chars)` (Zoho autenticado)
- `✓ Sin flag de error de conexión`

**Si reporta `✗` o `⚠`:** Compartir output completo en el próximo ciclo de soporte.

##### 6B — Protocolo QA Visual en Navegador (F12 DevTools)

Páginas a revisar: Home `/`, Projects, Contact Us, Join Our Team.

**Meta Box 5.12 + Gutenberg — Console tab:**
Errores que delatan incompatibilidad con React de Gutenberg:
- `Invalid hook call. Hooks can only be called inside of the body of a function component` → dos versiones de React colisionando
- `Warning: ReactDOM.render is no longer supported in React 18` → Meta Box usa API vieja
- `TypeError: wp.element.createElement is not a function` → order de dependencias roto

Trigger: Abrir cualquier `ot_portfolio` en editor Gutenberg y revisar Console.

**Kirki 6.0.9 — Elements tab:**
1. F12 → Elements → buscar en `<head>` un `<style id="kirki-customizer-styles">` o similar.
2. Verificar que contiene variables del tema Engitech: `--primary-color`, `--body-font-family`, etc.
3. Si el `<style>` está vacío o ausente → Kirki 6 rompió la inyección de variables CSS.
4. En el inspector de estilos de un `<h1>`: verificar que `font-family` y `color` vienen de selector de Kirki, no del default del browser.

**Elementor 4.1.0 — Console + Network:**
- Console: `elementorFrontend.hooks is not defined` → Elementor no inicializó correctamente
- Network → JS → verificar `elementor-frontend.min.js` carga con status 200 (no 404)
- Si 404 → limpiar caché LiteSpeed + `Elementor → Herramientas → Regenerar Archivos CSS`

**Cloudflare Turnstile + CF7 6.1.6 — Network tab:**
1. F12 → Network → activar **Preserve log**.
2. Completar y enviar el formulario de contacto.
3. Filtrar por `feedback` → click en `POST .../contact-forms/{ID}/feedback`.
4. **Payload tab:** buscar campo `_wpcf7_turnstile_response` con valor tipo `"0.AbCd..."` (~800 chars). Si está vacío → Turnstile no resolvió antes del submit.
5. **Response tab (JSON esperado):** `{"status":"mail_sent","message":"Tu mensaje ha sido enviado..."}`.
   - `validation_failed` con `_wpcf7_turnstile_response` → token vacío o inválido
   - `spam` → Turnstile keys de prod/stg mezcladas (verificar en CF7 → Integración)
   - `mail_failed` → problema SMTP, no de Turnstile

**Borde naranja en CF7:** Widget Turnstile no completó el challenge. Causas: timeout, modo Invisible vs. Managed mal configurado, o adblocker bloqueó `challenges.cloudflare.com`.



Verificar manualmente en https://www.vertexray.com/:

```bash
# Tests rápidos desde terminal (reemplazar con credenciales si hay auth básica en prod)
PROD="https://www.vertexray.com"

# 1. Sitio responde
curl -s -o /dev/null -w "Home HTTP: %{http_code}\n" "$PROD/"

# 2. Archivos sensibles bloqueados
curl -s -o /dev/null -w "xmlrpc.php (debe 403): %{http_code}\n" "$PROD/xmlrpc.php"
curl -s -o /dev/null -w "readme.html (debe 403): %{http_code}\n" "$PROD/readme.html"
curl -s -o /dev/null -w "wp-config (debe 403): %{http_code}\n" "$PROD/wp-config.php"

# 3. REST API /users bloqueado
curl -s -o /dev/null -w "REST users (debe 401): %{http_code}\n" "$PROD/wp-json/wp/v2/users"

# 4. Security headers presentes
curl -s -I "$PROD/" | grep -i "x-frame-options\|x-content-type\|referrer-policy"
```

**Verificación manual en navegador:**
- [ ] Home carga sin errores de JS/CSS
- [ ] Título: "Vertex Ray – Creating Experience" (sin "Engitech")
- [ ] Botón WhatsApp visible → abre wa.me/59892250103
- [ ] Formulario Contact Us funciona (enviar test)
- [ ] DevTools → Console → sin errores
- [ ] DevTools → Network → `gtm.js` y `analytics.js` presentes
- [ ] DevTools → Console → `dataLayer` con datos

### 7. Post-Deploy
- [ ] Forzar reindexación en Google Search Console
  - URL Inspection → Design, Projects, Contact Us, Join Our Team → Request Indexing
- [ ] Verificar títulos en resultados de búsqueda (puede tardar 2-7 días)
- [ ] Monitorear Analytics por 48h (Google Analytics Real Time)
- [ ] Confirmar WhatsApp funcionando en prod
- [ ] Backup final con UpdraftPlus post-deploy
- [ ] Commit y push de cualquier ajuste final

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
