<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    
    //---------------------------------------------------------------------------
    // Register Custom Widgets
    //---------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_register_widgets' ) ) :
        
        function hippo_register_widgets() {
            
            if ( class_exists( 'Hippo_Recent_Post_Widget' ) ):
                register_widget( 'Hippo_Recent_Post_Widget' );
            endif;
            
            /*if ( class_exists( 'Hippo_Latest_Tweet_Widget' ) ):
                register_widget( 'Hippo_Latest_Tweet_Widget' );
            endif;*/
            
            if ( class_exists( 'Hippo_Flickr_Photo_Widget' ) ):
                register_widget( 'Hippo_Flickr_Photo_Widget' );
            endif;
        }
        
        add_action( 'widgets_init', 'hippo_register_widgets' );
    endif;
    
    //---------------------------------------------------------------------------
    // Recent Post widget
    //---------------------------------------------------------------------------
    
    if ( ! class_exists( 'Hippo_Recent_Post_Widget' ) ):
        
        class Hippo_Recent_Post_Widget extends WP_Widget {
            
            public function __construct() {
                parent::__construct( 'hippo_recent_post', // Base ID
                                     sprintf( __( '%s Theme - Recent Posts', 'hippo-plugin' ), HIPPO_THEME_NAME ), // Name
                                     array( 'description' => __( 'Show off recent post with thumb', 'hippo-plugin' ), ) // Args
                );
            }
            
            public function form( $instance ) {
                $defaults = array(
                    'title'        => '',
                    'post_limit'   => '5',
                    'title_length' => '25',
                    'show_meta'    => '',
                    'thumb'        => '',
                    'thumb_width'  => '50',
                    'thumb_height' => '50',
                );
                //$instance = wp_parse_args( (array) $instance, $defaults );
                
                $title        = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
                $post_limit   = ( isset( $instance[ 'post_limit' ] ) ) ? $instance[ 'post_limit' ] : '5';
                $title_length = ( isset( $instance[ 'title_length' ] ) ) ? $instance[ 'title_length' ] : '25';
                $show_meta    = ( isset( $instance[ 'show_meta' ] ) ) ? $instance[ 'show_meta' ] : '';
                $thumb        = ( isset( $instance[ 'thumb' ] ) ) ? $instance[ 'thumb' ] : '';
                $thumb_width  = ( isset( $instance[ 'thumb_width' ] ) ) ? $instance[ 'thumb_width' ] : '50';
                $thumb_height = ( isset( $instance[ 'thumb_height' ] ) ) ? $instance[ 'thumb_height' ] : '50';
                ?>
                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                           name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                           value="<?php echo esc_attr( $title ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'post_limit' ); ?>"><?php _e( 'Number of posts to show: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'post_limit' ); ?>"
                           name="<?php echo $this->get_field_name( 'post_limit' ); ?>" type="text"
                           value="<?php echo esc_attr( $post_limit ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'title_length' ); ?>"><?php _e( 'Title length: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title_length' ); ?>"
                           name="<?php echo $this->get_field_name( 'title_length' ); ?>" type="text"
                           value="<?php echo esc_attr( $title_length ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'show_meta' ); ?>"><?php _e( 'Show meta ? ', 'hippo-plugin' ); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'show_meta' ); ?>"
                            name="<?php echo $this->get_field_name( 'show_meta' ); ?>" style="width:100%;">
                        <option
                                value="yes" <?php selected( $show_meta, 'yes' ); ?>><?php _e( 'Yes', 'hippo-plugin' ) ?></option>
                        <option
                                value="no" <?php selected( $show_meta, 'no' ); ?>><?php _e( 'No', 'hippo-plugin' ) ?></option>
                    </select>
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Show Post thumbnail ? ', 'hippo-plugin' ); ?></label>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'thumb' ); ?>"
                            name="<?php echo $this->get_field_name( 'thumb' ); ?>" style="width:100%;">
                        <option
                                value="yes" <?php selected( $thumb, 'yes' ); ?>><?php _e( 'Yes', 'hippo-plugin' ) ?></option>
                        <option
                                value="no" <?php selected( $thumb, 'no' ); ?>><?php _e( 'No', 'hippo-plugin' ) ?></option>
                    </select>
                </p>

                <p>
                    <?php _e( 'Thumbnail Image Size:', 'hippo-plugin' ); ?>
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'thumb_width' ); ?>"><?php _e( 'Width:', 'hippo-plugin' ); ?></label>
                    <input id="<?php echo $this->get_field_id( 'thumb_width' ); ?>"
                           name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" type="text"
                           value="<?php echo esc_attr( $thumb_width ); ?>" size="2">

                    <label
                            for="<?php echo $this->get_field_id( 'thumb_height' ); ?>"><?php _e( 'Height:', 'hippo-plugin' ); ?></label>
                    <input id="<?php echo $this->get_field_id( 'thumb_height' ); ?>"
                           name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" type="text"
                           value="<?php echo esc_attr( $thumb_height ); ?>" size="2">
                </p>
            
            <?php }
            
            public function update( $new_instance, $old_instance ) {
                $instance                   = array();
                $instance[ 'title' ]        = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
                $instance[ 'post_limit' ]   = ( ! empty( $new_instance[ 'post_limit' ] ) ) ? strip_tags( $new_instance[ 'post_limit' ] ) : '5';
                $instance[ 'title_length' ] = ( ! empty( $new_instance[ 'title_length' ] ) ) ? strip_tags( $new_instance[ 'title_length' ] ) : '25';
                $instance[ 'show_meta' ]    = ( ! empty( $new_instance[ 'show_meta' ] ) ) ? strip_tags( $new_instance[ 'show_meta' ] ) : '';
                $instance[ 'thumb' ]        = ( ! empty( $new_instance[ 'thumb' ] ) ) ? strip_tags( $new_instance[ 'thumb' ] ) : '';
                $instance[ 'thumb_width' ]  = ( ! empty( $new_instance[ 'thumb_width' ] ) ) ? strip_tags( $new_instance[ 'thumb_width' ] ) : '50';
                $instance[ 'thumb_height' ] = ( ! empty( $new_instance[ 'thumb_height' ] ) ) ? strip_tags( $new_instance[ 'thumb_height' ] ) : '50';
                
                return $instance;
            }
            
            public function widget( $args, $instance ) {
                
                echo $args[ 'before_widget' ];
                $title = apply_filters( 'widget_title', $instance[ 'title' ] );
                if ( ! empty( $title ) ) {
                    echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
                }
                ?>

                <div class="recent-post-wrapper">
                    <div class="recent-post">
                        <?php
                            $qargs = array(
                                'post_type'      => 'post',
                                'posts_per_page' => $instance[ 'post_limit' ],
                                'post_status'    => 'publish'
                            );
                            
                            $the_query = new WP_Query( $qargs );
                            if ( $the_query->have_posts() ) {
                                while ( $the_query->have_posts() ) {
                                    $the_query->the_post(); ?>
                                    <div class="media">
                                        
                                        <?php
                                            $image_thumb = array(
                                                $instance[ 'thumb_width' ],
                                                $instance[ 'thumb_height' ]
                                            );
                                            $thumb_id    = get_post_thumbnail_id(); // Get the featured image id.
                                            $image       = wp_get_attachment_image_src( $thumb_id, $image_thumb ); // Get img URL.
                                            //
                                            if ( $instance[ 'thumb' ] == 'yes' ) {
                                                if ( has_post_thumbnail() ) { ?>
                                                    <div class="media-left">
                                                        <a class="post-thumb" href="<?php the_permalink(); ?>">
                                                            <img class="media-object recent-post-tab-thumb-image"
                                                                 src="<?php echo esc_url( $image[ 0 ] ); ?>"
                                                                 width="<?php echo intval( $instance[ 'thumb_width' ] ) ?>"
                                                                 alt="<?php echo esc_attr( get_the_title() ); ?>">
                                                        </a>
                                                    </div>
                                                <?php }
                                            } ?>

                                        <div class="media-body">
                                            <?php $hippo_title_length = $instance[ 'title_length' ]; ?>
                                            <h3 class="media-heading">
                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), $hippo_title_length ); ?></a>
                                            </h3>
                                            <?php
                                                if ( $instance[ 'show_meta' ] == 'yes' ) { ?>
                                                    <div class="entry-meta">
                                                        <div class="entry-meta">
                                                            <ul class="list-inline">
                                                                <li>
                                                                <span class="post-date">
                                                                    <?php the_time( 'j F, Y' ) ?>
                                                                </span>
                                                                </li>
                                                                <li>
                                                                <span class="post-author">
                                                                    <?php esc_html_e( 'By', 'sink' ); ?><?php printf( '<a class="url fn n" href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) ) ?>
                                                                </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                        </div>
                                        <!-- /.media-body -->
                                    </div> <!-- /.media -->
                                
                                <?php }
                                
                            }
                            else { ?>

                                <p><?php _e( 'Not Found!', 'hippo-plugin' ) ?></p>
                            
                            <?php }
                            wp_reset_postdata(); ?>
                    </div>
                    <!-- /.recent-post -->
                </div> <!-- /.recent-post-wrapper -->
                <?php
                echo $args[ 'after_widget' ];
            }
        }
    endif;
    
    //---------------------------------------------------------------------------
    //  Latest Tweet Widget
    //---------------------------------------------------------------------------
    if ( ! class_exists( 'Hippo_Latest_Tweet_Widget' ) ):
        
        class Hippo_Latest_Tweet_Widget extends WP_Widget {
            
            public function __construct() {
                parent::__construct( 'hippo_latest_tweet', // Base ID
                                     sprintf( __( '%s Theme - Latest Tweet', 'hippo-plugin' ), HIPPO_THEME_NAME ),  // Name
                                     array( 'description' => __( 'Displays latest tweet in any sidebar', 'hippo-plugin' ), ) // Args
                );
            }
            
            public function form( $instance ) {
                
                $defaults = array(
                    'title'     => '',
                    'widget_id' => '',
                    'max_tweet' => '5'
                );
                //$instance = wp_parse_args( (array) $instance, $defaults );
                
                $title     = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
                $widget_id = ( isset( $instance[ 'widget_id' ] ) ) ? $instance[ 'widget_id' ] : '';
                $max_tweet = ( isset( $instance[ 'max_tweet' ] ) ) ? $instance[ 'max_tweet' ] : '5';
                
                ?>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                           name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                           value="<?php echo esc_attr( $title ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'widget_id' ); ?>"><?php _e( 'Twitter widget ID: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'widget_id' ); ?>"
                           name="<?php echo $this->get_field_name( 'widget_id' ); ?>" type="text"
                           value="<?php echo esc_attr( $widget_id ); ?>">
                    <small><?php _e( 'e.g: 567185781790228482, get instruction from <a href="http://blog.topdevs.net/2013/06/19/where-to-find-twitter-widget-id/">here</a>', 'hippo-plugin' ); ?></small>
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'max_tweet' ); ?>"><?php _e( 'Max tweet number: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'max_tweet' ); ?>"
                           name="<?php echo $this->get_field_name( 'max_tweet' ); ?>" type="text"
                           value="<?php echo esc_attr( $max_tweet ); ?>">
                </p>
            
            
            <?php }
            
            public function update( $new_instance, $old_instance ) {
                $instance                = array();
                $instance[ 'title' ]     = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
                $instance[ 'widget_id' ] = ( ! empty( $new_instance[ 'widget_id' ] ) ) ? strip_tags( $new_instance[ 'widget_id' ] ) : '';
                $instance[ 'max_tweet' ] = ( ! empty( $new_instance[ 'max_tweet' ] ) ) ? strip_tags( $new_instance[ 'max_tweet' ] ) : '5';
                
                return $instance;
            }
            
            public function widget( $args, $instance ) {
                
                extract( $args );
                echo $before_widget;
                $title = apply_filters( 'widget_title', $instance[ 'title' ] );
                if ( ! empty( $title ) ) {
                    echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
                }
                
                $instance[ 'widget_id' ] = isset( $instance[ 'widget_id' ] ) ? $instance[ 'widget_id' ] : '';
                $instance[ 'max_tweet' ] = isset( $instance[ 'max_tweet' ] ) ? $instance[ 'max_tweet' ] : '';
                ?>


                <div class="twitterWidget" data-widget-id="<?php echo esc_attr( $instance[ 'widget_id' ] ); ?>"
                     data-max-tweet="<?php echo esc_attr( $instance[ 'max_tweet' ] ); ?>">

                    <div class="twitter-widget"></div>

                </div>
                
                
                <?php
                echo $after_widget;
                
            }
        }
    endif;
    
    //---------------------------------------------------------------------------
    //  Flickr photo widget
    //---------------------------------------------------------------------------
    
    if ( ! class_exists( 'Hippo_Flickr_Photo_Widget' ) ):
        
        class Hippo_Flickr_Photo_Widget extends WP_Widget {
            
            public function __construct() {
                parent::__construct( 'hippo_flickr_photo', // Base ID
                                     sprintf( __( '%s Theme - Flickr Photo', 'hippo-plugin' ), HIPPO_THEME_NAME ), // Name
                                     array( 'description' => __( 'Displays flickr photo on any sidebar', 'hippo-plugin' ), ) // Args
                );
            }
            
            public function form( $instance ) {
                
                $defaults = array(
                    'title'       => '',
                    'photo_limit' => '8',
                    'flickr_id'   => '52617155@N08',
                );
                //$instance = wp_parse_args( (array) $instance, $defaults );
                
                $title       = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
                $photo_limit = ( isset( $instance[ 'photo_limit' ] ) ) ? $instance[ 'photo_limit' ] : '8';
                $flickr_id   = ( isset( $instance[ 'flickr_id' ] ) ) ? $instance[ 'flickr_id' ] : '52617155@N08';
                
                ?>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                           name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                           value="<?php echo esc_attr( $title ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'flickr_id' ); ?>"><?php _e( 'Flickr ID: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'flickr_id' ); ?>"
                           name="<?php echo $this->get_field_name( 'flickr_id' ); ?>" type="text"
                           value="<?php echo esc_attr( $flickr_id ); ?>">
                </p>

                <p>
                    <label
                            for="<?php echo $this->get_field_id( 'photo_limit' ); ?>"><?php _e( 'Photo Limit: ', 'hippo-plugin' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'photo_limit' ); ?>"
                           name="<?php echo $this->get_field_name( 'photo_limit' ); ?>" type="text"
                           value="<?php echo esc_attr( $photo_limit ); ?>">
                </p>
            
            
            <?php }
            
            public function update( $new_instance, $old_instance ) {
                $instance                  = array();
                $instance[ 'title' ]       = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
                $instance[ 'photo_limit' ] = ( ! empty( $new_instance[ 'photo_limit' ] ) ) ? strip_tags( $new_instance[ 'photo_limit' ] ) : '8';
                $instance[ 'flickr_id' ]   = ( ! empty( $new_instance[ 'flickr_id' ] ) ) ? strip_tags( $new_instance[ 'flickr_id' ] ) : '52617155@N08';
                
                return $instance;
            }
            
            public function widget( $args, $instance ) {
                
                extract( $args );
                echo $before_widget;
                $title = apply_filters( 'widget_title', $instance[ 'title' ] );
                if ( ! empty( $title ) ) {
                    echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
                }
                ?>

                <ul class="flickr-photo-stream" data-flickr-id="<?php echo esc_attr( $instance[ 'flickr_id' ] ); ?>"
                    data-photo-limit="<?php echo esc_attr( $instance[ 'photo_limit' ] ); ?>"></ul>
                
                <?php
                echo $after_widget;
                
            }
        }
    endif;

