<?php
/*
* Plugin Name: SQL Export
* Plugin URI: http://vmassuchetto.wordpress.com/sqldump-wordpress-plugin
* Description: Export a bzipped mysqldump output from WordPress admin interface.
* Version: 0.02
* Author: Vinicius Massuchetto
* Author URI: http://vmassuchetto.wordpress.com
*/

class SQL_Dump {

    var $plugin_basename;
    var $plugin_basedir;
    var $plugin_dir_path;
    var $plugin_dir_url;

    function SQL_Dump() {

        $this->plugin_basename = plugin_basename( __FILE__ );
        $this->plugin_basedir = dirname( $this->plugin_basename );
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_url = plugin_dir_url( __FILE__ );

        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

    }

    /* Admin */

    function admin_menu() {
        add_submenu_page( __( 'tools.php', 'sql_export' ), __( 'Export SQL File', 'sql_export' ),
             __( 'Export SQL File', 'sql_export' ), 'edit_posts', 'sql_export',
             array( $this, 'admin_page' ) );
    }

    function admin_init() {

        if ( isset( $_GET['sql_export_export'] ) )
            $this->export_file();

    }

    function command_exists( $cmd ) {
        $r = shell_exec( "which $cmd" );
        return empty( $r ) ? false : true;
    }

    function count_total_rows() {
        global $wpdb;
        $rows = 0;
        foreach ( $wpdb->tables as $table ) {
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}{$table}";
            if ( $r = $wpdb->get_var( $sql ) )
                $rows += $r;
        }
        return $rows;
    }

    function admin_page() {
        $max_execution_time = ini_get( 'max_execution_time' );
        $memory_limit = ini_get( 'memory_limit' );
        $rows = $this->count_total_rows();
        ?>
        <div class="wrap wp-diagram">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e( 'Export SQL File', 'sql_export' ); ?></h2>

            <?php if ( !$this->command_exists( 'bzip2' ) ) : ?>
                <div class="error"><p><?php _e( 'Warning: You don\'t have <code>bzip2</code> installed in your server. Exporting the file can take too long.', 'sql_export' ); ?></p></div>
            <?php endif; ?>

            <?php if ( !$this->command_exists( 'mysqldump' ) ) : ?>
                <div class="error"><p><?php _e( 'Warning: You don\'t have <code>mysqldump</code> installed in your server. It\'s not possible to export a MySQL file.', 'sql_export' ); ?></p></div>
            <?php endif; ?>

            <?php if ( $this->command_exists( 'mysqldump' ) ) : ?>

                <p>
                    <?php printf( __( 'You have %s rows to be exported.', 'sql_export' ), number_format( $rows ) ); ?>
                    <?php if ( $rows < 10000 ) : ?>
                        <?php _e( 'This is OK.', 'sql_export' ); ?>
                    <?php elseif( $rows < 100000 ) : ?>
                        <?php _e( 'That\'s a considerable database.', 'sql_export' ); ?>
                    <?php elseif( $rows < 500000 ) : ?>
                        <?php _e( 'This is big somewhat.', 'sql_export' ); ?>
                    <?php elseif( $rows < 1000000 ) : ?>
                        <?php _e( 'This is a huge database.', 'sql_export' ); ?>
                    <?php elseif( $rows < 2000000 ) : ?>
                        <?php _e( 'That\'s a really big database. Please consider exporting it in some other way.', 'sql_export' ); ?>
                    <?php endif; ?>
                </p>

                <p>
                    <?php printf( __( 'Your server execution time is %d seconds.', 'sql_export' ), $max_execution_time ); ?>
                    <?php if ( $max_execution_time < 30 ) : ?>
                        <?php _e( 'You may exceed that exporting large databases (~1000 posts).', 'sql_export' ); ?>
                    <?php elseif ( $max_execution_time < 60 ) : ?>
                        <?php _e( 'It should be fine for intermediate databases (~2000 posts).', 'sql_export' ); ?>
                    <?php else : ?>
                        <?php _e( 'It\'s OK for many databases, but you can still exceed that depending of how many posts you have.', 'sql_export' ); ?>
                    <?php endif; ?>
                </p>

                <p>
                    <?php printf( __( 'Your memory limit is %dMB.', 'sql_export' ), $memory_limit ); ?>
                    <?php if ( $memory_limit < 512 ) : ?>
                        <?php _e( 'You can exaust your server exporting large databases (~1000 posts).', 'sql_export' ); ?>
                    <?php elseif ( $memory_limit < 1024 ) : ?>
                        <?php _e( 'It should be fine for intermediate databases (~2000 posts).', 'sql_export' ); ?>
                    <?php else : ?>
                        <?php _e( 'It\'s ok for many databases, but you can still have problems depending of how many posts you have.', 'sql_export' ); ?>
                    <?php endif; ?>
                </p>

                <p><?php _e( 'The plugin will try to increase the values above, but that depends of the global limits in your <code>php.ini</code>. If you get to see a blank page with nothing to download, then you probably reached your server limits.', 'sql_export' ); ?></p>

                <p><?php _e( 'Exporting may take a while depending on your database size and server power. Click and wait.', 'sql_export' ); ?></p>

                <p><a class="button" href="?sql_export_export"><?php _e( 'Export SQL File', 'sql_export' ); ?></a></p>

            <?php endif; ?>
        </div>
        <?php
    }

    function export_file() {

        if ( !current_user_can( 'edit_posts' ) )
            wp_die();

        if ( $this->command_exists( 'bzip2' ) ) {
            $compression_pipe = '| bzip2 --stdout';
            $ext = '.sql.bz2';
        } else {
            $compression_pipe = '';
            $ext = '.sql';
        }

        @ini_set( 'memory_limit', '2048M' );
        @ini_set( 'max_execution_time', 0 );

        $tmpname = '/tmp/' . sha1( uniqid() ) . $ext;
        $filename = sanitize_title( get_bloginfo( 'name' ) ) . '-' . date( 'YmdHis' ) . $ext;
        $cmd = sprintf( "mysqldump -h'%s' -u'%s' -p'%s' %s --single-transaction %s > %s",
            DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $compression_pipe, $tmpname );

        exec( $cmd );
        header( 'Content-Type: application/bzip' );
        header( 'Content-Length: ' . filesize( $tmpname ) );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        readfile( $tmpname );
        unlink( $tmpname );

        exit();
    }

}

function sql_export_init() {
    $sql_export = new SQL_Dump();
    do_action( 'sql_export_init' );
}
add_action( 'plugins_loaded', 'sql_export_init' );

?>
