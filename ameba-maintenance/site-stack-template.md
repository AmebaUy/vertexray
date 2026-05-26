# Site Stack — [Nombre del Sitio]

> Archivo persistente. Se sobreescribe en cada sesión de mantenimiento — nunca se duplica.
> Última actualización: [YYYY-MM-DD] por [dev/agente]

---

## 1. Inventario de Stack

> Completar la sección correspondiente al stack del proyecto. Eliminar la que no aplique.

### WordPress

**WordPress Core:** vX.X.X
**Theme activo:** [Nombre del tema] vX.X.X
**PHP:** X.X

#### Plugins Activos

> Para cada plugin: nombre, versión, función principal, configuración relevante documentada y nota de auditoría si aplica.
> Notas: `[⚠️ Licencia vencida]` · `[⚠️ Redundante con X]` · `[⚠️ Solo para migraciones]` · `[💡 Reemplazable por código]` · `[🔍 Verificar uso activo]`

**Constructores y Diseño (Builders & UI)**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes documentados.]

**Formularios e Integraciones (Forms & CRM)**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes.]

**Seguridad y Prevención de Spam (Security)**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes.]

**Performance y Caché**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes.]

**Gestión de Contenido y Datos (Content & Data)**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes.]

**Utilidades del Sistema y SEO (Utilities & SEO)**

- **[Plugin] (vX.X.X):** [Función principal.] Config: [ajustes relevantes.]

---

### Jamstack / Node

**Framework:** [Next.js / Nuxt / Astro / etc.] vX.X.X
**Node:** vX.X.X
**Package manager:** npm / yarn / pnpm

#### Packages Clave

| Package | Versión | Propósito |
| --- | --- | --- |
| [nombre] | vX.X.X | [Para qué se usa en este proyecto] |

---

## 2. Alternativas de Código (Plugins Candidatos a Reemplazar)

> Plugins que pueden eliminarse reemplazando su funcionalidad con un snippet en `functions.php` u otro código propio. Documentar la propuesta concreta.

| Plugin | Función actual | Propuesta de reemplazo | Estado |
| --- | --- | --- | --- |
| [Nombre] | [Qué hace] | [Snippet en functions.php / código en tema / etc.] | ⏳ Pendiente / ✅ Implementado |

---

## 3. Procedimiento de Actualización

> Orden obligatorio. No saltear pasos ni invertirlos.

### WordPress

1. **Backup completo** — `npm run pull-env` (desde `ameba-deploy/`) + verificar volcado local
2. **WordPress Core** — `wp core update`
3. **Tema activo** (solo si es de terceros; temas Ameba se actualizan desde rama `dev`) — `wp theme update [slug]`
4. **Plugins de terceros** — `wp plugin update --all` (excepto los que requieren licencia activa)
5. **Plugins con licencia** — actualizar manualmente desde el panel o con clave de licencia activa
6. **Verificación post-update** — correr tests E2E + revisar error_log

### Jamstack / Node

1. **Backup** — `git stash` o rama de trabajo
2. **Revisar outdated** — `npm outdated`
3. **Actualizar** — `npm update` (patch/minor) o `npm install [pkg]@latest` (major con revisión)
4. **Build local** — `npm run build`
5. **Verificación** — correr tests + revisar consola

---

## 4. Configuración Básica WP

> Estado documentado de los ajustes sensibles. Actualizar en cada sesión.

### Thumbnails / Image Sizes

> Verificar con E2E (Playwright) los tamaños reales de output en el front-end y contrastar con los registrados. Eliminar los no utilizados y regenerar si es necesario (`wp media regenerate --yes`).

| Tamaño registrado | Dimensiones | ¿En uso real? | Acción |
| --- | --- | --- | --- |
| thumbnail | 150×150 | ✅ / ❌ | — / Eliminar |
| medium | 300×300 | ✅ / ❌ | — / Eliminar |
| large | 1024×1024 | ✅ / ❌ | — / Eliminar |
| [custom size] | X×Y | ✅ / ❌ | — / Eliminar |

### Ajustes Generales

| Ajuste | Valor actual | Estado | Nota |
| --- | --- | --- | --- |
| Comentarios habilitados | Sí / No | ✅ / ⚠️ | [contexto si aplica] |
| Pingbacks / Trackbacks | Habilitados / Deshabilitados | ✅ / ⚠️ | Deshabilitar si no se usan |
| XML-RPC | Habilitado / Deshabilitado | ✅ / ⚠️ | Deshabilitar si no hay integración que lo requiera |
| WP Cron (nativo) | Habilitado / Deshabilitado | ✅ / ⚠️ | Reemplazar por cron real del servidor si el tráfico es bajo |
| Revisiones de entradas | N máximo | ✅ / ⚠️ | Limitar a 5 en wp-config.php |
| Zona horaria | [timezone] | ✅ / ⚠️ | — |
| Formato de fecha/hora | [formato] | ✅ / ⚠️ | — |
| Modo Debug | Off | ✅ / ⚠️ | Nunca ON en producción |

---

## 5. Security Audit

> Actualizar en cada sesión. El audit completo se documenta también en Notion.

**Fecha del último audit:** [YYYY-MM-DD]
**Realizado por:** [dev/agente]

| Ítem | Estado | Detalle |
| --- | --- | --- |
| Wordfence scan | ✅ Limpio / ⚠️ Issues | [resultado del último scan] |
| HSTS habilitado | ✅ / ❌ | — |
| WPS Hide Login | ✅ Activo / ❌ No instalado | URL: /[custom-login-url] |
| Usuarios administradores | N usuarios — verificados | [listado breve o nota] |
| Contraseñas fuertes | ✅ Verificado / ⚠️ Pendiente | — |
| Permisos de archivos | ✅ Correctos / ⚠️ Revisar | — |
| SSL/HTTPS | ✅ OK / ⚠️ Expira pronto | Vence: [fecha] |
| Spam en formularios | N pendientes eliminados | — |
| XML-RPC bloqueado | ✅ / ❌ | — |

---

## 6. QA Funcional del Sitio

> Checklist específico de este cliente. Completar con URLs y datos reales del sitio.
> Correr pre y post actualización. Marcar con ✅ OK / ❌ Falla / ⚠️ Degradado.

### Formularios

| Formulario | URL | Última verificación | Estado |
| --- | --- | --- | --- |
| [Nombre del formulario] | [URL de la página] | [YYYY-MM-DD] | ✅ / ❌ |

### Búsqueda y Filtros

| Funcionalidad | URL | Última verificación | Estado |
| --- | --- | --- | --- |
| Búsqueda general | [URL] | [YYYY-MM-DD] | ✅ / ❌ |
| [Filtro específico] | [URL] | [YYYY-MM-DD] | ✅ / ❌ |

### Integraciones y APIs

| Integración | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| [ERP/CRM/API] | [Comando o acción para probar] | [YYYY-MM-DD] | ✅ / ❌ |

### Analytics

| Evento / Vista | Cómo verificar | Última verificación | Estado |
| --- | --- | --- | --- |
| Pageview Home | GA4 Real Time | [YYYY-MM-DD] | ✅ / ❌ |
| [Evento de conversión] | [Herramienta] | [YYYY-MM-DD] | ✅ / ❌ |

### E-commerce (si aplica)

| Flujo | URL de inicio | Última verificación | Estado |
| --- | --- | --- | --- |
| Agregar al carrito | [URL producto] | [YYYY-MM-DD] | ✅ / ❌ |
| Checkout (compra real) | /checkout | [YYYY-MM-DD] | ✅ / ❌ |
| Email de confirmación | — | [YYYY-MM-DD] | ✅ / ❌ |

### Tests E2E (Playwright)

| Test | Archivo | Última ejecución | Estado |
| --- | --- | --- | --- |
| [Nombre del test] | [ruta/al/test.spec.ts] | [YYYY-MM-DD] | ✅ / ❌ |

---

## 7. Pipeline & Deploy

> Proceso de pasaje a producción documentado para este cliente.

**Ramas:** `dev` → `main/master`
**Script de deploy:** `ameba-deploy/` (comandos vía `npm run <cmd>` — ver `ameba-deploy/agents.md`)
**Hosting:** [Netuy / WPE / Vercel / etc.]
**URL de producción:** https://www.ejemplo.com
**URL de staging:** https://stg.ejemplo.com (si aplica)

### Orden de deploy

1. Crear rama `chore/mantenimiento-mes-año` desde `dev`
2. Realizar cambios y verificar localmente
3. PR a `dev` → merge
4. `npm run push-code` (desde `ameba-deploy/`) (código únicamente — **NUNCA push-db en producción**)
5. Verificar en producción con QA funcional básico
6. Si WooCommerce u otro plugin requiere migración de BD: iniciar sesión en WP-Admin de producción y presionar el botón de actualización desde allí

### Accesos y cuentas

| Recurso | Cuenta / URL | Nota |
| --- | --- | --- |
| Hosting panel | [URL] | — |
| SSH | [usuario@host] | — |
| WP-Admin producción | [URL/wp-admin] | — |
| WP-Admin staging | [URL/wp-admin] | — |

---

*Site Stack generado por Agentic Maintenance vX.X.X · [dominio del sitio]*
