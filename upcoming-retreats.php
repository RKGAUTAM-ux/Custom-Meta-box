<?php
/**
 * Plugin Name:  Upcoming Retreats
 * Author:       RK
 * Version: 	  0.0.1
 * Description:  Upcoming Retreats 
 * Text Domain:  rhup
 * Domain Path:  /lang
 */

/**
* Including Plugin file for security
* Include_once
* 
* @since 1.0.0
*/
include( plugin_dir_path( __FILE__ ) . 'inc/functions.php');

function enqueue(){
    wp_enqueue_style( 'rhup-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css'); 
    wp_enqueue_style( 'owlcarmin-style', plugin_dir_url( __FILE__ ) . 'assets/css/owl.carousel.min.css'); 
    wp_enqueue_style( 'ownlcardefault-style', plugin_dir_url( __FILE__ ) . 'assets/css/owl.theme.default.min.css'); 
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_register_script( 'rhup_common', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array('jquery'), null, true);
    wp_register_script( 'owlc_common', plugin_dir_url( __FILE__ ) . 'assets/js/owl.carousel.js', array('jquery'), null, true);

    wp_localize_script( 'rhup_common', 'ajax', array(
    'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_script( 'rhup_common' );
    wp_enqueue_script( 'owlc_common' );

}
add_action( 'wp_enqueue_scripts', 'enqueue', 0 );

function rh_upcoming_retreacts_list(){

    if(isset($_POST['page'])){
        $page = $_POST['page'];
      }else{
        $page = 1;
      }
    //echo $page;
    $args = array( 
        'post_type' => 'upcomingretreats', 
        'posts_per_page' => 3,
        'post_status'    => array( 'publish' ),
        'paged' => $page,
        'order' => ' '
    );
    

    $the_query = new WP_Query( $args ); 
    $max_pages = $the_query->max_num_pages;
    $html = '';

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
        // $up_imgOne = wp_get_attachment_image(get_post_meta( $post_id, '1', true),'thumbnail'); 
        // $up_imgTwo = wp_get_attachment_image(get_post_meta( $post_id, '2', true),'thumbnail'); 
        // $up_imgThree = wp_get_attachment_image(get_post_meta( $post_id, '3', true),'thumbnail'); 
        // $up_imgFour = wp_get_attachment_image(get_post_meta( $post_id, '4', true),'thumbnail'); 
        $img = get_option('up_add_key');
        $getImage = "";
        for ($x = 1; $x <=$img; $x++) {
          $getImage .= '<div class="up_item">'.wp_get_attachment_image(get_post_meta( $post_id, $x , true),'thumbnail').'</div>';
        }
        $pack_text = 'This package includes:';
        $from_price = 'From';
        $pp_price = 'pp*';
        if($rating !== ''){
            $getStar = "";
            for ($i=1; $i<=5; $i++) {
                $getStar .= '<span class="fa fa-star'.($i <= (int)$rating ? '' : ' upnostar').'"></span>';
            }
          }
        if($content != ''){
            $trimmed_content = wp_trim_words($content, 20);
        }
            
            $html .= 
            '<div class="rhup_post">
                <div id="owl-demo" class="owl-carousel owl-theme up_imgae">
                    '.$getImage.'
                </div>
                <div class="rhup_desc">
                    <div class="rhup_tdr">
                        <p class="up-tags">'.$up_tags.'</hp>
                        <p class="up-date">'.$up_date.'</p>
                        <p class="up-star">'.$getStar.' <span class="countStar"> ('.$rating.')</span></p>
                    </div>
                </div>
                <div class="rhup_tdp">
                    <h2>'.$post_title.'</h2>
                    <p>'.$trimmed_content.'</p>
                    <p>'.$pack_text.'</p>
                    <span>'.$up_package.'</span>
                </div>
                <div class="rhup_price">
                    <p>'.$from_price.' <b>'.$up_price.'</b>'.$pp_price.' ('.$up_bonus_value.')</p>
                </div>
            </div>';
        endwhile;
  
    wp_reset_postdata(); 
    else: 
        $html .='No More Upcoming Retreats';
    endif; 

    $result_json = [
        'result_html' => $html,
        'max' => $max_pages
      ];
      
    echo json_encode($result_json);
    exit();
}
add_action('wp_ajax_rh_upcoming_retreacts_list', 'rh_upcoming_retreacts_list');
add_action('wp_ajax_nopriv_rh_upcoming_retreacts_list', 'rh_upcoming_retreacts_list');

// // on plugin activation
register_activation_hook( __FILE__, 'wp_rh_on_activation');
function wp_rh_on_activation() {

}

// on plugin deactivation
register_deactivation_hook( __FILE__, 'wp_rh_on_deactivation' );
function wp_rh_on_deactivation() {
    
} 

?>