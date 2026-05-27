# Site Stack — Vertex Ray

> Archivo persistente. Se sobreescribe en cada sesión de mantenimiento — nunca se duplica.
> Última actualización: 2026-05-27 por agente (mantenimiento mayo 2026)

---

## 1. Inventario de Stack

### WordPress

**WordPress Core:** 7.0
**Theme activo:** engitech-child v1.0 (parent: engitech v1.2.1)
**PHP:** 8.4
**Hosting:** WP Engine (Nginx)
**URL Producción:** https://www.vertexray.com
**URL Staging:** https://vertexraystg.wpengine.com

#### Plugins Activos

> Auto-updates habilitados en todos los plugins de producción (27/05/2026).
> Notas: `[⚠️ Licencia]` · `[💡 Reemplazable por código]` · `[🔍 Verificar uso activo]`

**Constructores y Diseño**

- **Elementor (v4.1.0):** Page builder principal. Auto-update: ✅

**Formularios e Integraciones**

- **Contact Form 7 (v6.1.6):** Formularios de contacto. Mensajes traducidos al español vía filtro `gettext` + `_messages` en DB. CF7 Turnstile (captcha) pendiente configuración en prod por Andrés Bolani. Auto-update: ✅
- **Mailchimp for WP (v4.12.6):** Integración suscripción newsletter. Auto-update: ✅
- **Zoho Campaigns (v2.1.7):** Integración email marketing Zoho. Auto-update: ✅
- **WP Mail SMTP Pro (v4.8.0):** Envío de email vía SMTP. `[⚠️ Licencia]` Auto-update: ✅

**Seguridad y Prevención de Spam**

- **Akismet (v5.7):** Filtro antispam de comentarios. Auto-update: ✅

**Performance y Caché**

- LiteSpeed Cache gestionado a nivel servidor por WP Engine (no plugin local).

**Gestión de Contenido y Datos**

- **Meta Box (v5.12.0):** Custom fields / metaboxes para custom post types. Auto-update: ✅
- **OT Portfolios (v1.0):** Custom post type Portfolio. Plugin custom del tema Engitech. Auto-update: ✅

**Utilidades del Sistema**

- **UpdraftPlus (v1.26.4):** Backups automáticos. Auto-update: ✅
- **Kirki (v6.0.9):** Customizer API para opciones del tema. `[💡 Reemplazable por opciones en functions.php si el tema custom lo permite]` Auto-update: ✅
- **Joinchat / creame-whatsapp-me (v6.2.3):** Botón WhatsApp flotante. `[🔍 Verificar si el plugin sigue activo o fue reemplazado por el snippet custom de functions.php]` Auto-update: ✅

---

## 2. Alternativas de Código (Plugins Candidatos a Reemplazar)

| Plugin | Función actual | Propuesta de reemplazo | Estado |
| --- | --- | --- | --- |
| Joinchat (creame-whatsapp-me) | Botón WhatsApp flotante | Snippet HTML/CSS/JS en `functions.php` (ya implementado en sesión anterior) | 🔍 Verificar si el plugin fue desactivado o coexiste con el snippet |
| Kirki | Customizer theme options | Eliminar si el tema child no usa el customizer activamente; opciones fijas en `functions.php` | ⏳ Pendiente evaluación |

---

## 3. Procedimiento de Actualización

### Orden obligatorio

1. **Backup completo** — `cd ameba-deploy && npm run pull-env-prod` + verificar volcado local
2. **WordPress Core** — `wp core update && wp core update-db`
3. **Plugins (auto-update activos)** — se actualizan solos; verificar en WP Engine Activity Log
4. **Plugins con licencia** (`wp-mail-smtp-pro`) — actualizar manualmente con licencia activa
5. **Verificación post-update** — correr tests E2E + revisar `wp --info` y error_log

### Deploy de código (tema/plugins custom)

```bash
cd ameba-deploy/
npm run push-code-prod   # rsync wp-content/ → prod (excluye uploads)
```

> ⚠️ Nunca usar `npm run push-theme-prod` sin el parámetro de nombre de tema.

---

## 4. Configuración Básica WP

### Ajustes Generales

| Ajuste | Valor actual | Estado | Nota |
| --- | --- | --- | --- |
| Comentarios | Cerrados globalmente | ✅ | Deshabilitados vía `functions.php` + opción WP |
| Pingbacks / Trackbacks | Deshabilitados | ✅ | Deshabilitados vía `functions.php` |
| XML-RPC | Bloqueado | ✅ | Bloqueado vía `functions.php` + `.htaccess` |
| WP Cron (nativo) | Habilitado | ✅ | WP Engine gestiona el cron real del servidor |
| Revisiones de entradas | Máximo 5 | ✅ | `WP_POST_REVISIONS = 5` en `wp-config.php` |
| Zona horaria | America/Montevideo | ✅ | — |
| Modo Debug | Off | ✅ | `WP_DEBUG = false` en producción |
| WP_ENVIRONMENT_TYPE | `production` | ✅ | Separado de local (`local`) |

### Thumbnails / Image Sizes

> Auditoría de thumbnails pendiente para próxima sesión (Playwright visual regression).

| Tamaño | Dimensiones | ¿En uso real? | Acción |
| --- | --- | --- | --- |
| thumbnail | 150×150 | 🔍 Sin verificar | Pendiente Playwright |
| medium | 300×300 | 🔍 Sin verificar | Pendiente Playwright |
| large | 1024×1024 | 🔍 Sin verificar | Pendiente Playwright |

---

## 5. Security Audit

**Fecha del último audit:** 2026-05-27
**Realizado por:** agente (mantenimiento mayo 2026)

| Ítem | Estado | Detalle |
| --- | --- | --- |
| Hardening headers HTTP | ✅ Activo | X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy via `functions.php` |
| XML-RPC bloqueado | ✅ | `functions.php` + `.htaccess` |
| Enumeración de usuarios | ✅ Bloqueada | REST `/wp/v2/users` deshabilitado; author archive redirigido |
| Versión WP oculta | ✅ | Query strings `?ver=` eliminadas de assets |
| Usuario `admin` | ✅ Eliminado | Existía `admin`/`abonjour@gmail.com`; autoría transferida a `alex`; usuario eliminado (27/05/2026) |
| Administradores activos | 1 usuario — `alex@vertexray.com` | Único admin en producción |
| Spam en formularios | ✅ 189 eliminados | Limpieza ejecutada 27/05/2026 |
| SSL/HTTPS | ✅ OK | Gestionado por WP Engine (renovación automática) |
| robots.txt | ✅ Físico | Bloquea wp-admin, wp-includes, plugins, xmlrpc; declara sitemap |
| Auto-updates plugins | ✅ 11/11 on | Habilitados 27/05/2026 vía WP-CLI |
| CF7 Captcha (Turnstile) | ⏳ Pendiente | Andrés Bolani debe crear widget en Cloudflare y configurar en CF7 Integration |

---

## 6. QA Funcional del Sitio

### Formularios

| Formulario | URL | Última verificación | Estado |
| --- | --- | --- | --- |
| Contacto principal | https://www.vertexray.com/contact-us/ | 2026-05-27 | 🔍 Mensajes CF7 en español — pendiente confirmación visual |

### Integraciones y APIs

| Integración | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| Zoho Campaigns | Suscribirse al formulario y verificar en Zoho | 2026-05-27 | 🔍 Pendiente verificación OAuth |
| Mailchimp for WP | Suscribirse y verificar lista en Mailchimp | — | 🔍 Pendiente |
| WP Mail SMTP Pro | `wp eval 'wp_mail("test@example.com","Test","Test");'` | — | 🔍 Pendiente |

### Analytics

| Evento / Vista | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| Pageview | GA4 Real Time — UA-2468946-1 | — | 🔍 Confirmar IDs con Andrés Bolani |
| GTM container | GTM-NPL5XMZ activo en `<head>` | 2026-05-27 | ✅ Código en `functions.php` (solo producción) |

### Tests E2E (Playwright)

| Test | Archivo | Última ejecución | Estado |
| --- | --- | --- | --- |
| Flujos principales | `tests/e2e-flows.spec.js` | — | ⏳ Pendiente próxima sesión |
| Formularios | `tests/forms.spec.js` | — | ⏳ Pendiente próxima sesión |
| Seguridad | `tests/security.spec.js` | — | ⏳ Pendiente próxima sesión |
| Visual regression | `tests/visual-regression.spec.js` | — | ⏳ Pendiente próxima sesión |

---

## 7. Pipeline & Deploy

**Ramas Git:** `dev` (default) → branches de mantenimiento (`chore/mantenimiento-YYYY-MM`)
**Repositorio:** `AmebaUy/vertexray`
**Script de deploy:** `ameba-deploy/` — `npm run push-code-prod`
**Hosting:** WP Engine

### SSH

| Entorno | Host | Path |
| --- | --- | --- |
| Producción | `vertexray@vertexray.ssh.wpengine.net` | `/sites/vertexray` |
| Staging | `vertexraystg@vertexraystg.ssh.wpengine.net` | `/sites/vertexraystg` |

> ⚠️ **WP Engine SSH quirk:** No usar comillas dobles dentro de strings con comillas simples. Siempre usar heredoc:
> ```bash
> ssh vertexray@vertexray.ssh.wpengine.net << 'ENDSSH'
> wp db query "SELECT ..."
> ENDSSH
> ```

### Comandos frecuentes

```bash
# Deploy código a prod
cd ameba-deploy && npm run push-code-prod

# BD cleanup prod (transients, spam, orphans, optimize)
ssh vertexray@vertexray.ssh.wpengine.net << 'ENDSSH'
wp transient delete --expired
wp comment delete $(wp comment list --status=spam --format=ids) --force
wp db optimize
ENDSSH

# Flush cache
ssh vertexray@vertexray.ssh.wpengine.net << 'ENDSSH'
wp cache flush
ENDSSH
```

---

## 8. Pendientes para Próxima Sesión

| Ítem | Responsable | Prioridad |
| --- | --- | --- |
| CF7 Turnstile: crear widget en Cloudflare para vertexray.com y configurar en CF7 | Andrés Bolani | Alta |
| Confirmar IDs de GA (UA-2468946-1) y GTM (GTM-NPL5XMZ) son los correctos de prod | Andrés Bolani | Alta |
| Playwright E2E completo en staging | Dev / Agente | Media |
| Lighthouse CLI sobre prod (performance, a11y, seo, best-practices) | Agente | Media |
| Auditoría thumbnails (tamaños registrados vs uso real) | Agente | Baja |
| Verificar si Joinchat plugin coexiste con snippet custom o fue reemplazado | Dev | Media |
| Zoho Campaigns OAuth check (integración activa) | Dev | Media |
