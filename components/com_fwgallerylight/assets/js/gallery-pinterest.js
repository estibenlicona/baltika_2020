(function($) {
	function init_gallery_pinterest()
	{
	   var $gallery = $('#gallery_pinterest');
	   if($gallery.length == 0) return;

	   // Isotope
	   $gallery.isotope({
		  itemSelector: '.fwg-single-gallery-item',
		  resizable: false,
		  layoutMode: 'masonry'
	   });

	   // Remove min-height for fwg-single-gallery-item when image is loaded on home page
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
                  $($this.data('target')).append($elems).isotope('appended', $elems).isotope('arrange');
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
		  $gallery.find('.fwg-loading img').one('load', function() {
			 $(this).closest('.fwg-loading').removeClass('fwg-loading');;
			 $gallery.isotope();
		  }).each(function(){if(this.complete) $(this).load();});
	   }

	}
	
    $(function() {
        if($.fn.isotope) {
            init_gallery_pinterest();
        }
    });
})(jQuery);
