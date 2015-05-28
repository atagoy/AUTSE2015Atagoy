<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class CrystallineFusionMenuLayout extends AbstractRokMenuLayout {

    public function stageHeader() {
        global $gantry;
        if ($this->args['fusion_effect'] == 'slidefade') $this->args['fusion_effect'] = "slide and fade";
         if ($gantry->browser->name == "ie" && $gantry->browser->shortversion == "6"){
            $this->args['fusion_enable_js'] = 0;
            $gantry->addScript('sfhover.js');
         }

        $gantry->addStyle($gantry->templateUrl."/css/fusionmenu.css");

        if ($gantry->browser->name == "ie" && $this->args['fusion_effect'] == 'slide and fade') $this->args['fusion_effect'] = "slide";
        
        if ($this->args['fusion_enable_js']) {
            $gantry->addScript("fusion.js");

            ob_start();
            ?>
            new Fusion('ul.menutop', {
                pill: <?php echo $this->args['fusion_pill']; ?>,
                effect: '<?php echo $this->args['fusion_effect']; ?>',
                opacity:  <?php echo $this->args['fusion_opacity']; ?>,
                hideDelay:  <?php echo $this->args['fusion_hidedelay']; ?>,
                centered:  <?php echo $this->args['fusion_centeredOffset']; ?>,
                tweakInitial: {'x': <?php echo $this->args['fusion_tweakInitial_x']; ?>, 'y': <?php echo $this->args['fusion_tweakInitial_y']; ?>},
                tweakSubsequent: {'x':  <?php echo $this->args['fusion_tweakSubsequent_x']; ?>, 'y':  <?php echo $this->args['fusion_tweakSubsequent_y']; ?>},
                menuFx: {duration:  <?php echo $this->args['fusion_menu_duration']; ?>, transition: Fx.Transitions.<?php echo $this->args['fusion_menu_animation']; ?>},
                pillFx: {duration:  <?php echo $this->args['fusion_pill_duration']; ?>, transition: Fx.Transitions.<?php echo $this->args['fusion_pill_animation']; ?>}
            });
            <?php
            $inline = ob_get_clean();
            $gantry->addDomReadyScript($inline);
        }
        if ($this->args['fusion_load_css']) {
            $gantry->addStyle($gantry->templateUrl."/html/gantrymenu/themes/gantry_fusion/css/fusion.css");
        }

    }

    public function renderMenu(&$menu) {
    	global $gantry;
        ob_start();
        if (!$this->args['fusion_pill']): ?>
		<div class="nopill">
		<?php endif; ?>
        <ul class="header-shadows-<?php echo $gantry->get('header-shadows'); ?> menutop level1 theme-fusion">
	        <?php foreach ($menu->getChildren() as $item) : ?>
		        <?php echo $this->renderItem($item, $menu); ?>
	        <?php endforeach; ?>
        </ul>
        <?php if (!$this->args['fusion_pill']): ?>
		</div>
		<?php endif;
        return ob_get_clean();
    }

    protected function renderItem(RokMenuNode &$item, &$menu) {
        global $gantry;
        //get custom image
        if ($item->getAttribute('icon'))
            $item->addLinkClass('image');
        else
            $item->addLinkClass('bullet');
        
        if ($item->getAttribute('subtext'))
            $item->addLinkClass('subtext');

        ?>
        <li <?php if($item->hasListItemClasses()) : ?>class="<?php echo $item->getListItemClasses();?>"<?php endif;?> <?php if ($item->getCssId()): ?>id="<?php echo $item->getCssId();?>"<?php endif;?>>
            <a <?php if ($item->hasLinkClasses()): ?>class="<?php echo $item->getLinkClasses();?>" <?php endif;?><?php if ($item->hasLink()): ?>href="<?php echo $item->getLink();?>" <?php endif;?><?php if ($item->getTarget()): ?>target="<?php echo $item->getTarget();?>" <?php endif;?><?php if ($item->hasLinkAttribs()): ?> <?php echo $item->getLinkAttribs(); ?><?php endif;?>>
            <span>
             <?php
             $icon = $item->getAttribute('icon');
             ?>
             <?php if (!empty($icon)) : ?>
                 <img src="<?php echo $gantry->templateUrl.'/images/icons/'.$icon; ?>"
                      alt=""/>
             <?php endif; ?>
             <?php echo $item->getTitle();?>
             <?php
             $subtext = $item->getAttribute('subtext');
             if (is_array($subtext)) :
                 $subtext = implode("\n", $subtext);
             endif;
             ?>
             <?php if (!empty($subtext)): ?><em><?php echo stripslashes($subtext); ?></em><?php endif;?>
            </span>
            </a>
	        <?php $columns = $item->getAttribute('submenu_cols'); ?>
	        <?php if ($item->hasChildren()): ?>
        	<div class="fusion-submenu-wrapper level<?php echo intval($item->getLevel())+2; ?><?php if ($columns > 1) echo ' columns'.$columns; ?>">
				<div class="drop-top"></div>
	            <ul class="level<?php echo intval($item->getLevel()) + 2; ?><?php if ($columns > 1) echo ' columns'.$columns; ?>">
		            <?php foreach ($item->getChildren() as $child) : ?>
			            <?php echo $this->renderItem($child, $menu); ?>
		            <?php endforeach; ?>
	            </ul>
	        </div>
	        <?php endif; ?>
        </li>
        <?php
    }
}




