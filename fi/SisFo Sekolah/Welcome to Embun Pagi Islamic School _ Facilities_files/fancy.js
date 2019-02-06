$(document).ready(function() {
			
			$("a#example4").fancybox({
				'padding'		: 5,
				'opacity'		: true,
				'overlayShow'	: true,
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'none'
			});

			$(".various3").fancybox({
				'padding'			: 5,
				'width'				: '40%',
				'height'			: '80%',
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic',
				'type'				: 'iframe'
			});
			
			$(document).ready(function(){
               $("#fancybox-close").click(function(){
               }); 
            });
			
		});