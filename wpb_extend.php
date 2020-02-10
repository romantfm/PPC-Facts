<?php
/*
Plugin Name: PPC Facts
Plugin URI: http://topfloormarketing.net
Description: Premium
Version: 1.0.0
Author: Top Floor Marketing
Author URI: http://topfloormarketing.net
License: GPLv2 or later
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtendAddonClass {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'bartag', array( $this, 'renderMyBartag' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );

        add_action( 'wp_footer', array( $this, 'showAll' ) );
    }
 
    public function integrateWithVC() {
        // Check if WPBakery Page Builder is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Extend WPBakery Page Builder is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
 
        vc_map( array(
            "name" => __("PPC Facts", 'vc_extend'),
            "description" => __("", 'vc_extend'),
            "base" => "bartag",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/asterisk_yellow.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('Content', 'js_composer'),
            //'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
            //'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
            "params" => array(
                array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Text", 'vc_extend'),
                  "param_name" => "foo",
                  "value" => __("", 'vc_extend'),
                  "description" => __("Ex: https://tfm-web-api.herokuapp.com/graphql", 'vc_extend')
              ),
            
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderMyBartag( $atts ) {
      extract( shortcode_atts( array(
        'foo' => 'https://tfm-web-api.herokuapp.com/graphql',
      ), $atts ) );
      //$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content


      $output = '<div class="center">
        <div class="ptc-logs-card green">
          <div class="ptc-logs-additional">
            <div class="ptc-logs-user-card">
              <div class="ptc-device-status-history">
                <strong>PPC</strong>
              </div>
              <div class="ptc-logs-points center">
                FACT
              </div>
            </div>
            <div class="ptc-logs-more-info">
              <h1 class="cta heading">Convinced Already?</h1>
              <div class="ptc-logs-coords">
                <span><a class="link" href="/contact-us">CONTACT US!</a></span>
              </div>
            </div>
          </div>
          <div class="ptc-logs-general">
            <div class="headingDid">
              <h1 class="heading">Did you know...</h1>
            </div>
            <div class="content">
              <span class="ptc-logs-more fact">Over 40,000 searches are made on Google every second</span>
            </div>
          </div>
        </div>
      </div>';

      $output = $output . '<script type="text/javascript">
      jQuery(window).on( "scroll", function(){
         if(jQuery(window).scrollTop() >= 500) {
      jQuery("#ppcFacts").removeClass("hideBar");
      jQuery("#ppcFacts").addClass("showBar");
      } else {
      jQuery("#ppcFacts").addClass("hideBar");
      jQuery("#ppcFacts").removeClass("showBar");
      }
      });
      
      $( document ).ready(function() {
          fetch("'.${foo}.'", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: "{ randomTip { description title department } }" }),
      })
        .then(res => res.json())
        .then(res => jQuery(".fact").text(res.data.randomTip.description));
      });
      
      
      </script>';

      return $output;
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_extend_style', plugins_url('assets/vc_extend.css', __FILE__) );
      wp_enqueue_style( 'vc_extend_style' );    
      
      wp_enqueue_script( 'vc_extend_js', plugins_url('assets/jquery-3.4.1.min.js', __FILE__), array('jquery') );
    }

    public function showAll($atts) {
      extract( shortcode_atts( array(
        'foo' => 'https://tfm-web-api.herokuapp.com/graphql',
      ), $atts ) );
      //$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
      $output = "<div data-foo='${foo}'>${foo}</div>";

      

      return $box;
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new VCExtendAddonClass();