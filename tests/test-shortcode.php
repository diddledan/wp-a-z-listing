<?php
class AZ_Shortcode_Tests extends WP_UnitTestCase {
	function test_empty() {
		$expected = file_get_contents( 'tests/data/default-listing.txt' );
		$actual = do_shortcode( '[a-z-listing]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_listing() {
		$title = 'Test Page';
		$p = $this->factory->post->create( array(
			'post_title' => $title,
			'post_type' => 'page',
		) );

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $title, $p );
		$actual = do_shortcode( '[a-z-listing]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_lowercase_titles() {
		$title = 'test page';
		$p = $this->factory->post->create( array(
			'post_title' => $title,
			'post_type' => 'page',
		) );

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $title, $p );
		$actual = do_shortcode( '[a-z-listing]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	function test_populated_taxonomy_listing() {
		$title = 'test category';
		$t = $this->factory->term->create( array(
			'name' => $title,
			'taxonomy' => 'category',
		) );

		$expected = sprintf( file_get_contents( 'tests/data/populated-taxonomy-listing.txt' ) , $title, $t );
		$actual = do_shortcode( '[a-z-listing display="terms" taxonomy="category"]' );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );

		$this->assertEquals( $expected, $actual );
	}

	function test_populated_filtered_listing() {
		$cat = 'test category';
		$t = $this->factory->term->create( array(
			'name' => $cat,
			'taxonomy' => 'category',
		) );

		$title = 'Test Page';
		$p = $this->factory->post->create( array(
			'post_title' => $title,
			'post_type' => 'page',
		) );

		wp_set_post_terms( $p, $t, 'category' );

		$term = get_term( $t, 'category' );

		$expected = sprintf( file_get_contents( 'tests/data/populated-listing.txt' ), $title, $p );
		$actual = do_shortcode( sprintf( '[a-z-listing taxonomy="category" terms="%s"]', $term->slug ) );

		$expected = preg_replace( '/\s{2,}|\t|\n/', '', $expected );
		$actual = preg_replace( '/\s{2,}|\t|\n/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}
}
