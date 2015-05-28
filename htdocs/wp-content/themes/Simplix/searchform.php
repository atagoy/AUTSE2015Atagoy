<form method="get" id="searchform" class="search" action="<?php bloginfo('home'); ?>/">
<input type="text" class="input" value="SEARCH" onblur="if (!value)value='SEARCH'" onclick="value=''" onfocus="value=''"  name="s" id="s" />
<input id="searchsubmit" type="submit" class="button" value="go" />
<input name="q" type="hidden" value="site:<?php bloginfo('url'); ?>" />

</form>

