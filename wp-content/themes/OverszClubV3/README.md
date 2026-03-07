# OverszClub

Tema WordPress premium orientado a streetwear editorial con WooCommerce.

## Instalacion

1. Copia la carpeta `OverszClub` dentro de `/wp-content/themes/`.
2. Activa el tema desde `Apariencia > Temas`.
3. Instala y activa WooCommerce.
4. Crea o asigna estas paginas si quieres replicar la demo:
   `Inicio`, `Tienda`, `Lookbook`, `About`, `Carrito`, `Finalizar compra`, `Mi cuenta`.
5. En `Apariencia > Personalizar > OverszClub` configura:
   hero, marquee, colores, redes, contenido About y lookbook.
6. Asigna menus a las ubicaciones `Menu principal` y `Menu footer`.

## Recomendaciones WooCommerce

- Usa atributo global `pa_size` con tallas `S`, `M`, `L`, `XL`.
- Marca productos destacados para que aparezcan en portada.
- Configura imagen destacada y galeria por producto.

## Componentes incluidos

- Header sticky y menu editorial
- Hero fullscreen y marquee animado
- Grid de productos con quick view
- Side cart AJAX
- Filtros AJAX en tienda
- Lookbook con hotspots
- Overrides WooCommerce para carrito, checkout y cuenta

## Verificacion recomendada

1. Activar el tema y revisar portada.
2. Probar add to cart AJAX en home, shop y quick view.
3. Probar filtros por categoria, precio y talla.
4. Revisar carrito, checkout y mi cuenta.
5. Confirmar que los ajustes del Customizer actualizan hero, colores y lookbook.

## Limitacion de esta entrega

No se ha podido ejecutar `php -l` en este entorno porque la CLI de PHP no esta instalada aqui. Conviene hacer esa comprobacion dentro del stack WordPress de destino.
