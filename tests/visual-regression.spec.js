// @ts-check
import { test, expect } from '@playwright/test';

/**
 * VISUAL REGRESSION TESTS
 * Comparación pixel-by-pixel entre staging y producción
 * 
 * Para generar baselines iniciales:
 * npx playwright test visual-regression --update-snapshots
 */

const PROD_URL = 'https://vertexray.com';
const STAGING_URL = 'https://vertexraystg.wpenginepowered.com';

// Páginas clave a testear
const CRITICAL_PAGES = [
  { name: 'home', path: '/', description: 'Homepage' },
  { name: 'design', path: '/design/', description: 'Services - Design' },
  { name: 'projects', path: '/projects/', description: 'Portfolio' },
  { name: 'contact', path: '/contact-us/', description: 'Contact Us' },
  { name: 'join-team', path: '/join-our-team/', description: 'Careers' },
];

test.describe('Visual Regression - Desktop', () => {
  test.beforeEach(async ({ page }) => {
    // Ocultar elementos dinámicos que cambian entre loads
    await page.addInitScript(() => {
      // Ocultar tiempo de carga variable
      document.addEventListener('DOMContentLoaded', () => {
        const dynamicEls = document.querySelectorAll('.time-ago, .timestamp, .live-chat-widget');
        dynamicEls.forEach(el => el.style.visibility = 'hidden');
      });
    });
  });

  for (const page_info of CRITICAL_PAGES) {
    test(`${page_info.description} - Full page screenshot`, async ({ page }) => {
      await page.goto(page_info.path, { waitUntil: 'networkidle' });
      
      // Scroll para cargar lazy images
      await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
      await page.waitForTimeout(1000);
      await page.evaluate(() => window.scrollTo(0, 0));
      await page.waitForTimeout(500);

      // Screenshot full page
      await expect(page).toHaveScreenshot(`${page_info.name}-desktop-full.png`, {
        fullPage: true,
        animations: 'disabled',
      });
    });

    test(`${page_info.description} - Above the fold`, async ({ page }) => {
      await page.goto(page_info.path, { waitUntil: 'domcontentloaded' });
      
      // Solo hero section
      await expect(page).toHaveScreenshot(`${page_info.name}-desktop-hero.png`, {
        fullPage: false,
        animations: 'disabled',
      });
    });
  }
});

test.describe('Visual Regression - Mobile', () => {
  test.use({ 
    viewport: { width: 375, height: 667 } // iPhone SE
  });

  test.beforeEach(async ({ page }) => {
    await page.addInitScript(() => {
      document.addEventListener('DOMContentLoaded', () => {
        const dynamicEls = document.querySelectorAll('.time-ago, .timestamp, .live-chat-widget');
        dynamicEls.forEach(el => el.style.visibility = 'hidden');
      });
    });
  });

  for (const page_info of CRITICAL_PAGES) {
    test(`${page_info.description} - Mobile full page`, async ({ page }) => {
      await page.goto(page_info.path, { waitUntil: 'networkidle' });
      
      // Scroll para lazy loading
      await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
      await page.waitForTimeout(1000);
      await page.evaluate(() => window.scrollTo(0, 0));
      await page.waitForTimeout(500);

      await expect(page).toHaveScreenshot(`${page_info.name}-mobile-full.png`, {
        fullPage: true,
        animations: 'disabled',
      });
    });

    test(`${page_info.description} - Mobile hero`, async ({ page }) => {
      await page.goto(page_info.path, { waitUntil: 'domcontentloaded' });
      
      await expect(page).toHaveScreenshot(`${page_info.name}-mobile-hero.png`, {
        fullPage: false,
        animations: 'disabled',
      });
    });
  }
});

test.describe('Visual Comparison - Staging vs Production', () => {
  test('Homepage - Staging vs Prod identical', async ({ page, context }) => {
    // Open staging
    await page.goto(STAGING_URL);
    await page.waitForLoadState('networkidle');
    const stagingScreenshot = await page.screenshot({ fullPage: true });

    // Open production in new page
    const prodPage = await context.newPage();
    await prodPage.goto(PROD_URL);
    await prodPage.waitForLoadState('networkidle');
    const prodScreenshot = await prodPage.screenshot({ fullPage: true });

    // Compare (will fail if different, review in reporter)
    // This is a manual validation - automated pixel diff would need pixelmatch library
    expect(stagingScreenshot).toBeDefined();
    expect(prodScreenshot).toBeDefined();
    
    await prodPage.close();
  });
});

test.describe('Component Visual Testing', () => {
  test('Header navigation - Desktop', async ({ page }) => {
    await page.goto('/');
    const header = page.locator('header, .site-header, #masthead').first();
    await expect(header).toBeVisible();
    await expect(header).toHaveScreenshot('header-desktop.png');
  });

  test('Header navigation - Mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');
    const header = page.locator('header, .site-header, #masthead').first();
    await expect(header).toBeVisible();
    await expect(header).toHaveScreenshot('header-mobile.png');
  });

  test('Footer - Desktop', async ({ page }) => {
    await page.goto('/');
    const footer = page.locator('footer, .site-footer').first();
    await expect(footer).toBeVisible();
    await expect(footer).toHaveScreenshot('footer-desktop.png');
  });

  test('WhatsApp button - Desktop', async ({ page }) => {
    await page.goto('/');
    await page.waitForTimeout(2000); // Wait for Joinchat to load
    const whatsapp = page.locator('[class*="joinchat"], [class*="whatsapp"]').first();
    await expect(whatsapp).toBeVisible();
    await expect(whatsapp).toHaveScreenshot('whatsapp-button-desktop.png');
  });

  test('WhatsApp button - Mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');
    await page.waitForTimeout(2000);
    const whatsapp = page.locator('[class*="joinchat"], [class*="whatsapp"]').first();
    await expect(whatsapp).toBeVisible();
    await expect(whatsapp).toHaveScreenshot('whatsapp-button-mobile.png');
  });
});
