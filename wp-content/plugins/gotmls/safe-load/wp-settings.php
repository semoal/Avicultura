<?php
require_once("../../../../wp-includes/version.php");
//home/mauivalu/sites/mauivalue.com/public_html/wp-includes/default-constants.php:              
if ( !defined('WP_DEBUG_DISPLAY') )
 define( 'WP_DEBUG_DISPLAY', true );

if (!function_exists("apply_filters")) {
function apply_filters($filter, $value) {
	return $value;
}}
if (!function_exists("wp_load_translations_early")) {
function wp_load_translations_early() {
	return false;
}}
if (!function_exists("wp_debug_backtrace_summary")) {
function wp_debug_backtrace_summary() {
	return false;
}}
if (!function_exists("is_multisite")) {
function is_multisite() {
	return false;
}}

if (!function_exists("is_wp_error")) {
function is_wp_error() {
	return false;
}}

if (!function_exists("mbstring_binary_safe_encoding")) {
function mbstring_binary_safe_encoding( $reset = false ) {
    static $encodings = array();
    static $overloaded = null;
 
    if ( is_null( $overloaded ) )
        $overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );
 
    if ( false === $overloaded )
        return;
 
    if ( ! $reset ) {
        $encoding = mb_internal_encoding();
        array_push( $encodings, $encoding );
        mb_internal_encoding( 'ISO-8859-1' );
    }
 
    if ( $reset && $encodings ) {
        $encoding = array_pop( $encodings );
        mb_internal_encoding( $encoding );
    }
}
function reset_mbstring_encoding() {
    mbstring_binary_safe_encoding( true );
}}

require_once("../../../../wp-includes/wp-db.php");

$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

if (!function_exists("delete_option")) {
function delete_option($index) {
	global $wpdb, $table_prefix;
	$wpdb->delete($table_prefix."options", array( 'option_name' => "'$index'"));
//	echo "<li>del:".$index."<li>qry:".$wpdb->last_query."<li>err:".$wpdb->last_error;
}}

if (!function_exists("update_option")) {
function update_option($index, $value = "") {
	global $wpdb, $table_prefix;
	if (is_array($value))
		$value = serialize($value);
//	$value = mysqli_real_escape_string($wpdb, $value);
	$return = $wpdb->update($table_prefix."options", array('option_value' => $value), array('option_name' => $index));
//	echo "<li>upd:".$index."<li>qry:".$wpdb->last_query."<li>err:".$wpdb->last_error;
	return $return;
}}

if (!function_exists("get_option")) {
function get_option($index, $value = array()) {
	global $wpdb, $table_prefix;
	$qry = "SELECT option_value FROM {$table_prefix}options WHERE option_name = '$index'";
	$return = $wpdb->get_var( $qry );
	if (@unserialize($return) && is_array(@unserialize($return)))
		return unserialize($return);
	else
		return $return;
//	echo $wpdb->func_call."<li>get:".$index."<li>qry:$qry;/".$wpdb->last_query."<li>err:".$wpdb->last_error;
}}
