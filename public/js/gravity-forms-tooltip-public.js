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

	$.fn.appearTooltip = function() {
		this.each(function() {
			var placement = $(this).parent().attr('placement').length ? $(this).parent().attr('placement') : 'top';
			var animation = $(this).parent().attr('animation');
			var theme = $(this).parent().attr('theme');

			var tippyConfig = {
				content: $(this).parent().attr('tooltip'),
				allowHTML: true,
				placement: placement,
				interactive: true,
			};

			if(animation.length && animation !== 'none') {
				tippyConfig.animation = animation;
			}

			if(theme.length && theme !== 'default') {
				tippyConfig.theme = theme;
			}

			tippy($(this)[0], tippyConfig);
        });

        return this;
	};

	$(document).ready(function() {
		//display label tooltip
		$('.gravity-tooltip').appearTooltip();

	});
	$(document).on('gform_page_loaded', function(event, form_id, current_page){
        $('.gravity-tooltip').appearTooltip();
    });

})( jQuery );