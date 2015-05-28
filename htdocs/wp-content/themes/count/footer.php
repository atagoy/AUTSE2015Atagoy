<?php include (TEMPLATEPATH . "/includes/retrieve-options.php"); ?>
<div class="footer">
	<p>
		<?php
			if($wp_footer) {
				echo $wp_footer; 
			} else {
				?>
				&copy; <?php echo date('Y'); ?>. Все права защищены. Дизайн WPCount. Перевод Create.ru – <a href="http://www.create.ru/">создание сайта</a>
				<?php
			} 
		?>
	</p>
</div>

</body>
</html>
