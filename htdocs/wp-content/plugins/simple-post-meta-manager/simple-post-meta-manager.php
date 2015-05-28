<?php
/*
  Plugin Name: Simple Post Meta Manager
  Plugin URI: http://www.sandorkovacs.ro/blog/simple-post-meta-manager-wordpress-plugin/
  Description: Replace meta values or rename meta keys. It is usefull when custom fields and its values were messed up. 
  Author: Sandor Kovacs
  Version: 1.0.9
  Author URI: http://sandorkovacs.ro/en/
 */

// Create submenu for post meta manager 

add_action('admin_menu', 'register_simple_pmm_submenu_page');


// Register ajax request 

add_action('admin_footer', 'simple_pmm_replace_meta_values');
add_action('wp_ajax_simple_pmm_replace_meta_values', 'simple_pmm_replace_meta_values_callback');

function register_simple_pmm_submenu_page() {
    add_submenu_page(
            'edit.php', __('Meta Manager'), __('Meta Manager'), 'edit_posts', 'simple-simple-post-meta-manager', 'simple_pmm_callback');
}

/** Create tabbed menu * */
function simple_pmm_admin_tabs($current = 'replace_meta_values') {
    $tabs = array(
        'replace_meta_values' => 'Replace meta values',
        'rename_meta_keys' => 'Rename meta keys',
    );

    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=simple-simple-post-meta-manager&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

/** POST META MANAGER PLUGIN PAGE * */
function simple_pmm_callback() {

    $pmm_tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : '';

    if (isset($_GET['tab']))
        simple_pmm_admin_tabs($_GET['tab']);
    else
        simple_pmm_admin_tabs('replace_meta_values');

    // Process form replace meta values
    if (isset($_POST['submit-replace'])) {
        $meta_key = $_POST['simple_pmm_meta_key'];
        $old_meta_value = $_POST['simple_pmm_meta_value'];
        $new_meta_value = trim($_POST['pmm_replace_value']);

        // Save options in the DB
        update_option('simple_pmm_replace_meta_key', $meta_key);
        update_option('simple_pmm_replace_old_meta_value', $old_meta_value);
        update_option('simple_pmm_replace_new_meta_value', $new_meta_value);

        // Check if has values
        if (!empty($new_meta_value) || !empty($old_meta_value) || !empty($meta_key)) {
            // update in database 
            pmm_replace_all_values_by_key($meta_key, $old_meta_value, $new_meta_value);
            printf( __( '<div id="message" class="updated fade">
                The values were updated for meta key <strong>%1$s</strong>
                from <strong>%2$s</strong> to <strong>%3$s</strong></div> '),
                    $meta_key, $old_meta_value, $new_meta_value );

            
        }
    }

    // Process form rename meta keys 
    if (isset($_POST['submit-rename'])) {
        $meta_key = $_POST['simple_pmm_meta_key'];
        $new_meta_key_name = trim($_POST['pmm_new_meta_key_name']);


        // Validation
        if (!empty($meta_key) || !empty($new_meta_key_name)) {
            update_option('simple_pmm_replace_meta_key', $new_meta_key_name);

            pmm_rename_meta_keys($meta_key, $new_meta_key_name);
                        printf( __( '<div id="message" class="updated fade">
                The meta key was has been renamed from <strong>%1$s</strong>
                to <strong>%2$s</strong> </div> '),
                    $meta_key,$new_meta_key_name );
            
        }
    }
    ?>

    <div class="wrap" id='simple-sf'>

    <?php if (empty($pmm_tab) || $pmm_tab == 'replace_meta_values'): ?>

            <form action="" method="post" name="pmm_replace_meta_values">

                <table class="form-table">

                    <tr>
                        <th scope="row"><?php _e('Select meta key') ?></th>
                        <td>
        <?php echo pmm_all_meta_keys_dropdown() ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('Select meta value') ?></th>
                        <td>
                            <?php echo pmm_all_meta_values_dropdown(get_option('simple_pmm_replace_meta_key', '_edit_last')) ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('New value for the selected') ?></th>
                        <td>
                            <input type="text" 
                                   name="pmm_replace_value"
                                   id="pmm-replace-value"
                                   placeholder="Enter new value"
                                   class="regular-text"
                                   />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>      
                            <input type='submit' 
                                   onclick='if (!window.confirm("Are you really sure?")) return false'
                                   name='submit-replace' value='<?php _e('Replace') ?>' />
                        </td>
                    </tr>

                </table>

            </form>




    <?php else: ?>
            <!--SECOND TAB-->

            <form action="" method="post" name="pmm_rename_meta_keys">

                <table class="form-table">

                    <tr>
                        <th scope="row"><?php _e('Select meta key') ?></th>
                        <td>
        <?php echo pmm_all_meta_keys_dropdown() ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('New name') ?></th>
                        <td>
                            <input type="text" 
                                   name="pmm_new_meta_key_name"
                                   id="pmm-new-meta-key-name"
                                   placeholder="Enter new name"
                                   class="regular-text"
                                   />
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td>      
                            <input type='submit' 
                                   onclick='if (!window.confirm("Are you really sure?")) return false'
                                   name='submit-rename' value='<?php _e('Rename') ?>' />
                        </td>
                    </tr>

                </table>

            </form>



    <?php endif; ?>

            
        <div class="updated" style="margin-top:60px;">
            <strong><?php _e('Attention!!!') ?></strong>
        <ol>
            <li><?php _e('Always create database backup before you rename or replace meta keys and values') ?> </li>
            <li><?php _e('The actions are not reversible') ?></li>
            <li><?php _e('The author of this plugin is not responsible for data loss.') ?></li>
        </ol>
    </div>


    </div>

    <?php
}

/** Get meta keys * */
function pmm_all_meta_keys_dropdown() {
    global $wpdb;

    $selected = get_option('simple_pmm_replace_meta_key', '');

    $sql = "SELECT DISTINCT(meta_key) FROM $wpdb->postmeta";

    $return_str = "
        <select name='simple_pmm_meta_key' id='simple-pmm-meta-key'>
";
    foreach ($wpdb->get_results($sql) as $v) {
        $return_str .= " <option value='" . $v->meta_key . "' " . (($v->meta_key == $selected) ? " selected='selected' " : "") . ">" . $v->meta_key . "</option>";
    }
    $return_str .= '</select>';
    return $return_str;
}

/** Get meta values * */
function pmm_all_meta_values_dropdown($meta_key = NULL) {
    global $wpdb;
    $sql = "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta";

    if ($meta_key != NULL)
        $sql .= " WHERE meta_key = '$meta_key'";
    $sql .= " ORDER BY meta_value ASC";

    $return_str = "
        <select name='simple_pmm_meta_value' id='simple-pmm-meta-value' >
";
    foreach ($wpdb->get_results($sql) as $v) {

        $return_str .= " <option value='" . $v->meta_value . "' >" . substr($v->meta_value, 0, 180) . "</option>";
    }
    $return_str .= '</select>';
    return $return_str;
}

// Ajax request for replacing meta values
function simple_pmm_replace_meta_values() {
    ?>
    <script type="text/javascript" >
        jQuery(document).ready(function($) {

            $('#simple-pmm-meta-key').change(function(){
                var data = {
                    action: 'simple_pmm_replace_meta_values',
                    meta_key: $('#simple-pmm-meta-key option:selected').val()
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post(ajaxurl, data, function(response) {
                    $('#simple-pmm-meta-value').parent().html(response);
                    //                    alert('Got this from the server: ' + response);
                                            
                });
            })
        });
    </script>
    <?php
}

function simple_pmm_replace_meta_values_callback() {
    global $wpdb; // this is how you get access to the database

    $return = pmm_all_meta_values_dropdown($_POST['meta_key']);
    echo $return;
    die(); // this is required to return a proper result
}

// Replace meta values in the database 

function pmm_replace_all_values_by_key($meta_key = NULL, $old_meta_value = NULL, $new_meta_value = NULL) {
    if ($meta_key == NULL || $old_meta_value == NULL || $new_meta_value == NULL)
        return;

    global $wpdb;
    $sql = "UPDATE $wpdb->postmeta 
            SET meta_value = '$new_meta_value'
            WHERE meta_key = '$meta_key'
                  AND meta_value = '$old_meta_value'";

    return $wpdb->query($sql);
}

// Rename meta keys 

function pmm_rename_meta_keys($old_meta_key = NULL, $new_meta_key = NULL) {
    if ($old_meta_key == NULL || $new_meta_key == NULL)
        return;

    global $wpdb;
    $sql = "UPDATE $wpdb->postmeta 
            SET meta_key = '$new_meta_key'
            WHERE meta_key = '$old_meta_key'";
    return $wpdb->query($sql);
}

