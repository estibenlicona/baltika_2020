(function($) {
    function init_blog_masonry_isotope() {
       // Isotope
	   var $gallery = $('.isotope-masonry');

       $gallery.isotope({
          itemSelector: '.isotope-item',
          resizable: false,
          layoutMode: 'masonry'
       });

       function process_images_load(){
          // Remove min-height for gl-item when image loaded on home page
          $gallery.find('.fwg-loading img').one('load', function() {
             $(this).closest('.fwg-loading').removeClass('fwg-loading');
             $gallery.isotope();
          }).each(function(){if(this.complete) $(this).load();});
       }

       process_images_load();

       // Load more
	   $('.footer-loadmore').each(function() {
          var $this = $(this);
		  var limit = $this.data('items-per-page');
		  var total = parseInt($this.data('total'));
		  var loaded = $('.galleries-item-image').length;
		  var qty = Math.min(limit, total - loaded);
		  $('span', $this).html(qty);
	   });
       $('.footer-loadmore').on( 'click', function(e) {
          e.preventDefault();
          var $this = $(this).hide();
          var $spinner = $(this).siblings('.loading-spinner').show();
		  var limit = $this.data('items-per-page');

          $.ajax({
              url: '',
              type: 'post',
              data: {
                  format: 'json',
                  layout: $this.data('layout'),
                  limit: limit,
                  limitstart: $('.galleries-item-image').length
              }
          }).done(function(html) {
              $spinner.hide();
              $this.show();
              var data = $.parseJSON(html);
              if (data) {
                  var $elems = $(data.html);
                  $gallery.append($elems).isotope('appended', $elems);
				  
				  var total = parseInt($this.data('total'));
				  var loaded = $('.galleries-item-image').length;
				  var qty = Math.min(limit, total - loaded);
				  
				  $('span', $this).html(qty);
                  if (total == loaded) {
                      $this.hide();
                  }
				  process_images_load();
                  if (data.msg) alert(data.msg);
              }
          });
       });
    }
    $(function() {
        if($.fn.isotope) {
            init_blog_masonry_isotope();
        }
    });
})(jQuery);
