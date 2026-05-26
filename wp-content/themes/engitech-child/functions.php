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
   TAREA 2: REEMPLAZO DE PLUGINS GOOLYTICS Y JOINCHAT
   ============================================================================ */

/**
 * Insertar Google Analytics 4 / GTM solo en producción
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

/**
 * Botón flotante de WhatsApp personalizado
 * Reemplaza el plugin Joinchat / creame-whatsapp-me
 */
function vertexray_whatsapp_button() {
    // Número de WhatsApp (formato internacional sin +, espacios ni guiones)
    // NOTA: Número obtenido de producción (59892250103)
    // TODO: Confirmar con Andrés Bolani que es el número correcto
    $whatsapp_number = '59892250103'; // Uruguay +598 9225 0103
    
    // Mensaje predeterminado (español)
    $default_message = 'Hola! Quería más información';
    
    // URL de WhatsApp
    $whatsapp_url = 'https://wa.me/' . esc_attr( $whatsapp_number );
    if ( ! empty( $default_message ) ) {
        $whatsapp_url .= '?text=' . rawurlencode( $default_message );
    }
    ?>
    <!-- Botón flotante de WhatsApp - Vertex Ray -->
    <div id="vertexray-whatsapp-button" class="vertexray-wa-floating">
        <a href="<?php echo esc_url( $whatsapp_url ); ?>" 
           target="_blank" 
           rel="noopener noreferrer nofollow"
           aria-label="Chat on WhatsApp"
           title="Chat with us on WhatsApp">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.281 4.65C24.326 1.687 20.359 0.058 16.149 0.056C7.313 0.056 0.11 7.257 0.107 16.094C0.106 18.914 0.854 21.664 2.271 24.08L0 32L8.111 29.775C10.436 31.069 13.063 31.751 15.735 31.752H15.743C24.576 31.752 31.779 24.551 31.782 15.714C31.783 11.51 30.235 7.614 27.281 4.65ZM16.149 29.091H16.142C13.746 29.09 11.396 28.44 9.349 27.212L8.849 26.917L3.831 28.17L5.109 23.275L4.784 22.755C3.435 20.642 2.728 18.2 2.729 15.694C2.731 8.704 8.276 3.161 16.155 3.161C19.981 3.162 23.563 4.653 26.26 7.353C28.956 10.053 30.442 13.638 30.441 17.466C30.439 24.458 24.894 29.091 16.149 29.091ZM24.86 20.192C24.463 20.002 22.463 19.018 22.108 18.889C21.753 18.761 21.502 18.697 21.251 19.095C21 19.492 20.236 20.381 20.017 20.632C19.798 20.883 19.579 20.914 19.182 20.724C18.785 20.535 17.513 20.111 16.003 18.763C14.826 17.712 14.033 16.424 13.814 16.027C13.595 15.63 13.79 15.426 13.979 15.238C14.149 15.069 14.357 14.798 14.547 14.579C14.737 14.36 14.8 14.203 14.929 13.952C15.058 13.701 14.994 13.482 14.899 13.292C14.804 13.103 14.014 11.101 13.691 10.307C13.374 9.531 13.053 9.641 12.824 9.629C12.605 9.618 12.354 9.616 12.103 9.616C11.852 9.616 11.455 9.711 11.1 10.108C10.745 10.505 9.699 11.489 9.699 13.491C9.699 15.493 11.131 17.434 11.321 17.685C11.511 17.936 14.029 21.908 17.924 23.687C18.817 24.093 19.511 24.329 20.051 24.505C20.944 24.793 21.756 24.751 22.395 24.653C23.11 24.544 24.735 23.718 25.058 22.809C25.381 21.9 25.381 21.119 25.286 20.961C25.191 20.803 24.94 20.708 24.543 20.518L24.86 20.192Z" fill="currentColor"/>
            </svg>
        </a>
    </div>
    
    <style>
        /* Estilos del botón flotante de WhatsApp */
        .vertexray-wa-floating {
            position: fixed;
            bottom: 10rem;
            right: 3rem;
            z-index: 9999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .vertexray-wa-floating a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .vertexray-wa-floating a:hover {
            background: #128C7E;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
        }
        
        .vertexray-wa-floating a:active {
            transform: scale(0.95);
        }
        
        /* Animación de entrada */
        @keyframes vertexray-wa-fadein {
            from {
                opacity: 0;
                transform: scale(0.5) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .vertexray-wa-floating {
            animation: vertexray-wa-fadein 0.5s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .vertexray-wa-floating {
                bottom: 9rem;
                right: 2rem;
            }
            
            .vertexray-wa-floating a {
                width: 56px;
                height: 56px;
            }
        }
    </style>
    <?php
}
add_action( 'wp_footer', 'vertexray_whatsapp_button', 999 );


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
