<?php
/**
 * Engitech Child Theme Functions
 * 
 * @package Engitech Child
 * @author Vertex Ray - Mantenimiento 2026-05
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ============================================================================
   TAREA 1: CORRECCIÓN DE METATAGS DE TÍTULOS
   ============================================================================ */

/**
 * Filtro para limpiar títulos de páginas específicas eliminando "Engitech"
 * y asegurando que se use el nombre correcto del sitio
 * 
 * @param array $title Partes del título
 * @return array Título modificado
 */
function vertexray_fix_page_titles( $title ) {
    // Solo ejecutar en frontend
    if ( is_admin() ) {
        return $title;
    }
    
    // Páginas específicas a corregir: Design, Join Our Team, Projects, Contact Us
    if ( is_page() ) {
        $page_id = get_queried_object_id();
        $page_title = get_the_title( $page_id );
        
        // Array de páginas problemáticas
        $problematic_pages = array(
            'Design',
            'Join Our Team',
            'Projects',
            'Contact Us'
        );
        
        // Si es una de las páginas problemáticas
        if ( in_array( $page_title, $problematic_pages ) ) {
            // Limpiar cualquier referencia a "Engitech" en todas las partes del título
            foreach ( $title as $key => $value ) {
                if ( is_string( $value ) ) {
                    // Remover "Engitech" y sus variantes
                    $value = str_replace( array( 'Engitech', 'engitech', 'ENGITECH' ), '', $value );
                    // Limpiar espacios múltiples y guiones sobrantes
                    $value = preg_replace( '/\s+/', ' ', $value );
                    $value = trim( $value, ' -–—|' );
                    $title[$key] = $value;
                }
            }
            
            // Asegurar que el tagline sea correcto
            if ( isset( $title['tagline'] ) ) {
                $title['tagline'] = 'Vertex Ray';
            }
            
            // Si el sitename tiene "Engitech", reemplazarlo
            if ( isset( $title['site'] ) ) {
                $title['site'] = 'Vertex Ray';
            }
        }
    }
    
    // Para la página de Projects (portafolio)
    if ( is_post_type_archive( 'ot_portfolio' ) || is_singular( 'ot_portfolio' ) ) {
        foreach ( $title as $key => $value ) {
            if ( is_string( $value ) ) {
                $value = str_replace( array( 'Engitech', 'engitech', 'ENGITECH' ), 'Vertex Ray', $value );
                $title[$key] = $value;
            }
        }
    }
    
    return $title;
}
add_filter( 'document_title_parts', 'vertexray_fix_page_titles', 100 );

/**
 * Reemplazo completo del título si el filtro anterior no funciona
 * Este hook tiene prioridad aún mayor
 */
function vertexray_override_wp_title( $title ) {
    if ( is_admin() ) {
        return $title;
    }
    
    // Limpiar "Engitech" de cualquier título generado
    $title = str_replace( array( 'Engitech', 'engitech', 'ENGITECH' ), 'Vertex Ray', $title );
    
    return $title;
}
add_filter( 'pre_get_document_title', 'vertexray_override_wp_title', 999 );

/**
 * Forzar Open Graph y meta tags correctos para redes sociales
 */
function vertexray_custom_meta_tags() {
    if ( is_page() || is_post_type_archive( 'ot_portfolio' ) || is_singular( 'ot_portfolio' ) ) {
        $title = get_the_title();
        
        // Si es archivo de portafolio
        if ( is_post_type_archive( 'ot_portfolio' ) ) {
            $title = 'Projects - Vertex Ray';
        }
        
        // Limpiar título
        $title = str_replace( array( 'Engitech', 'engitech', 'ENGITECH' ), 'Vertex Ray', $title );
        $title = esc_attr( $title );
        
        echo '<meta property="og:title" content="' . $title . '" />' . "\n";
        echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
        echo '<meta property="og:site_name" content="Vertex Ray" />' . "\n";
    }
}
add_action( 'wp_head', 'vertexray_custom_meta_tags', 5 );


/* ============================================================================
   TAREA 2: REEMPLAZO DE PLUGIN GOOLYTICS
   ============================================================================
   NOTA: El plugin Joinchat (WhatsApp) se mantiene activo por decisión de arquitectura.
   Razón: Plugin ligero (6.54 KB), mantenido por terceros, configurable sin código.
   El ahorro de 3 KB no justifica mantener código custom adicional.
   ============================================================================ */

/**
 * Insertar Google Analytics / GTM solo en producción
 * Reemplaza el plugin Goolytics
 */
function vertexray_insert_google_analytics() {
    // NO ejecutar en entorno local o desarrollo
    $is_local = (
        in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ||
        strpos( $_SERVER['HTTP_HOST'], 'local' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], '.test' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], '.dev' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], 'staging' ) !== false
    );
    
    // Solo ejecutar en producción (vertexray.com)
    if ( $is_local ) {
        return;
    }
    
    // NO ejecutar si el usuario está logueado como administrador
    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // IDs de tracking (detectados del header.php actual)
    // ⚠️ NOTA: Estos IDs fueron extraídos del código hardcodeado en header.php
    // Se recomienda verificar en Google Analytics/GTM que sean los correctos
    // Ya que pueden existir múltiples contenedores/propiedades
    $ga_tracking_id = 'UA-2468946-1'; // Universal Analytics (legacy)
    $gtm_id = 'GTM-NPL5XMZ'; // Google Tag Manager
    
    ?>
    <!-- Google Tag Manager - Vertex Ray -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_tracking_id ); ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo esc_js( $ga_tracking_id ); ?>', {
        'anonymize_ip': true,
        'cookie_flags': 'SameSite=None;Secure'
      });
    </script>
    <?php
}
add_action( 'wp_head', 'vertexray_insert_google_analytics', 1 );

/**
 * Insertar noscript de GTM después del body
 */
function vertexray_gtm_noscript() {
    // Mismas condiciones que la función anterior
    $is_local = (
        in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ||
        strpos( $_SERVER['HTTP_HOST'], 'local' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], '.test' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], '.dev' ) !== false ||
        strpos( $_SERVER['HTTP_HOST'], 'staging' ) !== false
    );
    
    if ( $is_local || ( is_user_logged_in() && current_user_can( 'manage_options' ) ) ) {
        return;
    }
    
    $gtm_id = 'GTM-NPL5XMZ';
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>" 
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action( 'wp_body_open', 'vertexray_gtm_noscript', 1 );


/* ============================================================================
   TAREA 3: HARDENING DE SEGURIDAD SIN PLUGINS
   ============================================================================ */

/**
 * 3.1 - Deshabilitar completamente xmlrpc.php
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Bloquear acceso directo a xmlrpc.php con header 403
 */
function vertexray_block_xmlrpc_requests() {
    if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'xmlrpc.php' ) !== false ) {
        header( 'HTTP/1.1 403 Forbidden' );
        die( 'XML-RPC is disabled on this site.' );
    }
}
add_action( 'init', 'vertexray_block_xmlrpc_requests', 1 );

/**
 * 3.2 - Ocultar versión de WordPress de múltiples ubicaciones
 */

// Remover meta generator de WP
remove_action( 'wp_head', 'wp_generator' );

// Remover versión de RSS feeds
add_filter( 'the_generator', '__return_empty_string' );

// Remover versión de scripts y styles encolados
function vertexray_remove_wp_version_strings( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'vertexray_remove_wp_version_strings', 9999 );
add_filter( 'script_loader_src', 'vertexray_remove_wp_version_strings', 9999 );

/**
 * 3.3 - Deshabilitar endpoints de API REST que exponen usuarios
 */
function vertexray_disable_user_endpoints( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
}
add_filter( 'rest_endpoints', 'vertexray_disable_user_endpoints' );

/**
 * Restringir acceso a REST API solo para usuarios autenticados (opcional - comentado)
 * Descomentar si se desea protección más agresiva
 */
/*
function vertexray_restrict_rest_api( $result ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_forbidden',
            __( 'REST API restricted to authenticated users.', 'engitech-child' ),
            array( 'status' => 401 )
        );
    }
    return $result;
}
add_filter( 'rest_authentication_errors', 'vertexray_restrict_rest_api' );
*/

/**
 * 3.4 - Cabeceras de seguridad HTTP
 */
function vertexray_security_headers() {
    // X-Frame-Options: prevenir clickjacking
    header( 'X-Frame-Options: SAMEORIGIN' );
    
    // X-Content-Type-Options: prevenir MIME sniffing
    header( 'X-Content-Type-Options: nosniff' );
    
    // X-XSS-Protection: protección XSS en navegadores antiguos
    header( 'X-XSS-Protection: 1; mode=block' );
    
    // Referrer-Policy: controlar información de referrer
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    
    // Permissions-Policy: deshabilitar APIs no necesarias
    header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
    
    // Content-Security-Policy: protección contra XSS (básica - ajustar según necesidades)
    // Comentado por defecto para evitar romper funcionalidad - descomentar y ajustar según sea necesario
    /*
    header( "Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;" );
    */
}
add_action( 'send_headers', 'vertexray_security_headers' );

/**
 * Deshabilitar edición de archivos desde el panel de administración
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Protección adicional: ocultar errores de login
 */
function vertexray_login_errors() {
    return __( 'Login credentials are incorrect. Please try again.', 'engitech-child' );
}
add_filter( 'login_errors', 'vertexray_login_errors' );

/**
 * Protección adicional: deshabilitar author archives para prevenir enumeración
 */
function vertexray_disable_author_archives() {
    if ( is_author() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'vertexray_disable_author_archives' );


/* ============================================================================
   TAREA 4: OPTIMIZACIÓN Y LIMPIEZA DE PERFORMANCE
   ============================================================================ */

/**
 * 4.1 - Deshabilitar comentarios y pingbacks globalmente
 * Vertex Ray es sitio de servicios/portfolio, sin blog activo.
 */
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open',    '__return_false', 20, 2 );

// Eliminar cabecera X-Pingback del HTTP response
add_filter( 'wp_headers', function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

// Ocultar menú de Comentarios en el panel de administración
function vertexray_disable_comments_admin_ui() {
    remove_menu_page( 'edit-comments.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action( 'admin_menu', 'vertexray_disable_comments_admin_ui' );

// Quitar indicador de comentarios de la barra de admin
add_action( 'wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
} );

/**
 * 4.2 - Deshabilitar script y estilos de emoji de WordPress
 * Ahorro: ~10 KB JS + CSS + 1 DNS lookup innecesario
 */
remove_action( 'wp_head',                'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts',    'print_emoji_detection_script' );
remove_action( 'wp_print_styles',        'print_emoji_styles' );
remove_action( 'admin_print_styles',     'print_emoji_styles' );
remove_filter( 'the_content_feed',       'wp_staticize_emoji' );
remove_filter( 'comment_text_rss',       'wp_staticize_emoji' );
remove_filter( 'wp_mail',                'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );

/**
 * 4.3 - Deshabilitar oEmbed discovery links (no se usa oEmbed en el sitio)
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * 4.4 - Preconnect a dominios de terceros críticos
 * Solo en producción — sincronizado con vertexray_insert_google_analytics()
 * Reduce latencia de primer request a GTM/GA en ~100-300 ms
 */
function vertexray_preconnect_hints() {
    $host     = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
    $is_local = (
        strpos( $host, 'local' )     !== false ||
        strpos( $host, 'localhost' ) !== false ||
        strpos( $host, '.test' )     !== false ||
        strpos( $host, '.dev' )      !== false ||
        strpos( $host, 'staging' )   !== false
    );
    if ( $is_local ) {
        return;
    }
    ?>
<link rel="preconnect" href="https://www.googletagmanager.com">
<link rel="preconnect" href="https://www.google-analytics.com">
<link rel="dns-prefetch" href="https://www.googletagmanager.com">
<link rel="dns-prefetch" href="https://www.google-analytics.com">
    <?php
}
add_action( 'wp_head', 'vertexray_preconnect_hints', 0 );

/**
 * Remover información de versiones de plugins y temas del código fuente
 */
function vertexray_remove_version_info() {
    return '';
}
add_filter( 'style_loader_tag', function( $html ) {
    return preg_replace( '/\?ver=[\d\.]+/', '', $html );
}, 10, 1 );

add_filter( 'script_loader_tag', function( $html ) {
    return preg_replace( '/\?ver=[\d\.]+/', '', $html );
}, 10, 1 );


/* ============================================================================
   TAREA 4: MENSAJES DE CONTACT FORM 7 EN ESPAÑOL
   ============================================================================
   CF7 muestra sus mensajes por defecto en inglés porque WordPress está
   configurado en locale inglés. Este filtro los reemplaza con español
   directamente, sin depender del idioma del sitio ni de archivos .po/.mo.
   
   Aplica a mensajes por defecto (formularios sin mensajes personalizados en DB)
   y también sirve como fallback. Para formularios con mensajes en DB, ver
   el script WP-CLI: ameba-deploy/docs/cf7-messages-es.sh
   ============================================================================ */

/**
 * Traducir mensajes de Contact Form 7 al español
 */
function vertexray_cf7_messages_spanish( $translated_text, $original_text, $domain ) {
    if ( $domain !== 'contact-form-7' ) {
        return $translated_text;
    }

    static $translations = null;
    if ( null === $translations ) {
        $translations = [
            'Thank you for your message. It has been sent.'
                => 'Gracias por tu mensaje. Ha sido enviado correctamente.',
            'There was an error trying to send your message. Please try again later.'
                => 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.',
            'One or more fields have an error. Please check and try again.'
                => 'Uno o más campos contienen errores. Por favor, revísalos e inténtalo de nuevo.',
            'There was an error trying to send your message. Please try again later.'
                => 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.',
            'You must accept the terms and conditions before sending your message.'
                => 'Debes aceptar los términos y condiciones antes de enviar tu mensaje.',
            'The field is required.'
                => 'Este campo es obligatorio.',
            'The field is too long.'
                => 'El contenido de este campo es demasiado largo.',
            'The field is too short.'
                => 'El contenido de este campo es demasiado corto.',
            'There was an unknown error uploading the file.'
                => 'Hubo un error desconocido al subir el archivo.',
            'You are not allowed to upload files of this type.'
                => 'No está permitido subir archivos de este tipo.',
            'The file is too big.'
                => 'El archivo es demasiado grande.',
            'There was an error uploading the file.'
                => 'Hubo un error al subir el archivo.',
            'The date format is incorrect.'
                => 'El formato de fecha no es válido.',
            'The date is before the earliest one allowed.'
                => 'La fecha es anterior al mínimo permitido.',
            'The date is after the latest one allowed.'
                => 'La fecha es posterior al máximo permitido.',
            'The number format is invalid.'
                => 'El formato del número no es válido.',
            'The number is smaller than the minimum allowed.'
                => 'El número es menor que el mínimo permitido.',
            'The number is larger than the maximum allowed.'
                => 'El número es mayor que el máximo permitido.',
            'The answer to the quiz is incorrect.'
                => 'La respuesta al cuestionario no es correcta.',
            'The e-mail address entered is invalid.'
                => 'La dirección de correo electrónico no es válida.',
            'The URL is invalid.'
                => 'La URL no es válida.',
            'The telephone number is invalid.'
                => 'El número de teléfono no es válido.',
        ];
    }

    return isset( $translations[ $original_text ] )
        ? $translations[ $original_text ]
        : $translated_text;
}
add_filter( 'gettext', 'vertexray_cf7_messages_spanish', 10, 3 );


/* ============================================================================
   FIN DE CONFIGURACIONES DE MANTENIMIENTO
   ============================================================================ */

// Cargar estilos del tema padre (si no está ya implementado)
function vertexray_enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'vertexray_enqueue_parent_styles', 1 );
