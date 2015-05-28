<?php // example: angled module with image borders ?>
<div class="module <?php echo $style; ?> <?php echo $color; ?> <?php echo $yootools; ?> <?php echo $first; ?> <?php echo $last; ?>">

	<?php echo $badge; ?>
	
	<div class="box-1">
		<div class="box-2">
			<div class="box-3">
				<div class="box-4 deepest">
				
					<?php if ($showtitle) : ?>
					<h3 class="header"><span class="header-2"><span class="header-3"><?php echo $title; ?></span></span></h3>
					<?php endif; ?>
					
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</div>
		
</div>