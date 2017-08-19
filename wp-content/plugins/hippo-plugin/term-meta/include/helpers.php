<?php

	// Exit if accessed directly
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	/**
	 * Add metadata field to a term.
	 *
	 * @since 0.1.0
	 *
	 * @param  int    $term_id      Post ID
	 * @param  string $meta_key     Metadata name
	 * @param  mixed  $meta_value   Metadata value
	 * @param  bool   $unique       Optional, default is false. Whether the same key
	 *                              can be duplicated
	 *
	 * @return bool False for failure. True for success.
	 */
	function hippo_add_term_meta( $term_id, $meta_key, $meta_value, $unique = FALSE ) {
		return add_metadata( 'hippo_term', $term_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Remove metadata matching criteria from a term.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key if needed.
	 *
	 * @since 0.1.0
	 *
	 * @param  int    $term_id    Term ID
	 * @param  string $meta_key   Metadata name
	 * @param  mixed  $meta_value Optional. Metadata value
	 *
	 * @return bool False for failure. True for success.
	 */
	function hippo_delete_term_meta( $term_id, $meta_key, $meta_value = '' ) {
		return delete_metadata( 'hippo_term', $term_id, $meta_key, $meta_value );
	}


	/**
	 * Delete everything from term meta matching meta key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $term_meta_key Key to search for when deleting.
	 *
	 * @return bool Whether the term meta key was deleted from the database.
	 */
	function hippo_delete_term_meta_by_key( $term_meta_key ) {
		return delete_metadata( 'hippo_term', NULL, $term_meta_key, '', TRUE );
	}


	/**
	 * Retrieve term meta field for a term.
	 *
	 * @since 0.1.0
	 *
	 * @param  int    $term_id Term ID
	 * @param  string $key     The meta key to retrieve
	 * @param  bool   $single  Whether to return a single value
	 *
	 * @return mixed Will be an array if $single is false. Will be value of meta
	 *               data field if $single is true
	 */
	function hippo_get_term_meta( $term_id, $key, $single = FALSE ) {
		return get_metadata( 'hippo_term', $term_id, $key, $single );
	}

	/**
	 * Update term meta field based on term ID.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and term ID.
	 *
	 * If the meta field for the term does not exist, it will be added.
	 *
	 * @since 0.1.0
	 *
	 * @param  int    $term_id    Term ID
	 * @param  string $meta_key   Metadata key
	 * @param  mixed  $meta_value Metadata value
	 * @param  mixed  $prev_value Optional. Previous value to check before removing
	 *
	 * @return bool False on failure, true if success.
	 */
	function hippo_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		return update_metadata( 'hippo_term', $term_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Hippo Term item Meta API - set table name
	 */
	function hippo_taxonomy_metadata_wpdbfix() {
		global $wpdb;
		$termmeta_name = 'hippo_termmeta';

		$wpdb->hippo_termmeta = $wpdb->prefix . $termmeta_name;
		$wpdb->tables[]       = 'hippo_termmeta';
	}

	add_action( 'init', 'hippo_taxonomy_metadata_wpdbfix', 0 );
	add_action( 'switch_blog', 'hippo_taxonomy_metadata_wpdbfix', 0 );

