# Reporte de Mantenimiento & Seguridad

**Sitio:** https://www.ejemplo.com/

**Período:** [Del X al Y de Mes de Año]

**Preparado por:** [Nombre]

**Fecha de emisión:** [Fecha completa]

---

## ✅ Fase 0 Completada

- [ ] `ameba-maintenance/agents.md` leído y actualizado
- [ ] `ameba-maintenance/ameba-monthly-support-prompt.md` verificado (sincronizado automáticamente)
- [ ] `ameba-maintenance/site-stack.md` verificado / creado
- [ ] Versión de Agentic Maintenance verificada
- [ ] Redis Cache Key Salt verificado (si aplica Netuy)
- [ ] Rama de mantenimiento creada (`chore/mantenimiento-mes-año`)

---

## Resumen Ejecutivo

> Párrafo breve (3-5 líneas) describiendo los frentes cubiertos en esta sesión y el resultado más relevante. No listar todo — comunicar el impacto global. Ejemplo: "Durante esta semana se realizó una intervención profunda cubriendo tres frentes: performance, seguridad y estabilidad de integraciones. El resultado es un sitio notablemente más rápido y un panel de administración considerablemente más potente."

---

## 1. [Frente de Trabajo 1 — ej. Performance y Velocidad de Carga]

> Cada sección agrupa trabajo por categoría temática. Los nombres no son fijos — adaptarlos según lo realizado en la sesión (ej. "Performance", "Integración ERP", "Experiencia Móvil", "Seguridad", "Panel de Administración", "Infraestructura CI/CD").

### [Subsección — Tarea específica]

[Descripción de lo que se hizo y por qué. Incluir métricas antes/después cuando estén disponibles.]

- **[Métrica clave]:** de ~X → **Y** *(mejora de Nx)*

### [Subsección — Otra tarea]

[Descripción del fix o mejora, causa raíz y solución aplicada.]

---

## 2. [Frente de Trabajo 2 — ej. Actualizaciones y Limpieza]

### Sistema y Plugins

- **WordPress Core:** de vX.X.X → **vX.X.X**
- **Theme:** de vX.X.X → **vX.X.X**
- **[Plugin]:** de vX.X.X → **vX.X.X**

### Plugins Eliminados / Desactivados

| **Acción** | **Plugin** | **Motivo** |
| --- | --- | --- |
| Eliminado | [Nombre del plugin] | [Motivo: redundante / riesgo seguridad / performance] |
| Desactivado (sin borrar datos) | [Nombre del plugin] | [Motivo] |

---

## 3. [Frente de Trabajo 3 — ej. Optimización de Base de Datos]

| **Tarea** | **Resultado** |
| --- | --- |
| Revisiones de entradas eliminadas | N registros |
| Borradores automáticos eliminados | N registros |
| Transients expirados eliminados | N registros |
| Tablas huérfanas eliminadas | [Listado de tablas] |

---

## 4. [Frente de Trabajo 4 — ej. Seguridad y Hardening]

- [Acción tomada con contexto. Ej: "Eliminación de 5 usuarios administradores inactivos"]
- [Acción tomada. Ej: "Instalación de WPS Hide Login — URL movida a /nueva-url"]
- [Acción tomada. Ej: "Activación de cabeceras de seguridad HSTS / XSS"]

---

## 5. [Frente de Trabajo 5 — ej. QA y Testing]

- [Resultado del testing pre y post actualización]
- [Problemas detectados durante el proceso y cómo se resolvieron]
- [Estado del entorno de staging si aplica]

---

## 6. Integraciones & Estado de APIs

| **Integración** | **Estado** | **Detalle** |
| --- | --- | --- |
| [ERP/CRM/API] | ✅ OK / ⚠️ Advertencia / ❌ Error | [Descripción del resultado] |

---

## 7. Core Web Vitals (estado actual en producción)

| Métrica | Mobile | Desktop |
| --- | --- | --- |
| LCP | X.X s ✅/⚠️ | X.X s ✅/⚠️ |
| INP | X ms ✅/⚠️ | X ms ✅/⚠️ |
| CLS | X.XX ✅/⚠️ | X.XX ✅/⚠️ |
| TTFB | X.X s ✅/⚠️ | X.X s ✅/⚠️ |

> Referencia de umbrales: LCP ≤2.5s ✅, INP ≤200ms ✅, CLS ≤0.1 ✅, TTFB ≤0.8s ✅

### Lighthouse (PageSpeed Insights)

| **Página** | **Performance** | **Accessibility** | **Best Practices** | **SEO** |
| --- | --- | --- | --- | --- |
| Home | XX | XX | XX | XX |

### Error Logs

| **Entorno** | **Estado** | **Detalle** |
| --- | --- | --- |
| Producción | ✅ Limpio / ⚠️ Warnings / ❌ Errores | [Tamaño del log / errores encontrados] |
| Local / STG | ✅ Limpio / ⚠️ Warnings / ❌ Errores | [Tamaño del log / errores encontrados] |

---

## 8. Action Items — Acciones Requeridas

> Ítems que bloquean el trabajo técnico y requieren acción del cliente o del PM antes de la próxima sesión (licencias vencidas, accesos necesarios, decisiones pendientes). Omitir esta sección si no hay ítems bloqueantes.

- **[Plugin / Área]:** [Descripción del problema y acción requerida. Ej: "La licencia de Gravity Forms expiró. Se requiere renovación para poder actualizar el plugin y sus add-ons. Riesgo: vulnerabilidad de seguridad sin actualizar."]
- **[Plugin / Área]:** [Descripción.]

---

## 9. Cambios de Stack en esta Sesión

> Solo el delta respecto al inventario documentado en `ameba-maintenance/site-stack.md`. Para el inventario completo actualizado, ver `ameba-maintenance/site-stack.md`.

### Actualizaciones

- **WordPress Core:** de vX.X.X → **vX.X.X**
- **Theme:** de vX.X.X → **vX.X.X**
- **[Plugin]:** de vX.X.X → **vX.X.X**

### Plugins / Packages Eliminados o Desactivados

| **Acción** | **Plugin / Package** | **Motivo** |
| --- | --- | --- |
| Eliminado | [Nombre] | [Motivo: redundante / riesgo seguridad / reemplazado por código] |
| Desactivado (sin borrar datos) | [Nombre] | [Motivo] |

### Agregados en esta Sesión

| **Plugin / Package** | **Versión** | **Motivo** |
| --- | --- | --- |
| [Nombre] | vX.X.X | [Por qué se agregó] |

### Candidatos a Reemplazar por Código (identificados esta sesión)

| **Plugin** | **Función actual** | **Propuesta** |
| --- | --- | --- |
| [Nombre] | [Qué hace] | [Snippet en functions.php / código propio] |

> Inventario completo del stack → `ameba-maintenance/site-stack.md`

---

## 10. Actualización de ameba-maintenance/agents.md

> Lo que se anotó en `ameba-maintenance/agents.md` en esta sesión (workarounds, errores descubiertos, cambios de stack, decisiones importantes).

- [Ítem anotado con contexto]

## 11. Documentación & Cierre

- **Tarea Asana:** [Link al comentario en la tarea]
- **PR de mantenimiento:** [Link al PR / merge a dev]

---

## 🚀 Resultados Esperados tras la Intervención

> Qué mejoras concretas se anticipan en la próxima medición o sesión gracias a los cambios de esta sesión.

- **[Área de mejora]:** [Explicación del impacto esperado. Ej: "Los cambios en LiteSpeed Cache y la imagen LCP priorizada deberían impactar positivamente en LCP y TTFB en la próxima medición."]
- **[Área de mejora]:** [Explicación.]

---

## ⚙️ Avisos para Gestión

> Temas que requieren coordinación con el cliente o decisión del PM antes de la próxima sesión. Omitir esta sección si no hay avisos.

1. **[Tema]:** [Descripción del aviso y qué acción se necesita del cliente o PM. Ej: "Consultar si desean activar CAPTCHA en formularios de WooCommerce."]
2. **[Tema]:** [Descripción.]

---

## ⏭️ Próximos Pasos

> Esta sección es la que el agente leerá al inicio de la próxima sesión.
> Ser específico y priorizado. El agente empezará desde acá.

### 1. [Tema prioritario]

- **Acción:** [Descripción clara de qué hacer y por qué es prioritario]

### 2. [Tema prioritario]

- **Acción:** [Descripción clara de qué hacer]

### 3. Pendientes no resueltos de esta sesión

- [ ] [Ítem pendiente con contexto suficiente para retomarlo sin preguntas]
- [ ] [Ítem pendiente con contexto]

---

## ✉️ Brief para el Cliente

> Versión simplificada y no técnica del reporte, lista para enviar al cliente en su idioma.
> Adaptar tono y nivel técnico según el perfil del cliente (extraído de `ameba-maintenance/agents.md`).
> Omitir esta sección si el cliente no recibe reportes directos.

**Asunto:** Reporte de Mantenimiento, Seguridad y Optimización — [Mes Año]

> [Párrafo introductorio breve explicando el contexto de la sesión.]
>
> **¿Qué hicimos?**
>
> - **[Categoría]:** [Explicación en lenguaje no técnico de lo realizado y su beneficio.]
> - **[Categoría]:** [Explicación.]
>
> **Tu revisión es fundamental:**
>
> [Solicitar al cliente que verifique las funcionalidades principales del sitio.]
>
> **Próximos pasos que tenemos en agenda:**
>
> - [Punto 1 con beneficio para el cliente]
> - [Punto 2]
>
> Quedamos a total disposición si notan algo fuera de lo común o si tienen alguna consulta.
>
> Saludos,
> El equipo técnico de Ameba.

---

*Reporte generado por Agentic Maintenance vX.X.X · [dominio del sitio]*
