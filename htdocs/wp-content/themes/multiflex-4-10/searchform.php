<h1>Поиск</h1>
<!-- Search form -->                  
<div class="searchform">
  <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
    <fieldset>
      <input type="text" class="field"  value="<?php the_search_query(); ?>" name="s" id="s" />
      <input type="submit" name="button" class="button" value="ОК" />
    </fieldset>
  </form>
</div>
