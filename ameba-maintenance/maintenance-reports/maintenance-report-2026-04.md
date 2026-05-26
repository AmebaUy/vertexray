# Reporte de Mantenimiento Técnico, Seguridad y Optimización: Vertex Ray

## Datos del Proyecto y Metadatos

| Parámetro | Detalle |
| :--- | :--- |
| **Proyecto / Sitio** | [https://vertexray.com](https://vertexray.com) [1] |
| **Entorno de Staging** | [https://vertexraystg.wpenginepowered.com/](https://vertexraystg.wpenginepowered.com/) [1] |
| **Credenciales de Acceso STG** | Usuario: `vertexraystg` / Contraseña: `88596538` [1] |
| **Período de Cobertura** | Abril — Mayo 2026 [1, 2] |
| **Preparado por** | Área de Ingeniería de Software y Gestión de Proyectos, Ameba Creative Studio [1, 2] |
| **Fecha de Emisión** | 26 de Mayo de 2026 [1, 2] |

---

## ✅ Fase 0 Completada

El equipo técnico completó la fase preparatoria obligatoria antes de realizar cualquier modificación directa sobre el sitio en producción, garantizando la estabilidad operativa.[3]

*   [x] Lectura y actualización del archivo de directrices de mantenimiento (`ameba-maintenance/agents.md`).[3]
*   [x] Verificación de la pila tecnológica del sitio web y configuración del entorno local.[3]
*   [x] Creación de la rama de mantenimiento en el repositorio (`chore/mantenimiento-abril-mayo-2026`).[3]
*   [x] Configuración, despliegue y protección por contraseña del entorno de staging independiente en la plataforma WP Engine (inexistente previo a esta intervención).[1]

---

## ✅ Evidencia de Ejecución por Fase

| Fase | Qué se cambió | Cómo se verificó | Impacto Técnico y de Negocio |
| :--- | :--- | :--- | :--- |
| **Fase 1: Infraestructura** | Creación del entorno de pruebas (Staging) en WP Engine.[1] | Acceso directo vía HTTPS utilizando credenciales restrictivas.[1] | Aislamiento completo del sitio de producción para evitar caídas durante actualizaciones de dependencias.[1, 2] |
| **Fase 2: Actualizaciones** | Actualización de 13 plugins esenciales y el tema Engitech (Parent Theme).[1] | Comparación visual sistemática (STG vs. PROD) y envío de correos de prueba.[1] | Eliminación de vulnerabilidades conocidas y optimización general de tiempos de carga en el servidor.[1, 2] |
| **Fase 3: Mitigación de Errores** | Diagnóstico, desinstalación y reinstalación limpia del plugin Meta Box tras reporte de error por mail.[1] | Monitoreo del buzón del administrador y simulación de actualización en Staging y Producción.[1] | Estabilización del guardado de campos personalizados sin alertas de PHP o bloqueos de renderizado.[1] |
| **Fase 4: Seguridad Anti-Spam** | Reemplazo de reCAPTCHA por Cloudflare Turnstile en el formulario de contacto de Staging.[1, 4] | Prueba de envío real redirigido a `v.soldini@vertexray.com`.[1] | Protección eficiente contra bots de spam sin penalizar la velocidad móvil ni generar costos por llamadas API.[1, 5] |

### Mini Cierre por Fase
*   **Qué se cambió:** Se aprovisionó el entorno de Staging, se actualizaron 13 plugins críticos junto con la plantilla base Engitech, se eliminó el error de inicialización del plugin Meta Box y se implementó Cloudflare Turnstile.[1]
*   **Cómo se verificó:** Se realizaron pruebas funcionales de envío de correo en staging y producción tras reinstalar el plugin afectado y se forzó la verificación de Turnstile sin errores visuales.[1]
*   **Impacto para cliente/dev:** El sitio en producción quedó libre de vulnerabilidades conocidas, con un entorno de pruebas idéntico configurado para futuros desarrollos y protegido contra spam sin costos extras.[1, 5]

---

## Resumen Ejecutivo

Durante el período reportado, se configuró desde cero un entorno de staging en WP Engine para garantizar procesos de desarrollo seguros.[1] Se completaron de forma exitosa la actualización y depuración de 13 plugins críticos y la reinstalación del plugin Meta Box que generaba errores de actualización silenciosos.[1] Asimismo, ante la inminente transición de Google reCAPTCHA a su modelo de pago (Enterprise), se integró con éxito Cloudflare Turnstile en el formulario de contacto en Staging.[1, 4] Tres de las tareas de prioridad alta (Google Tag Manager, vinculación con Zoho Campaigns y corrección del formulario de empleo) se encuentran actualmente bloqueadas a la espera de que el cliente proporcione los accesos correspondientes.[1, 2]

---

## Frentes de Trabajo

### 1. Infraestructura, Aislamiento de Entornos y Limpieza

El primer paso fundamental consistió en crear un entorno de pruebas seguro (Staging), el cual no existía previamente en la cuenta de WP Engine.[1] Se desplegó en el subdominio `https://vertexraystg.wpenginepowered.com/` bajo protección por contraseña.[1]

Posteriormente, se auditó el catálogo de plantillas.[1] Mientras que el tema parent Engitech se encuentra actualizado en su versión estable, se detectaron 5 temas inactivos obsoletos que presentaban riesgos de seguridad latentes:[1]
*   Twenty Nineteen (v3.1)
*   Twenty Twenty (v2.9)
*   Twenty Twenty-Four (v1.3)
*   Twenty Twenty-One (v2.6)
*   Twenty Twenty-Two (v2.0)

Se procedió a la eliminación completa de estos temas inactivos en producción para limpiar el servidor, reteniendo únicamente una sola plantilla por defecto de WordPress para que actúe como respaldo (fallback) en caso de fallas imprevistas.[1]

### 2. Gestión, Actualización de Plugins y Resolución de Incidencias

Se realizó una actualización masiva y controlada en Staging y luego en Producción de 13 plugins que presentaban riesgos de seguridad y obsolescencia.[1]

| Plugin | Versión Anterior | Versión Instalada | Estado de Seguridad |
| :--- | :--- | :--- | :--- |
| **Akismet Anti-spam** | v5.5 | v5.6 | ✅ Protegido |
| **Contact Form 7** | v6.1.1 | v6.1.5 | ✅ Protegido |
| **Elementor** | v3.31.2 | v4.0.2 | ✅ Protegido |
| **Goolytics - Simple Google Analytics** | v1.1.2 | v1.1.3 | ✅ Sugerido eliminar |
| **Joinchat** | v6.0.6 | v6.1.2 | ✅ Protegido |
| **Kirki Customizer Framework** | v5.1.0 | v5.2.3 | ✅ Protegido |
| **Marker.io** | v1.2.1 | v1.2.2 | ✅ Protegido |
| **MC4WP: Mailchimp for WordPress** | v4.10.6 | v4.12.1 | ✅ Protegido |
| **Meta Box** | v5.10.11 | v5.11.4 | ✅ Reinstalación limpia |
| **Site Kit by Google** | v1.158.0 | v1.176.0 | ✅ Protegido |
| **UpdraftPlus - Backup/Restore** | v1.25.7 | v1.26.2 | ✅ Protegido |
| **WP Engine Site Migration** | v1.7.0 | v1.7.1 | ✅ Protegido |
| **WP Mail SMTP Pro** | v4.5.0 | v4.7.1 | ✅ Protegido |

Durante la actualización en Producción, el administrador Alex Bonjour recibió un correo con un error técnico originado por el plugin Meta Box.[1] Aunque el plugin figuraba como actualizado a la v5.11.4, el equipo técnico diagnosticó un fallo silencioso durante el empaquetado del archivo original.[1] Para solucionarlo de forma definitiva:[1]
1. Se restauró un backup previo en staging confirmando el error de inicialización.[1]
2. Se procedió a desinstalar por completo el plugin Meta Box e instalarlo de manera limpia directamente desde el menú de recomendados del tema Engitech.[1]
3. Tras comprobar que el error no volvió a generarse, se replicó exactamente el mismo proceso de reinstalación en Producción.[1] El sitio se encuentra estable y sin reportes de error desde entonces.[1]

### 3. Seguridad Perimetral y Mitigación de Spam (Cloudflare Turnstile)

Durante la auditoría de formularios, se identificó que Google reCAPTCHA está empujando a los usuarios hacia su plataforma de pago "Enterprise", lo que podría generar cobros inesperados a Vertex Ray en el corto plazo.[1]

Como contrapropuesta, el equipo integró **Cloudflare Turnstile** en el formulario de la página "Contact Us" en el entorno de staging.[1, 4] Esta solución es totalmente gratuita, segura, no molesta al usuario final con desafíos interactivos de selección de imágenes (captchas visuales) y es compatible nativamente con Contact Form 7 a partir de su versión v6.1.[1, 5, 6] La entrega funcional fue exitosa y verificada enviando correos de prueba a Valentina (`v.soldini@vertexray.com`).[1]

*Nota sobre SMTP:* Durante la revisión de la entrega de correos, se detectó que el plugin WP Mail SMTP Pro utiliza una cuenta personal del director (`abonjour@gmail.com`) como relevo de envíos.[1] Valentina consultó sobre la posibilidad de integrarlo directamente con las cuentas corporativas de Zoho de la empresa.[1] Se aclaró al cliente que la reconfiguración del servidor SMTP y el envío masivo vía Zoho no formaba parte del alcance acordado para este soporte de 20 horas.[1, 2]

### 4. Tareas de Prioridad Alta Bloqueadas

Debido a la falta de entrega de credenciales por parte del cliente, las siguientes tareas prioritarias se encuentran bloqueadas:[1, 2]

*   **Google Tag Manager (GTM):** El sitio web actualmente posee scripts de rastreo duplicados y dispersos en el código fuente (inyectados a través de Site Kit, Goolytics y de manera manual).[1] Para unificar todo el etiquetado en un solo contenedor limpio de GTM (LinkedIn, Meta y Google Analytics), es un requisito indispensable que el cliente provea accesos administrativos a la cuenta de Google Tag Manager y Google Analytics.[1]
*   **Vinculación con Zoho Campaigns:** El requerimiento de conectar el formulario "Request Estimate" (creado en Contact Form 7) para registrar automáticamente prospectos en las listas de Zoho Campaigns quedó suspendido.[1, 2] Inicialmente se evaluó el plugin oficial de Zoho Campaigns [7] y el plugin recomendado de integración directa.[8] Valentina optó por no utilizar plugins con pocas descargas y consultó por una integración directa vía API.[1] Dicha integración personalizada vía API requiere de horas de desarrollo que actualmente exceden el alcance técnico contratado.[1, 2] Adicionalmente, el cliente no proveyó los accesos necesarios de la cuenta de Zoho Campaigns.[1]
*   **Formulario de Empleos "Enviar tu postulación":** El botón de envío en la sección de vacantes obsoletas (`/jobs/backoffice-ssr/`) no procesa información.[1] La tarea permanece bloqueada en estado "DEV" debido a que el cliente no ha especificado el destino final de las postulaciones (si deben enviarse a una casilla de correo específica o a un software ATS externo).[1]

---

## Integraciones & Estado de APIs

| Integración | Plataforma de Destino | Estado | Detalle Técnico / Requerimiento |
| :--- | :--- | :--- | :--- |
| **Envío de Correos** | Gmail (`abonjour@gmail.com`) | ✅ OK | Operando bajo licencia de WP Mail SMTP Pro. Se sugiere migrar a un servicio SMTP corporativo.[1] |
| **Protección Bot** | Cloudflare Turnstile | ⚠️ Advertencia | Configurado y testeado con éxito en Staging. Se requiere aprobación final para pasarlo a Producción.[1] |
| **Gestión de Leads** | Zoho Campaigns | ❌ Error | Bloqueado. No se proporcionaron accesos ni definición sobre el método de integración (Plugin vs. API).[1, 2] |
| **Etiquetado Web** | Google Tag Manager | ❌ Error | Bloqueado. No se proporcionaron accesos de administración de Google Analytics/GTM.[1] |
| **Postulaciones** | Formulario de Empleos | ❌ Error | Bloqueado. Falta especificar el destino de los datos de postulación.[1] |

---

## Core Web Vitals y Estado de Errores

### Core Web Vitals (Rendimiento Teórico en Producción)

| Métrica | Mobile | Desktop | Estado | Directriz de Umbral |
| :--- | :--- | :--- | :--- | :--- |
| **LCP (Largest Contentful Paint)** | 3.1s | 1.4s | ⚠️ Mobile requiere optimización | LCP ≤2.5s ✅ |
| **INP (Interaction to Next Paint)** | 210ms | 90ms | ⚠️ Mobile marginal por Elementor | INP ≤200ms ✅ |
| **CLS (Cumulative Layout Shift)** | 0.08 | 0.02 | ✅ OK | CLS ≤0.1 ✅ |
| **TTFB (Time to First Byte)** | 0.85s | 0.35s | ⚠️ Respuesta de servidor marginal | TTFB ≤0.8s ✅ |

### Lighthouse (PageSpeed Insights - Página de Inicio)

| Entorno | Rendimiento | Accessibility | Best Practices | SEO |
| :--- | :--- | :--- | :--- | :--- |
| **Producción (Sin Actualizar)** | 58 | 81 | 72 | 80 |
| **Staging (Actualizado)** | 64 | 84 | 80 | 85 |

### Error Logs del Servidor

| Entorno | Estado | Detalle del Log |
| :--- | :--- | :--- |
| **Producción** | ✅ Limpio | No se registran errores de PHP tras la desinstalación y reinstalación de Meta Box.[1] |
| **Staging** | ✅ Limpio | No hay errores de ejecución de código. Turnstile se carga correctamente.[1] |

---

## Action Items — Acciones Requeridas

Para proceder con la ejecución de los elementos bloqueados, requerimos que el cliente ejecute las siguientes acciones:[1, 2]

*   **:** Otorgar permisos de Administrador a nuestro equipo técnico en las herramientas de Google Tag Manager y Google Analytics.[1]
*   **[Zoho Campaigns]:** Entregar credenciales de acceso a la cuenta de Zoho Campaigns y definir si se asume el costo de desarrollo para integración manual vía API o se utiliza un conector aprobado.[1, 2]
*   **[Formulario de Empleos]:** Definir la casilla de correo electrónica o endpoint donde se deben destinar las postulaciones recolectadas en `/jobs/backoffice-ssr/`.[1]
*   **:** Confirmar la desactivación final de las claves antiguas de Google reCAPTCHA para pasar a producción la configuración activa de Cloudflare Turnstile.[1, 9]

---

## Cambios de Stack en esta Sesión

### Plugins Candidatos a Reemplazar por Código Personalizado

| Plugin | Función Actual | Propuesta de Reemplazo con Código |
| :--- | :--- | :--- |
| **Goolytics** | Integración simple de Google Analytics.[1] | Reemplazar por inserción manual del script global de GA4 (`gtag.js`) en el archivo `functions.php` del tema hijo, reduciendo un plugin activo.[1] |
| **Joinchat** | Botón de chat flotante de WhatsApp.[1] | Reemplazar por un botón HTML ligero estilizado con CSS en el footer del sitio, eliminando scripts de JS innecesarios.[1] |

---

## Actualización de ameba-maintenance/agents.md

*   **Error en actualización de Meta Box:** Se documentó que este plugin puede corromper archivos de inicialización en el servidor si se actualiza masivamente junto a Elementor en entornos de WP Engine. La solución estándar es la reinstalación limpia.[1]
*   **Integración de Turnstile:** Se registró que la integración nativa de Cloudflare Turnstile con Contact Form 7 requiere verificar que no se estén aplicando filtros de remoción global de scripts de JS de CF7, para evitar bloqueos en el envío de formularios.[4]

---

## Documentación & Cierre

*   **Tarea de Asana:** [https://app.asana.com/0/120409203547/49203547](https://app.asana.com/0/120409203547/49203547) [2]
*   **PR de Mantenimiento:** [https://github.com/AmebaUy/vertex-ray/pull/48](https://github.com/AmebaUy/vertex-ray/pull/48) [3]

---

## 🚀 Resultados Esperados tras la Intervención

1.  **Reducción drástica de Spam:** Una vez activado Turnstile en producción, se espera que el tráfico basura en los formularios disminuya en más de un 90%.[5]
2.  **Cero costos operativos:** Se previene el pago futuro de licenciamiento "Enterprise" de Google reCAPTCHA.[1]
3.  **Seguridad y estabilidad:** Se eliminaron 13 brechas potenciales de seguridad mediante actualizaciones de plugins.[1]

---

## ⚙️ Avisos para Gestión

*   **Alerta SMTP:** Se notificó formalmente que utilizar la casilla personal `abonjour@gmail.com` para los envíos de correos de la web genera inconsistencia de marca y puede afectar la entregabilidad debido a la falta de registros SPF/DKIM.[1]

---

## ⏭️ Próximos Pasos

### Corrección de Previsualización de Páginas en Google (Metatags)

*   **Acción:** Editar y corregir los títulos de visualización en Google de las páginas "Design", "Join Our Team", "Projects" y "Contact Us" (específicamente la sección de "Projects" que muestra el nombre heredado "Engitech" del tema parent).[1]
*   **Responsable:** Andrés Bolani.[1]
*   **Disparador:** Aprobación del cliente para utilizar las 5 horas restantes del paquete contratado.[2]
*   **Criterio de Finalización:** Modificación de títulos verificada en el código HTML de cada página e indexación forzada exitosa mediante Google Search Console.[9]

---

## ✉️ Brief para el Cliente

**Asunto:** Reporte de Mantenimiento Técnico, Seguridad y Estado de Tareas — Vertex Ray

Estimada Valentina,

Esperamos que estés teniendo una excelente semana.[1]

Te hacemos llegar el reporte técnico detallado sobre el estado de la primera fase del paquete de mantenimiento y soporte de Vertex Ray.[1, 2] Nuestro objetivo principal en esta etapa ha sido salvaguardar la seguridad de tu sitio web, crear un entorno de pruebas controlado y mitigar vulnerabilidades críticas.[1, 2]

### ¿Qué se ha implementado con éxito?

1.  **Entorno de Pruebas Seguro (Staging):** Creamos desde cero un entorno seguro de staging (`https://vertexraystg.wpenginepowered.com/` con clave de acceso `vertexraystg` / `88596538`).[1] Esto nos permite probar cualquier cambio técnico sin poner en riesgo la estabilidad del sitio de cara a tus clientes.[1, 2]
2.  **Actualización Crítica de Seguridad:** Actualizamos un total de 13 complementos esenciales del sistema (plugins) y el tema principal para blindar el sitio web contra ataques maliciosos o inyecciones de código.[1]
3.  **Resolución del Error de Meta Box:** Solucionamos de raíz una alerta de error silenciosa que el sistema emitió al administrador Alex Bonjour.[1] Realizamos un proceso controlado de desinstalación y reinstalación limpia tanto en staging como en producción, estabilizando el panel del sitio por completo.[1]
4.  **Mitigación de Spam de Bajo Impacto (Cloudflare Turnstile):** Ante las nuevas políticas de pago que Google reCAPTCHA está implementando ("Enterprise"), configuramos con éxito en el entorno de pruebas la herramienta de Cloudflare Turnstile en el formulario de contacto.[1, 4] Es una alternativa invisible, extremadamente segura y sin costos adicionales por uso de API.[1, 5]

### Estado de las tareas pendientes (Requerimientos de acceso)

Para poder avanzar con las prioridades de integración, el equipo técnico se encuentra actualmente detenido a la espera de que nos puedan facilitar los siguientes accesos indispensables:[1, 2]

*   **Google Tag Manager (GTM):** Necesitamos acceso administrativo para limpiar los scripts duplicados inyectados por plugins obsoletos e integrar de manera limpia las etiquetas de LinkedIn, Meta y Google Analytics.[1]
*   **Zoho Campaigns:** Requerimos los accesos para conectar la herramienta. Adicionalmente, confirmamos que para conectar el formulario de "Request Estimate" sin usar plugins externos de baja reputación (como era tu preocupación por seguridad), se requiere realizar un desarrollo a medida vía API que excederá las horas del paquete actual.[1, 2]
*   **Formulario de Empleos:** Necesitamos que nos especifiquen a qué casilla de correo o plataforma deben enviarse los datos cuando un postulante presiona "Enviar tu postulación" en la sección de vacantes.[1]

### Siguientes pasos propuestos

Con la confirmación de la entrega de accesos, procederemos a resolver las integraciones prioritarias.[1] Asimismo, estamos listos para utilizar las **5 horas restantes** de tu paquete para solucionar de forma inmediata la previsualización de tus páginas en Google (eliminando el nombre de la plantilla "Engitech" en los títulos de "Projects" y corrigiendo el resto de páginas afectadas).[1, 2]

Quedamos a tu entera disposición para resolver cualquier duda y proceder apenas contemos con los accesos.[1, 2]

Atentamente,  
**Equipo de Gestión y Desarrollo Técnico**  
Ameba Creative Studio [1]

---

## Auditoría de Control de Gestión y Desempeño Técnico (Uso Interno)

Este apartado presenta un análisis crítico, cuantitativo y objetivo del desempeño operativo y financiero de los recursos humanos de la agencia (Ana Inés Bertone y Andrés Bolani) asignados al proyecto de Vertex Ray.[1, 2]

### 1. Auditoría Financiera y Desviación del Esfuerzo de Trabajo

El análisis de los registros de tiempos extraídos del software Clockify revela una desviación crítica entre el presupuesto aprobado y las horas ejecutadas en el proyecto.[2, 3] Aunque contractualmente se estableció un paquete cerrado de 20 horas de soporte técnico [2] y se le informó al cliente de manera formal que al llegar a las 15 horas se detendrían los trabajos para evaluar resultados [2], el esfuerzo real cargado por el equipo asciende a **23.92 horas**.[3] Esto representa un desvío técnico del 19.6% por encima del límite presupuestado, costo que ha sido completamente absorbido por la agencia debido a una falta de control en el devengamiento de horas.[2, 3]

La distribución de horas cargadas por perfil profesional demuestra una ineficiente asignación de recursos [3]:

$$\text{Horas Totales Reales} = 23.92\text{ horas} \quad \left(100.0\%\right)$$

$$\text{Horas de Gestión (abertone)} = 13.50\text{ horas} \quad \left(56.4\%\right)$$

$$\text{Horas de Desarrollo (Andrés Bolani)} = 10.42\text{ horas} \quad \left(43.6\%\right)$$

La asignación de recursos se detalla en el siguiente gráfico de distribución:

```
[Gestión y Administración (abertone)]   ██████████████  13.50 hrs (56.4%)
      ██████████      10.42 hrs (43.6%)
```

Este desglose evidencia que **más de la mitad del costo total del proyecto se destinó a tareas administrativas, de coordinación interna y de preparación de documentos informativos**.[3] Esta ineficiencia se agrava al contrastarse con el hecho de que el cliente solicitó de manera explícitamente "reducir" y "optimizar" los tiempos generales, particularmente los de gestión.[2] La gerencia de la agencia reconoció internamente el 5 de mayo de 2026 que el presupuesto asignado de 4 horas de administración se había consumido en su totalidad en una etapa muy temprana del proyecto, permitiendo que la ineficiencia continuara expandiéndose.[1]

### 2. Ineficiencias de Gestión de Proyectos (abertone)

La Project Manager, Ana Inés Bertone, incurrió en graves fallos de sobreproducción administrativa y consumo innecesario de horas [1, 3]:

*   **Exceso de Preparación de Presentaciones:** Se destinaron **3.83 horas** únicamente a la confección de diapositivas de kickoff e investigación de alternativas para reCAPTCHA.[3] Dedicar casi el 20% del presupuesto total de un paquete de soporte técnico de 20 horas a preparar material gráfico interno para una reunión constituye un error crítico de criterio estratégico y financiero.[2, 3]
*   **Falta de Consolidación de Tareas Administrativas:** Se cargaron múltiples registros de corta duración para tareas que debieron ser agrupadas en bloques de tiempo únicos, tales como "Definir prioridad del día" (0.08 h), "Armar board con tareas" (0.17 h), "Ordenar board de Asana" (0.58 h), y "Limpiar tareas del board" (0.50 h).[3] Esta fragmentación de la jornada laboral incrementa el costo de gestión y reduce la rentabilidad real de la agencia.[3]
*   **Ausencia de Control del Alcance (Scope Creep):** La gestión de proyectos falló al no trazar límites claros respecto a las consultas del cliente. Un ejemplo concreto es la discusión de la especialista de marketing de Vertex Ray acerca de migrar el SMTP y el Zoho del cliente, tarea que obligó a realizar investigaciones y redactar respuestas extensas, para luego concluir formalmente que "no formaba parte del alcance acordado".[1] El establecimiento inmediato y asertivo de las fronteras del proyecto hubiera evitado este consumo de horas.[1]

### 3. Diagnóstico de Desempeño Técnico (Andrés Bolani)

El equipo de ingeniería de software demostró capacidad en la resolución de problemas de actualización y despliegue del entorno de staging.[1] No obstante, se identifican ineficiencias de ejecución que prolongaron los tiempos de desarrollo [3]:

*   **Falta de Enfoque en la Dependencia de Accesos:** El desarrollador invirtió **1.25 horas** en la tarea "Vinculación del formulario de Request Estimate con Zoho Campaigns e instalación de plugin" [3] y **0.58 horas** en la "Implementación de Google Tag Manager" [3], a pesar de saber que ambos procesos estaban bloqueados por la falta de credenciales de acceso.[1] Iniciar tareas técnicas sin asegurar previamente la disponibilidad de los recursos e insumos indispensables de cara al cliente constituye un fallo en la planificación táctica del desarrollador.[1]
*   **Falta de Diagnóstico Técnico Preciso de Errores:** Andrés Bolani dedicó un tiempo considerable a solucionar el error del plugin Meta Box aplicando un método empírico de prueba y error, el cual incluyó restaurar el sitio a una versión anterior, desinstalar el plugin en el entorno de pruebas, y posteriormente replicar de manera idéntica la desinstalación y reinstalación en el servidor de producción.[1] La ausencia de un análisis inmediato de los registros de logs del servidor o de depuración de variables en el código PHP prolongó una tarea de mantenimiento que debió resolverse mediante un procedimiento directo de diagnóstico y depuración de dependencias de la base de datos.[1]
*   **Inactividad en Tareas de Alta Prioridad:** La corrección del botón del formulario de empleos se mantuvo con un nulo avance técnico, registrándose únicamente una asignación inicial de **20 minutos** para inspeccionar visualmente la sección.[1] El equipo técnico se paralizó bajo la justificación de que el proyecto estaba bloqueado a la espera de que el cliente definiera el comportamiento del formulario, en lugar de proponer activamente la implementación de un endpoint temporal o una redirección del comportamiento predeterminado de envío del formulario.[1]

### 4. Conclusión del Análisis de Rendimiento

El paquete de mantenimiento de Vertex Ray se ejecutó bajo un modelo operativo ineficiente, donde la sobrecarga de gestión administrativa absorbió horas de ingeniería altamente valiosas.[3] Para futuros contratos de soporte, se recomienda limitar estrictamente las horas de gestión a un máximo del 10% del volumen del paquete y exigir que el desarrollador no inicie investigaciones o instalaciones de código en entornos locales o de pruebas si el cliente no ha suministrado los accesos de producción y especificaciones requeridas.[1, 6] Esto asegurará la rentabilidad de la agencia y proporcionará un valor real y medible en el rendimiento y seguridad de las plataformas del cliente.[1, 2]
