<div id="warp" class="widget-options wrap">
	
    <h2>Widget Options</h2>
	<p>Customize your widgets appearance and select your favorite style, icon or badge. To configure your widgets, please visit the <a href="widgets.php">widgets settings</a> screen.</p>

	<form id="options" method="post" action="">
		<div>
		<?php
			$output = array('config' => array(), 'no_config' => array());

		    foreach ($this->warp->system->getWidgets() as $position => $widgets) {
		        if ($position == 'wp_inactive_widgets' || !count($widgets)) continue;

				// configurable ?
	    		if ($position_settings[$position]['configurable']) {

					$html   = array('<div class="box postbox position">');
					$html[] = '<h3>'.$position.'</h3>';
					$html[] = '<div class="content">';

	                foreach ($widgets as $widget) {

						$html[] = '<div class="box postbox widget collapsible collapsed">';
						$html[] = '<h3>'.$widget->name.(isset($widget->params['title']) ? '<span class="in-widget-title">: '.$widget->params['title'].'</span>' : null).'</h3>';
						$html[] = '<div class="content">';
					
						foreach ($module_settings as $node) {

							$name  = $node->attributes('name');
							$value = isset($widget->options[$name]) ? $widget->options[$name] : $node->attributes('default');

					        $html[] = '<div class="option">';
	   				        $html[] = '<h4>'.$node->attributes('label').'</h4>';
							$html[] = '<div class="value">'.$this->warp->control->render($node->attributes('type'), 'warp_widget_options['.$widget->id.']['.$name.']', $value, $node, compact('widget')).'</div>';
					        $html[] = '</div>';
						}

						$html[] = '<div class="option"><input type="submit" value="Save" class="button-primary"/><span></span></div>';
						$html[] = '</div>';
						$html[] = '</div>';
					}
					$html[] = '</div>';
					$html[] = '</div>';

					$output['config'][] = $html;

				} else {

					$html   = array('<div class="box postbox position no-config">');
					$html[] = '<h3>'.$position.'</h3>';
					$html[] = '<div class="content">'.$position_settings[$position]['info'].'</div>';
					$html[] = '</div>';

					$output['no_config'][] = $html;
				}
			}

			// create columns
			$positions = array_merge($output['config'], $output['no_config']);
			$count     = count($positions);
			$columns   = array();

			for ($i = 0; $i < $count; $i++) { 
				$column = $i % 3;
				
				if (!isset($columns[$column])) {
					$columns[$column] = '';
				}

				if ($pos = array_shift($positions)) {
					$columns[$column] .= implode("\n", $pos);
				}
			}

			foreach ($columns as $column) {
				printf('<div class="column">%s</div>', $column);
			}

			settings_fields('template-parameters');
		?>
		</div>
		<input type="hidden" name="warp-ajax-save" value="1" />
	</form>

</div>