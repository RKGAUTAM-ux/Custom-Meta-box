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
    //wp_enqueue_script( 'jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'); 
    wp_enqueue_style( 'owlcarmin-style', plugin_dir_url( __FILE__ ) . 'assets/css/owl.carousel.min.css'); 
    wp_enqueue_style( 'magnific-popup-css', plugin_dir_url( __FILE__ ) . 'assets/css/magnific-popup.min.css'); 
    wp_enqueue_style( 'ownlcardefault-style', plugin_dir_url( __FILE__ ) . 'assets/css/owl.theme.default.min.css'); 
    wp_enqueue_style( 'font-awesome', 'assets/css/font-awesome.min.css');
    wp_register_script( 'rhup_common', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array('jquery'), null, true);
    wp_register_script( 'magnific-popup-js', plugin_dir_url( __FILE__ ) . 'assets/js/magnific-popup.min.js', array('jquery'), null, true);
    wp_register_script( 'owlc_common', plugin_dir_url( __FILE__ ) . 'assets/js/owl.carousel.js', array('jquery'), null, true);

    wp_localize_script( 'rhup_common', 'ajax', array(
    'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_script( 'rhup_common' );
    wp_enqueue_script( 'magnific-popup-js' );
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
        'order' => 'DESC',
        'meta_query' =>array(
            array(
              "key" => "rhup-date-cst-range",
              "value" => strtotime("now"),
              "compare" => ">="
            ),
            array(
              "key" => "rhup-date-cst-range",
              "value" => '',
              "compare" => "!="
            )
        )
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
          $up_item_image = wp_get_attachment_image(get_post_meta( $post_id, $x , true),array("500"));
          $attachment_id = get_post_meta($post_id, $x , true);
          $attachment_element = wp_get_attachment_url( $attachment_id, 'medium' );
          if( $up_item_image !=''){  
            $getImage .= '<div class="up_item"><a href="'.$attachment_element.'">'. $up_item_image.'</a></div>';
          }
        }
        $pack_text = 'This package includes:';
        $from_price = 'From';
        $pp_price = 'pp*';
        if($rating !== ''){
            $getStar = "";
            for ($i=1; $i<=5; $i++) {
                $getStar .= '<i class="fa fa-star'.($i <= (int)$rating ? '' : ' upnostar').'"></i>';
            }
          }
        if($content != ''){
            $trimmed_content = wp_trim_words($content, 20);
        }
            
            $html .= 
            '<div class="rhup_post">
                <div class="tpcrslblg"><div id="owl-demo" class="owl-carousel owl-theme up_imgae">
                    '.$getImage.'
                </div></div>
                <div class="rhup_desc">
                    <div class="rhup_tdr">
						<div class="dt-tag">
							<div class="up-tags">'.$up_tags.'</div>
							<div class="up-date">'.$up_date.'</div>
						</div>
						<div class="up-star">'.$getStar.' <span class="countStar"> ('.$rating.')</span></div>
                    </div>
                    <div class="rhup_tdp">
                        <h2>'.$post_title.'</h2>
                        <p>'.$trimmed_content.'</p>
                        <div class="pkginc">
						   <h4>'.$pack_text.'</h4>
                           <div class="flt">'.$up_package.'</div>
					    </div>
                    </div>
                    <div class="rhup_price">
                        <span class="pric">'.$from_price.' <strong>'.$up_price.'</strong>'.$pp_price.' ('.$up_bonus_value.')</span>
                    </div>
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