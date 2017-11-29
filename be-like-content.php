<?php
/**
 * Plugin Name: BE Like Content
 * Plugin URI:  https://github.com/billerickson/be-like-content
 * Description: Allow users to like content
 * Author:      Bill Erickson
 * Author URI:  https://www.billerickson.net
 * Version:     1.0.0
 *
 * BE Like Content is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * BE Like Content is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BE Like Content. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    BE_Like_Content
 * @author     Bill Erickson
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2017
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main class
 *
 * @since 1.0.0
 * @package BE_Like_Content
 */
final class BE_Like_Content {

	/**
	 * Instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Settings
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $settings = array();

	/**
	 * Class Instance.
	 *
	 * @since 1.0.0
	 * @return BE_Like_Content
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof BE_Like_Content ) ) {
			self::$instance = new BE_Like_Content;
			add_action( 'init', array( self::$instance, 'init' ) );
		}
		return self::$instance;
	}

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 */
	function init() {

		$this->settings = apply_filters( 'be_like_content_settings', $this->default_settings() );

		add_action( 'wp_ajax_be_like_content',        array( $this, 'update_count' ) );
		add_action( 'wp_ajax_nopriv_be_like_content', array( $this, 'update_count' ) );
	}

	/**
	 * Default Settings
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function default_settings() {
		return array(
			'zero' => 'Like the post? Give it a +1',
			'one' => '{count}',
			'many' => '{count}',
			'post_types' => array( 'post' ),
		);
	}

	/**
	 * Update Count
	 *
	 * @since 1.0.0
	 */
	function update_count() {

		$post_id = intval( $_POST['post_id'] );

		if( ! $post_id )
			wp_send_json_error( 'No Post ID' );

		if( !in_array( get_post_type( $post_id ), $this->settings['post_types'] ) )
			wp_send_json_error( 'This post type does not support likes' );


		$count = $this->count( $post_id );
		$count++;
		update_post_meta( $post_id, '_be_like_content', $count );

		$data = $this->maybe_count( $post_id, $count );
		wp_send_json_success( $data );

		wp_die();
	}

	/**
	 * Display
	 *
	 * @since 1.0.0
	 */
	function display() {

		if( ! is_singular() || !in_array( get_post_type(), $this->settings['post_types'] ) )
			return;

		echo '<a href="#" class="be-like-content" data-post-id="' . get_the_ID() . '">' . $this->maybe_count( get_the_ID() ) . '</a>';
	}

	/**
	 * Maybe Count
	 *
	 * @since 1.0.0
	 */
	function maybe_count( $post_id = '', $count = false ) {

		if( empty( $post_id ) )
			return;

		$count = $count ? intval( $count ) : $this->count( $post_id );
		$text = 0 == $count ? $this->settings['zero'] : _n( $this->settings['one'], $this->settings['many'], $count );
		$text = apply_filters( 'be_like_content_display_count', $text );
		return str_replace( '{count}', $count, $text );
	}

	/**
	 * Count
	 *
	 * @since 1.0.0
	 */
	function count( $post_id = '' ) {

		if( empty( $post_id ) )
			return;

		return intval( get_post_meta( $post_id, '_be_like_content', true ) );
	}

}

/**
 * The function provides access to the class methods.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.0.0
 * @return object
 */
function be_like_content() {
	return BE_Like_Content::instance();
}
be_like_content();
