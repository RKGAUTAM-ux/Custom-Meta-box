<?php

function create_posttype() { 
    register_post_type( 'upcomingretreats',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Upcoming Retreats' ),
                'singular_name' => __( 'Upcoming Retreats' )
            ),
            'public' => false,
            'show_in_menu'  =>  true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'upcomingretreats'),
            'menu_position' =>  10,
            'show_ui' =>  true,
            'hierarchical'  =>  false,
            'taxonomies'    => array( 'category' ),
            'supports' => array('title', 'editor')
        )
    );
}
add_action( 'init', 'create_posttype' );


// Jobs Posttype custom 
add_action('admin_menu', 'register_up_submenu_page');
function register_up_submenu_page() {
  add_submenu_page( 'edit.php?post_type=upcomingretreats', 'Settings', 'Settings', 'manage_options', 'up-submenu-page', 'up_submenu_page_callback' ); 
}

function up_submenu_page_callback() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>Upcoming Setting</h2>';
	echo '</div>';

    $value = get_option( 'up_add_key', '' );
    echo '<form method="POST"><label><strong>Add Image</strong> <input style="width: 30%" type="text" id="up_add_key" name="up_add_key" value="' . $value . '" /></label> <input type="submit" name="up_settings_submit" value="Save" /> </form>';
    // $up_key_value = get_option('up_add_key');
    // $print_up_key = range(1,$up_key_value);
    // print_r($print_up_key);
  }

if(isset($_POST['up_settings_submit'])){
    $up_key = $_POST['up_add_key'];
    if(isset($up_key)){
        update_option("up_add_key", $up_key);
    }else{
        add_option('up_add_key', $up_key);
    }
}


//meta fields
function upcomingretreats_dates_metabox() {
    add_meta_box( 
        'upcomingretreats_dates_metabox', 
        __( 'Information', 'rhup'), 
        'upcomingretreats_dates_metabox_callback', 
        'upcomingretreats', 
        'normal', 
        'default'
    ); 
}
add_action( 'add_meta_boxes', 'upcomingretreats_dates_metabox' );

// CallBack 
function upcomingretreats_dates_metabox_callback( $post ) { 
    wp_nonce_field( 'upcomingretreats_dates_metabox_nonce', 'upcomingretreats_dates_nonce' ); ?>
  <?php  
    $rhup_tags = get_post_meta( $post->ID, 'rhup-tags', true );
    $rhup_date = get_post_meta( $post->ID, 'rhup-date', true );  
    $rhup_package = get_post_meta( $post->ID, 'rhup-package', true );       
    $rhup_price = get_post_meta( $post->ID, 'rhup-price', true );
    $rhup_bonus_value = get_post_meta( $post->ID, 'rhup-bonus', true );
    $rhup_rating = get_post_meta( $post->ID, 'rhup-rating', true );
  ?>
   <p>
    <label for="rhup_tags"><b><?php _e('Tags', 'rhup' ); ?></b></label><br/> 
    <input id="story" class="widefat rhup_tags" name="rhup_tags" value=" <?php echo esc_attr( $rhup_tags ); ?>" />
  </p> 
   <p>
    <label for="rhup_date"><b><?php _e('Date start end/', 'rhup' ); ?></b></label><br/> 
    <input id="story" class="widefat rhup_date" name="rhup_date" value=" <?php echo esc_attr( $rhup_date ); ?>" />
  </p> 
  <p>
    <label for="rhup_package"><b><?php _e('Package Include', 'rhup' ); ?></b></label><br/> 
    <input id="story" class="widefat rhup_package" name="rhup_package" value=" <?php echo esc_attr( $rhup_package ); ?>" />
  </p>  
  <p>   
    <label for="rhup_price"><b><?php _e('Price', 'rhup' ); ?></b></label><br/>    
    <input type="text" class="widefat rhup_price" name="rhup_price" value="<?php echo esc_attr( $rhup_price ); ?>" />
  </p>  
  <p>
    <label for="rhup_bonus_value"><b><?php _e('Bonus value', 'rhup' ); ?></b></label><br/> 
    <input type="text" class="widefat rhup_bonus_value" name="rhup_bonus_value" value="<?php echo esc_attr( $rhup_bonus_value ); ?>" />
  </p>
  <p>
    <label for="my_meta_box_post_type"><b>Rating</b></label>
      <select name="rhup_rating" id="rhup_rating">
            <option value="1" <?php if($rhup_rating == '1') echo 'selected'; ?>>1</option>
            <option value="2" <?php if($rhup_rating == '2') echo 'selected'; ?>>2</option>
            <option value="3" <?php if($rhup_rating == '3') echo 'selected'; ?>>3</option>
            <option value="4" <?php if($rhup_rating == '4') echo 'selected'; ?>>4</option>
            <option value="5" <?php if($rhup_rating == '5') echo 'selected'; ?>>5</option>
    </select>
    </p>
    <p><label for="my_meta_box_post_type"><b>Add Images</b></label></p>
    <?php
     $up_key_value = get_option('up_add_key');
     $print_up_key = range(1,$up_key_value);
     //print_r($print_up_key);
       $meta_keys = $print_up_key;
       foreach($meta_keys as $meta_key){
          $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
          ?>
          <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;display: flex;justify-content: space-evenly;align-items: center;">
              <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="width:50px;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
              <a class="addimage button" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('add image','yourdomain'); ?></a><br>
              <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('remove image','yourdomain'); ?></a>
              <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
          </div>
    <?php } ?>
    <script>
        function custom_postimage_add_image(key){
    
            var $wrapper = jQuery('#'+key+'_wrapper');
    
            custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('select image','yourdomain'); ?>',
                button: {
                    text: '<?php _e('select image','yourdomain'); ?>'
                },
                multiple: false
            });
            custom_postimage_uploader.on('select', function() {
    
                var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
                var img_url = attachment['url'];
                var img_id = attachment['id'];
                $wrapper.find('input#'+key).val(img_id);
                $wrapper.find('img').attr('src',img_url);
                $wrapper.find('img').show();
                $wrapper.find('a.removeimage').show();
            });
            custom_postimage_uploader.on('open', function(){
                var selection = custom_postimage_uploader.state().get('selection');
                var selected = $wrapper.find('input#'+key).val();
                if(selected){
                    selection.add(wp.media.attachment(selected));
                }
            });
            custom_postimage_uploader.open();
            return false;
        }
    
        function custom_postimage_remove_image(key){
            var $wrapper = jQuery('#'+key+'_wrapper');
            $wrapper.find('input#'+key).val('');
            $wrapper.find('img').hide();
            $wrapper.find('a.removeimage').hide();
            return false;
        }
        </script>
    <p> Shortcode : [rh_upcoming_retreacts]</p>
<?php }
function upcomingretreats_dates_save_meta( $post_id ) {

  if( !isset( $_POST['upcomingretreats_dates_nonce'] ) || !wp_verify_nonce( $_POST['upcomingretreats_dates_nonce'],'upcomingretreats_dates_metabox_nonce') ) 
    return;

  if ( !current_user_can( 'edit_post', $post_id ))
    return;

  if ( isset($_POST['rhup_tags']) ) {        
    update_post_meta($post_id, 'rhup-tags', sanitize_text_field( $_POST['rhup_tags'] )); 
  }
  if ( isset($_POST['rhup_date']) ) {        
    update_post_meta($post_id, 'rhup-date', sanitize_text_field( $_POST['rhup_date'] )); 
  }
  if ( isset($_POST['rhup_package']) ) {        
    update_post_meta($post_id, 'rhup-package', sanitize_text_field( $_POST['rhup_package'] )); 
  }
  if ( isset($_POST['rhup_price']) ) {        
    update_post_meta($post_id, 'rhup-price', sanitize_text_field( $_POST['rhup_price']));      
  }  
  if ( isset($_POST['rhup_bonus_value']) ) {        
    update_post_meta($post_id, 'rhup-bonus',  sanitize_text_field($_POST['rhup_bonus_value']));      
  }
  if ( isset($_POST['rhup_rating']) ) {        
    update_post_meta($post_id, 'rhup-rating',  sanitize_text_field($_POST['rhup_rating']));      
  }
  $meta_keys = $print_up_key;
  if (is_array($meta_keys)){
  foreach($meta_keys as $meta_key){
      if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
          update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
      }else{
          update_post_meta( $post_id, $meta_key, '');
      }
  }
}
}
add_action('save_post', 'upcomingretreats_dates_save_meta');



function upcoming_retreacts_list(){ 
  ob_start();
  ?>
  <div class="rhup_post-wrapper">
  <?php
     
  $property_per_page = 3;
  if ( get_query_var( 'paged' ) ) { 
      $paged = get_query_var( 'paged' ); 
  } elseif ( get_query_var( 'page' ) ) { 
      $paged = get_query_var( 'page' ); 
  } else { 
      $paged = 1; 
  }
  $args = array( 
  'post_type' => 'upcomingretreats', 
  'post_status'    => array( 'publish' ),
  'posts_per_page' => $property_per_page ? (int)$property_per_page : 6,
  'paged' => $paged,
  'order' => 'DESC'
  );
  $the_query = new WP_Query( $args ); 
  $max_pages = $the_query->max_num_pages;
  if ( $the_query->have_posts() ) :
     while ( $the_query->have_posts() ) : $the_query->the_post(); 
      $post_id = get_the_id();
      $post_title = get_the_title();
      $content = get_the_content();
      $up_tags = get_post_meta(get_the_ID(), "rhup-tags", true);
      $up_date = get_post_meta(get_the_ID(), "rhup-date", true);
      $up_package = get_post_meta(get_the_ID(), "rhup-package", true);
      $up_price = get_post_meta(get_the_ID(), "rhup-price", true);
      $up_bonus_value = get_post_meta(get_the_ID(), "rhup-bonus", true);
      $rating = get_post_meta(get_the_ID(), "rhup-rating", true);
      if($content != ''){
        $trimmed_content = wp_trim_words($content, 20);
      }
      ?>
      <div class="rhup_post">
        <div id="owl-demo" class="owl-carousel owl-theme up_imgae">
          <?php 
           $img = get_option('up_add_key');
           for ($x = 1; $x <=$img; $x++) {
             ?>
              <div class="up_item"><?php echo wp_get_attachment_image(get_post_meta( $post_id, $x , true),'thumbnail'); ?> </div>
             <?php
           }
          ?>
        </div>
        <div class="rhup_desc">
          <div class="rhup_tdr">
            <p class="up-tags"><?php echo $up_tags; ?></hp>
            <p class="up-date"><?php echo $up_date; ?></p>
            <p class="up-star">
                <?php
                if($rating !== ''){
                  for ($i=1; $i<=5; $i++) {
                    echo '<span class="fa fa-star'.($i <= (int)$rating ? '' : ' upnostar').'"></span>';
                  }
                  echo '<span class="countStar">('.$rating.')</span>';
                }
                ?>
            </p>
          </div>
          <div class="rhup_tdp">
            <h2><?php echo $post_title; ?></h2>
            <p><?php echo $trimmed_content;?></p>
            <p><?php _e('This package includes:', 'rhup' ); ?></p>
            <span><?php echo $up_package; ?></span>
          </div>
          <div class="rhup_price">
            <p><?php _e('From', 'rhup' ); ?> <b><?php echo $up_price; ?></b> <?php _e('pp*', 'rhup' ); ?> (<?php echo $up_bonus_value; ?>)</p>
          </div>
        </div>
      </div>
      <?php endwhile;

  wp_reset_postdata(); 
  else: 
      echo 'No Upcoming Retreats';
  endif; 
  ?>
  </div>
    <button class="load-more">View More</button>
    <div class="up_popupform"><button class="close_up_popup">X</button><?php echo do_shortcode('[gravityform id="12" field_values="input_1='.$post_title.'"]'); ?> </div>

  <?php
  return ob_get_clean();

}
add_shortcode('rh_upcoming_retreacts', 'upcoming_retreacts_list');

?>