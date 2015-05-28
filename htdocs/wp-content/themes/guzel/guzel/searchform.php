<form class="searchform" method="get" action="<?php bloginfo('url'); ?>/">
<fieldset>
<label>Поиск</label>
<input type="text" value="<?php the_search_query(); ?>" name="s" class="searchinput" />
<input type="submit" value="ок" class="searchbutton" />
</fieldset>
</form>