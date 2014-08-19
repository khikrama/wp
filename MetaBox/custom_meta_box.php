<?php


function ik_cpmb_add_meta_box(){
	add_meta_box(
		'ik_cpmb_county',				//The ID for the meta box
		'Country Name', 				//The title for the meta box
		'ik_cpmb_display_meta_box',		//The function for rendering the markup
		'page',							//Will only display in page
		'normal',						//Where the meta box will appear
		'high'							//Where the meta box display
	);
}
add_action( 'add_meta_boxes', 'ik_cpmb_add_meta_box' );


function ik_cpmb_display_meta_box($post){
	wp_nonce_field( basename( __FILE__ ), 'ik-cpmb-nonce-field' );
	$html = '<label>';
		$html .= 'Country Name: ';
	$html .= '</label>';
	$html .= '<input type="text" id="country_name" name="country_name" value="' . get_post_meta( $post->ID, 'country_name', true ) . '">';

	echo $html;
}


function ik_cpmb_save_meta_box_data($page_id){
	if( ik_cpmb_user_can_save($page_id, 'ik-cpmb-nonce-field') ){

		if ( isset( $_POST['country_name'] ) && 0 < count( strlen( trim( $_POST['country_name'] ) ) ) ) {
			
			$country_name = stripslashes( strip_tags( $_POST['country_name'] ) );
			update_post_meta( $page_id, 'country_name', $country_name );
			
		}

	}

}
add_action( 'save_post', 'ik_cpmb_save_meta_box_data' );

function ik_cpmb_user_can_save($page_id, $nonce){
	$is_autosave = wp_is_post_autosave( $page_id );
	$is_revision = wp_is_post_revision( $page_id );
	$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], basename( __FILE__ ) ) );

	return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
}