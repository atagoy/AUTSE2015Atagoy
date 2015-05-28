<?php

// Bootstrap WP
include "../../../../wp-load.php";

/**
 * Unit tests for the DWLS_Util class
 */
class Tests_DWLS_Util extends PHPUnit_Framework_TestCase {

	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test a single "img src" embedded in a block of content
	 * @author Dave Ross <dave@davidmichaelross.com>
	 */
	function test_first_image_basic() {
		$test_content = <<<TEST
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at erat posuere, convallis velit a, molestie urna. Morbi vel lectus sit amet orci consequat suscipit. Mauris vulputate viverra congue. Morbi vitae malesuada dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dignissim tempus lectus, non lobortis tortor varius non. Morbi malesuada vel enim id ultrices. Quisque vitae mattis lectus. Cras at risus commodo, sollicitudin diam ac, pharetra libero. Vivamus convallis, sem at ullamcorper consequat, elit eros porttitor dui, vitae viverra lectus diam eget augue. Nullam ipsum nisi, tempor nec sollicitudin in, auctor a tortor. Vivamus eu leo varius, semper erat hendrerit, tincidunt ante. Morbi molestie leo et pulvinar luctus. Mauris nibh sapien, molestie at justo ut, sagittis elementum tellus. Sed vestibulum convallis dapibus.

Donec justo neque, imperdiet eu suscipit quis, suscipit sit amet nisi. Vestibulum <img src="test.jpg" width="100" height="100" /> ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam feugiat tincidunt condimentum. Nulla vel elit arcu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque accumsan nisl ac euismod vulputate. Aliquam cursus semper ligula, in iaculis neque vulputate quis. Proin sed felis condimentum, pellentesque risus sit amet, cursus eros.

Maecenas tincidunt a augue vel sodales. Donec placerat purus et euismod rhoncus. Aenean augue elit, pharetra sit amet massa eu, rhoncus congue ligula. Sed feugiat turpis semper elementum condimentum. Cras dapibus turpis in odio sodales sagittis. Sed malesuada commodo mauris, vel luctus turpis mollis nec. Maecenas pharetra congue dolor, in volutpat neque gravida vel. Sed vestibulum, elit non elementum interdum, purus lacus lacinia erat, ac sagittis elit odio a turpis. Phasellus ornare consequat imperdiet. Cras tempus a metus sit amet convallis. Etiam sit amet nulla purus.
TEST;
		$first_image = DWLS_Util::firstImg( $test_content );
		$this->assertEquals($first_image, 'test.jpg');
	}

	/**
	 * Test a single "img src" where "src" isn't the first attribute
	 * @author Dave Ross <dave@davidmichaelross.com>
	 */
	function test_first_image_attr_order() {
		$test_content = <<<TEST
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at erat posuere, convallis velit a, molestie urna. Morbi vel lectus sit amet orci consequat suscipit. Mauris vulputate viverra congue. Morbi vitae malesuada dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dignissim tempus lectus, non lobortis tortor varius non. Morbi malesuada vel enim id ultrices. Quisque vitae mattis lectus. Cras at risus commodo, sollicitudin diam ac, pharetra libero. Vivamus convallis, sem at ullamcorper consequat, elit eros porttitor dui, vitae viverra lectus diam eget augue. Nullam ipsum nisi, tempor nec sollicitudin in, auctor a tortor. Vivamus eu leo varius, semper erat hendrerit, tincidunt ante. Morbi molestie leo et pulvinar luctus. Mauris nibh sapien, molestie at justo ut, sagittis elementum tellus. Sed vestibulum convallis dapibus.

Donec justo neque, imperdiet eu suscipit quis, suscipit sit amet nisi. Vestibulum <img width="100" height="100" src="test.jpg" /> ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam feugiat tincidunt condimentum. Nulla vel elit arcu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque accumsan nisl ac euismod vulputate. Aliquam cursus semper ligula, in iaculis neque vulputate quis. Proin sed felis condimentum, pellentesque risus sit amet, cursus eros.

Maecenas tincidunt a augue vel sodales. Donec placerat purus et euismod rhoncus. Aenean augue elit, pharetra sit amet massa eu, rhoncus congue ligula. Sed feugiat turpis semper elementum condimentum. Cras dapibus turpis in odio sodales sagittis. Sed malesuada commodo mauris, vel luctus turpis mollis nec. Maecenas pharetra congue dolor, in volutpat neque gravida vel. Sed vestibulum, elit non elementum interdum, purus lacus lacinia erat, ac sagittis elit odio a turpis. Phasellus ornare consequat imperdiet. Cras tempus a metus sit amet convallis. Etiam sit amet nulla purus.
TEST;
		$first_image = DWLS_Util::firstImg( $test_content );
		$this->assertEquals($first_image, 'test.jpg');
	}

	/**
	 * Test two images, where test.jpg is the first
	 * @author Dave Ross <dave@davidmichaelross.com>
	 */
	function test_first_image_two_images() {
		$test_content = <<<TEST
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at erat posuere, convallis velit a, molestie urna. Morbi vel lectus sit amet orci consequat suscipit. Mauris vulputate viverra congue. Morbi vitae malesuada dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dignissim tempus lectus, non lobortis tortor varius non. Morbi malesuada vel enim id ultrices. Quisque vitae mattis lectus. Cras at risus commodo, sollicitudin diam ac, pharetra libero. Vivamus convallis, sem at ullamcorper consequat, elit eros porttitor dui, vitae viverra lectus diam eget augue. Nullam ipsum nisi, tempor nec sollicitudin in, auctor a tortor. Vivamus eu leo varius, semper erat hendrerit, tincidunt ante. Morbi molestie leo et pulvinar luctus. Mauris nibh sapien, molestie at justo ut, sagittis elementum tellus. Sed vestibulum convallis dapibus.

Donec justo neque, imperdiet eu suscipit quis, suscipit sit amet nisi. Vestibulum <img width="100" height="100" src="test.jpg" /> ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam feugiat tincidunt condimentum. Nulla vel elit arcu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque accumsan nisl ac euismod vulputate. Aliquam cursus semper ligula, in iaculis neque vulputate quis. Proin sed felis condimentum, pellentesque risus sit amet, cursus eros.

Maecenas tincidunt a augue vel sodales. Donec placerat purus et euismod rhoncus. Aenean augue elit, pharetra sit amet massa eu, rhoncus <img width="100" height="100" src="test2.jpg" /> congue ligula. Sed feugiat turpis semper elementum condimentum. Cras dapibus turpis in odio sodales sagittis. Sed malesuada commodo mauris, vel luctus turpis mollis nec. Maecenas pharetra congue dolor, in volutpat neque gravida vel. Sed vestibulum, elit non elementum interdum, purus lacus lacinia erat, ac sagittis elit odio a turpis. Phasellus ornare consequat imperdiet. Cras tempus a metus sit amet convallis. Etiam sit amet nulla purus.
TEST;
		$first_image = DWLS_Util::firstImg( $test_content );
		$this->assertEquals($first_image, 'test.jpg');
	}

	/**
	 * Test no images
	 * @author Dave Ross <dave@davidmichaelross.com>
	 */
	function test_first_image_missing() {
		$test_content = <<<TEST
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at erat posuere, convallis velit a, molestie urna. Morbi vel lectus sit amet orci consequat suscipit. Mauris vulputate viverra congue. Morbi vitae malesuada dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dignissim tempus lectus, non lobortis tortor varius non. Morbi malesuada vel enim id ultrices. Quisque vitae mattis lectus. Cras at risus commodo, sollicitudin diam ac, pharetra libero. Vivamus convallis, sem at ullamcorper consequat, elit eros porttitor dui, vitae viverra lectus diam eget augue. Nullam ipsum nisi, tempor nec sollicitudin in, auctor a tortor. Vivamus eu leo varius, semper erat hendrerit, tincidunt ante. Morbi molestie leo et pulvinar luctus. Mauris nibh sapien, molestie at justo ut, sagittis elementum tellus. Sed vestibulum convallis dapibus.

Donec justo neque, imperdiet eu suscipit quis, suscipit sit amet nisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam feugiat tincidunt condimentum. Nulla vel elit arcu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque accumsan nisl ac euismod vulputate. Aliquam cursus semper ligula, in iaculis neque vulputate quis. Proin sed felis condimentum, pellentesque risus sit amet, cursus eros.

Maecenas tincidunt a augue vel sodales. Donec placerat purus et euismod rhoncus. Aenean augue elit, pharetra sit amet massa eu, rhoncus congue ligula. Sed feugiat turpis semper elementum condimentum. Cras dapibus turpis in odio sodales sagittis. Sed malesuada commodo mauris, vel luctus turpis mollis nec. Maecenas pharetra congue dolor, in volutpat neque gravida vel. Sed vestibulum, elit non elementum interdum, purus lacus lacinia erat, ac sagittis elit odio a turpis. Phasellus ornare consequat imperdiet. Cras tempus a metus sit amet convallis. Etiam sit amet nulla purus.
TEST;
		$first_image = DWLS_Util::firstImg( $test_content );
		$this->assertEmpty($first_image);
	}

	/**
	 * Test a single "img src" embedded in a block of content
	 * @author Dave Ross <dave@davidmichaelross.com>
	 */
	function test_first_image_single_quotes() {
		$test_content = <<<TEST
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at erat posuere, convallis velit a, molestie urna. Morbi vel lectus sit amet orci consequat suscipit. Mauris vulputate viverra congue. Morbi vitae malesuada dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dignissim tempus lectus, non lobortis tortor varius non. Morbi malesuada vel enim id ultrices. Quisque vitae mattis lectus. Cras at risus commodo, sollicitudin diam ac, pharetra libero. Vivamus convallis, sem at ullamcorper consequat, elit eros porttitor dui, vitae viverra lectus diam eget augue. Nullam ipsum nisi, tempor nec sollicitudin in, auctor a tortor. Vivamus eu leo varius, semper erat hendrerit, tincidunt ante. Morbi molestie leo et pulvinar luctus. Mauris nibh sapien, molestie at justo ut, sagittis elementum tellus. Sed vestibulum convallis dapibus.

Donec justo neque, imperdiet eu suscipit quis, suscipit sit amet nisi. Vestibulum <img src='test.jpg' width='100' height='100' /> ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam feugiat tincidunt condimentum. Nulla vel elit arcu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque accumsan nisl ac euismod vulputate. Aliquam cursus semper ligula, in iaculis neque vulputate quis. Proin sed felis condimentum, pellentesque risus sit amet, cursus eros.

Maecenas tincidunt a augue vel sodales. Donec placerat purus et euismod rhoncus. Aenean augue elit, pharetra sit amet massa eu, rhoncus congue ligula. Sed feugiat turpis semper elementum condimentum. Cras dapibus turpis in odio sodales sagittis. Sed malesuada commodo mauris, vel luctus turpis mollis nec. Maecenas pharetra congue dolor, in volutpat neque gravida vel. Sed vestibulum, elit non elementum interdum, purus lacus lacinia erat, ac sagittis elit odio a turpis. Phasellus ornare consequat imperdiet. Cras tempus a metus sit amet convallis. Etiam sit amet nulla purus.
TEST;
		$first_image = DWLS_Util::firstImg( $test_content );
		$this->assertEquals($first_image, 'test.jpg');
	}

}
