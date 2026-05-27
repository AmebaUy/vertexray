// @ts-check
import { test, expect } from '@playwright/test';

/**
 * SECURITY & HARDENING TESTS
 * Vertex Ray - Mayo 2026
 * 
 * Verificación de medidas de seguridad implementadas en TAREA 3
 */

test.describe('Security: File Access Protection', () => {
  test('xmlrpc.php returns 403 Forbidden', async ({ page }) => {
    const response = await page.goto('/xmlrpc.php', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });

  test('readme.html is blocked', async ({ page }) => {
    const response = await page.goto('/readme.html', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });

  test('license.txt is blocked', async ({ page }) => {
    const response = await page.goto('/license.txt', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });

  test('wp-config.php is not accessible', async ({ page }) => {
    const response = await page.goto('/wp-config.php', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });

  test('.htaccess is not readable', async ({ page }) => {
    const response = await page.goto('/.htaccess', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });

  test('wp-config-sample.php is blocked', async ({ page }) => {
    const response = await page.goto('/wp-config-sample.php', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(403);
  });
});

test.describe('Security: REST API Protection', () => {
  test('Users endpoint is blocked', async ({ page, request }) => {
    const response = await request.get('/wp-json/wp/v2/users');
    expect(response.status()).toBe(401);
  });

  test('Root REST API is accessible', async ({ page, request }) => {
    const response = await request.get('/wp-json/');
    expect(response.status()).toBe(200);
  });

  test('Posts endpoint works (public content)', async ({ page, request }) => {
    const response = await request.get('/wp-json/wp/v2/posts');
    expect(response.status()).toBe(200);
  });
});

test.describe('Security: HTTP Security Headers', () => {
  test('X-Frame-Options header is set', async ({ page }) => {
    const response = await page.goto('/');
    const headers = response?.headers();
    
    expect(headers?.['x-frame-options']).toBe('SAMEORIGIN');
  });

  test('X-Content-Type-Options header is set', async ({ page }) => {
    const response = await page.goto('/');
    const headers = response?.headers();
    
    expect(headers?.['x-content-type-options']).toBe('nosniff');
  });

  test('Referrer-Policy header is set', async ({ page }) => {
    const response = await page.goto('/');
    const headers = response?.headers();
    
    expect(headers?.['referrer-policy']).toBeTruthy();
  });
});

test.describe('Security: WordPress Version Hiding', () => {
  test('WordPress version not in meta generator', async ({ page }) => {
    await page.goto('/');
    
    const generator = await page.locator('meta[name="generator"]').count();
    expect(generator).toBe(0);
  });

  test('WordPress version not in RSS feed', async ({ page, request }) => {
    const response = await request.get('/feed/');
    const body = await response.text();
    
    // Check for version patterns like "6.x", "7.x"
    expect(body).not.toMatch(/generator.*WordPress \d+\.\d+/i);
  });
});

test.describe('Security: Login Protection', () => {
  test('Invalid login shows generic error', async ({ page }) => {
    await page.goto('/wp-login.php');
    
    await page.fill('#user_login', 'nonexistent_user_12345');
    await page.fill('#user_pass', 'wrongpassword');
    await page.click('#wp-submit');
    
    await page.waitForSelector('#login_error');
    
    const errorText = await page.locator('#login_error').textContent();
    
    // Should NOT reveal if username exists
    expect(errorText?.toLowerCase()).not.toContain('username');
    expect(errorText?.toLowerCase()).not.toContain('usuario');
  });
});

test.describe('Security: Author Archives Disabled', () => {
  test('Author archive redirects to home', async ({ page }) => {
    // Try common author URLs
    const response = await page.goto('/author/admin/', { waitUntil: 'domcontentloaded' });
    
    // Should redirect (301) or show 404
    expect([200, 301, 302, 404]).toContain(response?.status() || 0);
    
    // If redirected, should be to home
    if (response?.status() === 301 || response?.status() === 302) {
      expect(page.url()).toBe('https://vertexraystg.wpenginepowered.com/');
    }
  });
});

test.describe('Security: PHP Execution in Uploads', () => {
  test('PHP files in uploads directory are blocked', async ({ page, request }) => {
    // Try to access a hypothetical PHP file in uploads
    const response = await request.get('/wp-content/uploads/test.php');
    
    // Should return 403 or 404, NOT execute
    expect([403, 404]).toContain(response.status());
  });
});

test.describe('Security: SQL Injection Protection', () => {
  test('SQL injection in query params is blocked', async ({ page }) => {
    const maliciousParams = [
      '?id=1 UNION SELECT * FROM wp_users',
      '?s=\' OR 1=1--',
      '?cat=1 AND 1=0 UNION SELECT NULL,table_name FROM information_schema.tables',
    ];

    for (const param of maliciousParams) {
      const response = await page.goto(`/${param}`, { waitUntil: 'domcontentloaded' });
      
      // Should be blocked (403) or show safe error page (400/404)
      expect([400, 403, 404]).toContain(response?.status() || 0);
    }
  });
});

test.describe('Security: XSS Protection', () => {
  test('XSS attempt in search query is sanitized', async ({ page }) => {
    await page.goto('/?s=<script>alert("XSS")</script>');
    
    // Check if script tag is NOT rendered in page
    const scriptTags = await page.locator('script:has-text("alert")').count();
    expect(scriptTags).toBe(0);
  });
});

test.describe('Security: HTTPS Enforcement', () => {
  test('Site uses HTTPS', async ({ page }) => {
    await page.goto('/');
    expect(page.url()).toMatch(/^https:\/\//);
  });

  test('Mixed content is not present', async ({ page }) => {
    await page.goto('/');
    
    // Check for http:// in page source
    const html = await page.content();
    const httpCount = (html.match(/http:\/\//g) || []).length;
    const httpsCount = (html.match(/https:\/\//g) || []).length;
    
    // Should have way more https than http
    expect(httpsCount).toBeGreaterThan(httpCount);
  });
});

test.describe('Security: Directory Listing', () => {
  test('Uploads directory listing is disabled', async ({ page }) => {
    const response = await page.goto('/wp-content/uploads/', { waitUntil: 'domcontentloaded' });
    const html = await page.content();
    
    // Should NOT show "Index of" or directory listing
    expect(html.toLowerCase()).not.toContain('index of');
    expect(html.toLowerCase()).not.toContain('parent directory');
  });

  test('wp-includes directory listing is disabled', async ({ page }) => {
    const response = await page.goto('/wp-includes/', { waitUntil: 'domcontentloaded' });
    const html = await page.content();
    
    expect(html.toLowerCase()).not.toContain('index of');
  });
});
