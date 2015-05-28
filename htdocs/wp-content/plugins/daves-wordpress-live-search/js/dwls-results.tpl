<ul id="dwls_search_results" class="search_results dwls_search_results">
<input type="hidden" name="query" value="<%- resultsSearchTerm %>" />
<% _.each(searchResults, function(searchResult, index, list) { %>
        <%
        // Thumbnails
        if(DavesWordPressLiveSearchConfig.showThumbs == "true" && searchResult.attachment_thumbnail) {
                liClass = "post_with_thumb";
        }
        else {
                liClass = "";
        }
        %>
        <li class="daves-wordpress-live-search_result <%- liClass %> '">
        <% if(DavesWordPressLiveSearchConfig.showThumbs == "true" && searchResult.attachment_thumbnail) { %>
                <img src="<%= searchResult.attachment_thumbnail %>" class="post_thumb" />
        <% } %>

        <a href="<%= searchResult.permalink %>" class="daves-wordpress-live-search_title"><%= searchResult.post_title %></a>

        <% if(searchResult.post_price !== undefined) { %>
                <p class="price"><%- searchResult.post_price %></p>
        <% } %>

        <% if(DavesWordPressLiveSearchConfig.showExcerpt == "true" && searchResult.post_excerpt) { %>
                <p class="excerpt clearfix"><%= searchResult.post_excerpt %></p>
        <% } %>

        <% if(e.displayPostMeta) { %>
                <p class="meta clearfix daves-wordpress-live-search_author" id="daves-wordpress-live-search_author">Posted by <%- searchResult.post_author_nicename %></p><p id="daves-wordpress-live-search_date" class="meta clearfix daves-wordpress-live-search_date"><%- searchResult.post_date %></p>
        <% } %>
        <div class="clearfix"></div></li>
<% }); %>

<% if(searchResults[0].show_more !== undefined && searchResults[0].show_more && DavesWordPressLiveSearchConfig.showMoreResultsLink == "true") { %>
        <div class="clearfix search_footer"><a href="<%= DavesWordPressLiveSearchConfig.blogURL %>/?s=<%-  resultsSearchTerm %>"><%- DavesWordPressLiveSearchConfig.viewMoreText %></a></div>
<% } %>

</ul>