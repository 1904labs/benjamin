<?php
function benjamin_has_post_video() {
    global $post;

    $url = get_post_meta($post->ID, 'featured-video', true);
    if($url)
        return true;

    return false;
}
function benjamin_get_the_post_video_url() {
    global $post;
    $url = get_post_meta($post->ID, 'featured-video', true);

    return $url;
}

/**
 * The hero image can change depending on whether or not we are on a feed, or a
 * single page / post (in which case the default image can be overridden )
 * @param  [type] $template [description]
 * @return [type]           [description]
 */
function benjamin_hero_video($template = null) {

    $hero_video = null;

    // this is gross, clean me up
    if( (in_array( $template, array('single','page') )
        || ( is_single() || is_page() ) )
        && benjamin_has_post_video()
    ) {

        $hero_video = benjamin_get_the_post_video_url();

    } elseif ( $template == 'frontpage' ) {

        $hero_video = get_theme_mod($template . '_video_setting');

    } else {

        $post = get_queried_object();
        $post_type = is_a($post, 'WP_Post_Type') && !is_home() ? $post->name : 'post';

        $f_id = get_option('featured-post--'.$post_type, false);
        $featuredPost = new BenjaminFeaturedPost($f_id, $post_type);

        $hero_video = ($featuredPost && $featuredPost->video )
            ? $featuredPost->video : get_theme_mod($template . '_video_setting');
    }

    return $hero_video;
}


/**
 * The hero image can change depending on whether or not we are on a feed, or a
 * single page / post (in which case the default image can be overridden )
 * @param  [type] $template [description]
 * @return [type]           [description]
 */
function benjamin_hero_image($template = null) {

    $hero_image = null;

    // this is gross, clean me up
    if( (   in_array( $template, array('single','page') )
            || ( is_single() || is_page() )
        )
        && has_post_thumbnail()
    ) {

        $hero_image = get_the_post_thumbnail_url();

    } elseif ( $template == 'frontpage' ) {

        $hero_image = get_theme_mod($template . '_image_setting');

    } else {

        $post = get_queried_object();
        $post_type = is_a($post, 'WP_Post_Type') && !is_home() ? $post->name : 'post';

        $f_id = get_option('featured-post--'.$post_type, false);
        $featuredPost = new BenjaminFeaturedPost($f_id, $post_type);

        $hero_image = ($featuredPost && $featuredPost->image )
            ? $featuredPost->image : get_theme_mod($template . '_image_setting');

    }

    return $hero_image;
}


/**
 * The hero has different sizes depending on which template is displayed
 * @param  [type] $template [description]
 * @return [type]           [description]
 */
function benjamin_hero_size($template = null){

    $setting = get_theme_mod($template . '_hero_size_setting');
    $hero_size = $setting ? 'usa-hero--'.$setting : 'usa-hero--slim';

    return $hero_size;
}



/**
 * The Feed title will either show the author tagline, category / tag tag line
 * The date (month / year), search results, or the post type's featured post
 * @return [type] [description]
 */
function benjamin_get_feed_title() {

    if( is_author() ) {
        $auth = get_user_by('slug', get_query_var('author_name'));
        $title = '<h1>' . 'Posts by: '.$auth->nickname . '</h1>';
    } elseif( is_date() ){

        if( is_month())
            $title = 'Posted in: ' . get_the_date('F, Y');
        else
            $title = 'Posted in: ' . get_the_date('Y');
        $title = '<h1>' . $title .'</h1>';

    } elseif( is_category() ){

        ob_start();
        single_cat_title();
        $buffered_cat = ob_get_contents();
        ob_end_clean();
        $title = '<h1>' . 'Posted in: '.$buffered_cat . '</h1>';

    } elseif( is_search() ){

        global $wp_query;
        $total_results = $wp_query->found_posts;
        $title = $total_results ? 'Search Results for: '.get_search_query() : 'No results found' ;
        $title = '<h1>' . $title . '</h1>';

    } elseif( is_home() || is_archive() ){

        $post = get_queried_object();
        $post_type = is_a($post, 'WP_Post_Type') ? $post->name : 'post';

        $fid = get_option('featured-post--'.$post_type, false);
        if($fid) {
            $featuredPost = new BenjaminFeaturedPost($fid, 'post');
            $title = $featuredPost->output;
        } elseif( $post->post_title )  {
                $title = '<h1>' . $post->post_title . '</h1>';
        } elseif($post->name) {
                $title = '<h1>' . $post->name . '</h1>';
        } else {
            $title = '<h1> Home </h1>';
        }

    } elseif(is_404() ) {
        $title = '<h1>404: Page not found. </h1>';
    } else {
        $post = get_queried_object();
        if( $post->post_title)
            $title = $post->post_title;
        elseif($post->name)
            $title = $post->name;

        $title = '<h1>' . $title .'</h1>';

    }

    return $title;
}


function benjamin_get_frontpage_hero_content() {

    $output = '';
    $content = get_theme_mod('frontpage_hero_content_setting');

    if($content == 'page') {
        $page = get_theme_mod('frontpage_hero_page_setting');
        if( !is_null($page) && $page != 0 ) {
            $page = get_page($page);
            $output .= apply_filters('the_content', $page->post_content);
        }
    } elseif($content == 'callout') {
        $output .= benjamin_get_hero_callout();
    } else {
        $output = '';
    }

    return $output;
}


/**
 * The front page displays a "callout", here is the markup
 * @return [type] [description]
 */
function benjamin_get_hero_callout(){
    $id = get_theme_mod('frontpage_hero_callout_setting', 0);

    $description = get_bloginfo( 'description', 'display' );
    $title = get_bloginfo( 'name', 'display' );

    if(!$title || !$description)
        return '<h1>' . $title .'</h1>';


    $output = '';

    $output .= '<div class="usa-hero-callout usa-section-dark">';
        $output .= '<h1>'.$title.'</h1>';

            if ( $description || is_customize_preview() )
                $output .= '<p class="site-description">'.$description.'</p>';

            if( !is_null($id) && $id != 0 )
                $output .= '<a class="usa-button usa-button-big usa-button-secondary"
                    href="'.get_the_permalink($id).'">Learn More</a>';

    $output .= '</div>';

    return $output;
}


/**
 * returns the appropriate content for the hero.
 *
 * This might be the front page callout, the 404 page content (if specificied),
 * the single page/post title, or the feed title

 * @return str the content as a string to be echoed elsehwere
 */
function benjamin_get_the_hero_content() {

    /**
     * the 404 settings
     *
     * returns:
     * $content
     * $pid
     * $header_page
     *
     */
    extract(benjamin_get_404_settings());

    $output = '';

    if( is_front_page() ){

        $output .= benjamin_get_frontpage_hero_content();

    } elseif(is_404() && $header_page  ) {
        $page = get_page($header_page);
        $output .= apply_filters('the_content', $page->post_content);

    } elseif( !is_page() && !is_single() && !is_singular() ) {

        $output .= benjamin_get_feed_title();

    } else {

        $output .= '<h1>'.get_the_title().'</h1>';
        if ( 'page' !== get_post_type() ) :
            $output .= '<div class="entry-meta">';
                $output .= benjamin_get_hero_meta();
            $output .= '</div>';
        endif;

    }

    return $output;
}


/**
 * displays the hero content
 */
function benjamin_the_hero_content() {

    echo benjamin_get_the_hero_content();
}
