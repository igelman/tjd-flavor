<?php
$path = '/Users/alantest/Developer/src/wordpress-tests/bootstrap.php';
if( file_exists( $path ) ) {
    require_once $path;
} else {
    exit( "Couldn't find path to wordpress-tests/bootstrap.php\n" );
}

require_once '../classes/tmt-get-custom-fields-class.php';


class TestGetCustomFields extends PHPUnit_Framework_TestCase {

	public function setUp() {
		// Create post object
		$this->postFields = array(
			'post_title'    => 'My post',
			'post_content'  => 'This is my post.',
			'post_status'   => 'publish',
			'post_author'   => 1,
		);
		
		$this->postCustomFields = array(
			'url'				=> "",
			'logo_image'		=> "",
			'post_expiration'	=> "",
			'deal_expiration'	=> "",
			'coupon_codes'		=> "",
		);
		

		// Insert the post into the database
		$this->postId = wp_insert_post( $this->postFields );
		foreach ($this->postCustomFields as $key => $value) {
			update_post_meta($this->postId, $key, $value);
		}
		
		
		echo "postId: " . $this->postId . PHP_EOL;
	}
	
	public function testTest() {
		get_post_meta( $this->postId, $key, FALSE );
		$this->assertTrue(TRUE);
	}

}

?>