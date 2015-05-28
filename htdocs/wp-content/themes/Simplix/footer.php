</div>		
			</div>
		</div>
	</div>
	
	<div class="bottom_bg">
		<div class="footer">
			<div class="footer_cont">
				<div id="footer_left">
					Powered by <a href="http://www.templat.org.ua/wordpress">WordPress</a>
				</div>
				<div id="footer-right">
				<div class="lin">
					<div class="linka"><a href="http://www.nattywp.com/">NattyWP Theme</a></div>
				</div>
				
			</div>
				
			
			</div>	
		</div>
	</div>

<?php
	$t_analytics = get_settings( "t_analytics" );
	if( $t_analytics != "" ) { 
?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
var pageTracker = _gat._getTracker("<?php echo $t_analytics; ?>");
pageTracker._initData();
pageTracker._trackPageview();
</script>
	
<?php } ?>

</body>
</html>