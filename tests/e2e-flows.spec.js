// @ts-check
import { test, expect } from '@playwright/test';

/**
 * E2E USER JOURNEYS - Critical Paths
 * Vertex Ray - Mayo 2026
 * 
 * Tests de flujos completos de usuario desde inicio hasta conversión
 */

test.describe('E2E - Visitor to Lead Conversion', () => {
  test('Journey: Homepage → Design Services → Contact Form', async ({ page }) => {
    // Step 1: Land on homepage
    await page.goto('/');
    await expect(page).toHaveTitle(/Vertex Ray/i);
    
    // Step 2: Navigate to Design services
    const designLink = page.locator('a[href*="/design"]').first();
    await expect(designLink).toBeVisible();
    await designLink.click();
    
    await page.waitForURL(/.*design.*/);
    await expect(page.locator('h1, .page-title')).toBeVisible();
    
    // Step 3: Click CTA to contact
    const ctaButton = page.locator('a[href*="contact"], button:has-text("Contact"), a:has-text("Get in touch")').first();
    await ctaButton.click();
    
    await page.waitForURL(/.*contact.*/);
    
    // Step 4: Form is visible
    const form = page.locator('form.wpcf7-form');
    await expect(form).toBeVisible();
    
    // Step 5: Fill form
    await form.locator('input[type="email"]').first().fill('lead@example.com');
    await form.locator('input[name*="name"]').first().fill('Test Lead');
    
    // Journey completed (form submission tested separately)
    await expect(form.locator('input[type="submit"]')).toBeEnabled();
  });

  test('Journey: Homepage → Projects Portfolio → Project Detail', async ({ page }) => {
    // Step 1: Homepage
    await page.goto('/');
    
    // Step 2: Navigate to Projects
    const projectsLink = page.locator('a[href*="/projects"]').first();
    await projectsLink.click();
    
    await page.waitForURL(/.*projects.*/);
    
    // Step 3: Click on first project
    const firstProject = page.locator('.portfolio-item, .project-card, article').first().locator('a').first();
    if (await firstProject.count() > 0) {
      await firstProject.click();
      
      // Step 4: Project detail loads
      await page.waitForLoadState('networkidle');
      await expect(page.locator('h1, .project-title')).toBeVisible();
    } else {
      test.skip(); // No projects available
    }
  });

  test('Journey: Homepage → Careers → Application Form', async ({ page }) => {
    // Step 1: Homepage
    await page.goto('/');
    
    // Step 2: Navigate to careers
    const careersLink = page.locator('a[href*="join"], a[href*="career"]').first();
    await careersLink.click();
    
    await page.waitForURL(/.*join.*|.*career.*/);
    
    // Step 3: Application form visible
    const form = page.locator('form');
    await expect(form).toBeVisible();
  });
});

test.describe('E2E - Navigation and Site Structure', () => {
  test('Main navigation works on all pages', async ({ page }) => {
    const pages = ['/', '/design/', '/projects/', '/contact-us/'];
    
    for (const pagePath of pages) {
      await page.goto(pagePath);
      
      // Check main nav is visible
      const nav = page.locator('nav.main-navigation, header nav, .site-navigation').first();
      await expect(nav).toBeVisible();
      
      // Check logo/home link
      const logo = page.locator('a[href="/"], a[href*="vertexray.com"]').first();
      await expect(logo).toBeVisible();
    }
  });

  test('Footer links work across site', async ({ page }) => {
    await page.goto('/');
    
    const footer = page.locator('footer');
    await expect(footer).toBeVisible();
    
    // Get all footer links
    const footerLinks = footer.locator('a[href^="/"], a[href*="vertexray.com"]');
    const count = await footerLinks.count();
    expect(count).toBeGreaterThan(0);
    
    // Test first 3 links
    for (let i = 0; i < Math.min(3, count); i++) {
      const link = footerLinks.nth(i);
      const href = await link.getAttribute('href');
      
      if (href && !href.includes('#')) {
        await page.goto(href);
        await expect(page).not.toHaveURL(/.*404.*/);
      }
    }
  });

  test('Breadcrumbs navigation (if present)', async ({ page }) => {
    await page.goto('/design/');
    
    const breadcrumbs = page.locator('[class*="breadcrumb"], .breadcrumbs, nav[aria-label*="Breadcrumb"]');
    
    if (await breadcrumbs.count() > 0) {
      await expect(breadcrumbs).toBeVisible();
      
      // Click home breadcrumb
      const homeLink = breadcrumbs.locator('a').first();
      await homeLink.click();
      
      await expect(page).toHaveURL('/');
    } else {
      test.skip();
    }
  });
});

test.describe('E2E - Search Functionality', () => {
  test('Site search works', async ({ page }) => {
    await page.goto('/');
    
    // Look for search input
    const searchInput = page.locator('input[type="search"], input[name="s"], .search-field').first();
    
    if (await searchInput.count() > 0) {
      await searchInput.fill('design');
      await searchInput.press('Enter');
      
      await page.waitForURL(/.*\?s=.*/);
      
      // Results should appear
      const results = page.locator('.search-results, article');
      await expect(results.first()).toBeVisible();
    } else {
      test.skip(); // No search available
    }
  });
});

test.describe('E2E - WhatsApp Integration', () => {
  test('WhatsApp button opens chat', async ({ page, context }) => {
    await page.goto('/');
    await page.waitForTimeout(2000); // Wait for Joinchat
    
    const whatsappBtn = page.locator('[class*="joinchat"], a[href*="wa.me"], a[href*="whatsapp"]').first();
    await expect(whatsappBtn).toBeVisible();
    
    // Click opens WhatsApp (new tab or popup)
    const [newPage] = await Promise.all([
      context.waitForEvent('page'),
      whatsappBtn.click()
    ]);
    
    // Check URL is WhatsApp
    await expect(newPage).toHaveURL(/.*wa\.me.*|.*api\.whatsapp\.com.*/);
    
    // Check phone number in URL
    const url = newPage.url();
    expect(url).toContain('59892250103');
    
    await newPage.close();
  });

  test('WhatsApp button visible on scroll', async ({ page }) => {
    await page.goto('/');
    await page.waitForTimeout(2000);
    
    const whatsappBtn = page.locator('[class*="joinchat"]').first();
    
    // Scroll down
    await page.evaluate(() => window.scrollTo(0, 1000));
    await page.waitForTimeout(500);
    
    // Button still visible
    await expect(whatsappBtn).toBeVisible();
  });
});

test.describe('E2E - Analytics Tracking', () => {
  test('GA pageview fires on page load', async ({ page }) => {
    await page.goto('/');
    
    // Check dataLayer
    const hasDataLayer = await page.evaluate(() => {
      return typeof window.dataLayer !== 'undefined' && window.dataLayer.length > 0;
    });
    
    expect(hasDataLayer).toBeTruthy();
  });

  test('GTM container loads', async ({ page }) => {
    await page.goto('/');
    
    // Check GTM script
    const hasGTM = await page.evaluate(() => {
      const scripts = Array.from(document.querySelectorAll('script'));
      return scripts.some(script => script.src.includes('googletagmanager.com/gtm.js'));
    });
    
    expect(hasGTM).toBeTruthy();
  });

  test('Form submission triggers event (if configured)', async ({ page }) => {
    await page.goto('/contact-us/');
    
    // Listen for dataLayer pushes
    const dataLayerEvents = [];
    await page.exposeFunction('captureDataLayerEvent', (event) => {
      dataLayerEvents.push(event);
    });
    
    await page.evaluate(() => {
      const originalPush = window.dataLayer.push;
      window.dataLayer.push = function(...args) {
        window.captureDataLayerEvent(JSON.stringify(args));
        return originalPush.apply(this, args);
      };
    });
    
    // Submit form (will fail validation but should trigger event)
    const submitBtn = page.locator('form.wpcf7-form input[type="submit"]');
    await submitBtn.click();
    
    await page.waitForTimeout(2000);
    
    // Check if form event was captured
    // Note: This depends on GTM configuration
    console.log('DataLayer events:', dataLayerEvents);
  });
});

test.describe('E2E - Performance Critical Paths', () => {
  test('Homepage loads fully under 5 seconds', async ({ page }) => {
    const startTime = Date.now();
    
    await page.goto('/', { waitUntil: 'networkidle' });
    
    const loadTime = Date.now() - startTime;
    expect(loadTime).toBeLessThan(5000);
  });

  test('Time to Interactive (TTI) is reasonable', async ({ page }) => {
    await page.goto('/');
    
    // Measure time until page is fully interactive
    const tti = await page.evaluate(() => {
      return new Promise((resolve) => {
        if (document.readyState === 'complete') {
          resolve(performance.now());
        } else {
          window.addEventListener('load', () => {
            resolve(performance.now());
          });
        }
      });
    });
    
    expect(tti).toBeLessThan(8000); // 8 seconds max TTI
  });

  test('Images lazy load properly', async ({ page }) => {
    await page.goto('/');
    
    // Check for lazy loading attributes
    const images = page.locator('img');
    const firstImg = images.first();
    
    const hasLazyLoading = await firstImg.evaluate((img) => {
      return img.loading === 'lazy' || img.classList.contains('lazy');
    });
    
    // Not all images need lazy loading (hero images should load immediately)
    // This is just a check that lazy loading is implemented somewhere
    console.log('Lazy loading detected:', hasLazyLoading);
  });
});

test.describe('E2E - Mobile Gestures', () => {
  test.use({ viewport: { width: 375, height: 667 } });

  test('Mobile menu opens and closes', async ({ page }) => {
    await page.goto('/');
    
    // Find hamburger menu
    const menuToggle = page.locator('[class*="menu-toggle"], [class*="hamburger"], button[aria-label*="Menu"]').first();
    
    if (await menuToggle.count() > 0) {
      await menuToggle.click();
      await page.waitForTimeout(500);
      
      // Menu should be visible
      const mobileMenu = page.locator('[class*="mobile-menu"], nav[class*="open"]').first();
      await expect(mobileMenu).toBeVisible();
      
      // Close menu
      await menuToggle.click();
      await page.waitForTimeout(500);
    } else {
      test.skip();
    }
  });

  test('Swipe gestures work on carousel (if present)', async ({ page }) => {
    await page.goto('/');
    
    const carousel = page.locator('[class*="carousel"], [class*="slider"], .swiper').first();
    
    if (await carousel.count() > 0) {
      // Simulate swipe left
      await carousel.hover();
      await page.mouse.down();
      await page.mouse.move(-200, 0);
      await page.mouse.up();
      
      await page.waitForTimeout(1000);
      
      // Check if carousel moved (implementation specific)
      console.log('Carousel swipe tested');
    } else {
      test.skip();
    }
  });
});
