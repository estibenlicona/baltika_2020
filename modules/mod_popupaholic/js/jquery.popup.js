 jQuery(document).ready(function() {
jQuery( ".closePopup" ).on( "click", function(e) {
	e.preventDefault();
 jQuery.ifancybox.close();
});
});

 // Exit intent
function addPopupEvent(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    }
    else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}
