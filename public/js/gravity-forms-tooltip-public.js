(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */









	$(document).ready(function() {
		//display label tooltip
		$('label.gfield_label').each(function(){
			var labeltext = $(this).data("tooltiptext");
			if(labeltext) {
				if(labeltext.length > 0) {
					$(this).append('<div class="gravity-tooltip"><span class="gravity-tooltiptext">'+labeltext+'</span></div>');
				}
			}
		});
		
		//For smaller screen move the tooltip to the top
		if($(window).width() < 700) {
			$('.advanced-tooltip').attr('flow', 'up');
		}
		$(window).on('resize', function(){
			if($(window).width() < 700) {
				$('.advanced-tooltip').attr('flow', 'up');
			}
		});
	});
	$(document).on('gform_page_loaded', function(event, form_id, current_page){
        $('label.gfield_label').each(function(){
			var labeltext = $(this).data("tooltiptext");
			if(labeltext) {
				if(labeltext.length > 0) {
					$(this).append('<div class="gravity-tooltip"><span class="gravity-tooltiptext">'+labeltext+'</span></div>');
				}
			}
		});
    });

})( jQuery );
