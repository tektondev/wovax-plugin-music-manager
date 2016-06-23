var wovax_mm = {
	
	wrap: jQuery('#wovax-music-selector-form'),
	
	init: function(){
		
		wovax_mm.form.bind_events();
		
		wovax_mm.form.apply_sort( wovax_mm.wrap );
		
	}, // end init
	
	form: {
		
		bind_events: function(){
			
			wovax_mm.wrap.find('input[name="s_term"]').keyup( function(){ wovax_mm.form.do_search( true , jQuery( this ) ); });
			
			wovax_mm.wrap.find('footer input[type="submit"]').on( 'click' , function( event ){ event.preventDefault(); wovax_mm.form.insert(); });
			
			wovax_mm.wrap.find('#wovax-music-selector-results').on( 'click' , '.mm-add-music' , function( event ){ event.preventDefault(); wovax_mm.form.add_item( jQuery( this ) ); });
			
		}, // end bind_events
		
		apply_sort: function( wrap ){
			
			wrap.find('.mm-items').sortable({
				connectWith: '.mm-items',
				stop: function( event, ui ) { wovax_mm.form.update_shortcode();}
			}).disableSelection();
			
			//wrap.find('.wovax-music-selector-items').sortable().disableSelection();
			
		}, // end apply_sort
		
		do_search: function( is_dynamic , inpt ){
			
			var val = inpt.val();
			
			if ( is_dynamic && ( val.length < 2 && val.length > 0 ) ) return;
			
			wovax_mm.form.get_search( val );
			
		}, //end do search
		
		get_search: function( val ){
			
			jQuery.post(
			
				ajaxurl,
				
				{ s : val , action : 'music_selector_search' },
				
				function( response ){
					
					var r_wrap = wovax_mm.wrap.find('#wovax-music-selector-results');
					
					r_wrap.empty();
					
					r_wrap.append( response );
					
				},
				
				'html'
			);
			
		}, // end get_search 
		
		update_shortcode: function(){
			
			var sel = wovax_mm.wrap.find('#wovax-music-selector-items');
			
			var sc = wovax_mm.wrap.find('#wovax-music-selector-shortcode');
			
			var ids = new Array();
			
			sel.children().each( function(){
				
				ids.push( jQuery( this ).find('input').val() );
				
			});
			
			sc.html( '[musicitems ids="' + ids.join(',') + '"]' );
			
		}, // end update_shortcode
		
		insert: function(){
			
			var sc = wovax_mm.wrap.find('#wovax-music-selector-shortcode');
			
			var ed = tinyMCE.get('content');
			
			ed.execCommand('mceInsertContent', 0, sc.html() );

		}, // end insert
		
		add_item: function( ic ){
			
			var sel = wovax_mm.wrap.find('#wovax-music-selector-items');
			
			sel.append( ic.closest('li').clone() );
			
			wovax_mm.form.update_shortcode();
			
		}, // end add item
		
	}, // end form
	
} // end wovax_mm

wovax_mm.init();