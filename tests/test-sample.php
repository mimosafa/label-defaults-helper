<?php
/**
 * Class SampleTest
 *
 * @package Test
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		# $this->assertTrue( true );
		$str1 = 'test';
		$result1 = PostTypeLabelDefaults::labelize( $str1 );
		$this->assertEquals( $result1, 'Test' );

		$str2 = 'test-sepalated';
		$result2 = PostTypeLabelDefaults::labelize( $str2 );
		$this->assertEquals( $result2, 'Test Sepalated' );
	}
}
