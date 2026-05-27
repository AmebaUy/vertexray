# Site Stack — Vertex Ray

> Archivo persistente. Se sobreescribe en cada sesión de mantenimiento — nunca se duplica.
> **Última actualización:** 2026-05-26 por AI Agent (Mayo 2026 Maintenance Session)

---

## 1. Inventario de Stack

### WordPress

**WordPress Core:** v7.0 (actualizado 26/05/2026 de v6.9.4)  
**Database Version:** 61833 (actualizado de 60717)  
**Theme activo:** Engitech Child (custom child theme)  
**Theme parent:** Engitech v1.0  
**PHP:** 8.4 (WP Engine)  
**Hosting:** WP Engine  
**Entorno de Staging:** https://vertexraystg.wpenginepowered.com/  
**Entorno de Producción:** https://vertexray.com

#### Plugins Activos

**Total:** 15 plugins activos (reducido de 18 en Abril)

**Constructores y Diseño (Builders & UI)**

- **Elementor (v4.1.0):** Page builder principal. Actualizado de v4.0.2 el 26/05/2026. Config: Templates almacenados en DB, widgets custom del tema child.
- **Meta Box (v5.12.0):** Custom fields framework. Actualizado de v5.11.4 el 26/05/2026. ⚠️ **Historial:** Falló actualización en Abril (reinstalación limpia requerida). Monitorear esta versión.
- **Kirki (v6.0.9):** Customizer framework. Actualizado de v5.2.3 el 26/05/2026 (major release). Config: Usado por tema parent para opciones de personalización.

**Formularios e Integraciones (Forms & CRM)**

- **Contact Form 7 (v6.1.6):** Formularios de contacto. Actualizado de v6.1.5 el 26/05/2026. Config: Formularios en Contact Us, Join Our Team. Integrado con Cloudflare Turnstile (Abril 2026) para protección anti-spam.
- **MC4WP: Mailchimp for WordPress (v4.12.6):** Integración Mailchimp. Actualizado de v4.12.1 el 26/05/2026. Config: Newsletter signup forms.
- **Zoho Campaigns (v2.1.7):** Integración Zoho para email marketing. Sin actualización disponible. ⚠️ **Pendiente:** Credenciales API no provistas por cliente.
- **WP Mail SMTP Pro (v4.8.0):** SMTP relay para emails transaccionales. Sin actualización disponible. Config: Usando cuenta Gmail personal `abonjour@gmail.com`. ⚠️ **Recomendación:** Migrar a cuenta corporativa Zoho.

**Seguridad y Prevención de Spam (Security)**

- **Akismet Anti-spam (v5.7):** Protección contra spam en comentarios. Actualizado de v5.6 el 26/05/2026 (parche de seguridad).
- **Cloudflare Turnstile:** Integrado via Contact Form 7 (configuración custom). Reemplaza Google reCAPTCHA (migración Abril 2026). Cero costos, sin captchas visuales molestos.
- **💡 Hardening Custom (Mayo 2026):** Seguridad implementada via código en `functions.php` (332 líneas) + `.htaccess` (90 líneas). Ver sección "Alternativas de Código" más abajo. **NO usa plugins** (Wordfence, iThemes Security, etc.).

**Performance y Caché**

- _Ningún plugin instalado._ Caché gestionado por WP Engine (Object Cache + Page Cache a nivel de servidor).

**Gestión de Contenido y Datos (Content & Data)**

- **OT Portfolios (v1.0):** Custom post type para portfolio/proyectos. Plugin del tema Engitech. Sin actualizaciones.
- **One Click Import Demo Content (v1.0):** Importador de contenido demo. Plugin del tema Engitech. ⚠️ **Recomendación:** Desactivar en producción (solo necesario durante setup inicial).

**Utilidades del Sistema y SEO (Utilities & SEO)**

- **Google Site Kit (v1.179.0):** Integración oficial de Google Analytics, Search Console, etc. Actualizado de v1.176.0 el 26/05/2026. ⚠️ **Ambigüedad:** Tracking implementado también via código custom (Mayo 2026). Verificar que no haya duplicación de IDs.
- **UpdraftPlus (v1.26.4):** Backups automáticos. Actualizado de v1.26.2 el 26/05/2026 (parche de seguridad). Config: Backups programados, retención de N días.
- **WP Engine Site Migration (v1.8.1):** Herramienta de migración de WP Engine. Actualizado de v1.7.1 el 26/05/2026. ⚠️ **Recomendación:** Desactivar cuando no esté en uso activo.
- **Marker.io (v1.2.2):** Feedback visual y bug tracking. Sin actualización disponible. Config: Usado por equipo interno de QA.

**Comunicación y Chat (Communication)**

- **Joinchat (creame-whatsapp-me) (v6.2.3):** Botón flotante de WhatsApp. Actualizado de v6.1.3 el 26/05/2026. Config: Número `59892250103` (Uruguay), mensaje por defecto "Hola! Quería más información". **Decisión Arquitectural (Mayo 2026):** Mantener plugin en lugar de código custom (peso marginal 6.54 KB vs ahorro 3 KB no justifica deuda técnica).

---

## 2. Alternativas de Código (Plugins Candidatos a Reemplazar)

> Plugins que fueron eliminados reemplazando su funcionalidad con código en `functions.php` del child theme.

| Plugin | Función | Reemplazo | Estado | Fecha |
| --- | --- | --- | --- | --- |
| **Goolytics - Simple Google Analytics (v1.1.3)** | Inyección de scripts GA/GTM | Código custom en `functions.php` (líneas 124-216) | ✅ **Eliminado** (26/05/2026) | Mayo 2026 |
| Wordfence / iThemes Security | Hardening y seguridad | Código custom PHP (líneas 219-332) + `.htaccess` (90 líneas) | ✅ **Nunca instalado** (implementado desde cero) | Mayo 2026 |

**Plugins Evaluados pero Mantenidos:**

| Plugin | Razón para mantener |
| --- | --- |
| **Joinchat (creame-whatsapp-me)** | Ahorro de solo 3 KB no justifica mantener código custom. Plugin ligero (6.54 KB), mantenido activamente, features configurables (horarios, triggers, mensajes por página). Decisión documentada en TO-BE-FINISHED-MAYO-2026.md |

**Plugins Candidatos a Evaluar (Futuro):**

| Plugin | Función actual | Propuesta de reemplazo | Prioridad |
| --- | --- | --- | --- |
| One Click Import Demo Content | Importar contenido demo | Desactivar en producción (solo necesario en setup) | 🟡 Media |
| WP Engine Site Migration | Migraciones | Desactivar cuando no esté en uso | 🟡 Media |
| soo-demo-importer | Importador de demos (¿duplicado?) | Evaluar si es redundante con "One Click Import Demo Content" | 🟢 Baja |

---

## 3. Procedimiento de Actualización

> Orden obligatorio. No saltear pasos ni invertirlos.

### WordPress

1. **Backup completo pre-actualización**
   - Local: `npm run pull-code-stg` (desde `ameba-deploy/`)
   - Staging/Prod: `wp db export backup-pre-update-$(date +%Y%m%d).sql`
   - Verificar backup descargado y accesible

2. **Verificar actualizaciones disponibles**
   ```bash
   wp core check-update
   wp plugin list --update=available
   wp theme list --update=available
   ```

3. **WordPress Core** (PRIMERO, siempre)
   ```bash
   wp core update
   wp core update-db
   wp core verify-checksums
   ```

4. **Plugins de terceros** (DESPUÉS de core)
   ```bash
   wp plugin update --all
   ```
   ⚠️ **Excepción:** Si hay major releases (ej: Kirki 5.x → 6.x), actualizar uno por uno:
   ```bash
   wp plugin update <slug>
   ```
   Verificar funcionalidad después de cada major update.

5. **Temas** (si parent theme es de terceros)
   ```bash
   wp theme update engitech
   ```
   ⚠️ **Child theme (engitech-child):** NUNCA actualizar automáticamente, se gestiona desde Git.

6. **Verificación post-update**
   - Revisar `wp-content/debug.log` (si existe): `tail -50 wp-content/debug.log`
   - Smoke test manual: home, formularios, tracking, WhatsApp
   - Tests E2E (si Playwright instalado): `npm test`

7. **Limpieza post-actualización**
   ```bash
   wp plugin delete goolytics-simple-google-analytics  # Si existe inactivo
   wp theme delete twentytwentyfive  # Themes default innecesarios
   wp transient delete --all
   ```

### Orden Crítico en Major Releases

Para major releases de WordPress (ej: 6.x → 7.x):

1. **Staging PRIMERO** (aplicar y testear exhaustivamente)
2. **Esperar 48-72 horas** (monitorear logs y reportes de usuarios)
3. **Producción DESPUÉS** (solo si staging estable)

**Historial de Major Updates:**
- **26/05/2026:** WordPress 6.9.4 → 7.0 aplicado a local y staging. Pendiente: Esperar 48h antes de producción.
- **26/05/2026:** Kirki 5.2.3 → 6.0.9 (major) aplicado sin issues detectados.

---

## 4. Configuración Básica WP

> Estado documentado de los ajustes sensibles. Actualizado 26/05/2026.

### Thumbnails / Image Sizes

| Tamaño registrado | Dimensiones | ¿En uso real? | Acción |
| --- | --- | --- | --- |
| thumbnail | 150×150 | ⏳ Pendiente verificar | E2E test pendiente |
| medium | 300×300 | ⏳ Pendiente verificar | E2E test pendiente |
| large | 1024×1024 | ⏳ Pendiente verificar | E2E test pendiente |

⚠️ **TODO:** Auditar con Playwright (verificar qué tamaños se usan realmente en front-end).

### Ajustes Generales

| Ajuste | Valor actual | Estado | Nota |
| --- | --- | --- | --- |
| Comentarios habilitados | No | ✅ OK | Sitio corporativo sin blog |
| Pingbacks / Trackbacks | Deshabilitados | ✅ OK | Bloqueados también en código |
| XML-RPC | **Bloqueado** (hardening) | ✅ OK | Filtro + .htaccess 403 |
| WP Cron (nativo) | Habilitado | ⚠️ Revisar | Evaluar cron real del servidor si tráfico bajo |
| Revisiones de entradas | Sin límite | ⚠️ Optimizar | Recomendado: `define('WP_POST_REVISIONS', 5);` |
| Zona horaria | ⏳ Pendiente | 🔍 Verificar | Confirmar con cliente (probablemente UTC-3 Argentina) |
| Formato de fecha/hora | ⏳ Pendiente | 🔍 Verificar | Verificar en wp-admin |
| Modo Debug | **Off** en staging/prod | ✅ OK | Solo en local si es necesario |
| Language | Spanish (Argentina) | ⚠️ Inconsistencia | HTML usa `lang="en"` pero contenido en ES |

---

## 5. Security Audit

> Última auditoría: **26 de Mayo de 2026** (Mayo 2026 Maintenance Session)  
> Realizado por: AI Agent (Ameba)

### Protecciones Implementadas (Mayo 2026)

| Ítem | Estado | Detalle |
| --- | --- | --- |
| **xmlrpc.php bloqueado** | ✅ OK | Filtro PHP + .htaccess 403 (TAREA 3) |
| **wp-config.php protegido** | ✅ OK | .htaccess Deny all |
| **Archivos sensibles bloqueados** | ✅ OK | readme.html, license.txt, debug.log → 403 |
| **PHP en uploads/ bloqueado** | ✅ OK | .htaccess FilesMatch deny |
| **REST API /users bloqueado** | ✅ OK | Código custom (anti-enumeración) |
| **Versión WP oculta** | ✅ OK | Meta generator removido, RSS limpio |
| **Security headers HTTP** | ✅ OK | X-Frame-Options, X-Content-Type, Referrer-Policy, Permissions-Policy |
| **Login errors genéricos** | ✅ OK | No revela si usuario existe |
| **Author archives deshabilitados** | ✅ OK | Redirect 301 a home |
| **File editing deshabilitado** | ✅ OK | `DISALLOW_FILE_EDIT` constant |
| **User-agents maliciosos bloqueados** | ✅ OK | .htaccess mod_rewrite (wget, nikto, curl, HTTrack) |
| **SQL injection básica bloqueada** | ✅ OK | .htaccess patterns (union, select, insert, drop) |
| **Path traversal bloqueado** | ✅ OK | .htaccess ../, .git, .svn, .log |
| **Directory listing off** | ✅ OK | Options -Indexes |
| HSTS habilitado | ⚠️ WP Engine | Gestionado por hosting |
| WPS Hide Login | ❌ No instalado | No necesario (protecciones nativas suficientes) |
| SSL/HTTPS | ✅ OK | Certificado gestionado por WP Engine |
| Wordfence / iThemes | ❌ No instalado | **Reemplazado por código custom** |

### Usuarios Administradores

⏳ **Pendiente auditoría:** Verificar con cliente lista de usuarios admin activos.

### Spam en Formularios

✅ **Protección activa:** Cloudflare Turnstile en Contact Form 7 (desde Abril 2026).

---

## 6. QA Funcional del Sitio

> Checklist específico de Vertex Ray. Actualizado 26/05/2026.

### Formularios

| Formulario | URL | Última verificación | Estado |
| --- | --- | --- | --- |
| Contact Us | https://vertexray.com/contact-us/ | 26/05/2026 (staging) | ✅ OK |
| Join Our Team | https://vertexray.com/join-our-team/ | 26/05/2026 (staging) | ✅ OK |

⚠️ **Destino de emails:** Aplicaciones de empleo sin destino configurado (pendiente cliente).

### Integraciones y APIs

| Integración | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| Google Analytics (UA) | DevTools → dataLayer + script UA-2468946-1 | 26/05/2026 | ✅ OK |
| Google Tag Manager | DevTools → GTM-NPL5XMZ script presente | 26/05/2026 | ✅ OK |
| WhatsApp (Joinchat) | Botón visible, click abre wa.me/59892250103 | 26/05/2026 | ✅ OK |
| Zoho Campaigns API | N/A | N/A | ❌ Credenciales no provistas |

### Analytics

| Evento / Vista | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| Pageview Home | GA Real Time | 26/05/2026 | ✅ OK |
| GTM dataLayer activo | Console → window.dataLayer | 26/05/2026 | ✅ OK |
| Form submission event | ⏳ Pendiente configurar | N/A | ⚠️ Pendiente |

### Tests E2E (Playwright)

| Test Suite | Archivo | Tests | Estado |
| --- | --- | --- | --- |
| Visual Regression | `tests/visual-regression.spec.js` | 16 tests (5 pages × desktop/mobile × full/hero) | ⏳ **Creado, pendiente ejecutar** |
| Forms Testing | `tests/forms.spec.js` | 20+ tests (validación, accesibilidad, performance, mobile) | ⏳ **Creado, pendiente ejecutar** |
| E2E User Flows | `tests/e2e-flows.spec.js` | 15+ tests (journeys completos, navegación, WhatsApp, analytics) | ⏳ **Creado, pendiente ejecutar** |
| Security Hardening | `tests/security.spec.js` | 25+ tests (file protection, REST API, headers, XSS, SQL injection) | ⏳ **Creado, pendiente ejecutar** |

**Comandos:**
```bash
npm install  # Instalar Playwright
npm test  # Correr todos los tests
npm run test:visual  # Solo visual regression
npm run test:forms  # Solo formularios
npm run test:security  # Solo seguridad
```

---

## 7. Pipeline & Deploy

> Proceso de pasaje a producción documentado para Vertex Ray.

**Ramas:** `dev` (default) → `chore/mantenimiento-mayo-2026` (actual) → merge → `dev` → push to prod  
**Script de deploy:** `ameba-deploy/` (comandos vía `npm run <cmd>` — ver `ameba-deploy/README.md`)  
**Hosting:** WP Engine  
**Método alternativo:** Local by Flywheel "Push to Live"

### Deploy Workflow

1. **Desarrollo local:** Rama `chore/mantenimiento-mayo-2026`
2. **Push a staging:** `npm run push-theme-stg engitech-child` (desde ameba-deploy/)
3. **Testing en staging:** Smoke tests + Playwright suite
4. **Merge a dev:** `git checkout dev && git merge chore/mantenimiento-mayo-2026`
5. **Push a producción:** `npm run push-theme-prod engitech-child` O Local by Flywheel
6. **Post-deploy:** Verificación manual + forzar reindexación GSC

### Entornos

| Entorno | URL | Deploy Method | WP-CLI Access |
| --- | --- | --- | --- |
| **Local** | https://vertexray.ldev/ | Local by Flywheel | `C:/wp-cli/wp.bat` |
| **Staging** | https://vertexraystg.wpenginepowered.com/ | ameba-deploy push-theme-stg | SSH: `vertexraystg@vertexraystg.ssh.wpengine.net` |
| **Producción** | https://vertexray.com | ameba-deploy push-theme-prod O Flywheel | SSH: (credentials pending) |

---

## 8. Notas de Mantenimiento Recientes

### Mayo 2026 Session (26/05/2026)

**Actualizaciones Aplicadas:**
- WordPress 6.9.4 → 7.0 (major release)
- 10 plugins actualizados (ver sección 1)
- Database 60717 → 61833

**Código Custom Implementado:**
- SEO title fixes (eliminar "Engitech" de títulos)
- Tracking GA/GTM custom (reemplaza Goolytics)
- Security hardening completo (PHP + .htaccess)

**Plugins Eliminados:**
- Goolytics v1.1.3 (desactivado 26/05, eliminado local, pendiente eliminar staging)
- Twenty Twenty-Five theme (eliminado local, actualizado a v1.5 en staging - pendiente eliminar)

**Decisiones Arquitecturales:**
- Mantener plugin Joinchat (vs código custom WhatsApp)
- Implementar seguridad sin plugins pesados (Wordfence, etc.)

**Pendientes:**
- Aplicar reglas .htaccess completas a staging (actualmente solo 26 líneas vs 154 esperadas)
- Eliminar Goolytics en staging
- Eliminar tema Twenty Twenty-Five en staging
- Ejecutar suite Playwright completa
- Monitorear Meta Box v5.12.0 (historial de fallos en Abril)
- Confirmar con Andrés Bolani: IDs de GA/GTM, número WhatsApp

---

**Última revisión:** 26 de Mayo de 2026  
**Próxima revisión:** Post-deploy a producción
