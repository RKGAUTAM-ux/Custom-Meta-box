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
                        nav: true,
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
            jQuery('.rhup_desc').on("click", function(){
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

        setTimeout(function(){
            jQuery(".tpcrslblg").each(function() {
               let getID = $(this).find('.up_imgae').attr("id");
               //console.log(getID);
               jQuery('#'+getID+' a').magnificPopup({
                type: 'image',
                mainClass: 'mfp-with-zoom', 
                gallery:{ enabled:true },
                zoom: {
                    enabled: true, 
                    duration: 300, 
                    easing: 'ease-in-out', 
                    opener: function(openerElement) {
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
               });
            });
        },500);
    });
});
jQuery(document).ready(function(){
    jQuery('.owl-carousel-date-cst-range').owlCarousel({
      margin:10,
      items:4,
          loop:true,
          center: false,
      responsiveClass:true,
      responsive:{
          0:{
              items:1,
                nav:true,
              loop:true
          },
             576:{
              items:2,
                nav:true,
              loop:true
          },
          767:{
              items:3,
               nav:true,
              loop:true
          },
          992:{
              items:4,
              nav:true,
              loop:true
          }
      }
    });
    jQuery('.owl-carousel-date-cst-range a').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom', 
        gallery:{ enabled:true },
        zoom: {
            enabled: true, 
            duration: 300, 
            easing: 'ease-in-out', 
            opener: function(openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });
    // jQuery('.owl-carousel-date-cst-range').magnificPopup({
    // 	delegate: 'a',
    // 	type: 'image',
    //     callbacks: {
    //       elementParse: function(item) {
    //         //console.log(item.el.context.className);
    //         if(item.el.context.className == 'video') {
    //           item.type = 'iframe',
    //           item.iframe = {
    //              patterns: {
    //                youtube: {
    //                  index: 'youtube.com/', 
    //                  id: 'v=', 
    //                  src: '//www.youtube.com/embed/%id%?autoplay=1'
    //                },
    //                vimeo: {
    //                  index: 'vimeo.com/',
    //                  id: '/',
    //                  src: '//player.vimeo.com/video/%id%?autoplay=1'
    //                },
    //                gmaps: {
    //                  index: '//maps.google.',
    //                  src: '%id%&output=embed'
    //                }
    //              }
    //           }
    //         } else {
    //            item.type = 'image',
    //            item.tLoading = 'Loading image #%curr%...',
    //            item.mainClass = 'mfp-img-mobile',
    //            item.image = {
    //              tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
    //            }
    //         }

    //       }
    //     },
    // 		gallery: {
    // 			enabled: true,
    // 			navigateByImgClick: true,
    // 			preload: [0,1] 
    // 		}
        
    // });
  
  });