<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class CrystallineSplitMenuLayout extends AbstractRokMenuLayout {

    public function stageHeader() {
        global $gantry;
        if ($gantry->browser->name == "ie" && $gantry->browser->shortversion == "6"){
            $gantry->addScript('sfhover.js');
        }
         
        $gantry->addBodyClass('menu-type-splitmenu');
        $gantry->addStyle($gantry->templateUrl."/css/splitmenu.css");
        
        if ($this->args['fusion_pill'] == '1') :
        
        	$gantry->addScript('gantry-pillanim.js');
     		
        	if($this->args['menu_suffix'] == 'top') :
		    	$gantry->addInlineScript("window.addEvent('domready', function() {new GantryPill('ul.menutop.theme-splitmenu', {duration: ".$this->args['fusion_pill_duration'].", transition: Fx.Transitions.".$this->args['fusion_pill_animation'].", color: '".$gantry->get('header-text')."'})});");
		    	$css = '.fusion-pill-l {height: 60px;margin:0;top:-6px;position:absolute;left:0;}'."\n";
        		$css .= '.fusion-pill-r {height: 60px;}';
			endif;
			
			$gantry->addInlineStyle($css);
			
		endif;

    }

    public function renderMenu(&$menu) {
        ob_start();
        if($menu->getChildren()) :
	  		if($this->args['fusion_pill'] != '1') : ?>
	        <div class="no-pill">
	        <?php endif; ?>
	        <ul class="menu<?php echo $this->args['menu_suffix']; ?> level1 theme-splitmenu">
		        <?php foreach ($menu->getChildren() as $item) : ?>
			        <?php echo $this->renderItem($item, $menu); ?>
		        <?php endforeach; ?>
	        </ul>
        	<?php if($this->args['fusion_pill'] != '1') : ?>
			</div>
	        <?php endif;
        endif;
        return ob_get_clean();
    }

    protected function renderItem(RokMenuNode &$item, &$menu) {
        global $gantry;

        if ($item->getAttribute('subtext'))
            $item->addLinkClass('subtext');

        ?>
        <li <?php if($item->hasListItemClasses()) : ?>class="<?php echo $item->getListItemClasses();?>"<?php endif;?>>
            <a <?php if ($item->hasLinkClasses()): ?>class="<?php echo $item->getLinkClasses();?>" <?php endif;?><?php if ($item->hasLink()): ?>href="<?php echo $item->getLink();?>" <?php endif;?><?php if ($item->getTarget()): ?>target="<?php echo $item->getTarget();?>" <?php endif;?><?php if ($item->hasLinkAttribs()): ?> <?php echo $item->getLinkAttribs(); ?><?php endif;?>>
            <span>
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
	        <?php if ($item->hasChildren()): ?>
            <ul class="level<?php echo intval($item->getLevel()) + 2; ?>">
	            <?php foreach ($item->getChildren() as $child) : ?>
		            <?php echo $this->renderItem($child, $menu); ?>
	            <?php endforeach; ?>
            </ul>
	        <?php endif; ?>
        </li>
        <?php
    }
}




