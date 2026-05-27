<?php
/**
 * Plugin Name: Ameba Deploy Uploads Fallback
 * Description: Redirect missing local uploads to production in local environment only.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function ameba_deploy_uploads_fallback_to_bool( $value, $default = false ) {
  if ( is_bool( $value ) ) {
    return $value;
  }

  if ( ! is_string( $value ) ) {
    return $default;
  }

  $normalized = strtolower( trim( $value ) );
  if ( in_array( $normalized, array( '1', 'true', 'yes', 'on' ), true ) ) {
    return true;
  }
  if ( in_array( $normalized, array( '0', 'false', 'no', 'off' ), true ) ) {
    return false;
  }

  return $default;
}

function ameba_deploy_uploads_fallback_parse_env_file( $file_path ) {
  $values = array();

  if ( ! is_readable( $file_path ) ) {
    return $values;
  }

  $lines = file( $file_path, FILE_IGNORE_NEW_LINES );
  if ( ! is_array( $lines ) ) {
    return $values;
  }

  foreach ( $lines as $line ) {
    $line = trim( (string) $line );
    if ( '' === $line || '#' === substr( $line, 0, 1 ) ) {
      continue;
    }

    $separator_pos = strpos( $line, '=' );
    if ( false === $separator_pos ) {
      continue;
    }

    $key = trim( substr( $line, 0, $separator_pos ) );
    $value = trim( substr( $line, $separator_pos + 1 ) );
    $value = trim( $value, "\"'" );

    if ( '' !== $key ) {
      $values[ $key ] = $value;
    }
  }

  return $values;
}

function ameba_deploy_uploads_fallback_get_config_values() {
  static $cache = null;

  if ( null !== $cache ) {
    return $cache;
  }

  $cache = array();
  $config_dir = ABSPATH . 'ameba-deploy-local-config';
  $files = array(
    $config_dir . '/.ameba-deploy.prod.env',
    $config_dir . '/.ameba-deploy.local.env',
    $config_dir . '/.ameba-deploy.user.env',
  );

  foreach ( $files as $file_path ) {
    $cache = array_merge( $cache, ameba_deploy_uploads_fallback_parse_env_file( $file_path ) );
  }

  return $cache;
}

function ameba_deploy_uploads_fallback_get_settings() {
  static $settings = null;

  if ( null !== $settings ) {
    return $settings;
  }

  $config = ameba_deploy_uploads_fallback_get_config_values();

  $resolved_prod_url = '';
  if ( ! empty( $config['PROD_URL'] ) && filter_var( $config['PROD_URL'], FILTER_VALIDATE_URL ) ) {
    $resolved_prod_url = $config['PROD_URL'];
  } elseif ( ! empty( $config['REMOTE_URL'] ) && filter_var( $config['REMOTE_URL'], FILTER_VALIDATE_URL ) ) {
    $resolved_prod_url = $config['REMOTE_URL'];
  } else {
    $resolved_prod_url = "https://www.vertexray.com";
  }

  $resolved_local_url = '';
  if ( ! empty( $config['LOCAL_URL'] ) && filter_var( $config['LOCAL_URL'], FILTER_VALIDATE_URL ) ) {
    $resolved_local_url = $config['LOCAL_URL'];
  } elseif ( filter_var( home_url(), FILTER_VALIDATE_URL ) ) {
    $resolved_local_url = home_url();
  }

  $local_hosts = array();
  if ( ! empty( $config['AMEBA_UPLOADS_FALLBACK_LOCAL_HOSTS'] ) ) {
    $raw_hosts = explode( ',', $config['AMEBA_UPLOADS_FALLBACK_LOCAL_HOSTS'] );
    foreach ( $raw_hosts as $raw_host ) {
      $host = strtolower( trim( $raw_host ) );
      if ( '' !== $host ) {
        $local_hosts[] = $host;
      }
    }
  }

  $configured_local_host = wp_parse_url( $resolved_local_url, PHP_URL_HOST );
  if ( is_string( $configured_local_host ) && '' !== $configured_local_host ) {
    $local_hosts[] = strtolower( $configured_local_host );
  }

  $legacy_host = "vertexray.ldev";
  if ( '' !== $legacy_host ) {
    $local_hosts[] = strtolower( $legacy_host );
  }

  $local_hosts[] = 'localhost';
  $local_hosts[] = '127.0.0.1';
  $local_hosts = array_values( array_unique( $local_hosts ) );

  $status_code = 302;
  if ( ! empty( $config['AMEBA_UPLOADS_FALLBACK_STATUS_CODE'] ) ) {
    $candidate = absint( $config['AMEBA_UPLOADS_FALLBACK_STATUS_CODE'] );
    if ( $candidate >= 301 && $candidate <= 399 ) {
      $status_code = $candidate;
    }
  }

  $enabled = true;
  if ( array_key_exists( 'AMEBA_UPLOADS_FALLBACK_ENABLED', $config ) ) {
    $enabled = ameba_deploy_uploads_fallback_to_bool( $config['AMEBA_UPLOADS_FALLBACK_ENABLED'], true );
  }

  $settings = array(
    'enabled' => $enabled,
    'prod_base_url' => untrailingslashit( (string) $resolved_prod_url ),
    'local_hosts' => $local_hosts,
    'status_code' => $status_code,
  );

  return $settings;
}

function ameba_deploy_uploads_fallback_redirect_missing_uploads() {
  $settings = ameba_deploy_uploads_fallback_get_settings();

  if ( empty( $settings['enabled'] ) ) {
    return;
  }

  if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
    return;
  }

  if ( defined( 'WP_ENVIRONMENT_TYPE' ) && 'production' === WP_ENVIRONMENT_TYPE ) {
    return;
  }

  $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
  $request_path = wp_parse_url( $request_uri, PHP_URL_PATH );

  if ( ! is_string( $request_path ) || 0 !== strpos( $request_path, '/wp-content/uploads/' ) ) {
    return;
  }

  $prod_base_url = isset( $settings['prod_base_url'] ) ? (string) $settings['prod_base_url'] : '';
  if ( '' === $prod_base_url ) {
    return;
  }

  $allowed_local_hosts = isset( $settings['local_hosts'] ) && is_array( $settings['local_hosts'] ) ? $settings['local_hosts'] : array();
  $current_host = wp_parse_url( home_url(), PHP_URL_HOST );
  $prod_host = wp_parse_url( $prod_base_url, PHP_URL_HOST );

  // Safety: if copied to production by mistake, never redirect.
  if ( is_string( $current_host ) && is_string( $prod_host ) && strtolower( $current_host ) === strtolower( $prod_host ) ) {
    return;
  }

  // Safety: enforce local-only behavior by hostname.
  if ( ! is_string( $current_host ) || ! in_array( strtolower( $current_host ), $allowed_local_hosts, true ) ) {
    return;
  }

  $relative_path = ltrim( substr( $request_path, strlen( '/wp-content/uploads/' ) ), '/' );
  if ( '' === $relative_path ) {
    return;
  }

  $local_file = wp_normalize_path( WP_CONTENT_DIR . '/uploads/' . $relative_path );
  if ( file_exists( $local_file ) || is_dir( $local_file ) ) {
    return;
  }

  $encoded_segments = array_map( 'rawurlencode', explode( '/', $relative_path ) );
  $target_url = rtrim( $prod_base_url, '/' ) . '/wp-content/uploads/' . implode( '/', $encoded_segments );

  $request_query = wp_parse_url( $request_uri, PHP_URL_QUERY );
  if ( is_string( $request_query ) && '' !== $request_query ) {
    $target_url .= '?' . $request_query;
  }

  $status_code = isset( $settings['status_code'] ) ? (int) $settings['status_code'] : 302;
  wp_redirect( $target_url, $status_code, 'Ameba Deploy Uploads Fallback' );
  exit;
}
add_action( 'template_redirect', 'ameba_deploy_uploads_fallback_redirect_missing_uploads', 0 );
