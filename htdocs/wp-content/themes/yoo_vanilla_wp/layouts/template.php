<?php
/**
* @package   Vanilla
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get template configuration
include(dirname(__FILE__).'/template.config.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->warp->config->get('language'); ?>" lang="<?php echo $this->warp->config->get('language'); ?>" dir="<?php echo $this->warp->config->get('direction'); ?>" >
<head>
<?php echo $this->warp->template->render('head'); ?>
<link rel="apple-touch-icon" href="<?php echo $this->warp->path->url('template:apple_touch_icon.png'); ?>" />
</head>

<body id="page" class="yoopage <?php echo $this->warp->config->get('columns'); ?> <?php echo $this->warp->config->get('itemcolor'); ?> <?php echo $this->warp->config->get('toolscolor'); ?> <?php echo 'style-'.$this->warp->config->get('style'); ?> <?php echo 'font-'.$this->warp->config->get('font'); ?> <?php echo $this->warp->config->get('webfonts'); ?> <?php echo $this->warp->config->get('contentwrapper-class'); ?> <?php echo !$this->warp->modules->count('top + topblock') ? "no-top": ""; ?> <?php echo !$this->warp->modules->count('bottom + bottomblock') ? "no-bottom": ""; ?>">

	<?php if ($this->warp->modules->count('absolute')) : ?>
	<div id="absolute">
		<?php echo $this->warp->modules->render('absolute'); ?>
	</div>
	<?php endif; ?>
	
	<div id="page-header">
		<div class="page-header-1">
			
			<div class="wrapper">
				<div style="position: absolute; left: -9999px;">
					<a href="http://kindle-coupon.blogspot.com/">kindle deals</a>
					<a href="http://kindledx-coupon.blogspot.com/">kindle dx coupon 2012</a>
					<a href="http://kindlefire-coupon.blogspot.com/">cheap kindle fire</a>
					<a href="http://kindletouch-coupon.blogspot.com/">kindle touch coupon</a>
					<a href="http://kindletouch3g-coupon.blogspot.com/">kindle touch 3G deals</a>
					<a href="http://kindlekeyboard3g-coupon.blogspot.com/">amazon kindle keyboard 3G coupon</a>
					<a href="http://nikond5100coupon.blogspot.com/">nikon d5100 best price</a>
					<a href="http://nikond800coupon.blogspot.com/">nikon d800 coupon</a>
					<a href="http://nikond3100coupon.blogspot.com/">cheap nikon d3100</a>
					<a href="http://nikond3200coupon.blogspot.com/">nikon d3200 coupon 2012</a>
					<a href="http://nikond7000coupons.blogspot.com/">nikon d7000 best price</a>
					<a href="http://nikond700coupon.blogspot.com/">nikon d700 best price 2012</a>
					</div> 
				<div id="header">
	
					<div id="toolbar">
						
						<?php if ($this->warp->modules->count('toolbarleft')) : ?>
						<div class="left">
							<?php echo $this->warp->modules->render('toolbarleft'); ?>
						</div>
						<?php endif; ?>
						
						<?php if ($this->warp->modules->count('toolbarright')) : ?>
						<div class="right">
							<?php echo $this->warp->modules->render('toolbarright'); ?>
						</div>
						<?php endif; ?>
						
						<?php if($this->warp->config->get('date')) : ?>
						<div id="date">
							<?php echo $this->warp->config->get('actual_date'); ?>
						</div>
						<?php endif; ?>
						
					</div>
					
					<?php  if ($this->warp->modules->count('menu')) : ?>
					<div id="menu">
						
						<?php echo $this->warp->modules->render('menu'); ?>
						
						<?php if ($this->warp->modules->count('search')) : ?>
						<div id="search">
							<?php echo $this->warp->modules->render('search'); ?>
						</div>
						<?php endif; ?>
						
					</div>
					<?php endif; ?>
					
					<?php if ($this->warp->modules->count('logo')) : ?>		
					<div id="logo">
						<?php echo $this->warp->modules->render('logo'); ?>
					</div>
					<?php endif; ?>
					
					<?php if ($this->warp->modules->count('banner')) : ?>
					<div id="banner">
						<?php echo $this->warp->modules->render('banner'); ?>
					</div>
					<?php endif;  ?>

				</div>
				<!-- header end -->				
				
			</div>
			
		</div>
	</div>
	
	<?php if ($this->warp->modules->count('top + topblock')) : ?>
	<div id="page-top">
		<div class="page-top-1">
			<div class="page-top-2">
			
				<div class="wrapper">
	
					
					<div id="top">
						<?php if($this->warp->modules->count('topblock')) : ?>
						<div class="vertical width100">
							<?php echo $this->warp->modules->render('topblock'); ?>
						</div>
						<?php endif; ?>
		
						<?php if ($this->warp->modules->count('top')) : ?>
							<?php echo $this->warp->modules->render('top', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('top'))); ?>
						<?php endif; ?>
					</div>
					<!-- top end -->
	
				</div>
		
			</div>	
		</div>
	</div>
	<?php endif; ?>
	
	<div id="page-body">
		<div class="page-body-1">
			<div class="page-body-2">

				<div class="wrapper">

					<div class="middle-wrapper">
						<div id="middle">
							<div id="middle-expand">
			
								<div id="main">
									<div id="main-shift">
			
										<?php if ($this->warp->modules->count('maintop')) : ?>
										<div id="maintop">
											<?php echo $this->warp->modules->render('maintop', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('maintop'))); ?>
										</div>
										<!-- maintop end -->
										<?php endif; ?>
			
										<div id="mainmiddle">
											<div id="mainmiddle-expand">
											
												<div id="content">
													<div id="content-shift">
			
														<?php if ($this->warp->modules->count('contenttop')) : ?>
														<div id="contenttop">
															<?php echo $this->warp->modules->render('contenttop', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('contenttop'))); ?>
														</div>
														<!-- contenttop end -->
														<?php endif; ?>
														
														<div id="component" class="floatbox">
															
															<?php if ($this->warp->modules->count('breadcrumbs')) : ?>
																<?php echo $this->warp->modules->render('breadcrumbs'); ?>
															<?php endif; ?>
															
															<?php echo $this->warp->template->render('content'); ?>
															
														</div>
							
														<?php if ($this->warp->modules->count('contentbottom')) : ?>
														<div id="contentbottom">
															<?php echo $this->warp->modules->render('contentbottom', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('contentbottom'))); ?>
														</div>
														<!-- mainbottom end -->
														<?php endif; ?>
													
													</div>
												</div>
												<!-- content end -->
												
												<?php if($this->warp->modules->count('contentleft')) : ?>
												<div id="contentleft" class="vertical">
													<div class="contentleft-1"></div>
													<?php echo $this->warp->modules->render('contentleft'); ?>
												</div>
												<?php endif; ?>
												
												<?php if($this->warp->modules->count('contentright')) : ?>
												<div id="contentright" class="vertical">
													<div class="contentright-1"></div>
													<?php echo $this->warp->modules->render('contentright'); ?>
												</div>
												<?php endif; ?>
												
											</div>
										</div>
										<!-- mainmiddle end -->
			
										<?php if ($this->warp->modules->count('mainbottom')) : ?>
										<div id="mainbottom">
											<?php echo $this->warp->modules->render('mainbottom', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('mainbottom'))); ?>
										</div>
										<!-- mainbottom end -->
										<?php endif; ?>
									
									</div>
								</div>
			
								<?php if($this->warp->modules->count('left')) : ?>
								<div id="left" class="vertical">
									<?php echo $this->warp->modules->render('left'); ?>
								</div>
								<?php endif; ?>
								
								<?php if($this->warp->modules->count('right')) : ?>
								<div id="right" class="vertical">
									<?php echo $this->warp->modules->render('right'); ?>
								</div>
								<?php endif; ?>
			
							</div>
						</div>
					</div>
	
				</div>
				
			</div>
		</div>
	</div>
	
	<?php if ($this->warp->modules->count('bottom + bottomblock')) : ?>
	<div id="page-bottom">
		<div class="page-bottom-1">
			<div class="page-bottom-2">
			
				<div class="wrapper">

					
					<div id="bottom">
						<?php if ($this->warp->modules->count('bottom')) : ?>
							<?php echo $this->warp->modules->render('bottom', array('wrapper'=>"horizontal float-left", 'layout'=>$this->warp->config->get('bottom'))); ?>
						<?php endif; ?>
	
						<?php if($this->warp->modules->count('bottomblock')) : ?>
						<div class="vertical width100">
							<?php echo $this->warp->modules->render('bottomblock'); ?>
						</div>
						<?php endif; ?>
					</div>
					<!-- bottom end -->

				</div>
			
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<div id="page-footer">
		<div class="wrapper">
			
			<div id="footer">
			
				<?php if ($this->warp->modules->count('footer + debug')) : ?>
				<a class="anchor" href="#page"></a>
				<?php echo $this->warp->modules->render('footer'); ?>
				<?php echo $this->warp->modules->render('debug'); ?>
				<?php endif; ?>
				
			</div>
			<!-- footer end -->

		</div>
	</div>
	
	<?php echo $this->render('footer'); ?>
	
</body>
</html>