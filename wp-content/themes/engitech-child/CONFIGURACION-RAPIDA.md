# ⚙️ CONFIGURACIÓN RÁPIDA - VARIABLES A PERSONALIZAR

## 📱 1. NÚMERO DE WHATSAPP

**Ubicación:** `functions.php` línea 228

**Valor actual:**
```php
$whatsapp_number = '5491153840067';
```

**Cambiar a:**
```php
$whatsapp_number = 'XXXXXXXXXXX'; // ← TU NÚMERO AQUÍ
```

**Formato:** 
- Código de país + número (sin +, espacios, guiones, paréntesis)
- Ejemplo USA: `15551234567`
- Ejemplo Argentina: `5491153840067`
- Ejemplo España: `34612345678`

---

## 💬 2. MENSAJE PREDETERMINADO DE WHATSAPP (OPCIONAL)

**Ubicación:** `functions.php` línea 231

**Valor actual:**
```php
$default_message = 'Hello! I\'m interested in your services';
```

**Sugerencias:**
```php
// Opción 1: Inglés formal
$default_message = 'Hello! I would like to learn more about your services';

// Opción 2: Español
$default_message = 'Hola! Me interesa conocer más sobre sus servicios';

// Opción 3: Sin mensaje (deja que el usuario escriba)
$default_message = '';
```

---

## 📊 3. IDS DE GOOGLE ANALYTICS / TAG MANAGER

**Ubicación:** `functions.php` líneas 139-140

**Valores detectados actualmente:**
```php
$ga_tracking_id = 'UA-2468946-1';  // Google Analytics Universal
$gtm_id = 'GTM-NPL5XMZ';          // Google Tag Manager
```

**⚠️ IMPORTANTE:** Estos IDs fueron detectados en tu `header.php` actual. 

**Verificar en:**
1. Google Analytics: https://analytics.google.com/ → Admin → Property Settings
2. Google Tag Manager: https://tagmanager.google.com/ → Container ID

**Si son diferentes, cambiar a:**
```php
$ga_tracking_id = 'UA-XXXXXXX-X';  // ← TU ID DE GA
$gtm_id = 'GTM-XXXXXXX';           // ← TU ID DE GTM
```

---

## 🎨 4. PERSONALIZACIÓN DEL BOTÓN DE WHATSAPP (OPCIONAL)

### Cambiar posición del botón

**Ubicación:** `functions.php` líneas 262-267

**Posición actual:** Abajo derecha
```css
.vertexray-wa-floating {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}
```

**Cambiar a abajo izquierda:**
```css
.vertexray-wa-floating {
    position: fixed;
    bottom: 20px;
    left: 20px;  /* Cambiar right por left */
    z-index: 9999;
}
```

### Cambiar tamaño del botón

**Ubicación:** `functions.php` líneas 269-278

**Tamaño actual:** 60x60px
```css
.vertexray-wa-floating a {
    width: 60px;
    height: 60px;
}
```

**Cambiar a botón más grande:**
```css
.vertexray-wa-floating a {
    width: 70px;   /* Aumentar tamaño */
    height: 70px;
}
```

### Cambiar color del botón

**Ubicación:** `functions.php` línea 274

**Color actual:** Verde WhatsApp oficial (#25D366)
```css
background: #25D366;
```

**Cambiar a color corporativo (ejemplo):**
```css
background: #1a73e8;  /* Azul corporativo */
background: #ff6b6b;  /* Rojo */
background: #4ecdc4;  /* Turquesa */
```

### Ocultar botón en páginas específicas

**Agregar después de la línea 187 en `functions.php`:**

```php
function vertexray_whatsapp_button() {
    // NO mostrar el botón en estas páginas
    if ( is_page( array( 'checkout', 'cart', 'thank-you' ) ) ) {
        return; // Salir sin mostrar el botón
    }
    
    // NO mostrar en páginas de login/admin
    if ( is_admin() || is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Resto del código...
```

---

## 🔒 5. CONFIGURACIONES DE SEGURIDAD AVANZADAS (OPCIONAL)

### Bloquear completamente la REST API

**Ubicación:** `functions.php` líneas 347-356

**Estado actual:** Comentado (solo bloquea endpoints de usuarios)

**Para bloquear TODO (no recomendado):**
```php
// Descomentar estas líneas:
function vertexray_restrict_rest_api( $result ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_forbidden',
            __( 'REST API restricted to authenticated users.', 'engitech-child' ),
            array( 'status' => 401 )
        );
    }
    return $result;
}
add_filter( 'rest_authentication_errors', 'vertexray_restrict_rest_api' );
```

⚠️ **ADVERTENCIA:** Esto puede romper plugins que usen la API REST (formularios, etc.)

### Habilitar Content Security Policy (CSP)

**Ubicación:** `functions.php` líneas 371-373

**Estado actual:** Comentado

**Para habilitar:**
```php
// Descomentar esta línea:
header( "Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;" );
```

⚠️ **ADVERTENCIA:** Probar exhaustivamente, puede romper funcionalidades.

---

## 🧹 6. LIMPIEZA DEL HEADER.PHP (CRÍTICO)

**Ubicación:** `wp-content/themes/engitech-child/header.php`

**OPCIÓN A: Renombrar archivo limpio (RECOMENDADO)**

```bash
cd wp-content/themes/engitech-child/
cp header.php header-backup-2026-05.php
mv header-clean.php header.php
```

**OPCIÓN B: Editar manualmente**

**BUSCAR estas líneas (20-29):**
```html
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-2468946-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-2468946-1');
</script>
<!-- Google Tag Manager --> <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','GTM-NPL5XMZ');</script> <!-- End Google Tag Manager -->
```

**Y ELIMINARLAS** (o comentarlas con `<!-- -->`).

**TAMBIÉN BUSCAR línea 33:**
```html
<!-- Google Tag Manager (noscript) --> <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NPL5XMZ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> <!-- End Google Tag Manager (noscript) -->
```

**Y ELIMINARLA** (o comentarla).

---

## ✅ CHECKLIST DE CONFIGURACIÓN

Antes de implementar en producción, verificar:

- [ ] Número de WhatsApp actualizado en `functions.php` línea 228
- [ ] Mensaje de WhatsApp personalizado (opcional) línea 231
- [ ] IDs de Google Analytics/GTM verificados líneas 139-140
- [ ] `header.php` limpiado (tracking duplicado eliminado)
- [ ] Estilos del botón personalizados (opcional)
- [ ] Posición del botón ajustada (opcional)
- [ ] Backup completo realizado ANTES de subir cambios

---

## 🚀 COMANDO RÁPIDO DE DEPLOY (LOCAL → PRODUCCIÓN)

### Usando ameba-deploy:
```bash
cd ameba-deploy
npm run deploy
```

### Usando Local by Flywheel:
1. Abrir Local by Flywheel
2. Click derecho en el sitio "vertexray"
3. Seleccionar "Push to Live"
4. Confirmar archivos a subir
5. Esperar sincronización

---

## 🔍 VERIFICACIÓN POST-DEPLOY

### 1. Verificar tracking de Google (con Google Tag Assistant):
```
https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk
```

### 2. Verificar botón de WhatsApp:
- Abrir vertexray.com
- Ver botón verde abajo a la derecha
- Click → debe abrir WhatsApp con tu número

### 3. Verificar seguridad:
- Ir a: `https://vertexray.com/xmlrpc.php` → debe dar error 403
- Ir a: `https://vertexray.com/wp-json/wp/v2/users` → debe dar error 401/403
- Ver código fuente → NO debe aparecer versión de WordPress

### 4. Verificar títulos SEO:
```
site:vertexray.com Projects
```
- NO debe aparecer "Engitech" en resultados (puede tardar días en actualizar)

---

## 📞 SOPORTE

Si algo no funciona después de la implementación:

1. **Revisar logs de errores:**
   - `wp-content/debug.log` (si WP_DEBUG está activo)
   - Logs del servidor (Apache/Nginx)

2. **Rollback rápido:**
   ```bash
   mv functions.php functions.php.disabled
   cp header-backup-2026-05.php header.php
   ```

3. **Limpiar caché:**
   - LiteSpeed Cache → Toolbox → Purge All
   - Navegador (Ctrl+Shift+Delete)

---

**NOTA IMPORTANTE:** Todas estas configuraciones son opcionales excepto:
1. ✅ **Número de WhatsApp** (obligatorio para que funcione el botón)
2. ✅ **Limpieza del header.php** (obligatorio para evitar tracking duplicado)

El resto de configuraciones son ajustes finos que puedes hacer después de verificar que todo funciona correctamente.
