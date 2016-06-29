var wovax_mm = {
	
	wrap: '',
	
	init: function(){
		
		wovax_mm.wrap = jQuery('#wovax-music-selector-form');
		
		wovax_mm.form.bind_events();
		
		wovax_mm.form.apply_sort( wovax_mm.wrap );
		
		wovax_mm.lb.init();
		
	}, // end init
	
	form: {
		
		bind_events: function(){
			
			jQuery('body').on('click','#mm-insert-music', function( event ){ event.preventDefault(); wovax_mm.lb.show();});
			
			jQuery('body').on('click','.mm-close-modal', function( event ){ event.preventDefault(); wovax_mm.lb.hide();});
			
			wovax_mm.wrap.find('input[name="s_term"]').keyup( function(){ wovax_mm.form.do_search( true , jQuery( this ) ); });
			
			wovax_mm.wrap.find('footer input[type="submit"]').on( 'click' , function( event ){ event.preventDefault(); wovax_mm.form.insert(); });
			
			wovax_mm.wrap.find('#wovax-music-selector-results').on( 'click' , '.mm-add-music' , function( event ){ event.preventDefault(); wovax_mm.form.add_item( jQuery( this ) ); });
			
			wovax_mm.wrap.find('#wovax-music-selector-items').on( 'click' , '.mm-remove-music' , function( event ){ event.preventDefault(); wovax_mm.form.remove_item( jQuery( this ) ); });
			
			wovax_mm.wrap.find('input[name="music_title"]').on('keyup', function(){ wovax_mm.form.update_shortcode(); });
			
			wovax_mm.wrap.find('select[name="music_category"]').on('change', function(){ wovax_mm.form.update_shortcode(); wovax_mm.form.do_search( false , wovax_mm.wrap.find('input[name="s_term"]') ); });
			
			wovax_mm.wrap.find('input.mm-selector-search').on( 'click' , function( event ){ 
				event.preventDefault(); 
				wovax_mm.form.do_search( false , wovax_mm.wrap.find('input[name="s_term"]') ) 
				});
			
		}, // end bind_events
		
		apply_sort: function( wrap ){
			
			wrap.find('#wovax-music-selector-items.mm-items').sortable({
				connectWith: '.mm-items',
				stop: function( event, ui ) { wovax_mm.form.update_shortcode();}
			}).disableSelection();
			
			//wrap.find('.wovax-music-selector-items').sortable().disableSelection();
			
		}, // end apply_sort
		
		do_search: function( is_dynamic , inpt ){
			
			var val = inpt.val();
			
			if ( is_dynamic && ( val.length < 2 && val.length > 0 ) ) return;
			
			var data = wovax_mm.wrap.find('fieldset input, fieldset select').serialize();
			
			wovax_mm.form.get_search( data );
			
		}, //end do search
		
		get_search: function( data ){
			
			var r_wrap = wovax_mm.wrap.find('#wovax-music-selector-results');
			
			r_wrap.addClass('active');
			
			data += '&action=music_selector_search';
			
			jQuery.post(
			
				ajaxurl,
				
				//{ s : val , action : 'music_selector_search' },
				
				data,
				
				function( response ){
					
					r_wrap.empty();
					
					setTimeout( function(){ r_wrap.removeClass('active'); }, 100 );
					
					r_wrap.append( response );
					
				},
				
				'html'
			);
			
		}, // end get_search 
		
		update_shortcode: function(){
			
			var sel = wovax_mm.wrap.find('#wovax-music-selector-items');
			
			var sc = wovax_mm.wrap.find('#wovax-music-selector-shortcode');
			
			var st = wovax_mm.wrap.find('input[name="music_title"]').val();
			
			var ids = new Array();
			
			sel.children().each( function(){
				
				ids.push( jQuery( this ).find('input').val() );
				
			});
			
			var code = '[musicitems';
			
			if ( st ){
				
				code += ' title="' + st + '"';
				
			} // end if
			
			if ( ids.length > 0 ){
				
				code += ' ids="' + ids.join(',') + '"';
				
			} // end if
			
			sc.html( code + ']' );
			
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
		
		remove_item: function( ic ){
			
			ic.closest('li').slideUp('fast' , function(){ 
				jQuery( this ).remove(); 
				wovax_mm.form.update_shortcode();
				});
			
			
			
		}, // end remove item
		
	}, // end form
	
	lb : {
		
		bg: false,
		
		frame: false, 
		
		init: function(){
			
			wovax_mm.lb.bg = jQuery('#wovax-mm-selector-bg');
		
			wovax_mm.lb.frame = jQuery('#wovax-mm-selector'); 
			
		},
		
		show: function(){
			
			wovax_mm.lb.bg.fadeIn('fast');
			
			wovax_mm.lb.frame.addClass('active');
			
			wovax_mm.lb.set_height();
			
		}, // end show
		
		hide: function(){
			
			wovax_mm.lb.bg.fadeOut('fast');
			
			wovax_mm.lb.frame.removeClass('active');
			
			wovax_mm.lb.frame.css('top', '-9999rem');
			
		}, // end show
		
		set_height: function(){
			
			wovax_mm.lb.frame.css( 'top' , jQuery( window ).scrollTop() ); 
			
		}
		
	}, // end lb
	
} // end wovax_mm

jQuery(document).ready( function(){ wovax_mm.init() });