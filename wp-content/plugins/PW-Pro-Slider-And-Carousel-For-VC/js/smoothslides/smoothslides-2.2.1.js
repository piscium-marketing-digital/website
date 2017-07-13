/*
Smoothslides 2.2.0 by Kevin Thornbloom is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License.
*/

(function($) {
	$.fn.extend({
		smoothSlides: function(options) {
			// These are overridden by options declared in footer
			var defaults = {
				effectDuration: 5000,
				transitionDuration: 500,
				effectModifier: 1.3,
				order: 'normal',
				autoPlay: 'true',
				effect: 'zoomOut,zoomIn,panUp,panDown,panLeft,panRight,diagTopLeftToBottomRight,diagTopRightToBottomLeft,diagBottomRightToTopLeft,diagBottomLeftToTopRight',
				effectEasing: 'ease-in-out',
				nextText: ' ►',
				prevText: '◄ ',
				captions: 'true',
				navigation: 'true',
				pagination: 'true',
				matchImageSize: 'true'
			}

			var options = $.extend(defaults, options),
				that = this,
				uniqueId = $(this).attr('id'),
				fullTime= options.effectDuration + options.transitionDuration,
				maxWidth = $(this).find('img').width(),
				effectModPercent = ((options.effectModifier * 100)-100)*.25;

			if (options.transitionDuration >= options.effectDuration) {
				console.log("Make sure effectDuration is greater than transitionDuration");
			}

			// Change wrapper class to remove loading spinner
			$('#'+uniqueId).removeClass('pl-smoothslides').addClass('pl-smoothslides-on');

			function fadeOutLast() {
				// Crapbag (<=IE9) detector
				if (document.all && !window.atob){
					// Crapbag detected! Use jQuery to fade
					$('#'+uniqueId).find('.pl-ss-slide:last').animate({
						'opacity':'0'
					})
				} else {
					// Fade out last with CSS
					$('#'+uniqueId).find('.pl-ss-slide:last').css({
						'transition':'all '+options.transitionDuration+'ms',
						'opacity':'0'
					});
				}
			}
			// FX
			that.crossFade = function() {
				fadeOutLast();
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale(1)  rotate(0deg)'
					});
				}, options.transitionDuration);
			}

			that.zoomOut = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') rotate(1.5deg)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale(1)  rotate(0deg)'
					});
				}, options.transitionDuration);
			}

			that.zoomIn = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale(1.1) rotate(-1.5deg)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') rotate(0deg)'
					});
				}, options.transitionDuration);
			}

			that.panLeft = function() {
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateX('+effectModPercent+'%)'
				});
				fadeOutLast();
				
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateX(0%)'
					});
				}, options.transitionDuration);
			}

			that.panRight = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateX(-'+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateX(0%)'
					});
				}, options.transitionDuration);
			}

			that.panUp = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY('+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%)'
					});
				}, options.transitionDuration);
			}

			that.panDown = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY(-'+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%)'
					});
				}, options.transitionDuration);
			}

			that.diagTopLeftToBottomRight = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY(-'+effectModPercent+'%) translateX(-'+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%) translateX(0%)'
					});
				}, options.transitionDuration);
			}

			that.diagBottomRightToTopLeft= function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY('+effectModPercent+'%) translateX('+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%) translateX(0%)'
					});
				}, options.transitionDuration);
			}

			that.diagTopRightToBottomLeft = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY(-'+effectModPercent+'%) translateX('+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%) translateX(0%)'
					});
				}, options.transitionDuration);
			}

			that.diagBottomLeftToTopRight = function() {
				fadeOutLast();
				// Set up next
				$(this).find('.pl-ss-slide:eq(-2)').css({
					'transition':'none',
					'transform':'scale('+options.effectModifier+') translateY('+effectModPercent+'%) translateX(-'+effectModPercent+'%)'
				});
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale('+options.effectModifier+') translateY(0%) translateX(0%)'
					});
				}, options.transitionDuration);
			}

			// Set max-width based on img size
			if(options.matchImageSize == 'true') {
				$('#'+uniqueId).css('maxWidth',maxWidth);
				$('#'+uniqueId+' img').css('maxWidth','100%');
			} else {
				$('#'+uniqueId+' img').css('width','100%');
			}

			// Wrap each in a div
			$(this).children().each(function(){
				$(this).wrap('<div class="pl-ss-slide"></div>');
			});
			
			// Function to randomize things. (used below)
			$.fn.smoothslidesRandomize=function(a){(a?this.find(a):this).parent().each(function(){$(this).children(a).sort(function(){return Math.random()-0.5}).detach().appendTo(this)});return this};

			// Set slide order
			if (options.order == "random") {
				$('#'+ uniqueId +'').smoothslidesRandomize('.pl-ss-slide');
			} else {
				$('#'+ uniqueId +' .pl-ss-slide').each(function() {
					$(this).prependTo('#'+uniqueId);
				});
			}

			// Set one as relative for height
			$('#'+uniqueId+' .pl-ss-slide:first').css('position','relative');


			if(options.autoPlay == 'true') {
				$(".pl-ss-slide:first", this).appendTo(this)
			}

			// Add CSS easing & duration. Add wrapper div around each image
			$(this).wrapInner("<div class='pl-ss-slide-stage'></div>")
			$(".pl-ss-slide",this).each(function(){
				$(this).css({
					transition: 'all ' + options.effectDuration + 'ms ' + options.effectEasing +''
				});
			});

			// Captions, Yo
			function captionUpdate() {
				var nextCaption = $('#'+uniqueId).find('.pl-ss-slide:eq(-2) img').prop('alt');
				if (!nextCaption) {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','0');
				} else {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','1').html(nextCaption);
				}
			}
			// Captions backward
			function captionUpdateBack() {
				var nextCaption = $('#'+uniqueId).find('.pl-ss-slide:eq(-1) img').prop('alt');
				if (!nextCaption) {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','0');
				} else {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','1').html(nextCaption);
				}
			}
			// Add Caption Markup
			if (options.captions == 'true') {
				$(that).append("<div class='pl-ss-caption-wrap'><div class='pl-ss-caption'></div></div>");
				if (options.autoPlay == 'true') {
					captionUpdate();
				} else {
					var nextCaption = $('#'+uniqueId).find('.pl-ss-slide:last img').prop('alt');
					if (!nextCaption) {
						$('#'+uniqueId).find(".pl-ss-caption").css('opacity','0');
					} else {
						$('#'+uniqueId).find(".pl-ss-caption").css('opacity','1').html(nextCaption);
					}
				}
			}

			// You want some Nav arrows? You got 'em
			if (options.navigation == 'true') {
				$(that).append('<div class="pl-pl-ss-nav-cnt"><a href="#" class="pl-ss-prev pl-ss-prev-on">' + options.prevText + '</a><a href="#" class="pl-ss-next pl-ss-next-on">' + options.nextText + '</a></div>');
			}

			// How 'bout some dots? We got dots.
			if (options.pagination == 'true') {
				$(that).append('<div class="pl-ss-paginate-wrap"><div class="pl-ss-paginate"></div></div>');
				$(".pl-ss-slide",that).each(function() {
					$('.pl-ss-paginate', that).append('<a href="#"></a>');
				});
				if (options.autoPlay == "true") {
					$('.pl-ss-paginate a:last', that).addClass("pl-ss-paginate-current");
				} else {
					$('.pl-ss-paginate a:first', that).addClass("pl-ss-paginate-current");
				}
			}

			// Update pagination forward
			function paginationUpdate() {
				var total = $(that).find('.pl-ss-paginate a').length;
				var	current = $(that).find('a.pl-ss-paginate-current').index();
				var next = current + 1;				
				if (next >= total) {
					$(that).find('a.pl-ss-paginate-current').removeClass();
					$(that).find('.pl-ss-paginate a:eq(0)').addClass('pl-ss-paginate-current');
				} else {
					$(that).find('a.pl-ss-paginate-current').removeClass();
					$(that).find('.pl-ss-paginate a:eq('+ next +')').addClass('pl-ss-paginate-current');
				}
			}

			// Update pagination backward
			function paginationUpdateBack() {
				var total = $(that).find('.pl-ss-paginate a').length;
				var	current = $(that).find('a.pl-ss-paginate-current').index();
				var next = current - 1;				
				if (next <= -2) {
					$(that).find('a.pl-ss-paginate-current').removeClass();
					$(that).find('.pl-ss-paginate a:eq('+total+')').addClass('pl-ss-paginate-current');
				} else {
					$(that).find('a.pl-ss-paginate-current').removeClass();
					$(that).find('.pl-ss-paginate a:eq('+ next +')').addClass('pl-ss-paginate-current');
				}
			}

			// Autoplay Function
			var autoPlay = function () {
				// Crapbag (<=IE9) detector
				if (document.all && !window.atob){
					that.crossFade();
				} else if ($('#' + uniqueId).find('.pl-ss-slide:eq(-2) img').attr('data-effect')){
					var selectedEffect = $('#' + uniqueId).find('.pl-ss-slide:eq(-2) img').attr('data-effect');
					that[selectedEffect]();
				} else {
					effectArray = options.effect.split(',');
					var effect = effectArray[Math.floor(Math.random() * effectArray.length)];
					that[effect]();
				}
				captionUpdate();
				paginationUpdate();
			}

			// Autoplay Interval
			if (options.autoPlay == 'true') {
				autoPlay();
				var playInterval = setInterval(autoPlay, fullTime);
			}

			// Pause on Nav hover
			$('.pl-ss-prev, .pl-ss-next, .pl-ss-paginate', that).mouseover(function() {
				clearInterval(playInterval);
			}).mouseout(function() {
				playInterval = setInterval(autoPlay, fullTime);
			});

			// Navigation Forward
			$('#'+uniqueId).on('click', '.pl-ss-next-on', function(event) {
				$('.pl-ss-next-on', that).removeClass('pl-ss-next-on');
				// Fade out last
				$(that).find('.pl-ss-slide:last').css({
					'transition':'all '+options.transitionDuration+'ms',
					'opacity':'0'
				});			
				captionUpdate();
				paginationUpdate();
				setTimeout(function(){
					// Wait for fade, then sort & animate next
					$(that).find('.pl-ss-slide:last').prependTo($(".pl-ss-slide-stage", that)).css({
						'opacity':'1',
						'transform':'none'
					});
					$(that).find('.pl-ss-slide:last').css({
						'transition': 'all ' + options.effectDuration + 'ms ' + options.effectEasing +'',
						'transform':'scale(1)  rotate(0deg)'
					});
					$('.pl-ss-next', that).addClass('pl-ss-next-on');
				}, options.transitionDuration);
				event.preventDefault();
			});

			// Navigation Backward
			$('#'+uniqueId).on('click', '.pl-ss-prev-on', function(event) {
				$('.pl-ss-prev-on', that).removeClass('pl-ss-prev-on');
				// Fade out last
				$('#'+uniqueId).find(".pl-ss-slide:first").css({
					'transition':'none',
					'opacity':'0'
				}).appendTo('#'+uniqueId+' .pl-ss-slide-stage');
				$('#'+uniqueId).find('.pl-ss-slide:last').css('opacity');
				$('#'+uniqueId).find('.pl-ss-slide:last').css({
					'transition':'all '+options.transitionDuration+'ms',
					'opacity':'1'
				});
				captionUpdateBack();
				paginationUpdateBack();
				setTimeout(function(){
					$('.pl-ss-prev').addClass('pl-ss-prev-on');
					
				}, options.transitionDuration);
				event.preventDefault();
			});

			// Disabled nav 
			$('#'+uniqueId).on('click', '.pl-ss-prev, .pl-ss-next', function(event) {
				event.preventDefault();
			});

			// Pagination Clicking
			$('#'+uniqueId).on('click', '.pl-ss-paginate a', function(event) {
				var dotClicked = $(this).index(); // 0 indexed
				var currentDot = $('#'+uniqueId+' .pl-ss-paginate-current').index(); // 0 indexed

				if (dotClicked < currentDot) {
					var iterate = (currentDot - dotClicked);
					for (var i = 0; i < iterate; i++) {
						$('#'+uniqueId).find('.pl-ss-slide:first').appendTo('#'+uniqueId+' .pl-ss-slide-stage');
					}
				} else if (dotClicked > currentDot) {
					var iterate = (dotClicked - currentDot);
					for (var i = 0; i < iterate; i++) {
						$('#'+uniqueId).find('.pl-ss-slide:last').prependTo('#'+uniqueId+' .pl-ss-slide-stage');
					}
				}
				$('#'+uniqueId).find('.pl-ss-paginate-current').removeClass();
				$('#'+uniqueId).find('.pl-ss-paginate a:eq('+dotClicked+')').addClass('pl-ss-paginate-current');
				var nextCaption = $('#'+uniqueId).find('.pl-ss-slide:eq(-1) img').prop('alt');
				if (!nextCaption) {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','0');
				} else {
					$('#'+uniqueId).find(".pl-ss-caption").css('opacity','1').html(nextCaption);
				}
				event.preventDefault();
			});
			
		}
	});
})(jQuery);