<?php

namespace SeriouslySimplePodcasting\Handlers;

class Options_Handler {

	/**
	 * Build options fields
	 *
	 * @return array Fields to be displayed on options page.
	 */
	public function options_fields() {
		global $wp_post_types;

		$post_type_options = array();

		// Set options for post type selection.
		foreach ( $wp_post_types as $post_type => $data ) {

			$disallowed_post_types = array(
				'page',
				'attachment',
				'revision',
				'nav_menu_item',
				'wooframework',
				'podcast',
			);
			if ( in_array( $post_type, $disallowed_post_types, true ) ) {
				continue;
			}

			$post_type_options[ $post_type ] = $data->labels->name;
		}

		$options = array();

		$subscribe_options_array = $this->get_subscribe_field_options();

		$options['general'] = array(
			'title'       => __( 'General', 'seriously-simple-podcasting' ),
			'description' => __( 'General Settings', 'seriously-simple-podcasting' ),
			'fields'      => $subscribe_options_array,
		);

		$options = apply_filters( 'ssp_options_fields', $options );

		return $options;
	}

	/**
	 * Builds the array of field settings for the subscribe links, based on the options stored in the options table.
	 * // @todo this is duplicated from the settings handler, so it should probably be placed in it's own class somewhere
	 *
	 * @return array
	 */
	public function get_subscribe_field_options() {
		$subscribe_field_options = array();
		$subscribe_links_options = get_option( 'ss_podcasting_subscribe_options', array() );
		if ( empty( $subscribe_links_options ) ) {
			return $subscribe_field_options;
		}

		$count = 1;
		foreach ( $subscribe_links_options as $key => $title ) {
			$subscribe_field_options[] = array(
				'id'          => 'subscribe_option_' . $count,
				// translators: %s: Service title eg iTunes
				'label'       => sprintf( __( 'Subscribe option %s', 'seriously-simple-podcasting' ), $count ),
				// translators: %s: Service title eg iTunes
				'description' => sprintf( __( 'Subscribe option %s.', 'seriously-simple-podcasting' ), $count ),
				'type'        => 'text',
				'default'     => $title,
				// translators: %s: Service title eg iTunes
				'placeholder' => sprintf( __( 'Subscribe option %s', 'seriously-simple-podcasting' ), $count ),
				'callback'    => 'wp_strip_all_tags',
				'class'       => 'text',
			);
			$count++;
		}

		return apply_filters( 'ssp_subscribe_field_options', $subscribe_field_options );
	}

}
