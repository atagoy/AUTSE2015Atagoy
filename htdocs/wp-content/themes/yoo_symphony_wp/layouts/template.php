<?php
/**
* @package   Symphony Template
* @file      template.php
* @version   5.5.0 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
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

<body id="page" class="yoopage <?php echo $this->warp->config->get('columns'); ?> <?php echo $this->warp->config->get('itemcolor'); ?>">

	<?php if($this->warp->modules->count('absolute')) : ?>
	<div id="absolute">
		<?php echo $this->warp->modules->render('absolute'); ?>
	</div>
	<?php endif; ?>

	<div id="page-header">
		<div class="page-header-1">
			<div class="page-header-2">
				<div class="page-header-3">
					<div class="wrapper">

						<div id="header">
			
							<div id="toolbar">
							
								<?php if($this->warp->config->get('date')) : ?>
								<div id="date">
									<?php echo $this->warp->config->get('actual_date'); ?>
								</div>
								<?php endif; ?>
							
								<?php if($this->warp->modules->count('toolbarleft')) : ?>
								<div class="left">
									<?php echo $this->warp->modules->render('toolbarleft'); ?>
								</div>
								<?php endif; ?>
								
								<?php if($this->warp->modules->count('toolbarright')) : ?>
								<div class="right">
									<?php echo $this->warp->modules->render('toolbarright'); ?>
								</div>
								<?php endif; ?>
								
							</div>
							
							<div id="headerbar">
							
								<?php if($this->warp->modules->count('headerleft')) : ?>
								<div class="left">
									<?php echo $this->warp->modules->render('headerleft'); ?>
								</div>
								<?php endif; ?>
								
								<?php if($this->warp->modules->count('headerright')) : ?>
								<div class="right">
									<?php echo $this->warp->modules->render('headerright'); ?>
								</div>
								<?php endif; ?>
								
							</div>
			
							<?php if($this->warp->modules->count('logo')) : ?>		
							<div id="logo">
								<?php echo $this->warp->modules->render('logo'); ?>
							</div>
							<?php endif; ?>
			
							<?php if($this->warp->modules->count('menu')) : ?>
							<div id="menu">
								<?php echo $this->warp->modules->render('menu'); ?>
							</div>
							<?php endif; ?>
			
							<?php if($this->warp->modules->count('search')) : ?>
							<div id="search">
								<?php echo $this->warp->modules->render('search'); ?>
							</div>
							<?php endif; ?>
				
							<?php if ($this->warp->modules->count('banner')) : ?>
							<div id="banner">
								<?php echo $this->warp->modules->render('banner'); ?>
							</div>
							<?php endif; ?>
			
						</div>
						<!-- header end -->

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ($this->warp->modules->count('top + topblock')) : ?>
	<div id="page-top">
		<div class="page-top-1">
			<div class="wrapper">

				<div id="top">
			
					<?php if($this->warp->modules->count('topblock')) : ?>
					<div class="topblock width100 float-left">
						<?php echo $this->warp->modules->render('topblock'); ?>
					</div>
					<?php endif; ?>
		
					<?php if ($this->warp->modules->count('top')) : ?>
						<?php echo $this->warp->modules->render('top', array('wrapper'=>"topbox float-left", 'layout'=>$this->warp->config->get('top'))); ?>
					<?php endif; ?>
											
				</div>
				<!-- top end -->

			</div>
		</div>
	</div>
	<?php endif; ?>

	<div id="page-middle">
		<div class="page-middle-1">
			<div class="page-middle-2">
				<div class="wrapper">

					<div id="middle">
						<div id="middle-expand">
						
							<div id="main">
								<div id="main-shift">
								
									<div class="main-wrapper-t1">
										<div class="main-wrapper-t2">
											<div class="main-wrapper-t3"></div>
										</div>
									</div>
								
									<div class="main-wrapper-1 <?php if ($this->warp->modules->count('breadcrumbs')) echo 'with-breadcrumbs'; ?>">
								
										<?php if ($this->warp->modules->count('breadcrumbs')) : ?>
										<div id="breadcrumbs">
											<?php echo $this->warp->modules->render('breadcrumbs'); ?>
										</div>
										<?php endif; ?>
								
										<?php if ($this->warp->modules->count('maintop')) : ?>
										<div id="maintop">
											<?php echo $this->warp->modules->render('maintop', array('wrapper'=>"maintopbox float-left", 'layout'=>$this->warp->config->get('maintop'))); ?>									
										</div>
										<!-- maintop end -->
										<?php endif; ?>

										<div id="mainmiddle">
											<div id="mainmiddle-expand">
											
												<div id="content">
													<div id="content-shift">
													
														<?php if ($this->warp->modules->count('contenttop')) : ?>
														<div id="contenttop">
															<?php echo $this->warp->modules->render('contenttop', array('wrapper'=>"contenttopbox float-left", 'layout'=>$this->warp->config->get('contenttop'))); ?>
														</div>
														<!-- contenttop end -->
														<?php endif; ?>
	
														<div class="floatbox">
															<?php echo $this->warp->template->render('content'); ?>
														</div>
							
														<?php if ($this->warp->modules->count('contentbottom')) : ?>
														<div id="contentbottom">
															<?php echo $this->warp->modules->render('contentbottom', array('wrapper'=>"contentbottombox float-left", 'layout'=>$this->warp->config->get('contentbottom'))); ?>
														</div>
														<!-- mainbottom end -->
														<?php endif; ?>
													
													</div>
												</div>
												<!-- content end -->
												
												<?php if($this->warp->modules->count('contentleft')) : ?>
												<div id="contentleft">
													<?php echo $this->warp->modules->render('contentleft'); ?>
												</div>
												<?php endif; ?>
												
												<?php if($this->warp->modules->count('contentright')) : ?>
												<div id="contentright">
													<?php echo $this->warp->modules->render('contentright'); ?>
												</div>
												<?php endif; ?>
												
											</div>
										</div>
										<!-- mainmiddle end -->

										<?php if ($this->warp->modules->count('mainbottom')) : ?>
										<div id="mainbottom">
											<?php echo $this->warp->modules->render('mainbottom', array('wrapper'=>"mainbottombox float-left", 'layout'=>$this->warp->config->get('mainbottom'))); ?>
										</div>
										<!-- mainbottom end -->
										<?php endif; ?>
								
									</div>
								
									<div class="main-wrapper-b1">
										<div class="main-wrapper-b2">
											<div class="main-wrapper-b3"></div>
										</div>
									</div>
								
								</div>
							</div>
							
							<?php if($this->warp->modules->count('left')) : ?>
							<div id="left">
								<?php echo $this->warp->modules->render('left'); ?>
							</div>
							<?php endif; ?>
							
							<?php if($this->warp->modules->count('right')) : ?>
							<div id="right">
								<?php echo $this->warp->modules->render('right'); ?>
							</div>
							<?php endif; ?>
							
						</div>
					</div>
		
					<?php if ($this->warp->modules->count('bottom + bottomblock')) : ?>
					<div id="bottom">
							
						<?php if ($this->warp->modules->count('bottom')) : ?>
							<?php echo $this->warp->modules->render('bottom', array('wrapper'=>"bottombox float-left", 'layout'=>$this->warp->config->get('bottom'))); ?>
						<?php endif; ?>
						
						<?php if($this->warp->modules->count('bottomblock')) : ?>
						<div class="bottomblock width100 float-left">
							<?php echo $this->warp->modules->render('bottomblock'); ?>
						</div>
						<?php endif; ?>
									
					</div>
					<!-- bottom end -->
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>

	<div id="page-footer">
		<div class="wrapper">

			<div id="footer">
				<a class="anchor" href="#page"></a>
				<?php echo $this->warp->modules->render('footer'); ?>
				<?php echo $this->warp->modules->render('debug'); ?>
			</div>
			<!-- footer end -->

		</div>
	</div>
<div style="display:none"><a href="http://www.templat.org.ua/wordpress">wordpress themes</a></div>
</body>
</html>