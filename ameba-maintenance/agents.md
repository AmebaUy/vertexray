# Vertex Ray - Configuración de Mantenimiento

## Stack Tecnológico

- **CMS:** WordPress 7.0 (actualizado 26/05/2026 de v6.9.4)
- **Database Version:** 61833
- **PHP:** 8.4 (WP Engine)
- **Tema:** Engitech Child (hijo de Engitech)
- **Page Builder:** Elementor 4.1.0 (actualizado 26/05/2026)
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
- **Google Tag Manager:** GTM-NPL5XMZ (código custom en functions.php desde Mayo 2026)
- **Google Analytics:** UA-2468946-1 (código custom en functions.php desde Mayo 2026)
- **Google Site Kit:** v1.179.0 (actualizado 26/05/2026) ⚠️ **Verificar duplicación de tracking**

### Email & CRM
- **SMTP:** WP Mail SMTP Pro v4.8.0 (Gmail: abonjour@gmail.com)
- **Formularios:** Contact Form 7 v6.1.6 (actualizado 26/05/2026)
- **Anti-spam:** Cloudflare Turnstile (integrado en Contact Form 7)
- **Zoho Campaigns:** v2.1.7 - Pendiente de integración (bloqueado por falta de credenciales API)

### Respaldo & Seguridad
- **Backups:** UpdraftPlus Premium v1.26.4 (actualizado 26/05/2026 - parche de seguridad)
- **Anti-spam secundario:** Akismet v5.7 (actualizado 26/05/2026)
- **WAF:** WP Engine security layer
- **Hardening custom:** Implementado Mayo 2026 (código PHP + .htaccess - ver site-stack)

## Plugins Activos Críticos (Actualizado 26/05/2026)

**Total:** 15 plugins activos

1. **Elementor** v4.1.0 (actualizado de v4.0.2)
2. **Contact Form 7** v6.1.6 (actualizado de v6.1.5)
3. **WP Mail SMTP Pro** v4.8.0
4. **Meta Box** v5.12.0 (actualizado de v5.11.4) ⚠️ **MONITOREAR** (historial de fallos en Abril)
5. **UpdraftPlus** v1.26.4 (actualizado de v1.26.2 - security patch)
6. **Kirki** v6.0.9 (actualizado de v5.2.3 - **MAJOR RELEASE**)
7. **Google Site Kit** v1.179.0 (actualizado de v1.176.0)
8. **Akismet** v5.7 (actualizado de v5.6)
9. **Joinchat (creame-whatsapp-me)** v6.2.3 (actualizado de v6.1.3) ✅ **MANTENER** (decisión arquitectural Mayo 2026)
10. **MC4WP Mailchimp** v4.12.6 (actualizado de v4.12.1)
11. **Zoho Campaigns** v2.1.7
12. **Marker.io** v1.2.2
13. **WP Engine Site Migration** v1.8.1 (actualizado de v1.7.1)
14. **OT Portfolios** v1.0
15. **One Click Import Demo Content** v1.0

**Plugins Eliminados:**
- ❌ **Goolytics** v1.1.3 - Eliminado 26/05/2026, reemplazado por código custom

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
