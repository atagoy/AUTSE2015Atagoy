                    
             <div class="column-left">
                    

                    <div class="box">
                        <div class="border-top">
                            <div class="border-bottom">
                                <div class="border-right">
                                    <div class="border-left">
                                        <div class="corner-top-right">
                                            <div class="corner-top-left">
                                                <div class="corner-bottom-left">
                                                    <div class="corner-bottom-right"> 
                                                        <div class="indent-box">
                                                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(__('Left Sidebar','theme1065')) ) : else : ?>
                                                            <div class="widget widget_categories">
                                                                <div class="title"><h2>Categories</h2></div>
                                                                <ul>
                                                                    <?php wp_list_categories('show_count=0&title_li='); ?>
                                                                </ul>
                                                            </div>
                                                            <div class="widget widget_archive">
                                                                <div class="title"><h2>Archives</h2></div>                                                               
                                                                <ul>
                                                                    <?php wp_get_archives('type=monthly'); ?>
                                                                </ul>                                                       
                                                            </div>
                                                            <div class="widget widget_meta">
                                                                <div class="title"><h2>Meta</h2></div>                        
                                                                <ul>
																	<?php wp_register(); ?>
                                                                    <li><?php wp_loginout(); ?></li>
                                                                    <li><a href="http://www.racktheme.com" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
                                                                    <li><a href="http://www.racktheme.com/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
                                                                    <li><a href="http://www.racktheme.com/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
                                                                    <?php wp_meta(); ?>
                                                                </ul>
                                                            </div>
                                                              <? endif; ?>
                                                            <div class="txt2">Search</div>
                                                            <?php get_search_form(); ?>
                                                            
                                                        </div>                                     
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
                <div class="column-center">
                    <div class="indent-col2">