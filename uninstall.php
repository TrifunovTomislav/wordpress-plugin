<?php
/**
 * Triger this file on plugin uninstall
 * 
 * @package sparky plagin
 */

 if(!defined('WP_UNINSTALL_PLUGIN')){
     die;
 }

//clear database stored data
//method 1
//  $svecice = get_posts( array('post_type' => 'Svecica', 'numberposts' => -1));

//  foreach ($svecice as $svecica) {
//      wp_delete_post( $svecica->ID, true );
//  }

//method 2
//Access the database via SQL
global $wpdb;
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'svecica'");
$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts) ");
$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts) ");