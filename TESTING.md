# Testing Suite - Vertex Ray

Suite completa de tests automatizados con Playwright para garantizar calidad y estabilidad del sitio.

## 📦 Instalación

```bash
# Instalar Playwright y dependencias
npm install

# Instalar navegadores (Chrome, Firefox, Safari)
npm run install:browsers

# Instalar dependencias del sistema (Linux)
npm run install:deps
```

## 🧪 Suites de Tests

### 1. **Visual Regression Testing** (`tests/visual-regression.spec.js`)
Compara screenshots pixel-by-pixel para detectar cambios visuales no deseados.

**Cobertura:**
- 5 páginas críticas (Home, Design, Projects, Contact, Careers)
- Desktop (1920x1080) + Mobile (iPhone SE)
- Full page + Above the fold
- Componentes individuales (header, footer, WhatsApp button)

**Total:** 16 tests visuales

```bash
# Correr tests visuales
npm run test:visual

# Generar baselines iniciales
npm run test:visual:update

# Solo desktop o mobile
npm run test:visual:desktop
npm run test:visual:mobile
```

### 2. **Form Testing** (`tests/forms.spec.js`)
Tests exhaustivos de formularios Contact Form 7 + Cloudflare Turnstile.

**Cobertura:**
- Validación de campos requeridos
- Validación de email/teléfono
- Cloudflare Turnstile loading
- Envío E2E completo
- Accesibilidad WCAG
- Performance (<5s response)
- Mobile responsiveness

**Total:** 20+ tests de formularios

```bash
npm run test:forms
npm run test:forms:headed  # Ver navegador
```

### 3. **E2E User Flows** (`tests/e2e-flows.spec.js`)
Journeys completos de usuario desde landing hasta conversión.

**Cobertura:**
- Homepage → Services → Contact form
- Portfolio browsing
- Careers application
- Navegación global (header, footer, breadcrumbs)
- Search functionality
- WhatsApp integration
- Analytics tracking (GA, GTM, dataLayer)
- Performance metrics

**Total:** 15+ tests E2E

```bash
npm run test:e2e
npm run test:e2e:headed
```

### 4. **Security Tests** (`tests/security.spec.js`)
Verificación de medidas de hardening implementadas (TAREA 3 Mayo 2026).

**Cobertura:**
- File access protection (xmlrpc.php, wp-config.php, readme.html)
- REST API /users endpoint blocked
- HTTP security headers (X-Frame-Options, X-Content-Type-Options, etc.)
- WordPress version hiding
- Login error messages (no user enumeration)
- Author archives disabled
- PHP execution in uploads blocked
- SQL injection protection
- XSS sanitization
- HTTPS enforcement
- Directory listing disabled

**Total:** 25+ tests de seguridad

```bash
npm run test:security
npm run test:security:headed
```

## 🚀 Comandos Principales

```bash
# Todos los tests
npm test

# UI Mode (interactivo, recomendado para desarrollo)
npm run test:ui

# Con navegador visible
npm run test:headed

# Debug mode (paso a paso)
npm run test:debug

# Tests críticos (forms + e2e + security)
npm run test:critical

# Solo desktop o mobile
npm run test:desktop
npm run test:mobile

# Ver reporte HTML
npm run report
```

## 📊 Estrategia de Testing

### Pre-Deploy Checklist

Antes de cualquier deploy a staging o producción:

1. **Visual Regression** → Detectar cambios UI no planeados
2. **Forms** → Garantizar que contact forms funcionen
3. **Security** → Verificar que hardening esté aplicado
4. **E2E Critical Paths** → Flows de conversión intactos

```bash
npm run test:critical
```

### Post-Deploy Verification

Después de deploy:

1. Smoke test manual (5 min)
2. Run full test suite (10-15 min)
3. Revisar reporte HTML de Playwright
4. Si hay fallos → rollback o fix inmediato

### Testing en CI/CD (Futuro)

Configurar GitHub Actions para correr tests automáticamente en cada push a `dev`:

```yaml
# .github/workflows/playwright.yml
name: Playwright Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
      - run: npm ci
      - run: npx playwright install --with-deps
      - run: npm test
      - uses: actions/upload-artifact@v3
        if: always()
        with:
          name: playwright-report
          path: playwright-report/
```

## 🎯 Coverage Goals

| Tipo de Test | Coverage Actual | Goal |
|--------------|----------------|------|
| **Visual Regression** | 5 páginas × 4 variantes = 20 screenshots | ✅ 100% páginas críticas |
| **Forms** | 2 formularios × 10 tests = 20 tests | ✅ 100% formularios |
| **E2E Flows** | 5 journeys críticos | ✅ 100% conversión paths |
| **Security** | 25+ reglas verificadas | ✅ 100% hardening Mayo 2026 |

## 📝 Estructura de Archivos

```
vertexray/
├── tests/
│   ├── visual-regression.spec.js   # Screenshots comparisons
│   ├── forms.spec.js                # Form validation & submission
│   ├── e2e-flows.spec.js            # User journeys
│   └── security.spec.js             # Hardening verification
├── playwright.config.js             # Configuración global
├── package.json                     # Scripts y dependencias
└── playwright-report/               # Reportes HTML (auto-generados)
```

## 🛠️ Troubleshooting

### Tests fallan por HTTP Basic Auth

Los tests están configurados para staging con credenciales automáticas:
```javascript
httpCredentials: {
  username: 'vertexraystg',
  password: '88596538'
}
```

Si cambias de entorno, actualiza `baseURL` y credentials en `playwright.config.js`.

### Visual regression tests siempre fallan

Primero genera baselines:
```bash
npm run test:visual:update
```

Esto creará screenshots de referencia en `tests/*.spec.js-snapshots/`.

### Tests de Turnstile fallan

Cloudflare Turnstile requiere interacción humana en producción. Los tests verifican que el challenge se cargue, pero no pueden auto-resolver el CAPTCHA.

Para bypass en testing, configurar Turnstile en "managed mode" o usar API key de testing.

### Timeout errors

Aumenta timeouts en `playwright.config.js`:
```javascript
timeout: 90000, // 90 segundos
```

## 📈 Métricas

### Execution Time (Approximate)

- Visual Regression: ~3 min (20 screenshots)
- Forms: ~2 min (20 tests)
- E2E Flows: ~4 min (15 journeys)
- Security: ~3 min (25 tests)

**Total:** ~12 minutos para suite completa

### Browsers Coverage

- ✅ Chromium (Desktop + Mobile)
- ✅ Safari (Desktop + Mobile)
- ⚠️ Firefox (opcional, descomentar en config)

## 🔗 Links Útiles

- [Playwright Docs](https://playwright.dev/)
- [Best Practices](https://playwright.dev/docs/best-practices)
- [Visual Comparisons](https://playwright.dev/docs/test-snapshots)
- [CI/CD Integration](https://playwright.dev/docs/ci)

## 📞 Soporte

**Mantenido por:** Ameba Creative Studio  
**Última actualización:** Mayo 2026  
**Contacto:** Ver `ameba-maintenance/agents.md`

---

## 🎯 Quick Start

```bash
# Clonar repo
git clone git@github.com:AmebaUy/vertexray.git
cd vertexray

# Instalar dependencias
npm install
npm run install:browsers

# Correr tests
npm test

# Ver resultados
npm run report
```

¡Listo para testear! 🚀
