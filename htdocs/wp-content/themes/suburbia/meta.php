<div class="meta">
    <?php
        $values = get_post_custom_values("notes");
        if (isset($values[0])) : ?>
            <h3>Article notes</h3>
            <p><?php echo $values[0]; ?></p>
    <?php endif; ?>
    
    <h3>Информация</h3>
    <p>Статья написана <?php the_time('d M Y'); ?> в категории <?php the_category(', '); ?>.</p>
    
    <?php if ( get_the_tags() ) : ?>
    <h3>Теги</h3>
    	<p><?php the_tags('', ', ', ''); ?></p>
    <?php endif; ?>
        
    <?php if ( is_user_logged_in() ) : ?>
        <h3><?php edit_post_link(__('Редактировать запись')); ?></h3>
    <?php endif; ?>
</div>
