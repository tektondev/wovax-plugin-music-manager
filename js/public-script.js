var wovax_mm = {
	
	init:function(){
		
		wovax_mm.library.bind_events();
			
		wovax_mm.modal.init();
		
	}, // end init
	
	
	library: {
		
		bind_events: function(){
		
			jQuery('#wovax-mm-library > nav > a').on('click', function( event ){ event.preventDefault(); wovax_mm.library.chng_sec( jQuery( this ) ); });
			
			jQuery('#wovax-mm-library-search form .mm-search-text input').keyup( function(){ wovax_mm.library.do_search( jQuery( this ) ); });
			
			jQuery('#wovax-mm-library-search form').on('submit' , function( event ){ event.preventDefault(); wovax_mm.library.do_scroll( jQuery('#wovax-mm-search-results') )}); 
			
			jQuery('#wovax-mm-library-search form').on('submit' , function( event ){ event.preventDefault(); wovax_mm.library.do_scroll( jQuery('#wovax-mm-search-results') )});
			
			jQuery('#wovax-mm-library-az-index > nav > ul > li > a').on('click' , function( event ){ 
				event.preventDefault(); 
				var targ = jQuery( '[name="' + jQuery( this ).data('idx') + '"]');
				wovax_mm.library.do_scroll( targ )});
			
		},
		
		do_scroll : function( target ){
			
			jQuery('html, body').animate({scrollTop: ( target.offset().top - 50 ) }, 500);
			
		}, // end do scroll submit
		
		chng_sec : function( ic ){
			
			if ( ! ic.hasClass('active') ){
				
				ic.addClass('active').siblings().removeClass('active');
				
				jQuery('.wovax-mm-library-section').eq( ic.index() ).show().siblings('.wovax-mm-library-section').hide();
				
			} // end if
			
		},
		
		do_search: function( inpt ){
			
			
			
			var val = inpt.val();
			
			if ( val.length > 2 ){
				
				
				
				jQuery.get(
					wovax_mm_ajax_url + '?wovax_mm_ajax=search',
					{ s : val },
					function( response ){
						
						var results = jQuery('.mm-search-results');
						
						results.empty();
						
						wovax_mm.library.set_resutls( response );
						
					},
					'html'
				);
				
			} // end if
			
		},
		
		set_resutls: function( response ){
			
			jQuery('.mm-search-results').append( response );
			
		} // end set_results
		
	}, // end library
	
	modal : {
		
		bg : false,
			
		frame : false,
		
		modal_html : '<div id="wovax-mm-modal-bg" class="wovax-close-modal-action" style="display:none"></div><div id="wovax-mm-modal-frame" style="display:none"><div class="wovax-mm-inner-wrapper"><header>' + 
					 '<a href="#" class="wovax-close-modal-action"></a></header><div class="wovax-mm-modal-content"></div></div></div>',
		
		init: function(){
			
			if ( jQuery('.wovax-mm-modal-item').length > 0 ){
				
				jQuery('body').append( wovax_mm.modal.modal_html );
				
				wovax_mm.modal.bg = jQuery('#wovax-mm-modal-bg');
				
				wovax_mm.modal.frame = jQuery('#wovax-mm-modal-frame');
				
				wovax_mm.modal.bind_events( false );
				
			} // end if
			
		}, // end init
		
		bind_events: function( container ){
			
			if ( false === container ) container = jQuery('body');
			
			container.on('click' , '.wovax-mm-modal-item' , function( event ){ event.preventDefault(); wovax_mm.modal.show( jQuery( this ) ); });	
			
			container.on('click' , '.wovax-close-modal-action' , function( event ){ event.preventDefault(); wovax_mm.modal.hide(); });	
			
		}, // bind_events
		
		show : function( m_item ){
			
			wovax_mm.modal.set_height();
			
			var c = m_item.siblings('.wovax-mm-modal-item-content').val();
			
			wovax_mm.modal.frame.find('.wovax-mm-modal-content').html( c );
			
			wovax_mm.modal.bg.fadeIn('fast',function(){
				
				wovax_mm.modal.frame.show();
				
				});
			
		}, // end show
		
		hide : function( m_item ){
			
			wovax_mm.modal.frame.hide();
			
			wovax_mm.modal.frame.find('.wovax-mm-modal-content').html( '' );
			
			wovax_mm.modal.bg.fadeOut('fast');
			
		}, // end hide
		
		set_height: function(){
			
			var wh = jQuery(window).scrollTop();
			
			wovax_mm.modal.frame.css( 'top' , wh + 100 );
			
		}, // end_set_height
		
	}, // end modal
	
	
	
	
	
}
wovax_mm.init();