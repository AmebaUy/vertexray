# Vertex Ray - Configuración de Mantenimiento

## Stack Tecnológico

- **CMS:** WordPress 6.x
- **Tema:** Engitech Child (hijo de Engitech)
- **Page Builder:** Elementor 4.0.2
- **Hosting:** WP Engine
- **Entorno de Staging:** https://vertexraystg.wpenginepowered.com/
- **Entorno de Producción:** https://vertexray.com
- **Idioma del Cliente:** Español (Argentina)

## Método de Deploy

- **Herramienta:** ameba-deploy (configurado en directorio raíz)
- **Método primario:** Git + Sincronización selectiva
- **Respaldo:** Local by Flywheel "Push to Live"
- **Repositorio:** GitHub - AmebaUy/vertexray
- **Rama principal:** dev

## Integraciones Activas

### Marketing & Analytics
- **Google Tag Manager:** GTM-NPL5XMZ
- **Google Analytics:** UA-2468946-1 (Universal Analytics legacy)
- **Google Site Kit:** Instalado y activo

### Email & CRM
- **SMTP:** WP Mail SMTP Pro (Gmail: abonjour@gmail.com)
- **Formularios:** Contact Form 7 v6.1.5
- **Anti-spam:** Cloudflare Turnstile (configurado en staging, pendiente activación en prod)
- **Zoho Campaigns:** Pendiente de integración (bloqueado por falta de credenciales)

### Respaldo & Seguridad
- **Backups:** UpdraftPlus Premium v1.26.2
- **Anti-spam secundario:** Akismet v5.6
- **WAF:** WP Engine security layer

## Plugins Activos Críticos

1. Elementor 4.0.2
2. Contact Form 7 6.1.5
3. WP Mail SMTP Pro 4.7.1
4. Meta Box 5.11.4 (reinstalado limpiamente tras error)
5. UpdraftPlus 1.26.2
6. Kirki Customizer Framework 5.2.3
7. Google Site Kit 1.176.0
8. Goolytics 1.1.3 ⚠️ **CANDIDATO A ELIMINACIÓN**
9. Joinchat 6.1.2 ⚠️ **CANDIDATO A ELIMINACIÓN**

## Configuraciones Especiales

### Redis Cache (WP Engine)
```php
// En wp-config.php debe existir:
define( 'WP_CACHE_KEY_SALT', 'vertexray_' );
```
Estado: ⚠️ **Pendiente de verificación**

### Detección de Entorno
El código personalizado detecta automáticamente entornos local/staging por:
- `localhost`, `.local`, `.test`, `.dev` en el hostname
- IP 127.0.0.1 o ::1
- Palabra `staging` en el hostname

### WhatsApp Business
- **Número actual (pendiente de verificación):** +54 9 11 5384 0067
- **Formato internacional:** 5491153840067

## Historial de Problemas Conocidos

### Meta Box Plugin
**Problema:** Error de inicialización tras actualización masiva junto a Elementor  
**Solución:** Desinstalación completa + reinstalación limpia desde el menú de Engitech  
**Estado:** Resuelto en abril 2026  

### Tracking Duplicado
**Problema:** Scripts de GA/GTM dispersos en múltiples ubicaciones (Site Kit, Goolytics, hardcodeado en header.php)  
**Solución propuesta:** Centralizar en functions.php y eliminar Goolytics  
**Estado:** ✅ Código generado en mayo 2026  

### Botón de WhatsApp
**Problema:** Plugin Joinchat añade peso innecesario (~20-30KB)  
**Solución propuesta:** Reemplazar por botón HTML/CSS puro  
**Estado:** ✅ Código generado en mayo 2026  

## Contactos del Cliente

- **Director:** Alex Bonjour (abonjour@gmail.com)
- **Project Manager:** Valentina Soldini (v.soldini@vertexray.com)

## Credenciales Pendientes (Bloqueadores)

- [ ] Acceso Admin a Google Tag Manager
- [ ] Acceso Admin a Google Analytics
- [ ] Credenciales de Zoho Campaigns
- [ ] Destino de postulaciones del formulario de empleos

## Notas de Mantenimiento

### Sesión Abril 2026
- Creado entorno de staging en WP Engine
- Actualizados 13 plugins críticos
- Eliminados 5 temas inactivos obsoletos
- Resuelto error de Meta Box
- Implementado Cloudflare Turnstile en staging

### Sesión Mayo 2026 (Actual)
- ✅ Generado código para eliminar "Engitech" de títulos SEO
- ✅ Generado reemplazo de Goolytics por código nativo
- ✅ Generado reemplazo de Joinchat por botón HTML/CSS
- ✅ Implementado hardening de seguridad sin plugins
- ⏳ Pendiente: configurar número de WhatsApp correcto
- ⏳ Pendiente: limpiar header.php de tracking duplicado
- ⏳ Pendiente: testing en staging
- ⏳ Pendiente: deploy a producción

## Divergencias Git vs Producción

_Sección para documentar diferencias entre el código en git y lo que está corriendo en producción_

Última actualización: 26 de mayo de 2026
