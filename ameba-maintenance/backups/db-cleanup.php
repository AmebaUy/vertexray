<?php
/**
 * BD Cleanup & Comments Hardening - Vertex Ray / Mayo 2026
 *
 * Ejecutar en LOCAL:
 *   wp eval-file ameba-maintenance/backups/db-cleanup.php
 *
 * Ejecutar en STAGING via SSH:
 *   ssh vertexraystg@vertexraystg.ssh.wpengine.net
 *   wp eval-file /sites/vertexraystg/ameba-maintenance/backups/db-cleanup.php --path=/sites/vertexraystg
 *
 * Ejecutar en PRODUCCIÓN via SSH (requiere confirmación explícita del dev):
 *   ssh vertexray@vertexray.ssh.wpengine.net
 *   wp eval-file /sites/vertexray/ameba-maintenance/backups/db-cleanup.php --path=/sites/vertexray
 *
 * NUNCA hacer push de DB local/stg a prod. Solo ejecutar este script por separado en cada entorno.
 */

global $wpdb;

echo "=== BD Cleanup & Comments Hardening ===\n";
echo "Sitio: " . get_bloginfo('url') . "\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// ── 1. SPAM Y METADATOS HUÉRFANOS ──────────────────────────────────────────

$spam_deleted = $wpdb->query(
    "DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'"
);
echo "Spam eliminado: {$spam_deleted} comentarios\n";

$meta_deleted = $wpdb->query(
    "DELETE FROM {$wpdb->commentmeta} WHERE comment_id NOT IN (SELECT comment_id FROM {$wpdb->comments})"
);
echo "Commentmeta huérfano: {$meta_deleted} filas\n";

$postmeta_deleted = $wpdb->query(
    "DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})"
);
echo "Postmeta huérfano: {$postmeta_deleted} filas\n";

// ── 2. CORREGIR RAÍZ: DESHABILITAR COMENTARIOS EN DISCUSSION SETTINGS ──────
// Vertex Ray es sitio de servicios/portfolio — los comentarios nunca debieron
// estar habilitados. Esto persiste la corrección en la BD de cada entorno.

update_option( 'default_comment_status', 'closed' );   // "Allow people to post comments on new articles" → OFF
update_option( 'default_ping_status',    'closed' );   // "Allow link notifications from other blogs" → OFF
update_option( 'require_name_email',     1 );           // Requiere nombre+email (defensa extra)
update_option( 'comment_registration',   1 );           // Solo usuarios registrados (defensa extra)
update_option( 'close_comments_for_old_posts', 1 );    // Cerrar comentarios en posts viejos
update_option( 'close_comments_days_old', 0 );         // 0 días = cierra de inmediato
update_option( 'thread_comments',        0 );           // Deshabilitar comentarios anidados
update_option( 'page_comments',          0 );           // Deshabilitar paginación de comentarios

$before = get_option('default_comment_status');
echo "\nDiscussion Settings → default_comment_status ahora: " . get_option('default_comment_status') . "\n";
echo "Discussion Settings → default_ping_status ahora: "    . get_option('default_ping_status') . "\n";

// ── 3. CERRAR COMENTARIOS EN TODOS LOS POSTS/PÁGINAS EXISTENTES ────────────
// Aunque el filtro PHP en functions.php ya los bloquea, esto cierra a nivel DB
// para que el estado sea consistente en el admin y sin depender del tema.

$closed = $wpdb->query(
    "UPDATE {$wpdb->posts}
     SET comment_status = 'closed', ping_status = 'closed'
     WHERE post_type IN ('post', 'page', 'ot_portfolio')
       AND post_status NOT IN ('auto-draft', 'inherit')"
);
echo "Posts/páginas con comentarios cerrados: {$closed}\n";

// ── 4. REVISIONES DE POSTS ─────────────────────────────────────────────────

$revisions = $wpdb->get_var(
    "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'revision'"
);
echo "\nRevisiones en BD: {$revisions} (WP_POST_REVISIONS=5 aplicará a futuras ediciones)\n";

// ── 5. OPTIMIZAR TABLAS ────────────────────────────────────────────────────

$tables    = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}%'" );
$optimized = 0;
foreach ( $tables as $table ) {
    $wpdb->query( "OPTIMIZE TABLE `{$table}`" );
    $optimized++;
}
echo "Tablas optimizadas: {$optimized}\n";

echo "\n✅ Completado en: " . get_bloginfo('url') . "\n";
