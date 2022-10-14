jQuery(document).ready(function(){
    // pagination
    let page = 2;
    jQuery('.load-more').on('click', function(){
        jQuery.ajax({
            type : "POST",
            url : ajax.ajaxurl,
            data : {
                action: "rh_upcoming_retreacts_list", 
                page: page
            },
            success: function(response) {
                let responseJSON = jQuery.parseJSON(response);

                jQuery('.load-more').show();
				
                if(responseJSON.max <= 1 || responseJSON.max == page) {
                    jQuery('.load-more').hide();
                }
                jQuery('.rhup_post-wrapper').append(responseJSON.result_html);
                page += 1;
            }
         });
    })
    window.addEventListener("DOMSubtreeModified", function () {
        jQuery(function () {
                var count = 0;
                jQuery('.owl-carousel').each(function () {
                    jQuery(this).attr('id', 'owl-demo' + count);
                    jQuery('#owl-demo' + count).owlCarousel({
                        items:1,
                        loop:true,
                        margin:10
                        //autoplay:true,
                        //autoplayTimeout:5000,
                        //autoplayHoverPause:true
                         
                    });
                    count++;
                });
        });
        // jQuery('.rhup_post').each(function(){
        //     let val_text = jQuery(this).find('.rhup_tdp h2').text();
        //     jQuery(this).find('.apply_up_filed input').val(val_text);
        // });
        jQuery('.rhup_post').each(function(){
            jQuery(this).on("click", function(){
              //jQuery(this).find('.up_popupform').addClass('openForm');
              let val_text = jQuery(this).find('.rhup_tdp h2').text();
              jQuery('.up_popupform').find('.apply_up_filed input').val(val_text);
              jQuery('.up_popupform').addClass('openForm'); 
           });
        });
        jQuery('.close_up_popup').click(function(){
            setTimeout(function () {
                jQuery('.up_popupform').removeClass('openForm'); 
            }, 500);
        });
    });
});
