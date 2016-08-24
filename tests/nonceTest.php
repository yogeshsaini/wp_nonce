<?php
/**
 * Class for Nonce testings
 *
 * @author Daffodil <http://daffodilsw.com>
 * @package Nonce
 */

// Loading wp functions.
define( 'WP_USE_THEMES', false );
require( WP_INSTALL . '/wp-blog-header.php' );

/**
 * Class for Nonce testings
 * @author Daffodil <http://daffodilsw.com>
 * @package Nonce
 */
class NonceTest extends PHPUnit_Framework_TestCase {
	/**
	 * Test case for wp_create_nonce
	 * @return void
	 */
	public function testWpCreateNonce() {
		$nonce = \Nonce\Wrapper::wp_create_nonce();
		$this->assertNotNull( $nonce );
	}

	/**
	 * Test case for wp_verify_nonce
	 * @return void
	 */
	public function testWpVerifyNonce() {
		$nonce = \Nonce\Wrapper::wp_create_nonce( );
		$this->assertEquals( 1, \Nonce\Wrapper::wp_verify_nonce( $nonce ) );
		$this->assertNotEquals( 1, \Nonce\Wrapper::wp_verify_nonce( $nonce ) . 'extra' );
	}

	/**
	 * Test case for wp_nonce_field
	 * @return void
	 */
	public function testWpNonceField() {
		$field = \Nonce\Wrapper::wp_nonce_field( -1, '_wpnonce', true, false );
		$this->assertNotNull( $field );
	}

	/**
	 * Test case for wp_nonce_url
	 * @return void
	 */
	public function testWpNonceUrl() {
		$url = \Nonce\Wrapper::wp_nonce_url( 'http://www.google.com' );
		$urlDetails = parse_url( $url );
		$query = $urlDetails['query'];

		$this->assertStringStartsWith( '_wpnonce=', $query );
	}

	/**
	 * Test case for check_admin_referer
	 * @return void
	 */
	public function testCheckAdminReferer() {
		$nonce = \Nonce\Wrapper::wp_create_nonce( );
		$_REQUEST['_wpnonce'] = $nonce;
		$this->assertEquals( 1, \Nonce\Wrapper::check_admin_referer( ) );
	}

	/**
	 * Test case for check_ajax_referer
	 * @return void
	 */
	public function testCheckAjaxReferer() {
		$nonce = \Nonce\Wrapper::wp_create_nonce( );
		$_REQUEST['_wpnonce'] = $nonce;
		$this->assertEquals( 1, \Nonce\Wrapper::check_ajax_referer( ) );
	}

	/**
	 * Test case for wp_referer_field
	 * @return void
	 */
	public function testWpRefererField() {
		$field = \Nonce\Wrapper::wp_referer_field( false );
		$this->assertEquals( '<input type="hidden" name="_wp_http_referer" value="" />', $field );
	}
}
