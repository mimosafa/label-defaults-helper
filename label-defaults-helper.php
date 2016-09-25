<?php
/**
 * Plugin Name: Label Defaults Helper
 * Description: Generate Custom Post Type Labels for Admin UI.
 * Author:      Toshimichi Mimoto <mimosafa@gmail.com>
 * Author URI:  http://mimosafa.me
 * Text Domain: label-defaults-helper
 * Domain Path: /languages
 * Version:     0.1.0
 */

PostTypeLabelDefaults::instance();

class PostTypeLabelDefaults {

	public static function instance() {
		static $instance;
		$instance ?: $instance = new static;
	}

	protected function __construct() {
		add_filter( 'register_post_type_args', [ $this, 'post_type_args' ], 10, 2 );
	}

	public function post_type_args( $args, $name ) {
		if ( isset( $args['_builtin'] ) && $args['_builtin'] ) {
			return $args;
		}
		if ( ! isset( $args['labels'] ) || ! is_array( $args['labels'] ) ) {
			$args['labels'] = [];
		}
		if ( ! isset( $args['labels']['name'] ) || ! filter_var( $args['labels']['name'] ) ) {
			$args['labels']['name'] = isset( $label ) && filter_var( $label ) ? $label : self::labelize( $name );
		}
		if ( ! isset( $args['labels']['singular_name'] ) || ! filter_var( $args['labels']['singular_name'] ) ) {
			$args['labels']['singular_name'] = $args['labels']['name'];
		}
		$args['labels'] = $this->gen( $args['labels'], $name );
		return $args;
	}

	protected function gen( $labels, $name ) {
		$singular = $labels['singular_name'];
		$plural   = $labels['name'];
		$featured_image = isset( $labels['featured_image'] ) && filter_var( $labels['featured_image'] ) ? $labels['featured_image'] : null;
		foreach ( self::getLabelFormats() as $key => $format ) {
			if ( ! isset( $labels[$key] ) || ! filter_var( $labels[$key] ) ) {
				if ( is_array( $format ) && ( $string = ${$format[0]} ) ) {
					$labels[$key] = esc_html( sprintf( $format[1], $string ) );
				}
			}
		}
		return $labels;
	}

	/**
	 * @static
	 * @access public
	 *
	 * @param  string $string
	 * @return string
	 */
	public static function labelize( $string ) {
		return trim( ucwords( str_replace( [ '-', '_' ], ' ', $string ) ) );
	}

	/**
	 * Label Formats
	 *
	 * @return array
	 */
	public static function getLabelFormats() {
		return apply_filters( 'post_type_label_defaults', [
			'name'                  => null,
			'singular_name'         => null,
			'add_new'               => null,
			'add_new_item'          => [ 'singular', __( 'Add New %s', 'label-defaults-helper' ) ],
			'edit_item'             => [ 'singular', __( 'Edit %s', 'label-defaults-helper' ) ],
			'new_item'              => [ 'singular', __( 'New %s', 'label-defaults-helper' ) ],
			'view_item'             => [ 'singular', __( 'View %s', 'label-defaults-helper' ) ],
			'search_items'          => [ 'plural', __( 'Search %s', 'label-defaults-helper' ) ],
			'not_found'             => [ 'plural', __( 'No %s found.', 'label-defaults-helper' ) ],
			'not_found_in_trash'    => [ 'plural', __( 'No %s found in Trash.', 'label-defaults-helper' ) ],
			'all_items'             => [ 'plural', __( 'All %s', 'label-defaults-helper' ) ],
			'parent_item_colon'     => [ 'singular', __( 'Parent %s:', 'label-defaults-helper' ) ],
			'uploaded_to_this_item' => [ 'singular', __( 'Uploaded to this %s', 'label-defaults-helper' ) ],
			'featured_image'        => null,
			'set_featured_image'    => [ 'featured_image', __( 'Set %s', 'label-defaults-helper' ) ],
			'remove_featured_image' => [ 'featured_image', __( 'Remove %s', 'label-defaults-helper' ) ],
			'use_featured_image'    => [ 'featured_image', __( 'Use as %s', 'label-defaults-helper' ) ],
			'archives'              => [ 'singular', __( '%s Archives', 'label-defaults-helper' ) ],
			'insert_into_item'      => [ 'singular', __( 'Insert into %s', 'label-defaults-helper' ) ],
			'filter_items_list'     => [ 'plural', __( 'Filter %s list', 'label-defaults-helper' ) ],
			'items_list_navigation' => [ 'plural', __( '%s list navigation', 'label-defaults-helper' ) ],
			'items_list'            => [ 'plural', __( '%s list', 'label-defaults-helper' ) ],
		] );
	}

}
