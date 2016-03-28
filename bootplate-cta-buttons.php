<?php 

/**
 * Plugin Name: Bootplate CTA Buttons
 * Plugin URI: http://bootplate.jdmdigital.co/plugins/call-to-action/
 * Description: There are many cases when theme developers may want the ability to add a call-to-action (or CTA) button to their theme that's easily editable from the WordPress backend. This reusable plugin does just that, and nothing more.
 * Version: 0.1
 * Author: JDM Digital
 * Author URI: http://jdmdigital.co
 * License: GPLv2 or later
 * GitHub Plugin URI: https://github.com/jdmdigital/bootplate-cta-buttons/
 * GitHub Branch: master
 */

// Adds a box to the main column on the Post edit screen.
if(!function_exists('bootplate_add_meta_box')){
	function bootplate_add_meta_box() {
	
		$screens = array( 'page' );
	
		foreach ( $screens as $screen ) {
	
			add_meta_box(
				'bootplate_sectionid',
				'Call to Action (CTA) Buttons',
				'bootplate_meta_box_callback',
				$screen
			);
		}
	}
	add_action( 'add_meta_boxes', 'jdm_add_meta_box' );
}

// Prints the box content.
if(!function_exists('bootplate_meta_box_callback')){
	function bootplate_meta_box_callback( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'bootplate_meta_box', 'bootplate_meta_box_nonce' );
	
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$cta1url   = get_post_meta( $post->ID, 'cta1url',   true ); // the URL
		$cta1txt   = get_post_meta( $post->ID, 'cta1txt',   true ); // the text
		$cta1class = get_post_meta( $post->ID, 'cta1class', true ); // the class
		
		$cta2url   = get_post_meta( $post->ID, 'cta2url',   true ); // the URL
		$cta2txt   = get_post_meta( $post->ID, 'cta2txt',   true ); // the text
		$cta2class = get_post_meta( $post->ID, 'cta2class', true ); // the class
	
		echo '<p style="margin-bottom:1em;">Setup custom call to action (CTA) buttons here. Just paste the URL (starting http:// or https://) and enter the text the button should say. If you want to remove the button, just remove the URL and the button will disappear when you click "Update."</p>';
		echo '<table class="form-table"><tbody>';
		echo '<tr>';
		echo '<th scope="row"><label for="ctaurl">Button Left Link:</label></th>';
		echo '<td><input type="url" id="cta1url" class="large-text" name="cta1url" value="' . esc_attr( $cta1url ) . '" placeholder="http://" /></td>';
		echo '</tr><tr>';
		echo '<th scope="row"><label for="ctatxt">Button Left Text:</label></th>';
		echo '<td><input type="text" id="cta1txt" class="normal-text" name="cta1txt" value="' . esc_attr( $cta1txt ) . '" placeholder="Click Here" style="" /></td>';
		echo '</tr><tr>';
		echo '<th scope="row"><label for="ctaurl">Button Left Class(es):  <small><a href="https://github.com/jdmdigital/jdm-cta-buttons" target="_blank" rel="nofollow">{help?}</a></small></label></th>';
		echo '<td><input type="text" id="cta1class" class="normal-text" name="cta1class" value="' . esc_attr( $cta1class ) . '" placeholder="btn-default" /></td>';
		echo '</tr><tr>';
		echo '<th scope="row"><label for="ctaurl">Button Right Link:</label></th>';
		echo '<td><input type="url" id="cta2url" class="large-text" name="cta2url" value="' . esc_attr( $cta2url ) . '" placeholder="http://" /></td>';
		echo '</tr><tr>';
		echo '<th scope="row"><label for="ctatxt">Button Right Text:</label></th>';
		echo '<td><input type="text" id="cta2txt" class="normal-text" name="cta2txt" value="' . esc_attr( $cta2txt ) . '" placeholder="Click Here" style="" /></td>';
		echo '</tr><tr>';
		echo '<th scope="row"><label for="ctaurl">Button Right Class(es):  <small><a href="https://github.com/jdmdigital/jdm-cta-buttons" target="_blank" rel="nofollow">{help?}</a></small></label></th>';
		echo '<td><input type="text" id="cta2class" class="normal-text" name="cta2class" value="' . esc_attr( $cta2class ) . '" placeholder="btn-primary" /></td>';
		echo '</tr>';
		echo '</tbody></table>';
		echo '<p><b>Note:</b> Leave blank to remove the button or buttons.</p>';
	}
}

/**
 * When the post is saved, saves our custom data.
 * @param int $post_id The ID of the post being saved.
 */
function bootplate_save_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['bootplate_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['bootplate_meta_box_nonce'], 'jdm_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	/* OK, it's safe for us to save the data now. */

	// Sanitize user input.
	$cta1url 	= sanitize_text_field( $_POST['cta1url'] );
	$cta1txt 	= sanitize_text_field( $_POST['cta1txt'] );
	$cta1class	= sanitize_text_field( $_POST['cta1class'] );
	
	$cta2url 	= sanitize_text_field( $_POST['cta2url'] );
	$cta2txt 	= sanitize_text_field( $_POST['cta2txt'] );
	$cta2class 	= sanitize_text_field( $_POST['cta2class'] );

	// Add or Update the meta field in the database.
	if ( ! update_post_meta ($post_id, 'cta1url', $cta1url) ) { 
		add_post_meta($post_id, 'cta1url', $cta1url, true );	
	};
	if ( ! update_post_meta ($post_id, 'cta1txt', $cta1txt) ) { 
		add_post_meta($post_id, 'cta1txt', $cta1txt, true );	
	};
	if ( ! update_post_meta ($post_id, 'cta1class', $cta1class) ) { 
		add_post_meta($post_id, 'cta1class', $cta1class, true );	
	};
	
	if ( ! update_post_meta ($post_id, 'cta2url', $cta2url) ) { 
		add_post_meta($post_id, 'cta2url', $cta2url, true );	
	};
	if ( ! update_post_meta ($post_id, 'cta2txt', $cta2txt) ) { 
		add_post_meta($post_id, 'cta2txt', $cta2txt, true );	
	};
	if ( ! update_post_meta ($post_id, 'cta2class', $cta2class) ) { 
		add_post_meta($post_id, 'cta2class', $cta2class, true );	
	};
}
add_action( 'save_post', 'bootplate_save_meta_box_data' );

/*
CTA Plugin Functions
*/

// Checks if the ctaurl post meta field is set/and not empty
if(!function_exists('have_cta1')) {
	function have_cta1(){
		global $page, $post; 
		$cta1url = get_post_meta( $post->ID, 'cta1url', true ); // the URL
		
		if(isset($cta1url) && !empty($cta1url) ) {
			return true;
		} else {
			return false;
		}
	} // end have_cta()
}

if(!function_exists('have_cta2')) {
	function have_cta2(){
		global $page, $post; 
		$cta2url = get_post_meta( $post->ID, 'cta2url', true ); // the URL
		
		if(isset($cta2url) && !empty($cta2url) ) {
			return true;
		} else {
			return false;
		}
	} // end have_cta()
}

if(!function_exists('has_cta1')) {
	function has_cta1(){
		global $page, $post; 
		$cta1url = get_post_meta( $post->ID, 'cta1url', true ); // the URL
		
		if(isset($cta1url) && !empty($cta1url) ) {
			return true;
		} else {
			return false;
		}
	} // end has_cta1()
}

if(!function_exists('has_cta2')) {
	function has_cta2(){
		global $page, $post; 
		$cta2url = get_post_meta( $post->ID, 'cta2url', true ); // the URL
		
		if(isset($cta2url) && !empty($cta2url) ) {
			return true;
		} else {
			return false;
		}
	} // end has_cta()
}

// Returns the HTML CTA link with the parameter class(es)
if(!function_exists('get_cta')) {
	function get_cta($ctanum = 1){
		global $page, $post; 
		
		if($catnum == 2) {
			$ctaurl 	= get_post_meta( $post->ID, 'cta2url', true ); // the URL
			$ctatxt 	= get_post_meta( $post->ID, 'cta2txt', true ); // the text
			$ctaclass	= get_post_meta( $post->ID, 'cta2class', true ); // the class	
		} else {
			$ctaurl 	= get_post_meta( $post->ID, 'cta1url', true ); // the URL
			$ctatxt 	= get_post_meta( $post->ID, 'cta1txt', true ); // the text
			$ctaclass	= get_post_meta( $post->ID, 'cta1class', true ); // the class
		}
		
		if(!isset($ctatxt) || empty($ctatxt) ) {
			$ctatxt = 'Click Here'; // default
		}
		
		if(!isset($ctaclass) || empty($ctaclass) ) {
			$ctaclass = 'btn btn-default'; // default
		}
		
		if(strpos($ctaclass, 'btn ') !== false) {
			$ctaclass = 'btn '.$ctaclass;
		}
		
		if(isset($ctaurl) && !empty($ctaurl) ) {
			return '<a href="'.$ctaurl.'" class="'.$ctaclass.'" role="button">'.$ctatxt.'</a>';
		} 
	} // end get_cta()
}


// Echos get_cta(), basically
if(!function_exists('the_cta') && function_exists('get_cta')) {
	function the_cta($ctanum = 1){		
		echo get_cta($catnum);
	} // end the_cta()
}

if(!function_exists('have_bootplate_btns')) {
	function have_bootplate_btns() {
		if(has_cta2() || has_cta1()) {
			return true;
		} else {
			return false;
		}
	}
}

?>
