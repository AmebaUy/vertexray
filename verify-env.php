<?php
/**
 * verify-env.php — Diagnóstico post-actualización WP 7.0 + PHP 8.4
 *
 * Uso (desde el directorio raíz del sitio):
 *   wp eval-file verify-env.php
 *
 * Staging : ssh vertexraystg@vertexraystg.ssh.wpengine.net → wp eval-file ~/sites/vertexraystg/verify-env.php
 * Prod    : ssh vertexray@vertexray.ssh.wpengine.net       → wp eval-file ~/sites/vertexray/verify-env.php
 *
 * NO renderiza HTML. Solo output de texto para terminal.
 * Borrar el archivo al terminar: rm verify-env.php
 */

// ─── Helpers ───────────────────────────────────────────────────────────────────

function vr_ok( string $msg ): void    { WP_CLI::log( "\033[32m✓ {$msg}\033[0m" ); }
function vr_warn( string $msg ): void  { WP_CLI::log( "\033[33m⚠ {$msg}\033[0m" ); }
function vr_fail( string $msg ): void  { WP_CLI::log( "\033[31m✗ {$msg}\033[0m" ); }
function vr_info( string $msg ): void  { WP_CLI::log( "  {$msg}" ); }
function vr_head( string $msg ): void  { WP_CLI::log( "\n── {$msg} " . str_repeat( '─', max( 0, 50 - strlen( $msg ) ) ) ); }

// ─── Header ────────────────────────────────────────────────────────────────────

WP_CLI::log( str_repeat( '═', 60 ) );
WP_CLI::log( '  DIAGNÓSTICO POST-ACTUALIZACIÓN — VERTEXRAY' );
WP_CLI::log( '  WP ' . get_bloginfo( 'version' ) . ' + PHP ' . PHP_VERSION );
WP_CLI::log( '  ' . date( 'Y-m-d H:i:s T' ) );
WP_CLI::log( '  Site: ' . get_option( 'siteurl' ) );
WP_CLI::log( str_repeat( '═', 60 ) );


// ════════════════════════════════════════════════════════════════
// BLOQUE 1: CPT ot_portfolio + Meta Box
// ════════════════════════════════════════════════════════════════

vr_head( 'BLOQUE 1: CPT ot_portfolio + Meta Box 5.12.0' );

global $wpdb;

// 1a. CPT registrado
$cpt = get_post_type_object( 'ot_portfolio' );
if ( $cpt ) {
	vr_ok( "CPT 'ot_portfolio' registrado correctamente (label: {$cpt->label})" );
} else {
	vr_fail( "CPT 'ot_portfolio' NO está registrado — ot_portfolio plugin puede estar fallando" );
}

// 1b. Cantidad de portfolios en DB
$count = (int) $wpdb->get_var(
	"SELECT COUNT(*) FROM {$wpdb->posts}
	 WHERE post_type = 'ot_portfolio' AND post_status != 'trash'"
);
vr_info( "Portfolios en DB: {$count}" );

// 1c. Campos de meta detectados
if ( $count > 0 ) {
	$meta_keys = $wpdb->get_results(
		"SELECT DISTINCT pm.meta_key, COUNT(*) AS total
		 FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		 WHERE p.post_type = 'ot_portfolio' AND p.post_status != 'trash'
		 GROUP BY pm.meta_key
		 ORDER BY total DESC
		 LIMIT 40"
	);

	if ( $meta_keys ) {
		$custom_keys = array_filter( $meta_keys, fn( $m ) =>
			! str_starts_with( $m->meta_key, '_edit_' ) &&
			! str_starts_with( $m->meta_key, '_wp_trash' ) &&
			$m->meta_key !== '_thumbnail_id' // separar thumbnail
		);

		$thumb = array_filter( $meta_keys, fn( $m ) => $m->meta_key === '_thumbnail_id' );
		if ( $thumb ) {
			vr_ok( "Thumbnails (_thumbnail_id): " . reset( $thumb )->total . " registros" );
		}

		if ( $custom_keys ) {
			vr_ok( "Campos Meta detectados (" . count( $custom_keys ) . " keys personalizadas):" );
			foreach ( $custom_keys as $m ) {
				vr_info( "  · {$m->meta_key}  ({$m->total} registros)" );
			}
		} else {
			vr_warn( "Solo meta estándar de WP — sin campos Meta Box personalizados" );
		}
	} else {
		vr_warn( "Sin postmeta para portfolios — posible pérdida de campos tras actualización" );
	}

	// 1d. Huérfanos en postmeta (cualquier post type)
	$orphans = (int) $wpdb->get_var(
		"SELECT COUNT(*) FROM {$wpdb->postmeta} pm
		 LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		 WHERE p.ID IS NULL"
	);
	if ( $orphans > 0 ) {
		vr_warn( "Postmeta huérfanos en DB: {$orphans} filas sin post padre (correr: wp db optimize)" );
	} else {
		vr_ok( "Sin postmeta huérfanos detectados" );
	}

} else {
	vr_warn( "Sin portfolios en DB — no hay campos Meta que verificar" );
}

// 1e. Meta Box mismo (versión activa)
$mb_data = get_file_data(
	WP_PLUGIN_DIR . '/meta-box/meta-box.php',
	[ 'Version' => 'Version' ]
);
if ( $mb_data['Version'] ) {
	vr_info( "Meta Box versión activa: {$mb_data['Version']}" );
	if ( version_compare( $mb_data['Version'], '5.12.0', '>=' ) ) {
		vr_ok( "Meta Box 5.12.0+ (actualizado correctamente)" );
	} else {
		vr_warn( "Meta Box < 5.12.0 en disco — ¿caché de plugin?" );
	}
}


// ════════════════════════════════════════════════════════════════
// BLOQUE 2: Compatibilidad PHP 8.4
// ════════════════════════════════════════════════════════════════

vr_head( 'BLOQUE 2: PHP 8.4 Compatibilidad — Plugins de riesgo' );

vr_info( 'PHP version : ' . PHP_VERSION );
vr_info( 'PHP SAPI    : ' . PHP_SAPI );

/**
 * Patrones deprecated/eliminados en PHP 8.x que pueden causar problemas.
 * Cada entrada: [ regex, descripción del issue, versión que lo eliminó ]
 */
$deprecated_patterns = [
	[ '/\beach\s*\(/',                      'each() — eliminado en PHP 8.0',                        '8.0' ],
	[ '/\bcreate_function\s*\(/',           'create_function() — eliminado en PHP 8.0',              '8.0' ],
	[ '/\bget_magic_quotes_gpc\s*\(/',      'get_magic_quotes_gpc() — eliminado en PHP 8.0',         '8.0' ],
	[ '/\bFILTER_SANITIZE_STRING\b/',       'FILTER_SANITIZE_STRING — eliminado en PHP 8.1',         '8.1' ],
	[ '/\bmb_convert_encoding.*UTF-8.*BIG5/', 'mb_convert_encoding BIG5 deprecated en PHP 8.2',      '8.2' ],
	[ '/\$\{[^}]+\}/',                      '${var} string interpolation — deprecated PHP 8.2',      '8.2' ],
	[ '/->{\$[^}]+}/',                      '->{ dynamic prop access — deprecated PHP 8.2',          '8.2' ],
	[ '/\bstrip_tags\s*\(\s*NULL/',         'strip_tags(NULL) — deprecated en PHP 8.x',              '8.x' ],
	[
		// Implicit nullable: function foo( Type $var = null ) sin ?Type
		'/function\s+\w+\s*\([^)]*(?<!\?)\b(?:string|int|float|bool|array|object|callable)\s+\$\w+\s*=\s*null/',
		'Implicit nullable parameter — deprecated PHP 8.4 (usar ?Type)',
		'8.4'
	],
];

$risk_plugins = [
	'soo-demo-importer' => 'soo-demo-importer/soo-demo-importer.php',
	'ot_portfolio'      => 'ot_portfolio/ot_portfolio.php',
	'marker-io'         => 'marker-io/marker-io.php',
	'zoho-campaigns'    => 'zoho-campaigns/index.php',
	'wp-mail-smtp-pro'  => 'wp-mail-smtp-pro/wp_mail_smtp.php',
];

foreach ( $risk_plugins as $slug => $main_file ) {
	$plugin_dir  = WP_PLUGIN_DIR . '/' . $slug;
	$plugin_main = WP_PLUGIN_DIR . '/' . $main_file;

	if ( ! is_dir( $plugin_dir ) ) {
		vr_info( "[SKIP] {$slug} — directorio no encontrado" );
		continue;
	}

	// Escanear todos los .php del plugin (no solo el main file)
	$php_files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $plugin_dir, FilesystemIterator::SKIP_DOTS )
	);

	$issues_found = [];
	$files_scanned = 0;
	$total_lines   = 0;

	foreach ( $php_files as $file ) {
		if ( $file->getExtension() !== 'php' ) {
			continue;
		}
		$files_scanned++;
		$source      = @file_get_contents( $file->getPathname() );
		$total_lines += substr_count( $source, "\n" );

		if ( $source === false ) {
			continue;
		}

		foreach ( $deprecated_patterns as [ $pattern, $desc, $since ] ) {
			if ( preg_match( $pattern, $source ) ) {
				$rel_path = str_replace( WP_PLUGIN_DIR . '/', '', $file->getPathname() );
				$issues_found[] = "[PHP {$since}] {$desc}\n     → {$rel_path}";
			}
		}
	}

	if ( empty( $issues_found ) ) {
		vr_ok( "{$slug} — sin patrones deprecated ({$files_scanned} archivos, {$total_lines} líneas)" );
	} else {
		vr_warn( "{$slug} — {$files_scanned} archivos, ISSUES detectados:" );
		foreach ( array_unique( $issues_found ) as $issue ) {
			vr_info( "  ⚠ {$issue}" );
		}
	}
}

// 2b. Error log reciente (desde 25/05/2026)
vr_head( 'BLOQUE 2b: Error logs desde 25/05/2026' );

$cutoff_ts = mktime( 0, 0, 0, 5, 25, 2026 );

// WP Engine almacena logs en rutas distintas según el entorno
$candidate_logs = array_filter( [
	WP_CONTENT_DIR . '/debug.log',
	ini_get( 'error_log' ),
	getenv( 'HOME' ) . '/sites/' . parse_url( get_option( 'siteurl' ), PHP_URL_HOST ) . '/logs/php/error.log',
] );

$log_checked = false;
foreach ( $candidate_logs as $log_path ) {
	if ( ! $log_path || ! is_readable( $log_path ) ) {
		continue;
	}

	$log_checked = true;
	$filesize    = round( filesize( $log_path ) / 1024, 1 );
	vr_info( "Log: {$log_path} ({$filesize} KB)" );

	// Leer últimas 500 líneas (evitar cargar todo el archivo)
	$lines = [];
	$fp    = fopen( $log_path, 'rb' );
	if ( $fp ) {
		fseek( $fp, 0, SEEK_END );
		$pos  = ftell( $fp );
		$buf  = '';
		$read = 0;
		while ( $pos > 0 && $read < 500 ) {
			$chunk  = min( 4096, $pos );
			$pos   -= $chunk;
			fseek( $fp, $pos );
			$buf    = fread( $fp, $chunk ) . $buf;
			$read  += substr_count( $buf, "\n" );
		}
		fclose( $fp );
		$lines = array_filter( explode( "\n", $buf ) );
	}

	// Filtrar por fecha desde 25/05/2026
	// WP debug.log: [25-May-2026 12:34:56 UTC]
	// PHP error_log: [2026-05-25 12:34:56]
	$recent = array_filter( $lines, function ( $line ) use ( $cutoff_ts ) {
		if ( preg_match( '/\[(\d{2}-[A-Za-z]+-\d{4} \d{2}:\d{2}:\d{2})|(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $line, $m ) ) {
			$ts = strtotime( $m[1] ?: $m[2] );
			return $ts !== false && $ts >= $cutoff_ts;
		}
		return false;
	} );

	// Categorizar
	$fatals      = array_filter( $recent, fn( $l ) => stripos( $l, 'Fatal error' ) !== false );
	$deprecateds = array_filter( $recent, fn( $l ) => stripos( $l, 'Deprecated' ) !== false );
	$warnings    = array_filter( $recent, fn( $l ) => stripos( $l, 'Warning' ) !== false && stripos( $l, 'Deprecated' ) === false );

	vr_info( "  Entradas recientes (≥25/05/2026): " . count( $recent ) );
	vr_info( "  Fatals: " . count( $fatals ) . " | Deprecated: " . count( $deprecateds ) . " | Warnings: " . count( $warnings ) );

	if ( ! empty( $fatals ) ) {
		vr_fail( "FATAL ERRORS DETECTADOS:" );
		foreach ( array_slice( $fatals, -5 ) as $l ) {
			vr_info( "  " . substr( trim( $l ), 0, 200 ) );
		}
	}

	if ( ! empty( $deprecateds ) ) {
		vr_warn( "Deprecation warnings (últimas 10):" );
		foreach ( array_slice( $deprecateds, -10 ) as $l ) {
			vr_info( "  " . substr( trim( $l ), 0, 200 ) );
		}
	}

	if ( empty( $recent ) ) {
		vr_ok( "Sin entradas de error desde 25/05/2026" );
	}

	break; // Procesar solo el primer log accesible
}

if ( ! $log_checked ) {
	vr_warn( "No se encontró error log accesible desde PHP" );
	vr_info( "En WP Engine revisar via portal o:" );
	vr_info( "  cat ~/logs/php/error.log | tail -100" );
	vr_info( "  O habilitar: define('WP_DEBUG', true); define('WP_DEBUG_LOG', true);" );
}


// ════════════════════════════════════════════════════════════════
// BLOQUE 3: Zoho Campaigns v2.1.7 — Estado OAuth 2.0
// ════════════════════════════════════════════════════════════════

vr_head( 'BLOQUE 3: Zoho Campaigns — Estado OAuth 2.0' );

$active_plugins = get_option( 'active_plugins', [] );
$zoho_active    = in_array( 'zoho-campaigns/index.php', $active_plugins, true );
vr_info( "Plugin activo: " . ( $zoho_active ? 'SÍ' : 'NO (inactivo)' ) );

// Token details (clave principal de autenticación)
$token_details = get_option( 'zcwc_token_details' );
if ( $token_details ) {
	$token_arr    = is_array( $token_details ) ? $token_details : (array) maybe_unserialize( $token_details );
	$access_token = $token_arr['access_token'] ?? '';
	$refresh      = $token_arr['refresh_token'] ?? '';

	if ( ! empty( $access_token ) ) {
		vr_ok( "access_token: SET (" . strlen( $access_token ) . " chars)" );
	} else {
		vr_fail( "access_token: VACÍO — OAuth no completado o expirado" );
	}

	if ( ! empty( $refresh ) ) {
		vr_ok( "refresh_token: SET (" . strlen( $refresh ) . " chars)" );
	} else {
		vr_warn( "refresh_token: vacío — no podrá refrescar automáticamente" );
	}

	// Mostrar otros campos sin exponer tokens
	foreach ( $token_arr as $k => $v ) {
		if ( in_array( $k, [ 'access_token', 'refresh_token' ], true ) ) {
			continue;
		}
		vr_info( "  {$k}: " . ( is_string( $v ) ? substr( $v, 0, 80 ) : json_encode( $v ) ) );
	}
} else {
	vr_fail( "zcwc_token_details: NO encontrado — Zoho no está autenticado" );
}

// Estado de conexión y usuario
$user_email  = get_option( 'zcwc_user_email' );
$connect_ts  = get_option( 'zcwc_connect_time' );
$domname     = get_option( 'zcwc_domname' );
$error_flag  = get_option( 'zcwc_error_msg' );

vr_info( "Usuario Zoho  : " . ( $user_email ?: '—' ) );
vr_info( "Dominio Zoho  : " . ( $domname ?: '—' ) );
vr_info( "Conectado el  : " . ( $connect_ts ? date( 'Y-m-d H:i:s', (int) $connect_ts ) : '—' ) );

if ( $error_flag == 1 ) {
	vr_fail( "zcwc_error_msg = 1 — El plugin reporta un error de conexión activo" );
	vr_info( "  Ir a WP Admin → Zoho Campaigns → reconectar OAuth" );
} else {
	vr_ok( "Sin flag de error de conexión" );
}

// Integración configurada
$integration = get_option( 'zcwc_integration' );
if ( $integration ) {
	vr_info( "Integración   : " . ( is_array( $integration ) ? json_encode( $integration ) : $integration ) );
}


// ════════════════════════════════════════════════════════════════
// RESUMEN FINAL
// ════════════════════════════════════════════════════════════════

vr_head( 'RESUMEN' );
vr_info( "Site URL      : " . get_option( 'siteurl' ) );
vr_info( "WP Version    : " . get_bloginfo( 'version' ) );
vr_info( "PHP Version   : " . PHP_VERSION );
vr_info( "DB Version    : " . $wpdb->db_version() );
vr_info( "Plugins activos: " . count( $active_plugins ) );
vr_info( "Tema activo   : " . get_option( 'stylesheet' ) );

WP_CLI::log( '' );
WP_CLI::log( str_repeat( '═', 60 ) );
WP_CLI::log( '  Diagnóstico completo.' );
WP_CLI::log( '  IMPORTANTE: Borrar este archivo: rm verify-env.php' );
WP_CLI::log( str_repeat( '═', 60 ) );
WP_CLI::log( '' );
