<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class CrystallineTouchMenuLayout extends AbstractRokMenuLayout {

    public function stageHeader() {
        global $gantry;
    	$gantry->addInlineScript("var animation = '" . $this->args['touchmenu-animation'] . "';");
	    $gantry->addScript('imenu.js');
    }

    public function renderMenu(&$menu) {
        ob_start();
        ?>
        <ul class="menu theme-touch">
        <?php foreach ($menu->getChildren() as $item) : ?>
        <?php $this->renderItem($item, $menu); ?>
        <?php endforeach; ?>
        </ul>
        <?php
        return ob_get_clean();
    }

    protected function renderItem(RokMenuNode &$item, &$menu) {
        ?>
        <li id="idrops-<?php echo $item->getId();; ?>"
            parent_id="idrops-<?php echo $item->getParent(); ?>" <?php if ($item->hasListItemClasses()) : ?>class="<?php echo $item->getListItemClasses();?>"<?php endif;?> <?php if ($item->getCssId()): ?>id="<?php echo $item->getCssId();?>"<?php endif;?>>
        <?php if (count($item->getChildren()) > 0 && $item->getParent() != 0): ?>
            <small class="menucount"><?php echo count($item->getChildren()); ?></small>
        <?php endif; ?>
            <a<?php if ($item->hasLinkClasses()): ?> class="<?php echo $item->getLinkClasses();?>"<?php endif;?><?php if ($item->hasLink()): ?> href="<?php echo $item->getLink();?>"<?php endif;?><?php if ($item->getTarget()): ?> target="<?php echo $item->getTarget();?>"<?php endif;?><?php if ($item->hasLinkAttribs()): ?> <?php echo $item->getLinkAttribs(); ?><?php endif;?>>
                <span <?php if ($item->hasSpanClasses()): ?>class="<?php echo $item->getSpanClasses();?>"<?php endif; ?>><?php echo $item->getTitle();?></span>
            </a>
        <?php if ($item->hasChildren()): ?>
            <ul>
            <?php
                        // force the parent menu item to appear
            $cls = explode(" ", $item->getListItemClasses());
            $isActive = (in_array('active', $cls));
            if ($item->getParent() != 0) :
                ?>
                    <li class="subnav">
                        <a href="#" parent_id="idrops-<?php echo $item->getParent(); ?>"
                           class="item backmenu"><span>Back</span></a>
                        <a href="#close" class="item closemenu"><span>Close</span></a>
                        <span class="clear"></span>
                    </li>
                <?php endif; ?>
                <li class="root-sub<?php echo ($isActive) ? ' active' : ''; ?>">
                <?php if (count($item->getChildren()) > 0 && (!$item->getParent() && $item->getParent() != 0)): ?>
                    <small class="menucount"><?php echo count($item->getChildren()); ?></small>
                <?php endif; ?>
                    <a <?php if ($item->hasLinkClasses()): ?>class="<?php echo $item->getLinkClasses();?>"<?php endif;?> <?php if ($item->hasLink()): ?>href="<?php echo $item->getLink();?>"<?php endif;?> <?php if ($item->getTarget()): ?>target="<?php echo $item->getTarget();?>"<?php endif;?> <?php if ($item->hasLinkAttribs()): ?> <?php echo $item->getLinkAttribs(); ?><?php endif;?>>
                        <span <?php if ($item->hasSpanClasses()): ?>class="<?php echo $item->getSpanClasses();?>"<?php endif; ?>><?php echo $item->getTitle();?></span>
                    </a>
                <?php foreach ($item->getChildren() as $child) : ?>
                <?php $this->renderItem($child, $menu); ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        </li>
        <?php

    }
}




