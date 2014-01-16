<?php
$path = '/Users/alantest/Developer/src/wordpress-tests/bootstrap.php';
if( file_exists( $path ) ) {
    require_once $path;
} else {
    exit( "Couldn't find path to wordpress-tests/bootstrap.php\n" );
}

require_once '../classes/tmt-get-custom-fields-class.php';


class TestGetCustomFields extends PHPUnit_Framework_TestCase {
}

?>