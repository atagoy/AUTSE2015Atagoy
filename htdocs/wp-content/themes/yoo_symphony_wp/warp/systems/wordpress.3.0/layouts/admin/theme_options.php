<div id="warp" class="theme-options wrap">

	<h2>Theme Options</h2>
	<p><?php $data = $xml->document->getElement('description'); echo $data->data(); ?></p>

	<?php if ($update && $update->status == 'update-available'): ?>
	<div class="update">
		<?php echo $update->message; ?>
	</div>
	<?php endif; ?>

	<div class="sidebar" style="width:280px;margin-right:20px;float:right;">
		<div class="box postbox">
			<h3>Theme</h3>
			<div class="content">
				<ul class="list">
					<li>Name: <?php $data = $xml->document->getElement('name'); echo $data->data(); ?></li>
					<li>Version: <?php $data = $xml->document->getElement('version'); echo $data->data(); ?></li>
					<li>Warp Framework Version: <?php $data = $warp_xml->document->getElement('version'); echo $data->data(); ?></li>
					<li>Created: <?php $data = $xml->document->getElement('creationDate'); echo $data->data(); ?></li>
					<li>Folder: <code>/themes/<?php echo get_template(); ?></code></li>
				</ul>
			</div>
		</div>
		<div class="box postbox">
			<h3>About</h3>
			<div class="content">
				<ul class="list">
					<li>This is a lovely handcrafted Wordpress theme from YOOtheme.</li>
					<li>Need help? Just head over to the <a href="http://www.yootheme.com/docs">documentation</a>.</li>
					<li>Get more themes at: <a href="http://www.yootheme.com">http://www.yootheme.com</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="options" style="margin-right:320px;">
		<form id="options" method="post" action="">
		    <?php $first = true; foreach ($this->warp->config->get('warp.settings.theme') as $group => $settings) : ?>
	    
		    <div class="box postbox collapsible <?php echo !$first ? 'collapsed' : null; $first = false; ?>">
				<h3><?php echo $group; ?></h3>
				<div class="content">
				<?php
					foreach ($settings as $node) {
				        echo '<div class="option">';
   				        echo '<h4>'.$node->attributes('label').'</h4>';
						echo '<div class="value">'.$this->warp->control->render($node->attributes('type'), $this->warp->system->prefix.$node->attributes('name'), $this->warp->config->get($node->attributes('name')), $node).'</div>';
   				        echo '<span class="description">'.$node->attributes('description').'</span>';
				        echo '</div>';
			        }
				?>
				</div>
			</div>
		
			<?php endforeach; ?>

			<?php 
				if ($checklog === false) {
					$description = 'Checksum file is missing! Your theme is maybe compromised.';
				} elseif (empty($checklog)) {
					$description = 'Verification successful, no file modifications detected.';
				} else {
					$description = 'Some theme files have been modified.';
					
					$content[] = '<ul>';
					foreach (array('modified', 'missing') as $type) {
						if (isset($checklog[$type])) {
							foreach ($checklog[$type] as $file) {
								$content[] = '<li class="'.$type.'">'.$file.($type == 'missing' ? ' (missing)' : null).'</li>';
							}
						}
					}
					$content[] = '</ul>';
				} 
			?>	

		    <div class="box postbox <?php echo (isset($content) ? 'collapsible collapsed' : null); ?>">
				<h3>Verify Files <span class="description"><?php echo $description; ?></span></h3>
				<?php if (isset($content)) : ?>
				<div class="content checklog">
					<?php echo implode("\n", $content); ?>
				</div>
				<?php endif; ?>
			</div>

			<?php settings_fields('template-parameters'); ?>
			<input type="hidden" name="warp-ajax-save" value="1" />
			<p>
				<input type="submit" value="Save changes" class="button-primary"/><span></span>
			</p>
		</form>
	</div>

</div>