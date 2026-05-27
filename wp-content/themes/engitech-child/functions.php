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
   FIN DE CONFIGURACIONES DE MANTENIMIENTO
   ============================================================================ */

// Cargar estilos del tema padre (si no está ya implementado)
function vertexray_enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'vertexray_enqueue_parent_styles', 1 );
