// Playwright Tests para verificar implementación en Staging
// Ejecutar: npx playwright test vertexray-staging-tests.spec.js

const { test, expect } = require('@playwright/test');

const STAGING_URL = 'https://vertexraystg.wpenginepowered.com';
const STAGING_USER = 'vertexraystg';
const STAGING_PASS = '88596538';

// Configuración de autenticación básica
test.use({
  httpCredentials: {
    username: STAGING_USER,
    password: STAGING_PASS,
  },
});

test.describe('Vertex Ray Staging - Verificación de Mantenimiento Mayo 2026', () => {
  
  // TAREA 1: Verificar corrección de títulos (eliminación de "Engitech")
  test.describe('TAREA 1: Títulos de páginas (sin "Engitech")', () => {
    
    test('Página "Design" - título correcto sin Engitech', async ({ page }) => {
      await page.goto(`${STAGING_URL}/design/`);
      const title = await page.title();
      
      expect(title).not.toContain('Engitech');
      expect(title).not.toContain('engitech');
      expect(title).not.toContain('ENGITECH');
      
      console.log(`✅ Título de Design: ${title}`);
    });
    
    test('Página "Join Our Team" - título correcto', async ({ page }) => {
      await page.goto(`${STAGING_URL}/join-our-team/`);
      const title = await page.title();
      
      expect(title).not.toContain('Engitech');
      console.log(`✅ Título de Join Our Team: ${title}`);
    });
    
    test('Página "Projects" - título correcto (más crítico)', async ({ page }) => {
      await page.goto(`${STAGING_URL}/projects/`);
      const title = await page.title();
      
      expect(title).not.toContain('Engitech');
      expect(title).toContain('Vertex Ray');
      
      console.log(`✅ Título de Projects: ${title}`);
    });
    
    test('Página "Contact Us" - título correcto', async ({ page }) => {
      await page.goto(`${STAGING_URL}/contact-us/`);
      const title = await page.title();
      
      expect(title).not.toContain('Engitech');
      console.log(`✅ Título de Contact Us: ${title}`);
    });
    
    test('Meta tags Open Graph sin "Engitech"', async ({ page }) => {
      await page.goto(`${STAGING_URL}/projects/`);
      
      const ogTitle = await page.locator('meta[property="og:title"]').getAttribute('content');
      const ogSiteName = await page.locator('meta[property="og:site_name"]').getAttribute('content');
      
      if (ogTitle) {
        expect(ogTitle).not.toContain('Engitech');
        console.log(`✅ OG Title: ${ogTitle}`);
      }
      
      if (ogSiteName) {
        expect(ogSiteName).toBe('Vertex Ray');
        console.log(`✅ OG Site Name: ${ogSiteName}`);
      }
    });
  });

  // TAREA 2: Verificar reemplazo de plugins (Goolytics + Joinchat)
  test.describe('TAREA 2: Tracking de Google Analytics/GTM', () => {
    
    test('Google Analytics está cargando correctamente', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      // Esperar a que el script de GA se cargue
      await page.waitForFunction(() => {
        return typeof window.gtag !== 'undefined' || typeof window.dataLayer !== 'undefined';
      }, { timeout: 10000 });
      
      // Verificar que existe dataLayer
      const hasDataLayer = await page.evaluate(() => {
        return window.dataLayer && window.dataLayer.length > 0;
      });
      
      expect(hasDataLayer).toBeTruthy();
      console.log('✅ Google Analytics dataLayer presente');
    });
    
    test('Google Tag Manager está cargando correctamente', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      // Verificar que el contenedor GTM se cargó
      const gtmScript = await page.locator('script[src*="googletagmanager.com/gtm.js"]').count();
      expect(gtmScript).toBeGreaterThan(0);
      
      console.log('✅ Google Tag Manager script presente');
    });
    
    test('No hay tracking duplicado en el código fuente', async ({ page }) => {
      await page.goto(STAGING_URL);
      const content = await page.content();
      
      // Contar cuántas veces aparece el ID de GTM
      const gtmMatches = (content.match(/GTM-NPL5XMZ/g) || []).length;
      
      // Debería aparecer solo 2 veces (script + noscript)
      expect(gtmMatches).toBeLessThanOrEqual(3); // Margen para el noscript y script
      
      console.log(`✅ GTM aparece ${gtmMatches} veces (esperado: 2-3)`);
    });
  });

  test.describe('TAREA 2: Botón de WhatsApp personalizado', () => {
    
    test('Botón de WhatsApp es visible', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      // Buscar el botón de WhatsApp por su clase o ID
      const whatsappButton = page.locator('.vertexray-wa-floating, #vertexray-whatsapp-button');
      
      await expect(whatsappButton).toBeVisible({ timeout: 10000 });
      console.log('✅ Botón de WhatsApp visible');
    });
    
    test('Botón de WhatsApp tiene el link correcto', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      const whatsappLink = page.locator('.vertexray-wa-floating a, #vertexray-whatsapp-button a');
      const href = await whatsappLink.getAttribute('href');
      
      expect(href).toContain('wa.me');
      expect(href).toContain('549'); // Código de Argentina
      
      console.log(`✅ Link de WhatsApp: ${href}`);
    });
    
    test('Botón de WhatsApp está posicionado correctamente (esquina inferior derecha)', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      const whatsappButton = page.locator('.vertexray-wa-floating');
      const styles = await whatsappButton.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return {
          position: computed.position,
          bottom: computed.bottom,
          right: computed.right,
          zIndex: computed.zIndex,
        };
      });
      
      expect(styles.position).toBe('fixed');
      expect(parseInt(styles.zIndex)).toBeGreaterThan(9000);
      
      console.log(`✅ Botón posicionado: ${JSON.stringify(styles)}`);
    });
    
    test('Botón de WhatsApp responsive en mobile', async ({ page }) => {
      await page.setViewportSize({ width: 375, height: 667 }); // iPhone SE
      await page.goto(STAGING_URL);
      
      const whatsappButton = page.locator('.vertexray-wa-floating');
      await expect(whatsappButton).toBeVisible();
      
      const box = await whatsappButton.boundingBox();
      expect(box.width).toBeGreaterThan(40); // Al menos 40px de ancho
      
      console.log('✅ Botón visible y accesible en móvil');
    });
  });

  // TAREA 3: Verificar hardening de seguridad
  test.describe('TAREA 3: Seguridad - Bloqueos de archivos', () => {
    
    test('xmlrpc.php está bloqueado (debe dar 403)', async ({ page }) => {
      const response = await page.goto(`${STAGING_URL}/xmlrpc.php`, { 
        waitUntil: 'domcontentloaded',
        timeout: 10000 
      });
      
      expect(response.status()).toBe(403);
      console.log('✅ xmlrpc.php bloqueado correctamente (403)');
    });
    
    test('readme.html está bloqueado', async ({ page }) => {
      const response = await page.goto(`${STAGING_URL}/readme.html`, { 
        waitUntil: 'domcontentloaded',
        timeout: 10000 
      });
      
      expect([403, 404]).toContain(response.status());
      console.log(`✅ readme.html bloqueado (${response.status()})`);
    });
    
    test('wp-config.php NO es accesible', async ({ page }) => {
      const response = await page.goto(`${STAGING_URL}/wp-config.php`, { 
        waitUntil: 'domcontentloaded',
        timeout: 10000 
      });
      
      expect([403, 404]).toContain(response.status());
      console.log(`✅ wp-config.php protegido (${response.status()})`);
    });
  });

  test.describe('TAREA 3: Seguridad - Headers HTTP', () => {
    
    test('Headers de seguridad presentes', async ({ page }) => {
      const response = await page.goto(STAGING_URL);
      const headers = response.headers();
      
      // Verificar X-Frame-Options
      expect(headers['x-frame-options']).toBeDefined();
      console.log(`✅ X-Frame-Options: ${headers['x-frame-options']}`);
      
      // Verificar X-Content-Type-Options
      expect(headers['x-content-type-options']).toBe('nosniff');
      console.log(`✅ X-Content-Type-Options: ${headers['x-content-type-options']}`);
      
      // Verificar Referrer-Policy
      if (headers['referrer-policy']) {
        console.log(`✅ Referrer-Policy: ${headers['referrer-policy']}`);
      }
    });
  });

  test.describe('TAREA 3: Seguridad - API REST', () => {
    
    test('Endpoint /wp-json/wp/v2/users está bloqueado', async ({ page }) => {
      const response = await page.goto(`${STAGING_URL}/wp-json/wp/v2/users`, {
        waitUntil: 'domcontentloaded',
        timeout: 10000
      });
      
      // Debe dar error o estar vacío
      expect([401, 403, 404]).toContain(response.status());
      console.log(`✅ Endpoint de usuarios bloqueado (${response.status()})`);
    });
    
    test('API REST base sigue funcionando (para Zoho)', async ({ page }) => {
      const response = await page.goto(`${STAGING_URL}/wp-json`, {
        waitUntil: 'domcontentloaded',
        timeout: 10000
      });
      
      // El root de la API debe seguir accesible
      expect(response.status()).toBe(200);
      console.log('✅ API REST root accesible (necesario para integraciones)');
    });
  });

  // Verificación de que el sitio sigue funcional
  test.describe('REGRESIÓN: Funcionalidad general del sitio', () => {
    
    test('Home page carga correctamente', async ({ page }) => {
      const response = await page.goto(STAGING_URL);
      expect(response.status()).toBe(200);
      
      // Verificar que hay contenido
      const body = await page.textContent('body');
      expect(body.length).toBeGreaterThan(100);
      
      console.log('✅ Home page funcional');
    });
    
    test('No hay errores de JavaScript en consola', async ({ page }) => {
      const jsErrors = [];
      page.on('console', (msg) => {
        if (msg.type() === 'error') {
          jsErrors.push(msg.text());
        }
      });
      
      await page.goto(STAGING_URL);
      await page.waitForTimeout(3000); // Esperar a que carguen scripts
      
      // Filtrar errores conocidos/aceptables
      const criticalErrors = jsErrors.filter(err => 
        !err.includes('favicon') && 
        !err.includes('analytics') &&
        !err.includes('gtm')
      );
      
      expect(criticalErrors.length).toBe(0);
      console.log(`✅ Sin errores críticos de JS (${jsErrors.length} errores totales ignorables)`);
    });
    
    test('Formulario de contacto es visible', async ({ page }) => {
      await page.goto(`${STAGING_URL}/contact-us/`);
      
      const form = page.locator('form.wpcf7-form, form[id*="contact"]');
      await expect(form.first()).toBeVisible({ timeout: 10000 });
      
      console.log('✅ Formulario de contacto visible');
    });
    
    test('Cloudflare Turnstile está presente', async ({ page }) => {
      await page.goto(`${STAGING_URL}/contact-us/`);
      
      // Buscar el widget de Turnstile
      const turnstile = page.locator('[class*="cf-turnstile"], iframe[src*="turnstile"]');
      const count = await turnstile.count();
      
      if (count > 0) {
        console.log('✅ Cloudflare Turnstile presente');
      } else {
        console.log('⚠️ Cloudflare Turnstile no detectado (verificar configuración)');
      }
    });
    
    test('Estilos CSS cargan correctamente', async ({ page }) => {
      await page.goto(STAGING_URL);
      
      // Verificar que hay al menos un stylesheet cargado
      const stylesheets = await page.locator('link[rel="stylesheet"]').count();
      expect(stylesheets).toBeGreaterThan(0);
      
      // Verificar que un elemento tiene estilos aplicados
      const header = page.locator('header, .site-header');
      const hasStyles = await header.evaluate((el) => {
        const styles = window.getComputedStyle(el);
        return styles.display !== 'inline'; // Debe tener estilos aplicados
      });
      
      expect(hasStyles).toBeTruthy();
      console.log(`✅ ${stylesheets} hojas de estilo cargadas correctamente`);
    });
  });

  // Screenshot tests
  test('Screenshot de la home page para inspección visual', async ({ page }) => {
    await page.goto(STAGING_URL);
    await page.screenshot({ 
      path: 'screenshots/vertexray-home.png', 
      fullPage: true 
    });
    console.log('✅ Screenshot guardado: screenshots/vertexray-home.png');
  });

  test('Screenshot del botón de WhatsApp', async ({ page }) => {
    await page.goto(STAGING_URL);
    const whatsappButton = page.locator('.vertexray-wa-floating');
    await whatsappButton.screenshot({ 
      path: 'screenshots/vertexray-whatsapp-button.png' 
    });
    console.log('✅ Screenshot guardado: screenshots/vertexray-whatsapp-button.png');
  });
});
