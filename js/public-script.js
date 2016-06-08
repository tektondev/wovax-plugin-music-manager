var wovax_mm = {
	
	init:function(){
		
		wovax_mm.library.bind_events();
		
	},
	
	
	
	library: {
		
		bind_events: function(){
		
			jQuery('#wovax-mm-library > nav > a').on('click', function( event ){ event.preventDefault(); wovax_mm.library.chng_sec( jQuery( this ) ); });
			
			jQuery('#wovax-mm-library-search form .mm-search-text input').keyup( function(){ wovax_mm.library.do_search( jQuery( this ) ); })
			
		},
		
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
	
	
	
	
	
}
wovax_mm.init();