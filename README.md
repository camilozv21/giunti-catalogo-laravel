# Catálogo de tests y formación — Versión B (Laravel)

Landing del catálogo de **Giunti Psychometrics Italia** (tests psicológicos y cursos para profesionales, textos en italiano) para la prueba técnica de Frontend Developer. Es la **Versión B** del brief: **Laravel 13 · Blade · Stylus · jQuery 4 · Laravel Mix 6**. Página sin estado: sin sesión, cookies ni base de datos, y cacheable.

La **Versión A** (Next.js · Tailwind CSS · TypeScript) está en su propio repositorio y se ve y se comporta igual: las dos parten de los mismos datos, textos, tokens de diseño, fuentes, iconos e imágenes.

| | |
|---|---|
| **Demo** | [giunti-catalogo-laravel-production.up.railway.app](https://giunti-catalogo-laravel-production.up.railway.app) |
| **Versión A (Next.js)** | [repositorio](https://github.com/camilozv21/giunti-catalogo-nextjs) · [demo](https://giunti-catalogo-nextjs.vercel.app) |

> **Demo técnica.** No es el sitio oficial de Giunti Psychometrics: productos, autores, precios y fechas son ficticios. No usa cookies ni analítica, y el logotipo es un monograma original.

## Versiones utilizadas

| Herramienta | Versión |
|---|---|
| PHP | 8.5.13 (Laravel 13 requiere ≥ 8.3) |
| Composer | 2.11.2 |
| Laravel | 13.43.0 |
| Node.js / npm | 24.14.1 / 11.11.0 |
| Laravel Mix / webpack | 6.0.49 / **5.105.4** (fijado, ver abajo) |
| Stylus / stylus-loader | 0.64.0 / 8.1.3 |
| jQuery | 4.1.0 (build *slim*, módulo ES) |

## Instalación y uso

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci

npm run dev          # compila assets (Mix, desarrollo)
npm run watch        # recompila al guardar
php artisan serve    # http://localhost:8000
composer run dev     # servidor + watch a la vez

npm run build        # build de producción (minificada y versionada, mix-manifest.json)

php artisan test     # PHPUnit: formato, dinero, filtro y hechos del catálogo, intérprete, landing sin cookies y cacheable, SEO
npm run test:js      # node --test: lógica de filtros, búsqueda, validación y plurales
vendor/bin/pint --test
```

Los tests de feature renderizan la página, así que necesitan los assets compilados (`npm run dev` o `npm run build`) antes de `php artisan test`.

**Sin PHP instalado (Docker):**

```bash
docker build -t catalogo-b .
docker run --rm -p 8080:8080 -e APP_KEY=base64:$(openssl rand -base64 32) catalogo-b
# http://localhost:8080 — FrankenPHP (Caddy + PHP 8.5), usuario sin privilegios, zstd/br/gzip
```

### Despliegue en Railway

1. *New Project → Deploy from GitHub repo* y elegir este repositorio. Railway detecta el `Dockerfile` y construye la misma imagen que la sección anterior.
2. En *Variables*, añadir solo `APP_KEY`, con el valor que imprime `php artisan key:generate --show` (o `echo "base64:$(openssl rand -base64 32)"`). El resto viene en la imagen: `APP_ENV=production`, `APP_DEBUG=false`, locale `it`, logs a stderr y sesión y caché en memoria. `APP_URL` no hace falta, porque el origen de canonical, Open Graph y sitemap sale de la petición.
3. En *Settings → Networking*, *Generate Domain*. Railway inyecta `PORT` y Caddy escucha en ese puerto.
4. En *Settings → Deploy*, *Healthcheck Path* `/up`. Conviene dejar desactivado *Serverless*: duerme el servicio sin tráfico y la primera visita esperaría al arranque del contenedor.

GitHub Actions ejecuta Pint, el build de Mix, los tests de JS y PHPUnit, y construye y prueba la imagen de producción en cada push (`.github/workflows/ci.yml`).

## Qué incluye

### Lo que pide el brief

1. **Header**: logotipo, navegación *Test · Formazione · Risorse · Contatti*, «Accedi» visual (popover nativo), buscador que filtra la grilla por nombre o sigla y abre una lista de resultados con imagen y nombre, y contador de carrito. En móvil el buscador pasa a una segunda fila y el menú es un `<dialog>` nativo.
2. **Hero**: una fotografía a pantalla completa (una maestra cuenta con los dedos junto a un niño en clase: la parte humana de cada evaluación), con la promesa, una línea y el CTA «Esplora il catalogo». Lo primero que aparece después es el catálogo.
3. **Grilla de 12 productos** (8 tests en 3 áreas + 4 cursos): imagen, nombre, autor o docente, rango de edad o fecha de inicio, precio con descuento y precio original en formato italiano (`1.062,50 €` / ~~`1.250,00 €`~~), badge de formato y botón «Aggiungi» (nombre accesible «Aggiungi al carrello – ORMA»). 1 / 2 / 3 columnas en 375 / 768 / 1280 px.
4. **Filtro por categoría** con «Tutti», en el cliente y sin recarga, combinable con la búsqueda; contador de resultados anunciado a lectores de pantalla y estado vacío con salida.
5. **Formulario de contacto**: validación en el cliente con mensajes en italiano, enlazados a cada campo, foco en el primer error y estado de éxito.
6. **Footer**: los tres datos de la empresa (75+ años, Europa y América Latina, Euronext Growth Milan), nombre, enlaces, redes (placeholders), copyright y aviso de demo.

### Más allá del brief

- **Tarjetas cuidadas, no llenas.** Cada tarjeta hace tres cosas: reconocer, comparar y actuar. Foto propia de cada producto en 3:2 (Unsplash, ver Créditos) con el badge de formato y el descuento; la sigla como etiqueta pequeña y el nombre en la tipografía editorial; el autor en una línea; tres datos como etiquetas que se reparten sin cortes (`18–90 anni` `45–60 min` `Qualifica C`, o inicio, horas y créditos ECM en los cursos); precio y un «Aggiungi» con borde, para que doce botones oscuros no compitan entre sí, que se rellena en verde al añadir. «IVA esclusa» aparece una vez, en la barra del catálogo. Las siglas y la marca llevan `translate="no"`.
- **Buscador con resultados**: el campo del header es un *combobox* ARIA 1.2. Mientras escribes filtra la grilla y abre una lista con imagen, nombre, sigla y precio; las flechas la recorren, Enter o un clic llevan a la tarjeta (scroll, foco y un destello breve) y Esc la cierra (un segundo Esc borra la búsqueda).
- **Filtro por la edad de la persona**, en la misma barra que las categorías: *Età [ 8 ] anni*. Las normas de un test dependen de la edad, así que es el filtro que decide: con una edad solo quedan los tests cuyo rango la contiene («3 test per 8 anni»), y «Formazione» la quita. Filtra mientras escribes. Es un campo de texto con teclado numérico y no `type="number"`, que oculta lo que no es un número (criterio de GOV.UK). Si el valor no es una edad (0–120), lo dice en la línea de resultados y conserva el último resultado válido. Mismas reglas y casos de prueba que la Versión A (`tests/fixtures/filters.json`), en PHP y en JavaScript.
- **«Chiedi al catalogo», una búsqueda guiada**: eliges un caso típico («Bambino di 8 anni, fatica a leggere», «Memoria di una signora di 72 anni»…) y la conversación se reproduce: pregunta, «escribiendo…» y respuesta. La respuesta lista lo que encuentra **el mismo filtro del catálogo** y dice qué filtros usó. El botón aplica esos filtros y lleva a la barra de resultados. Está marcada como «Esempio»: no simula un servicio en directo.
- **«Interpreta un punteggio»**: un control deslizante mueve un punteggio z y todo responde a la vez: aguja y área sobre la curva, punto T, punteggio standard, percentil, banda («Nella media»…) y una cuadrícula de 100 personas. Es didáctico (distribución normal, no normas de un test) y lo dice. La tarjeta reserva su altura, así que mover el control no desplaza la página.
- **Sección bento «Perché sceglierci»** con datos reales del catálogo: la franja de edad de cada test sobre una escala de 0 a 100 años, la duración (10–70 min), los cuatro formatos, la formación ECM (hasta 50 créditos) y una vista previa del presupuesto.
- **«Chi può acquistare» con el alcance real**: cada cualificación indica cuántos tests del catálogo permite comprar y cuáles (A: CIFRA 4-8; B: 4 tests; C: todos; HR: STILO y VERNA), con un medidor de esa proporción.
- **«Richiedi una demo» siempre a mano**: un botón flotante lleva al formulario, pone el foco en el primer campo y precarga el mensaje si está vacío. Se aparta mientras el formulario está en pantalla, y `scroll-padding-bottom` evita que tape el elemento con foco (WCAG 2.4.11).
- **Carrito lateral → «Richiedi un preventivo»**: cantidades, subtotal, ahorro, total «IVA esclusa», suma de créditos ECM y aviso de cualificación. El presupuesto precarga el formulario de contacto con las líneas del pedido, porque hospitales, escuelas y empresas compran por presupuesto, no con tarjeta.
- **Filtros en la URL** (`?categoria=formazione&q=assessment&eta=8`): una búsqueda se puede compartir con un colega.
- **Movimiento sin librerías** (`resources/stylus/components/_motion.styl`, el mismo CSS que usa la Versión A): titular que sube palabra a palabra, foto del hero con parallax, tarjetas que se recolocan con **View Transitions** al filtrar, barras y medidores ligados al scroll, drawer, menú y popover con `@starting-style`. Nada se mueve solo: todo ocurre una vez al cargar, por estado (hover, foco, filtro) o ligado al scroll que controla el usuario (WCAG 2.2.2). Con `prefers-reduced-motion` se muestra el estado final.

### Mejoras frente a la referencia ([giuntipsy.com/it-it](https://www.giuntipsy.com/it-it))

| En la referencia | En esta landing |
|---|---|
| La tarjeta muestra imagen, nombre y un precio del mismo tamaño que el nombre | La tarjeta deja **descartar sin hacer clic**: edad, duración y cualificación (tests) o inicio, horas y créditos ECM (cursos). El precio final, con el original tachado, y «Aggiungi» comparten la última fila |
| Filtros que recargan la página; facet de 191 siglas | Pills de categoría instantáneas (radios nativos), edad exacta de la persona, contador y estado vacío con sugerencias |
| Buscador en un modal, 500 ms de debounce sobre una API de 1,2 s | Buscador siempre visible e instantáneo, con lista de resultados que lleva a la tarjeta. Ignora mayúsculas, acentos y tipo de apóstrofo |
| Mega-menú de 28 enlaces en 3 ejes; un único `<h1>`… en el logo | 4 entradas planas; «Formazione» abre el catálogo ya filtrado; jerarquía de títulos correcta |
| Cadenas en inglés y español en la tienda italiana | Un único diccionario italiano (`lang/it/ui.php`) |
| No se puede añadir al carrito desde el listado | «Aggiungi al carrello» en cada tarjeta, con confirmación y contador |
| El contacto abre Zendesk en otra pestaña | Formulario en la página |

## Estructura

```
app/
├─ Catalog/      dominio: Product (abstracta, readonly) → TestProduct, CourseProduct; ProductFact, ProductImage;
│                enums Category / ProductFormat / ProductKind / Qualification; Catalog (agregado), ProductFilter,
│                CatalogFacts; CatalogRepository (interfaz) ← JsonCatalogRepository
├─ Interpreter/  ScoreInterpretation (value object), ScoreBand (enum)
├─ Support/      Money (value object), ItalianDate
├─ ViewModels/   un view model por sección con lógica (Hero, CategoryFilter, Assistant, Bento, Interpreter, TrustStrip) y Seo (layout)
├─ Seo/          StructuredData (JSON-LD del catálogo), SocialImage
├─ View/Components/Photo.php   <picture> AVIF/WebP/JPEG reutilizado por tarjetas y bento
├─ Providers/AppServiceProvider.php   enlaza el repositorio y registra los view models como view composers
└─ Http/         LandingController (invocable), RobotsController, SitemapController
bootstrap/app.php     ruta de la landing fuera del grupo "web" + cache.headers
config/site.php       año del copyright y redes del footer
lang/it/ui.php        textos de la interfaz para __() y trans_choice
resources/
├─ data/              catalog.json, copy.it.json
├─ views/
│  ├─ layouts/app.blade.php      @yield, @stack
│  ├─ pages/home.blade.php       @extends + @section, una @include por sección
│  ├─ sections/                  header, hero, catalog, assistant, bento, interpreter, resources, contact, footer
│  └─ partials/                  product-card, price, product-meta, format-badge, field, icon, section-heading,
│                                trust-strip, logo, nav-links, search (combobox con resultados), filter,
│                                empty-state, login, cart-button, cart-drawer, mobile-menu…
├─ stylus/
│  ├─ abstracts/   _tokens, _mixins (mq, hover, sr-only, escala tipográfica)
│  ├─ base/        _reset (Preflight de Tailwind portado), _fonts, _base, _utilities
│  ├─ components/  un bloque BEM por archivo: btn, badge, product-card, price, meta, filter, field, empty…
│  └─ sections/    site-header, hero, catalog, assistant, bento, interpreter, resources, contact, site-footer
└─ js/
   ├─ app.js       inicializa los módulos al cargar el DOM
   ├─ modules/     demo-fab, catalog/ (index, category-filter, age-field, results, items), search, filter-state, dialog, nav,
   │               cart, contact-form, assistant, interpreter, announcer (jQuery)
   └─ utils/       normalize, filters, validators, copy, format, scores (puros, testeados con Node) y motion
tests/            Unit/FormattingTest, Unit/ScoreInterpretationTest, Feature/LandingPageTest, Feature/CatalogTest, Feature/SeoTest,
                  js/logic.test.mjs, fixtures/
docker/           Caddyfile y php.ini de la imagen de producción
```

Los datos (`catalog.json`, `copy.it.json`), los tokens, la capa de movimiento, las fuentes, los iconos y los casos de prueba de `tests/fixtures/` son los mismos que usa la Versión A. Los archivos que lo indican en su cabecera se cambian a la vez en los dos repositorios.

## Decisiones técnicas

- **Blade literal respecto al brief.** Layout con `@extends/@section/@yield/@stack`, vistas por sección y parciales reutilizables. Los parciales con parámetros se usan como componentes anónimos (`<x-product-card :product="$p" />`) gracias a `Blade::anonymousComponentPath(resource_path('views/partials'))`, y los estáticos con `@include`. La carpeta `partials/` existe y se usa de las dos formas.
- **jQuery donde lo pide el brief.** Filtros, buscador y validación usan selección, eventos delegados, `.prop('hidden')`, `.attr()` y un evento propio `catalog:change`. APIs nativas solo donde jQuery no tiene equivalente (`<dialog>`, `Intl`, `document.startViewTransition()` para recolocar las tarjetas al filtrar). La lógica pura vive en `utils/*.mjs`, sin jQuery, con las mismas reglas y casos de prueba que la Versión A.
- **Dominio orientado a objetos, sin arrays sueltos.**
  - `Product` es abstracta y cada tipo resuelve lo suyo por polimorfismo (`coversAge()`, `facts()`, `byline()`, `ecmCredits()`, `kind()`): no queda ningún `instanceof` repartido por vistas y servicios.
  - Precios, puntuaciones, hechos del catálogo y filtros son value objects inmutables (`Money`, `ScoreInterpretation`, `CatalogFacts`, `ProductFilter`, este último como *specification*).
  - El controlador depende de la interfaz `CatalogRepository` (inversión de dependencias); la implementación JSON se enlaza en el contenedor y memoiza el catálogo.
  - Las vistas no calculan: cada sección con lógica recibe su view model mediante un *view composer*, y la etiqueta `<picture>` vive en un componente de clase (`<x-photo>`).
  - `declare(strict_types=1)` en todo el código y Pint con `strict_comparison`, `strict_param` y `void_return`.
- **Mismo comportamiento interactivo, sin framework.** `modules/assistant.mjs` reproduce el chat y filtra con `utils/filters.mjs`, leyendo los datos de la propia grilla. `modules/interpreter.mjs` usa `utils/scores.mjs`. El primer render lo hace el servidor con las mismas reglas: `ProductFilter` y `ScoreInterpretation`, probados con los mismos casos que los módulos jQuery, de modo que sin JavaScript también se ve un caso ya respondido y una puntuación interpretada.
- **Imágenes sin framework.** Las variantes de cada foto (AVIF, WebP, JPEG) están en `public/images`, y Blade escribe `<picture>` con `srcset`/`sizes`, `width`/`height` y carga diferida. La foto del hero tiene dirección de arte: recorte 9:16 para pantallas verticales, foto 16:9 de 640 a 3840 px para el resto y un `sizes` que sigue la altura cuando la pantalla es más estrecha que la imagen. Ocupa todo el viewport, bajo el header, así que Chrome la trata como fondo ([cambio de LCP de M88](https://chromium.googlesource.com/chromium/src/+/main/docs/speed/metrics_changelog/2020_11_lcp.md)) y el LCP es el titular.
- **Buscador como combobox.** Las filas de resultados (miniatura, nombre, sigla, precio) llegan en el HTML, ocultas; `modules/search.mjs` decide cuáles mostrar y sigue el patrón ARIA 1.2 de *combobox* con lista.
- **Capa de movimiento compartida.** Keyframes, líneas de tiempo de scroll, `@property` y View Transitions llegan como CSS literal dentro de un bloque `@css` (`components/_motion.styl`): Stylus no lo toca, y es el mismo CSS que usa la Versión A. La maquetación de cada sección sigue en bloques BEM propios.
- **Stylus + BEM con la misma base que la Versión A.** Incluye el *Preflight* de Tailwind 4 portado tal cual, los mismos tokens y las métricas de fuentes de respaldo que genera `next/font`, de modo que cada valor coincide con el de la versión Next.js.
- **Reglas de Stylus validadas con una prueba de tortura.** Stylus 0.64 altera en silencio CSS moderno:
  - `clamp()`, `min()` y `max()` los resuelve él mismo y da valores incorrectos, por lo que pasan por `unquote()` o por tokens en bloques `@css`;
  - `rgb(r g b / a)` con espacios produce un color equivocado y opaco, por lo que uso `rgba(r, g, b, a)`;
  - `color-mix()` y las media queries con sintaxis de rango no compilan;
  - `vendors = official` evita los `@keyframes` con prefijos obsoletos.
- **Laravel Mix, con conocimiento de causa.** El brief lo pide (o Gulp). Mix 6.0.49 no se publica desde 2022 y se rompe con webpack ≥ 5.107: importa `webpack/lib/SizeFormatHelpers`, que webpack eliminó. Por eso fijé **webpack 5.105.4** con `overrides`. También desactivé `cssDeclarationSorter` de cssnano, porque movía el atajo `animation` detrás de `animation-timeline` y anulaba las animaciones ligadas al scroll. En un proyecto nuevo usaría Vite, que es lo que trae Laravel por defecto.
- **Página sin estado y cacheable.** La ruta se registra fuera del grupo `web`, sin sesión, cookies ni CSRF, con `cache.headers` (`public`, `s-maxage`, `stale-while-revalidate`, `ETag` → `304`). Un test de feature comprueba las cabeceras y que no hay `Set-Cookie`. Sin JavaScript, el formulario hace `POST /contatto`, que responde 303 a `/#contatti` sin guardar nada.
- **SEO con las mismas etiquetas que la Versión A.** `SeoViewModel` alimenta el `<head>` del layout: canonical, `hreflang`, robots, Open Graph, Twitter, iconos y manifest, con los mismos valores que genera Next. El origen sale de la petición (`url('/')`), así que vale igual en local, en Docker y detrás del proxy de Railway (`trustProxies`). `StructuredData` arma el JSON-LD con los mismos objetos del catálogo que pintan las tarjetas, y `json_encode` con `JSON_HEX_TAG` evita que un texto cierre el `<script>`. `/robots.txt` y `/sitemap.xml` (con las 13 imágenes) son rutas con su propio controlador.
- **Formato.** `Money::format()` usa `number_format()` y no `NumberFormatter`: con CLDR ≥ 46, ICU imprime `1250,00 €` para `it_IT` y PHP no permite cambiarlo. Las fechas usan Carbon (`isoFormat('D MMMM YYYY')`), sin depender de ext-intl.
- **jQuery 4.1 slim** (19 KB gzip): incluye la corrección de seguridad de 4.1. El código usa solo la API común con 3.7.x, la versión que trae Magento 2.4.

### Cómo se llevaría a Magento

La tienda actual de Giunti es Magento, y el código está pensado para trasladarse:
- Los módulos jQuery pasarían a widgets de RequireJS (`$.widget`), usando la misma API común con jQuery 3.7.1.
- Los bloques BEM de Stylus pasarían a LESS, el preprocesador de Magento, de forma casi mecánica.
- Los parciales Blade pasarían a plantillas `.phtml` con bloques y view models.
- Los textos irían a `i18n/it_IT.csv`.

## Calidad

| Comprobación | Resultado |
|---|---|
| PHPUnit (formato, dinero, filtro y hechos del catálogo, intérprete, landing sin cookies y cacheable, SEO con el JSON-LD y sus precios) | 46/46 |
| `node --test` (filtros, búsqueda, validación, plurales, puntuaciones, asistente) | 63/63 |
| Pint (`declare_strict_types`, `strict_comparison`, `strict_param`) | sin cambios |
| Imagen Docker en CI | `lang="it"`, `/up`, `robots.txt` y sitemap, sin cookies, `Cache-Control: public`, `304` con `ETag` |
| Lighthouse móvil y escritorio (ver abajo) | 100 en las cuatro categorías |

## Rendimiento (build de producción, medido el 24-11-2026)

Lighthouse 13.6, mediana de tres ejecuciones por perfil, con la referencia medida el mismo día en el mismo equipo. La aplicación se sirvió con PHP y OPcache detrás de un proxy local con Brotli y las mismas cabeceras de caché que el Caddy de la imagen, porque `artisan serve` no comprime.

| | Versión B | Referencia |
|---|---|---|
| Móvil (4G lento simulado, CPU ×4): Rendimiento · Accesibilidad · Buenas prácticas · SEO | **100 · 100 · 100 · 100** | 56 · 89 · 73 · 85 |
| Escritorio: Rendimiento · Accesibilidad · Buenas prácticas · SEO | **100 · 100 · 100 · 100** | — |
| Móvil: LCP · FCP · TBT · CLS | 0,79 s · 0,79 s · 33 ms · 0 | 24,0 s · 2,6 s · 331 ms · 0 |
| Móvil: peso inicial · peticiones | 338 KB · 14 | 5.918 KB · 327 |
| JS transferido | **29 KB** (jQuery slim incluido) | 1.976 KB |
| CSS transferido | 12 KB, en línea en el HTML | 150 KB, bloqueante |

- **El LCP es el titular, no la foto.** La foto del hero ocupa todo el viewport y Chrome la trata como fondo. Antes, con la foto 113 px más baja que el viewport, el LCP simulado era 2,3 s (rendimiento 98): el tiempo de bajar la foto con 4G lento.
- **Foto nítida en móvil sin pesar de más.** Un móvil de 412 px descarga 86 KB y ve un píxel de imagen por píxel de pantalla. Antes descargaba 30 KB y la imagen se veía ampliada ×3.
- **Menos trabajo en el hilo principal.** El desenfoque de entrada del titular y un subrayado animado con `background-size` repintaban en cada frame; se eliminaron y la animación queda en `opacity` y `translate`. Con una sola hoja de 12 KB, el CSS va incrustado en el HTML y no bloquea con una petición más.
- **Fuentes.** Dos woff2 (84 KB) precargados, con respaldo de métricas ajustadas: CLS 0.
- **Lo que Lighthouse aún sugiere, sin restar puntos:** una caché de 7 días para las imágenes, que no llevan hash, y, en escritorio, que la variante de 2400 px sirve una foto de 1670 px.
- Son mediciones de laboratorio. Los Core Web Vitals reales (datos de campo de CrUX) solo existirán cuando el sitio publicado tenga tráfico.

## SEO

Lighthouse SEO 100. La Versión A publica las mismas etiquetas y el mismo JSON-LD.

- **Indexable.** `robots` con `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1`, sin `X-Robots-Tag`, y `robots.txt` con `Allow: /` y la URL del sitemap.
- **Título y descripción** en italiano, con los términos que se buscan: «Test psicologici e corsi di formazione | Giunti Psychometrics» y una descripción de 154 caracteres que menciona tests validados, cursos con créditos ECM, el filtro por ámbito y edad y el presupuesto.
- **URL canónica** absoluta, `hreflang` it-IT y x-default, `<html lang="it">`. El origen sale de la petición.
- **Open Graph y Twitter** (`summary_large_image`) con una imagen de 1200×630 generada desde el hero, con su texto alternativo, `og:locale`, `og:site_name` y dimensiones.
- **Iconos y manifest:** `favicon.ico` (48 y 32 px), `favicon.svg`, `apple-touch-icon`, iconos de 192 y 512 px, `theme-color` y `manifest.webmanifest` en italiano.
- **Datos estructurados** en un solo `@graph`: `Organization`, `WebSite` con `SearchAction` (la búsqueda funciona con `?q=`), `WebPage` con `dateModified`, e `ItemList` con los 12 productos como `Product` o `Course`. Cada uno lleva `Offer` en EUR, precio sin IVA (`UnitPriceSpecification`) y un enlace que abre el catálogo filtrado por su sigla. Los cursos añaden `CourseInstance` con modalidad, fecha de inicio, horas, docente y sede. `SeoTest` comprueba el JSON-LD y sus precios.
- **`sitemap.xml`** con `lastmod` y las 13 imágenes (las 12 fotos de producto y la de redes).

## Accesibilidad (WCAG 2.1 AA)

- Landmarks, *skip link*, un solo `<h1>` y jerarquía correcta; foco siempre visible.
- Pills como grupo de radios nativo (flechas, «1 de 5»); contador en `role="status"`; anuncios *polite* al añadir al carrito.
- Formulario con etiquetas visibles, errores en texto e icono enlazados por `aria-describedby`, `aria-invalid` y foco en el primer error.
- `alt` descriptivo en italiano en todas las imágenes de producto; badges diferenciados por relleno, icono y texto, nunca solo por color.
- Nombre accesible = texto visible + contexto («Aggiungi al carrello – ORMA», criterio 2.5.3).
- **El acento del brief (`#0EA5E9`) solo alcanza 2,77:1 sobre blanco**, así que en las secciones claras es decoración. Las secciones oscuras usan una tinta más profunda (`#0A2140`), sobre la que el acento llega a 5,81:1 y sirve como texto, botón y anillo de foco. En claro, enlaces, botones y foco usan el primario (8,29:1).
- El texto decorativo (numerales de sección y frase del footer) está oculto a los lectores de pantalla; las siglas y la marca llevan `translate="no"`.

## Supuestos

- 3 columnas desde 1024 px: cumple 1/2/3 en 375/768/1280 y evita dos tarjetas enormes en portátiles.
- La búsqueda cubre nombre y sigla del producto. «Test» lleva al catálogo y, si estaba activo el filtro «Formazione», lo quita.
- Precios «IVA esclusa», como en la referencia (venta B2B). «Accedi», checkout y envío del formulario son simulados. Sin JavaScript, el formulario hace `POST /contatto`, que redirige (303) a `#contatti` sin guardar nada.
- El formato de precios usa `number_format()`, con tests que comprueban la cadena exacta del brief (`1.250,00 €`), incluido el espacio de no separación.

## Mejoras propuestas

- Carrito persistente con «deshacer», histograma de edad en el filtro y selects personalizables, implementados a la vez en las dos versiones.
- Migrar de Laravel Mix a Vite y de Stylus a CSS nativo con anidamiento cuando el stack del equipo lo permita.
- Nombres con hash para las imágenes, para poder cachearlas un año.
- Servir el HTML detrás de un CDN, aprovechando las cabeceras `s-maxage` y `stale-while-revalidate` que ya envía.

## Créditos

- Fuentes: Fraunces e Inter (SIL OFL 1.1). Iconos: Lucide (ISC).
- Fotos (Unsplash License). Hero: Ahmadreza Rezaie. Cursos: Julio Lopez, Redd Francisco, Vitaly Gariev, Timur Shakerzianov. Tests: Vitaly Gariev (ORMA, IRED), Centre for Ageing Better (SOGLIA), Michał Parzuchowski (LESCA), Quilia (ARCA), Anna Mysłowska-Kiczek (CIFRA), Andreea Avramescu (STILO), bruce mars (VERNA). Enlaces a cada foto en `resources/data/catalog.json`.
- Ideas de componentes de [21st.dev](https://21st.dev), reimplementadas sin copiar código: [Interactive Hover Button](https://21st.dev/@dillionverma/components/interactive-hover-button), [Magic Card](https://21st.dev/@dillionverma/components/magic-card), [Hero Highlight](https://21st.dev/@manuarora700/components/hero-highlight), [Drawer](https://21st.dev/@efferd/components/drawer), [Origin UI Input](https://21st.dev/@originui/components/input), [Pill](https://21st.dev/@haydenbleasel/components/pill), [Empty](https://21st.dev/@shadcn/components/empty), [Multi-State Action Button](https://21st.dev/@themeocean/components/multi-state-action-button-with-micro-animations), [Cart Badge](https://21st.dev/@f0rextasy/components/cart-badge), [Text Effect](https://21st.dev/@ibelick/components/text-effect), [Number Ticker](https://21st.dev/@dillionverma/components/number-ticker), [Animated Background](https://21st.dev/@ibelick/components/animated-background), [Shine Border](https://21st.dev/@dillionverma/components/shine-border), [Bento Grid](https://21st.dev/@dillionverma/components/bento-grid), [Text Reveal](https://21st.dev/@dillionverma/components/text-reveal).
- Logotipo y curva del intérprete: originales.
