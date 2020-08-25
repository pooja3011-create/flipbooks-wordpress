(function(jQuery) {
	jQuery.fn.advCounter = function(){
		var src = this.find('.video-plugin-div').children('iframe').attr('src');
		this.find('.video-plugin-div').children('iframe').attr('src' , src + '?autoplay=1&controls=0&rel=0&showinfo=0&enablejsapi=1&widgetid=1');
		var counter = 6;
		this.addClass('added');
		var tBtn = jQuery(this);
		setInterval(function(){
			counter--;
			if(counter >= 0){
				var button = tBtn.find('#count');
				button.html(counter);
				jQuery(button).show();
			}
			if (counter === 0) {
				jQuery(button).hide();
				tBtn.find('.skip-video').show();
				clearInterval(counter);
			}

		}, 1000);
		//hide betube ad div
		jQuery('.skip-video').on('click', function(){
			jQuery(this).parent().parent().remove();
		});
		return this;
	};
})(jQuery);
jQuery.noConflict();
jQuery(document).ready(function() {	
	jQuery('.tabs-title').on('click', function(){
        var img = jQuery(this).find('a').attr('href');
        if(jQuery(img).hasClass('added')){        
        }else{
            jQuery(img).advCounter();
        }
    });    
    
});
jQuery(window).on('load', function() {		
	jQuery('#player1').advCounter();
});	
