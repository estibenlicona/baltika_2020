(function($) {

    function init_gallery_w_preview()
    {
       // =========== Variables
       var $gallery = $('#gallery-w-preview');

       if($gallery.length == 0) return false;

       var $gl_items = $();
       refresh_items_order_data();

       // Isotope
       $gallery.isotope({
          itemSelector: '.fwg-single-gallery-item',
          resizable: false,
          layoutMode: 'masonry',
          sortBy: 'origorder',
          getSortData: {
              origorder: function( itemElem ) {
                return $(itemElem).data('order');
              }
            }
       });

       // Remove min-height for fwg-single-gallery-item when image loaded on home page
       process_images_load();

       // Load more
	   $('.footer-loadmore').each(function() {
          var $this = $(this);
		  var limit = $this.data('items-per-page');
		  var total = parseInt($this.data('total'));
          var selector = $this.data('selector');
		  var loaded = $(selector).length;
		  var qty = Math.min(limit, total - loaded);
		  $('span', $this).html(qty);
	   });
       $('.footer-loadmore').on( 'click', function(e) {
          e.preventDefault();
          var $this = $(this).hide();
          var $spinner = $(this).siblings('.loading-spinner').show();
          var selector = $this.data('selector');
		  var limit = $this.data('items-per-page');
          $.ajax({
              url: '',
              type: 'post',
              data: {
                  format: 'json',
                  layout: $this.data('layout'),
                  limit: limit,
				  ic: $this.data('ic'),
                  limitstart: $(selector).length
              }
          }).done(function(html) {
              $spinner.hide();
              $this.show();
              var data = $.parseJSON(html);
              if (data) {
                  var $elems = $(data.html);
                  $($this.data('target')).append($elems);
				  refresh_items_order_data();
				  $($this.data('target')).isotope('appended', $elems);
				  process_images_load();

				  var total = parseInt($this.data('total'));
				  var loaded = $(selector).length;
				  var qty = Math.min(limit, total - loaded);

				  $('span', $this).html(qty);
                  if (total == loaded) {
                      $this.hide();
                  }
                  if (data.msg) alert(data.msg);
              }
          });
       });

       function process_images_load(){
          // Remove min-height for fwg-single-gallery-item when image loaded on home page
          $('.fwg-loading img').one('load', function() {
             $(this).closest('.fwg-loading').removeClass('fwg-loading');
			 if ($(this).closest('#gallery-w-preview').length) {
				 $gallery.isotope('layout');
			 }
          }).each(function(){if(this.complete) $(this).load();});
       }

       function refresh_items_order_data(){
          $gl_items = $gallery.find('.fwg-single-gallery-item:visible').each(function(){ $(this).attr('data-order', ($(this).index()+1)*10); })
       }

    } // init_gallery_w_preview
    $(function() {
        if($.fn.isotope) {
            init_gallery_w_preview();
        }
    });
})(jQuery);
