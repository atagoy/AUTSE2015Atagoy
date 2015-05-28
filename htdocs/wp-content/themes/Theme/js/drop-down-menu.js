
/*Plugin Name: JQuery Drop Down Menu 
Plugin URI: http://www.phpinterviewquestion.com/jquery-dropdown-menu-plugin/
Author: Sana  Ullah
Version: 1.5
Author URI: http://www.phpinterviewquestion.com/
 Copyright 2009 - phpinterviewquestion.com
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.*/
 
     $(document).ready(function(){
	 jQuery("#dropmenu ul ul").css({display: "none"}); 
	 // For 1 Level
	     jQuery("#dropmenu li:has(ul) a").append(''); 
	     jQuery("#dropmenu li ul a b").text('<b>1</b>');
	   // For 2 Level
	     jQuery("#dropmenu li ul li:has(ul) a").append(''); 
         jQuery("#dropmenu li ul li ul a b").text('<b>1</b>'); 
	   // For 3 Level
	     jQuery("#dropmenu li ul li ul li:has(ul) a").append(''); 
	     jQuery("#dropmenu li ul li ul li ul li a b").text('<b>1</b>');
	  
	  // For 4 Level
	    jQuery("#dropmenu li ul li ul li ul li:has(ul) a").append(''); 
	    jQuery("#dropmenu li ul li ul li ul li ul li a span").text('');
		
	  // For 5 Level
	     jQuery("#dropmenu li ul li ul li ul li ul li:has(ul) a").append(''); 
	     jQuery("#dropmenu li ul li ul li ul li ul li ul li a span").text('');
	  
	     // For 6 Level    
	     jQuery("#dropmenu li ul li ul li ul li ul li ul li:has(ul) a").append(''); 
	     jQuery("#dropmenu li ul li ul li ul li ul li ul li ul li a span").text('');
		 
	  // For 7 Level  
	     jQuery("#dropmenu li").hover(function(){
		 jQuery(this).find("ul:first").fadeIn('fast');
		},
		function(){
		jQuery(this).find("ul:first").fadeOut('fast');
		}); });