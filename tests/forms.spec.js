// @ts-check
import { test, expect } from '@playwright/test';

/**
 * FORM TESTING - Contact Form 7 + Cloudflare Turnstile
 * Vertex Ray - Mayo 2026
 * 
 * Tests exhaustivos de validación, envío y UX
 */

const FORM_PAGES = [
  { name: 'Contact Us', url: '/contact-us/', formSelector: 'form.wpcf7-form' },
  { name: 'Join Our Team', url: '/join-our-team/', formSelector: 'form.wpcf7-form' },
];

test.describe('Form Validation - Contact Form 7', () => {
  for (const formPage of FORM_PAGES) {
    test.describe(`${formPage.name} Form`, () => {
      test.beforeEach(async ({ page }) => {
        await page.goto(formPage.url);
        await page.waitForSelector(formPage.formSelector, { timeout: 10000 });
      });

      test('Form is visible and properly rendered', async ({ page }) => {
        const form = page.locator(formPage.formSelector);
        await expect(form).toBeVisible();
        
        // Check form fields exist
        const inputs = form.locator('input[type="text"], input[type="email"], input[type="tel"], textarea');
        const count = await inputs.count();
        expect(count).toBeGreaterThan(0);
      });

      test('Submit button is present and enabled', async ({ page }) => {
        const submitBtn = page.locator(formPage.formSelector).locator('input[type="submit"], button[type="submit"]');
        await expect(submitBtn).toBeVisible();
        await expect(submitBtn).toBeEnabled();
      });

      test('Cloudflare Turnstile challenge is loaded', async ({ page }) => {
        // Wait for Turnstile iframe
        const turnstile = page.frameLocator('iframe[src*="cloudflare"]').first();
        await expect(turnstile.locator('body')).toBeVisible({ timeout: 10000 });
      });

      test('Required field validation works', async ({ page }) => {
        const form = page.locator(formPage.formSelector);
        const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
        
        // Try to submit empty form
        await submitBtn.click();
        
        // Wait for CF7 validation message
        await page.waitForTimeout(1000);
        
        // Check for validation errors
        const validationErrors = page.locator('.wpcf7-not-valid-tip, .wpcf7-response-output');
        const errorCount = await validationErrors.count();
        expect(errorCount).toBeGreaterThan(0);
      });

      test('Email validation works', async ({ page }) => {
        const form = page.locator(formPage.formSelector);
        const emailInput = form.locator('input[type="email"]').first();
        const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
        
        // Enter invalid email
        await emailInput.fill('invalid-email');
        await submitBtn.click();
        await page.waitForTimeout(1000);
        
        // Check for email validation error
        const emailError = page.locator('.wpcf7-not-valid-tip').filter({ hasText: /email|correo/i });
        await expect(emailError).toBeVisible();
      });

      test('Phone validation works (if present)', async ({ page }) => {
        const form = page.locator(formPage.formSelector);
        const phoneInput = form.locator('input[type="tel"]').first();
        
        if (await phoneInput.count() > 0) {
          const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
          
          // Enter invalid phone
          await phoneInput.fill('abc123');
          await submitBtn.click();
          await page.waitForTimeout(1000);
          
          const phoneError = page.locator('.wpcf7-not-valid-tip').filter({ hasText: /tel|phone|teléfono/i });
          await expect(phoneError).toBeVisible();
        } else {
          test.skip();
        }
      });
    });
  }
});

test.describe('Form Submission E2E - Contact Form', () => {
  test('Complete form submission flow - Valid data', async ({ page }) => {
    await page.goto('/contact-us/');
    await page.waitForSelector('form.wpcf7-form');
    
    const form = page.locator('form.wpcf7-form');
    
    // Fill all required fields with valid data
    const nameInput = form.locator('input[name*="name"], input[name*="nombre"]').first();
    const emailInput = form.locator('input[type="email"]').first();
    const messageInput = form.locator('textarea[name*="message"], textarea[name*="mensaje"]').first();
    
    await nameInput.fill('Playwright Test User');
    await emailInput.fill('test-automation@vertexray.com');
    
    if (await messageInput.count() > 0) {
      await messageInput.fill('This is an automated test message from Playwright. Testing form submission flow. Please ignore.');
    }
    
    // Wait for Turnstile to be ready
    await page.waitForTimeout(3000);
    
    // Note: Turnstile auto-validation may happen
    // In production, this would require manual CAPTCHA solving or test mode
    
    const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
    await submitBtn.click();
    
    // Wait for response (success or error)
    await page.waitForTimeout(5000);
    
    // Check for success message OR Turnstile blocking
    const responseOutput = page.locator('.wpcf7-response-output');
    await expect(responseOutput).toBeVisible();
    
    const responseText = await responseOutput.textContent();
    console.log('Form response:', responseText);
    
    // Either success or Turnstile challenge
    const hasSuccess = responseText?.toLowerCase().includes('thank') || 
                       responseText?.toLowerCase().includes('gracias') ||
                       responseText?.toLowerCase().includes('success');
    const hasTurnstile = responseText?.toLowerCase().includes('captcha') ||
                         responseText?.toLowerCase().includes('turnstile');
    
    expect(hasSuccess || hasTurnstile).toBeTruthy();
  });
});

test.describe('Form Accessibility - WCAG Compliance', () => {
  test('Form labels are properly associated', async ({ page }) => {
    await page.goto('/contact-us/');
    const form = page.locator('form.wpcf7-form');
    
    // Check inputs have labels or aria-label
    const inputs = form.locator('input[type="text"], input[type="email"], input[type="tel"], textarea');
    const count = await inputs.count();
    
    for (let i = 0; i < count; i++) {
      const input = inputs.nth(i);
      const hasLabel = await input.evaluate((el) => {
        const id = el.id;
        const ariaLabel = el.getAttribute('aria-label');
        const ariaLabelledBy = el.getAttribute('aria-labelledby');
        const label = id ? document.querySelector(`label[for="${id}"]`) : null;
        const placeholder = el.getAttribute('placeholder');
        
        return !!(label || ariaLabel || ariaLabelledBy || placeholder);
      });
      
      expect(hasLabel).toBeTruthy();
    }
  });

  test('Form errors are announced to screen readers', async ({ page }) => {
    await page.goto('/contact-us/');
    const form = page.locator('form.wpcf7-form');
    const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
    
    // Submit empty form
    await submitBtn.click();
    await page.waitForTimeout(1000);
    
    // Check error container has aria-live or role=alert
    const errorContainer = page.locator('.wpcf7-response-output, .wpcf7-validation-errors');
    const hasAria = await errorContainer.evaluate((el) => {
      return el.getAttribute('aria-live') || el.getAttribute('role') === 'alert';
    });
    
    expect(hasAria).toBeTruthy();
  });

  test('Form is keyboard navigable', async ({ page }) => {
    await page.goto('/contact-us/');
    await page.waitForSelector('form.wpcf7-form');
    
    // Tab through form
    await page.keyboard.press('Tab');
    await page.keyboard.press('Tab');
    await page.keyboard.press('Tab');
    
    // Check focus is visible
    const focusedElement = await page.evaluate(() => {
      return document.activeElement?.tagName;
    });
    
    expect(['INPUT', 'TEXTAREA', 'BUTTON']).toContain(focusedElement);
  });
});

test.describe('Form Performance', () => {
  test('Form loads within 3 seconds', async ({ page }) => {
    const startTime = Date.now();
    
    await page.goto('/contact-us/');
    await page.waitForSelector('form.wpcf7-form');
    
    const loadTime = Date.now() - startTime;
    expect(loadTime).toBeLessThan(3000);
  });

  test('Form submission responds within 5 seconds', async ({ page }) => {
    await page.goto('/contact-us/');
    const form = page.locator('form.wpcf7-form');
    
    // Fill minimal required fields
    await form.locator('input[type="email"]').first().fill('test@test.com');
    
    const submitBtn = form.locator('input[type="submit"], button[type="submit"]');
    
    const startTime = Date.now();
    await submitBtn.click();
    
    // Wait for response
    await page.waitForSelector('.wpcf7-response-output', { timeout: 10000 });
    
    const responseTime = Date.now() - startTime;
    expect(responseTime).toBeLessThan(5000);
  });
});

test.describe('Form Mobile Responsiveness', () => {
  test.use({ viewport: { width: 375, height: 667 } });

  test('Form is usable on mobile', async ({ page }) => {
    await page.goto('/contact-us/');
    const form = page.locator('form.wpcf7-form');
    await expect(form).toBeVisible();
    
    // Check inputs are not too small
    const inputs = form.locator('input[type="text"], input[type="email"], textarea');
    const firstInput = inputs.first();
    
    const box = await firstInput.boundingBox();
    expect(box?.height).toBeGreaterThan(30); // Minimum touch target
  });

  test('Submit button is large enough for touch', async ({ page }) => {
    await page.goto('/contact-us/');
    const submitBtn = page.locator('form.wpcf7-form input[type="submit"], form.wpcf7-form button[type="submit"]');
    
    const box = await submitBtn.boundingBox();
    expect(box?.height).toBeGreaterThan(44); // iOS minimum touch target
  });

  test('Form inputs zoom properly on focus (iOS)', async ({ page }) => {
    await page.goto('/contact-us/');
    const input = page.locator('form.wpcf7-form input[type="text"]').first();
    
    // Check font-size is at least 16px to prevent iOS zoom
    const fontSize = await input.evaluate((el) => {
      return window.getComputedStyle(el).fontSize;
    });
    
    const fontSizeNum = parseInt(fontSize);
    expect(fontSizeNum).toBeGreaterThanOrEqual(16);
  });
});
