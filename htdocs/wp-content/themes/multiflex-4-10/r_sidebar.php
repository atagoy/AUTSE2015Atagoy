      <!-- B.3 SUBCONTENT -->
      <div class="main-subcontent">
      
      <?php if(is_single()) : ?>
        <!-- Subcontent unit -->
        <div class="subcontent-unit-border-green">
          <div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="green">About this post</h1>
          	<p>
            <small>
            This entry was posted
            <?php /* This is commented, because it requires a little adjusting sometimes.
                You'll need to download this plugin, and follow the instructions:
                http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
                /* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
            on<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
            and is filed under <?php the_category(', ') ?>.
            You can follow any responses to this entry through the <?php comments_rss_link('RSS 2.0'); ?> feed.

            <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
                // Both Comments and Pings are open ?>
                You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

            <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
                // Only Pings are Open ?>
                Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

            <?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
                // Comments are open, Pings are not ?>
                You can skip to the end and leave a response. Pinging is currently not allowed.

            <?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
                // Neither Comments, nor Pings are open ?>
                Both comments and pings are currently closed.

            <?php } edit_post_link('Edit this entry.','',''); ?>
            </small>
            </p>
        </div>
      <?php endif; ?>

        <!-- Subcontent unit -->
        <div class="subcontent-unit-border">
          <div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1>Статистика</h1>
			<?php
            $numposts = (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish'");
            $numcomms = (int) $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
            $numcats  = wp_count_terms('category');
            
            $post_str = sprintf(__ngettext('%1$s post', '%1$s posts', $numposts), number_format($numposts));
            $comm_str = sprintf(__ngettext('%1$s comment', '%1$s comments', $numcomms), number_format($numcomms));
            $cat_str  = sprintf(__ngettext('%1$s category', '%1$s categories', $numcats), number_format($numcats));
            ?>
            <p>
            <small>
            <?php printf(__('There are currently %1$s and %2$s, contained within %3$s.'), $post_str, $comm_str, $cat_str); ?>
            </small>
            </p>
        </div>
        
        <!-- Subcontent unit -->
        <div class="subcontent-unit-border-blue">
          <div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="blue">Метки</h1>
			<p>
            <?php wp_tag_cloud(); ?>
            </p>
        </div>
        
        <!-- Subcontent unit -->
        <div class="subcontent-unit-border-orange">
          <div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="orange">Ссылки</h1>
			<ul>
            <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
            </ul>
        </div>
        
        <!-- Subcontent unit -->
        <div class="subcontent-unit-border-green">
          <div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="green">Управление</h1>
			<ul>			
			<?php wp_register(); ?>
            <li><?php wp_loginout(); ?></li>
            <li><?php echo base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzczwvYT4=');?></li>
            <li><a href="http://www.yurgon.com.ua" title="Раскрутка сайта" >Раскрутка сайта</a></li>
            <li><?php echo base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj5NaXggU2tpbnM8L2E+');?></li>
            <?php wp_meta(); ?>
            </ul>
        </div>

      </div> <!-- <div class="main-subcontent"> -->    
