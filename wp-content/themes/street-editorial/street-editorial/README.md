# Street Editorial — WordPress Theme

Tema de ecommerce streetwear con estética editorial oscura.
Compatible con **WordPress 6.9.1** y **WooCommerce 9.x**.
Proyecto académico — sin uso comercial.

---

## 📁 Estructura de archivos

```
street-editorial/
│
├── style.css                          ← Cabecera del tema (obligatorio WordPress)
├── functions.php                      ← Punto de entrada — carga los módulos
├── index.php                          ← Plantilla de reserva
├── header.php                         ← Navbar + Cart Drawer
├── footer.php                         ← Pie de página
│
├── front-page.php                     ← Página de inicio
├── page.php                           ← Páginas genéricas
├── single.php                         ← Entradas de blog
├── woocommerce.php                    ← Wrapper WooCommerce de reserva
│
├── page-shop.php                      ← Template: Shop
├── page-lookbook.php                  ← Template: Lookbook
├── page-about.php                     ← Template: About
│
├── inc/
│   ├── theme-setup.php                ← after_setup_theme, Walker, tamaños imagen
│   ├── enqueue.php                    ← CSS + JS
│   ├── woo-custom.php                 ← Ajustes WooCommerce
│   └── cart-functions.php            ← AJAX: fragmento y eliminar item del carrito
│
├── assets/
│   ├── css/
│   │   └── theme.css                  ← Sistema de diseño completo
│   └── js/
│       ├── cart.js                    ← Drawer AJAX + Quick Add + Size selector
│       ├── marquee.js                 ← Marquee infinito
│       └── animations.js             ← Reveal on scroll + parallax + cursor
│
├── template-parts/
│   ├── hero.php                       ← Hero fullscreen
│   ├── marquee.php                    ← Barra de texto animada
│   ├── product-card.php               ← Tarjeta de producto reutilizable
│   └── lookbook-grid.php             ← Galería masonry
│
└── woocommerce/
    ├── archive-product.php            ← Catálogo de productos
    ├── single-product.php             ← Detalle de producto
    ├── cart/
    │   └── cart.php                   ← Página del carrito
    └── checkout/
        └── form-checkout.php         ← Página de checkout
```

---

## 🚀 Instalación rápida

### Requisitos
| Software | Versión mínima |
|---|---|
| WordPress | 6.0 |
| PHP | 8.0 |
| WooCommerce | 8.0 |
| XAMPP / Laragon / WAMP | cualquiera reciente |

---

### 1. Instalar el tema

1. Descomprime el ZIP
2. Copia la carpeta `street-editorial` dentro de:
   ```
   wp-content/themes/
   ```
3. En WordPress → **Apariencia → Temas** → busca **Street Editorial** → **Activar**

---

### 2. Instalar WooCommerce

1. **Plugins → Añadir nuevo** → busca `WooCommerce`
2. **Instalar ahora** → **Activar**
3. Sigue el asistente de configuración inicial (puedes saltar los pasos de pago)

---

### 3. Configurar la página de inicio

1. **Páginas → Añadir nueva** → llámala `Home` → guárda vacía
2. **Ajustes → Lectura**
   - "La portada muestra" → **Una página estática**
   - Portada: selecciona **Home**
3. El tema cargará `front-page.php` automáticamente

---

### 4. Crear el menú de navegación

1. **Apariencia → Menús** → **Crear nuevo menú**
2. Añade estas páginas (créalas primero):
   - `Home` → página de inicio
   - `Shop` → la crea WooCommerce automáticamente
   - `Lookbook` → crear página, asignar template **Lookbook**
   - `About` → crear página, asignar template **About**
3. **Lugar de menú** → marca **Menú principal**
4. **Guardar menú**

---

### 5. Crear categorías de producto

**Productos → Categorías** → crear:
- `Hoodies`
- `Jackets`
- `Tees`
- `Accessories`

---

### 6. Añadir productos con tallas

1. **Productos → Añadir nuevo**
2. Tipo de producto: **Variable**
3. Pestaña **Atributos** → añadir atributo `Talla` con valores: `xs | s | m | l | xl | xxl`
4. Pestaña **Variaciones** → crear variaciones desde todos los atributos
5. Asigna precio, stock, imagen a cada variación
6. Añade **imagen destacada** (ratio 3:4 ideal, p. ej. 900×1200 px)
7. Asigna categoría
8. **Publicar**

---

## 🎨 Personalización de colores

Edita las variables CSS en `style.css`:

```css
:root {
    --accent:  #e8c547;   /* Dorado — color de acento */
    --black:   #0a0a0a;   /* Fondo principal */
    --white:   #f5f1eb;   /* Texto principal */
    --muted:   #777770;   /* Texto secundario */
}
```

---

## 🖋 Tipografías

El tema usa Google Fonts cargadas automáticamente:
- **Bebas Neue** — títulos grandes
- **DM Sans** — cuerpo de texto

Para cambiarlas, edita `inc/enqueue.php` y actualiza la variable CSS `--f-title` y `--f-body` en `style.css`.

---

## ⚙️ Funcionalidades incluidas

| Feature | Descripción |
|---|---|
| Cart Drawer | Carrito lateral con AJAX (añadir/eliminar) |
| Quick Add | Selector de talla + añadir al carrito desde la card |
| Size Selector | En tarjeta de producto y en página de detalle |
| Marquee animado | Texto infinito horizontal con CSS |
| Reveal on scroll | Animación fadeInUp con IntersectionObserver |
| Parallax hero | Desplazamiento suave de la imagen del hero |
| Cursor personalizado | Punto dorado de seguimiento (solo desktop) |
| Filtros de categoría | Filtrado en JS sin recargar la página |
| Gallery swap | Cambio de imagen principal al hacer clic en miniatura |
| Responsive | Mobile-first, breakpoints en 900px y 560px |

---

## 📦 Plugins recomendados (todos gratuitos)

| Plugin | Para qué |
|---|---|
| **WooCommerce** | Tienda (obligatorio) |
| **WP Super Cache** | Caché básica |
| **Yoast SEO** | SEO básico |
| **WooCommerce Payments** | Pagos (sandbox/testing) |

---

## ⚠️ Notas importantes

- El tema **no usa TailwindCSS compilado** — todo el CSS está en `assets/css/theme.css` con custom properties, lo que lo hace igual de mantenible sin necesitar un proceso de build.
- Las imágenes del hero y lookbook apuntan a **Unsplash** para el prototipo. En producción sustituye por imágenes locales.
- El tema está pensado para **desarrollo local** (XAMPP/Laragon). No está auditado para producción.

---

## 📄 Licencia

Proyecto académico — sin uso comercial.
