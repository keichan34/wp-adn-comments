<?php
/**
 * @package ADN_Comments
 * @version 1.0
 */
/*
 * Plugin Name: ADN Comments
 * Plugin URI: http://wordpress.org/plugins/adn-comments/
 * Description: This plugin replaces default WordPress comments with comments based on the App.net microblogging service.
 * Author: Keitaroh Kobayashi
 * Version: 1.0
 * Author URI: http://kkob.us/
 */

if ( ! class_exists ( 'ADN_Comments' ) ) :

class ADN_Comments {
  public static function plugin_dir_url() {
    return plugin_dir_url(__FILE__);
  }

  public static function embed_file_url() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
      return plugin_dir_url(__FILE__) . 'embed.js';
    } else {
      return plugin_dir_url(__FILE__) . 'embed.min.js';
    }
  }

  public function __construct() {
    /*
     * The following functions have been adapted from the [Hide Comments Feature](http://wordpress.org/plugins/hide-comments-feature/) plugin.
     */
    add_action( 'wp_head', array( $this, 'remove_comments_css' ) );
    add_action( 'wp_meta', array( $this, 'remove_comments_link_meta' ) );
    add_action( 'admin_menu', array( $this, 'remove_discussion_options' ) );
    add_action( 'admin_head', array( $this, 'dashboard_right_now_hide_discussion' ) );
    add_action( 'admin_bar_menu', array( $this, 'remove_comments_from_admin_bar' ), 99 );
    add_action( 'get_comments_number', array( $this, 'comments_number_always_zero' ) );
    add_action( 'comments_template', array( $this, 'change_comments_template' ) );
    add_action( 'widgets_init', array( $this, 'remove_comments_widget' ), 0 );
    add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_comments_widget' ), 0 );

    // Add the ADN username in the author's user profile page.
    add_filter( 'user_contactmethods', array( $this, 'user_contactmethods' ) );
  }

  function remove_comments_css() {
    ?>
    <style type="text/css">
      .comments-link {
        display: none;
      }
    </style>
    <?php

  }

  function remove_comments_link_meta() {
    ?>
    <style type="text/css">
      .widget_meta li:nth-child(4) {
        display: none;
      }
    </style>
    <?php
  }

  function remove_discussion_options() {
    remove_menu_page( 'edit-comments.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
  }

  function dashboard_right_now_hide_discussion() {
    if ( ! apply_filters( 'hide_comments_dashboard_right_now', true ) )
      return;

    ?>
    <style type="text/css">
      #dashboard_right_now .table_discussion {
        display: none;
      }
    </style>
    <?php
  }

  function remove_comments_from_admin_bar( $admin_bar ) {
    $admin_bar->remove_menu( 'comments' );
    return $admin_bar;
  }

  function comments_number_always_zero() {
    return 0;
  }

  function change_comments_template() {
    global $wp_query;

    $wp_query->comments = array();
    $wp_query->comments_by_type = array();
    $wp_query->comment_count = '0';
    $wp_query->post->comment_count = '0';
    $wp_query->post->comment_status = 'closed';
    $wp_query->queried_object->comment_count = '0';
    $wp_query->queried_object->comment_status = 'closed';

    return plugin_dir_path( __FILE__ ) . 'template-comments.php';
  }

  function remove_comments_widget() {
    if ( function_exists( 'unregister_widget' ) ) {
      unregister_widget( 'WP_Widget_Recent_Comments' );
    }
  }

  function remove_dashboard_comments_widget() {
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  }

  function user_contactmethods( $contact_methods ) {

    $contact_methods['adn'] = __('App.net Username', 'adn-comments');

    return $contact_methods;
  }

}

new ADN_Comments();

endif;
