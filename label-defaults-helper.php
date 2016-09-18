<?php
/**
 * Plugin Name: Label Defaults Helper
 * 
 * Author: Toshimichi Mimoto
 */

PostTypeLabelDefaults::instance();

class PostTypeLabelDefaults {

	protected static $label_formats = [
		'name'                  => null,
		'singular_name'         => null,
		'add_new'               => null,
		'add_new_item'          => [ 'singular', 'Add New %s' ],
		'edit_item'             => [ 'singular', 'Edit %s' ],
		'new_item'              => [ 'singular', 'New %s' ],
		'view_item'             => [ 'singular', 'View %s' ],
		'search_items'          => [ 'plural', 'Search %s' ],
		'not_found'             => [ 'plural', 'No %s found.' ],
		'not_found_in_trash'    => [ 'plural', 'No %s found in Trash.' ],
		'all_items'             => [ 'plural', 'All %s' ],
		'parent_item_colon'     => [ 'singular', 'Parent %s:' ],
		'uploaded_to_this_item' => [ 'singular', 'Uploaded to this %s' ],
		'featured_image'        => null,
		'set_featured_image'    => [ 'featured_image', 'Set %s' ],
		'remove_featured_image' => [ 'featured_image', 'Remove %s' ],
		'use_featured_image'    => [ 'featured_image', 'Use as %s' ],
		'archives'              => [ 'singular', '%s Archives' ],
		'insert_into_item'      => [ 'singular', 'Insert into %s' ],
		'filter_items_list'     => [ 'plural', 'Filter %s list' ],
		'items_list_navigation' => [ 'plural', '%s list navigation' ],
		'items_list'            => [ 'plural', '%s list' ]
	];

	public static function instance() {
		static $instance;
		$instance ?: $instance = new static;
	}

	protected function __construct() {
		add_filter( 'register_post_type_args', [ $this, 'post_type_args' ], 10, 2 );
	}

	public function post_type_args( $args, $name ) {
		if ( ! isset( $args['labels'] ) ) {
			$args['labels'] = [];
		}
		if ( ! is_array( $args['labels'] ) ) {
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
		foreach ( self::$label_formats as $key => $format ) {
			if ( ! isset( $labels[$key] ) || ! filter_var( $labels[$key] ) ) {
				if ( is_array( $format ) && ( $string = ${$format[0]} ) ) {
					$labels[$key] = esc_html( sprintf( __( $format[1], 'post-type-label-defaults', 'test', 'label-defaults-helper', 'label-default-helper' ), $string ) );
				}
			}
		}
		return $labels;
	}

	/**
	 * @static
	 * @access protected
	 *
	 * @param  string $string
	 * @return string
	 */
	protected static function labelize( $string ) {
		return trim( ucwords( str_replace( [ '-', '_' ], ' ', $string ) ) );
	}

}
