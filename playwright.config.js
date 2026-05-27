// @ts-check
import { defineConfig, devices } from '@playwright/test';

/**
 * Configuración Playwright para Testing Visual y Funcional
 * Vertex Ray - Mayo 2026
 */
export default defineConfig({
  testDir: './tests',
  
  /* Configuración de timeouts */
  timeout: 60000, // 60s por test
  expect: {
    timeout: 10000, // 10s para expects
    toHaveScreenshot: {
      maxDiffPixels: 100, // Tolerancia para visual regression
      threshold: 0.2,
    },
  },

  /* Configuración de ejecución */
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : 4, // 4 workers: balance entre velocidad y estabilidad en WP Engine
  maxFailures: 20, // Detener si hay más de 20 fallos (evita que workers colgados bloqueen todo)

  /* Reporter */
  reporter: [
    ['html', { outputFolder: 'playwright-report' }],
    ['list'],
    ['json', { outputFile: 'test-results.json' }],
  ],

  /* Configuración global */
  use: {
    baseURL: 'https://vertexraystg.wpenginepowered.com',
    httpCredentials: {
      username: 'vertexraystg',
      password: '88596538'
    },
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'retain-on-failure',
    actionTimeout: 15000,
  },

  /* Configuración de proyectos (dispositivos) */
  projects: [
    {
      name: 'chromium-desktop',
      use: { 
        ...devices['Desktop Chrome'],
        viewport: { width: 1920, height: 1080 }
      },
    },
    {
      name: 'chromium-mobile',
      use: { 
        ...devices['Pixel 5']
      },
    },
    {
      name: 'safari-desktop',
      use: { 
        ...devices['Desktop Safari'],
        viewport: { width: 1920, height: 1080 }
      },
    },
    {
      name: 'safari-mobile',
      use: { 
        ...devices['iPhone 13']
      },
    },
  ],

  /* Web server local (opcional) */
  // webServer: {
  //   command: 'npm run start',
  //   url: 'http://localhost:3000',
  //   reuseExistingServer: !process.env.CI,
  // },
});
