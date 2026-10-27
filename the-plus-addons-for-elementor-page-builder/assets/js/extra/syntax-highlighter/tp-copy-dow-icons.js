/*unfold*/
(function ($) {
	"use strict";
	var WidgetshHandler = function($scope, $) {
		var container = $scope.find('.tp-code-highlighter.tpcpicon'),
		data_copy = container.data("prismjs-copy"),
		data_copyed = container.data("prismjs-copy-success"),
		data_copyicon = container.data("copyicon"),
		data_copiedbtnicon = container.data("copiedbtnicon"),
		tpdowicon = $scope.find('.tp-code-highlighter.tpdowicon'),
		data_dowtext = tpdowicon.data("download-text"),
		data_dowicon = tpdowicon.data("download-iconsh");
		
		/* Label as text, icon as the server-rendered markup: the label is
		   author text and must never be parsed as HTML. */
		var setLabel = function($el, text, icon) {
			$el.text(text || '');
			if (icon) {
				$el.append(icon);
			}
		};

		if(container.length){
			setLabel(container.find('.copy-to-clipboard-button'), data_copy, data_copyicon);
			$( ".copy-to-clipboard-button" ).on( "click", function() {
				setLabel(container.find('.copy-to-clipboard-button'), data_copyed, data_copiedbtnicon);
				setTimeout(function(){ 
					setLabel(container.find('.copy-to-clipboard-button'), data_copy, data_copyicon);
				}, 1500);
			});
		}
		
		if(tpdowicon.length){			
			setLabel(tpdowicon.find('.toolbar-item a'), data_dowtext, data_dowicon);			
		}
		
	};	
	
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/tp-syntax-highlighter.default', WidgetshHandler);
	});
})(jQuery);