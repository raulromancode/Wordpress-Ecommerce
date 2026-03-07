# Fashion Store — Tema WordPress

Tema de ecommerce para tienda de ropa. Proyecto académico desarrollado con WordPress y WooCommerce.

---

## 📁 Estructura del tema

```
fashion-store/
├── style.css                          ← Cabecera del tema (obligatorio)
├── functions.php                      ← Configuración, WooCommerce, AJAX
├── index.php                          ← Plantilla de reserva
├── front-page.php                     ← Página de inicio
├── page.php                           ← Páginas genéricas (About, etc.)
├── header.php                         ← Navbar + carrito lateral
├── footer.php                         ← Pie de página
├── assets/
│   ├── css/main.css                   ← Todos los estilos
│   └── js/main.js                     ← Carrito AJAX + filtros
└── woocommerce/
    ├── archive-product.php            ← Página de tienda
    ├── single-product.php             ← Producto individual
    ├── cart/cart.php                  ← Página del carrito
    └── checkout/form-checkout.php    ← Página de checkout
```

---

## 🚀 Instalación paso a paso

### Requisitos previos
- XAMPP / WAMP / Laragon instalado
- WordPress 6.0+
- Plugin **WooCommerce** (gratuito)

### 1. Instalar el tema
1. Copia la carpeta `fashion-store` dentro de `wp-content/themes/`
2. En el panel de WordPress → **Apariencia → Temas**
3. Busca **Fashion Store** y haz clic en **Activar**

### 2. Instalar WooCommerce
1. **Plugins → Añadir nuevo** → busca "WooCommerce" → Instalar → Activar
2. Completa el asistente de configuración inicial

### 3. Configurar la página de inicio
1. Crea una página en blanco llamada **Inicio** (sin contenido)
2. **Ajustes → Lectura** → "La portada muestra" → **Una página estática**
3. Portada: selecciona **Inicio**
4. Página de entradas: deja vacío (no usamos blog)

### 4. Crear el menú de navegación
1. **Apariencia → Menús** → Crear nuevo menú
2. Añade los siguientes elementos:
   - Inicio (página)
   - Tienda (página de WooCommerce)
   - Nosotros (crear página con slug `sobre-nosotros`)
3. Asigna al lugar → **Menú principal**
4. Guarda el menú

### 5. Crear categorías de producto
**Productos → Categorías** → Crear:
- Camisetas
- Sudaderas
- Pantalones
- Chaquetas
- Accesorios

### 6. Añadir productos
1. **Productos → Añadir nuevo**
2. Tipo de producto: **Variable** (para tener tallas)
3. En la pestaña **Atributos** → Añadir atributo `Talla` con valores: XS | S | M | L | XL | XXL
4. En **Variaciones** → Crear todas las variaciones
5. Añade imagen destacada (formato vertical, proporción 3:4 ideal)
6. Asigna una categoría

---

## 🎨 Personalización

### Cambiar colores
Edita las variables en `style.css`:
```css
:root {
    --c-accent: #c9a96e;   /* Dorado — color de acento principal */
    --c-bg:     #0d0d0d;   /* Fondo oscuro */
    --c-text:   #f2ede8;   /* Texto principal */
}
```

### Cambiar el nombre de la tienda
**Ajustes → Generales** → Título del sitio

### Cambiar imágenes del hero
Edita las URLs en `front-page.php` (líneas 6-7)

---

## ⚠️ Nota
Este proyecto usa **únicamente plugins y temas gratuitos**.
No requiere ninguna licencia de pago.

---

## 📄 Licencia
Proyecto académico — sin uso comercial.
