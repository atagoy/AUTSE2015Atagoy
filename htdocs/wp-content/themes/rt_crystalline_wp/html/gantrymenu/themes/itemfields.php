<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantryMenuItemFieldsDefault {
    public $fields = array(
        'gantrymenu_subtext',
        'gantrymenu_icon',
        'gantrymenu_submenu_cols'
    );

    public function renderFields($item_id, $item, $depth, $args) {
        global $gantry;
        ob_start();
        ?>
        <p class="field-gantrymenu_subtext description description-thin">
            <label for="edit-menu-item-gantrymenu_subtext-<?php echo $item_id; ?>">
            <?php _e('Subtext'); ?><br/>
                <input type="text" id="edit-menu-item-gantrymenu_subtext-<?php echo $item_id; ?>"
                       class="widefat code edit-menu-item-gantrymenu_subtext"
                       name="menu-item-gantrymenu_subtext[<?php echo $item_id; ?>]"
                       value="<?php echo esc_attr($item->gantrymenu_subtext); ?>"/>
            </label>
        </p>
        <p class="field-gantrymenu_icon description description-thin">
            <label for="edit-menu-item-gantrymenu_icon-<?php echo $item_id; ?>">
            <?php _e('Icon'); ?><br/>
                <select id="edit-menu-item-gantrymenu_icon-<?php echo $item_id; ?>"
                        class="widefat edit-menu-item-gantrymenu_icon"
                        name="menu-item-gantrymenu_icon[<?php echo $item_id; ?>]">
                    <option value=""<?php if (esc_attr($item->gantrymenu_icon) == ''): ?>
                            selected="selected"<?php endif;?>></option>
                <?php
                $icon_path = $gantry->templatePath . '/images/icons';
                $icons = array();
                if (file_exists($icon_path) && is_dir($icon_path)) {
                    $d = dir($icon_path);
                    while (false !== ($entry = $d->read())) {
                        if (!preg_match('/^\./', $entry) && preg_match('/\.png$/', $entry)) {
                            $icon_name = basename($entry, '.png');
                            $icons[$entry] = $icon_name;
                        }
                    }
                }?>
                <?php foreach ($icons as $iconurl => $iconname): ?>
                    <option value="<?php echo $iconurl;?>"<?php if (esc_attr($item->gantrymenu_icon) == $iconurl): ?>
                            selected="selected"<?php endif;?>><?php echo $iconname;?></option>
                <?php endforeach;?>
                </select>
            </label>
        </p>
        <p class="field-gantrymenu_submenu_cols description description-thin">
            <label for="edit-menu-item-gantrymenu_submenu_cols-<?php echo $item_id; ?>">
            <?php _e('Number of Columns in Submenu'); ?><br/>
                <select id="edit-menu-item-gantrymenu_submenu_cols-<?php echo $item_id; ?>"
                        class="widefat edit-menu-item-gantrymenu_submenu_cols"
                        name="menu-item-gantrymenu_submenu_cols[<?php echo $item_id; ?>]">
                    <option value="1"<?php if (esc_attr($item->gantrymenu_submenu_cols) == 1): ?> selected="selected"<?php endif;?>>1</option>
                    <option value="2"<?php if (esc_attr($item->gantrymenu_submenu_cols) == 2): ?> selected="selected"<?php endif;?>>2</option>
                </select>
            </label>
        </p>
        <?php
        echo ob_get_clean();
    }
}
