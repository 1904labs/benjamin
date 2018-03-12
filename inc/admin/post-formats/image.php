<?php

class PostFormatImage extends PostFormat {

    /**
     * The powt format name
     *
     * @var string
     */
    public static $format = 'image';

    /**
     * Registers the post format
     *
     * @return void
     */
    public static function register_meta_box()
    {
        foreach ( self::$screens as $screen ) {
            add_meta_box(
                'post_formats_image',
                __( 'Image', 'benjamin' ),
                array( 'PostFormatImage', 'meta_box_html' ),
                $screen,
                'top',
                'default'
            );
        }
    }


    /**
     * The HTML for the post meta box.
     *
     * @param  wp_post $post the post object.
     * @return void
     */
    public static function meta_box_html( $post )
    {
        wp_nonce_field( 'post_format_nonce_' . self::$format, 'post_format_nonce_' . self::$format );

        $value = self::meta_box_saved_value( $post->ID, self::$format, null );
    ?>
        <div class="pfp-media-holder">
            <?php echo call_user_func( 'benjamin_postformat_get_the_image_markup', $value ); // WPCS: xss ok. ?>
        </div>


        <a class="button pfp-js-media-library" data-media="image"
            id="post_format_image_select">
            <span class="dashicons dashicons-format-image"></span>
            <?php echo __( 'Select Image', 'benjamin'); ?>
        </a>

        <span class="pfp-or-hr"><?php __('or use an oembed url', 'benjamin'); ?></span>


        <input class="post_format_value" 
            data-media="image" 
            id="post_format_image_value" 
            name="post_format_value[<?php echo esc_attr( self::$format ); ?>]" 
            type="url" 
            value="<?php echo esc_url_raw( $value ); ?>" 
        />

        <a class="pfp-js-remove-media" data-media="image"
            href="#" ><?php echo __( 'Remove Image', 'benjamin' ); ?></a>

        <?php
    }


}
