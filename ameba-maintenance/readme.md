# ameba-maintenance/

Esta carpeta centraliza la memoria persistente del proyecto para el sistema de mantenimiento agentico de Ameba. Aquí viven todos los archivos que el agente de IA lee y actualiza en cada sesión de mantenimiento.

---

## Qué hay aquí

| Archivo | Quién lo crea / actualiza | Descripción |
|---|---|---|
| `agents.md` | Agente (primera sesión) o dev | Contexto del proyecto: stack, hosting, deploy, integraciones, idioma del cliente. |
| `site-stack.md` | Agente (Fase 5) | Inventario completo de plugins/paquetes, procedimientos de update, checklist QA y auditoría de seguridad. Se sobreescribe en cada sesión — nunca se duplica. |
| `maintenance-reports/maintenance-report-YYYY-MM.md` | Agente (sesiones de mantenimiento) | Reportes de mantenimiento rutinario. Un archivo por período. |
| `hot-fixes/incident-report-[incident-title]-[yyyy]-[mm]-[dd].md` | Agente (sesiones de incidentes/hot fix) | Reportes de resolución de bugs/incidentes puntuales. |
| `new-features/new-feature-[feature-name]-[yyyy]-[mm]-[dd].md` | Agente (sesiones de nuevas funcionalidades) | Reportes de funcionalidades o cambios no ligados a mantenimiento/incidentes. |
| `ameba-monthly-support-prompt.md` | 🔒 Centralizado — no editar | Prompt principal del agente orquestador. Se sincroniza automáticamente desde el repo central de Ameba. |
| `maintenance-report-template.md` | 🔒 Centralizado — no editar | Plantilla base para generar los reportes de sesión. |
| `incident-report-template.md` | 🔒 Centralizado — no editar | Plantilla base para reportes de incidentes/hot fixes. |
| `new-feature-report-template.md` | 🔒 Centralizado — no editar | Plantilla base para reportes de nuevas funcionalidades/cambios. |
| `site-stack-template.md` | 🔒 Centralizado — no editar | Plantilla base para crear `site-stack.md` la primera vez. |
| `readme.md` | 🔒 Centralizado — no editar | Este archivo. |

---

## Archivos centralizados 🔒

Los archivos marcados como **Centralizado** son gestionados por el [repositorio central Ameba-Agentic-Support](https://github.com/AmebaUy/Ameba-Agentic-Support) y se sincronizan automáticamente mediante PRs.

**No editar estos archivos directamente en el repo del proyecto.** Cualquier cambio será sobreescrito en la próxima sincronización. Si necesitás modificar el comportamiento del agente, hacelo en el repo central.

---

## Archivos del proyecto

### `agents.md`

El agente lo crea en la **primera sesión** si no existe. Contiene:

- Stack tecnológico (WordPress, Node, etc.)
- Hosting y método de deploy
- Integraciones (ERP, CRM, etc.)
- Idioma del cliente

**Mantenerlo actualizado** si cambia algo del stack o las integraciones.

### `site-stack.md`

Se genera en la **Fase 5** de la primera sesión a partir de `site-stack-template.md`. Documenta en detalle:

- Inventario de plugins/paquetes con versiones y configuración relevante
- Procedimiento de actualización
- Configuración base de WP/Node
- Auditoría de seguridad
- Checklist QA específico del proyecto
- Pipeline de deploy

El agente lo sobreescribe en cada sesión para mantenerlo actualizado. **No duplicar** — siempre existe un solo `site-stack.md`.

### Reportes por tipo de sesión

Para evitar acumular cientos de archivos en la raíz de `ameba-maintenance/`, los reportes se guardan por categoría:

- `maintenance-reports/maintenance-report-YYYY-MM.md`
- `hot-fixes/incident-report-[incident-title]-[yyyy]-[mm]-[dd].md`
- `new-features/new-feature-[feature-name]-[yyyy]-[mm]-[dd].md`

El agente lee el reporte más reciente de la categoría seleccionada al inicio de cada sesión para retomar desde donde se quedó.

---

## Cómo arrancar una sesión de mantenimiento

1. Abrí un chat con el agente de IA (Claude, Copilot, etc.).
2. Pegá el contenido completo de `ameba-maintenance/ameba-monthly-support-prompt.md` como primer mensaje.
3. El agente leerá automáticamente `agents.md`, `site-stack.md` y el último reporte antes de pedirte nada.
4. Confirmá cada fase antes de continuar — el agente no avanza solo.

> 💡 **Tip para PMs:** Al final de cada sesión el agente genera el reporte con los próximos pasos en la subcarpeta correspondiente (`maintenance-reports/`, `hot-fixes/` o `new-features/`), dando visibilidad completa de lo realizado sin necesidad de estar en la sesión.

---

## Dudas o cambios al sistema

Contactar al equipo de Ameba o abrir un issue en [Ameba-Agentic-Support](https://github.com/AmebaUy/Ameba-Agentic-Support).
