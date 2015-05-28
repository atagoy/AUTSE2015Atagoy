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
        $gantry->addBodyClass('menu-type-suckerfish');
        if ($this->args['fusion_effect'] == 'slidefade') $this->args['fusion_effect'] = "slide and fade";
        $gantry->addScript('mootools.js');
        $gantry->addScript('sfhover.js');
        $gantry->addStyle($gantry->templateUrl."/css/suckerfish.css");
    }

    public function renderMenu(&$menu) {
        ob_start();
        ?>
        <ul class="menutop level1 theme-suckerfish">
	        <?php foreach ($menu->getChildren() as $item) : ?>
		        <?php echo $this->renderItem($item, $menu); ?>
	        <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }

    protected function renderItem(RokMenuNode &$item, &$menu) {
        global $gantry;
        
        if ($item->getAttribute('subtext'))
            $item->addLinkClass('subtext');

        ?>
        <li id="<?php echo $item->getCssId();?>">
            <a<?php if($item->hasLinkClasses()):?> class="<?php echo $item->getLinkClasses();?>"<?php endif;?> <?php if($item->hasLink()):?>href="<?php echo $item->getLink();?>"<?php endif;?> <?php if($item->getTarget()):?> target="<?php echo $item->getTarget();?>"<?php endif;?><?php if($item->hasLinkAttribs()):?> <?php echo $item->getLinkAttribs();?><?php endif;?>>
                <span>
                <?php echo $item->getTitle();?>
                </span>
            </a>
            <?php if ($item->hasChildren()): ?>
                <ul class="level<?php echo intval($item->getLevel())+2; ?>">
                    <?php foreach ($item->getChildren() as $child) : ?>
                        <?php echo $this->renderItem($child, $menu); ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
        <?php
    }
}




