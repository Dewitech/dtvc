<?php
/*
Plugin Name: Dewitech Visual Composer Custom Plugin
Plugin URI: http://github.com/dewitech
Description: Extend Visual Composer with your own set of shortcodes.
Version: 0.1.1
Author: Ryan Hidajat
Author URI: http://bit.ly/ryanodesk
License: GPLv2 or later
*/

/*
This example/starter plugin can be used to speed up Visual Composer plugins creation process.
More information can be found here: http://kb.wpbakery.com/index.php?title=Category:Visual_Composer
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
    }
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("My Bar Shortcode", 'vc_extend'),
            "description" => __("Bar tag description text", 'vc_extend'),
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
                  "value" => __("Default params value", 'vc_extend'),
                  "description" => __("Description for foo param.", 'vc_extend')
              ),
              array(
                  "type" => "colorpicker",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Text color", 'vc_extend'),
                  "param_name" => "color",
                  "value" => '#FF0000', //Default Red color
                  "description" => __("Choose text color", 'vc_extend')
              ),
              array(
                  "type" => "textarea_html",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Content", 'vc_extend'),
                  "param_name" => "content",
                  "value" => __("<p>I am test text block. Click edit button to change this text.</p>", 'vc_extend'),
                  "description" => __("Enter your content.", 'vc_extend')
              ),
            )
        ) );
		
		vc_map( array(
	'name' => __( 'Row', 'js_composer' ),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Place content elements inside the row', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Font Color', 'wpb' ),
			'param_name' => 'font_color',
			'description' => __( 'Select font color', 'wpb' ),
			'edit_field_class' => 'col-md-6'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Font Color', 'wpb' ),
			'param_name' => 'row_type',
			'description' => __( 'Select font color', 'wpb' ),
			'edit_field_class' => 'col-md-6',
			'value'=>array('Container'=>'0', 'Full Width'=>'1', 'Parallax Full Width'=>'2', 'Parallax Container'=>'3', 'Parallax Full Screen'=>'4')
		),
	  array(
        "type" => "dropdown",
        "heading" => __('Background', 'wpb'),
        "param_name" => "bg_type",
        //"description" => __("-", "wpb"),
        "edit_field_class" => 'col-md-6',
		"value"=>array("Gray"=>"1", "Dark Gray"=>"2", "Image"=>"3", "Video"=>"4")
      ),
	  array(
        "type" => "textfield",
        "heading" => __('Background Video', 'wpb'),
        "param_name" => "bg_video",
        "description" => __("Set the background video", "wpb"),
        "edit_field_class" => 'col-md-6',
		"dependency"=>array('element'=>'bg_type','value'=>array('4'))
      ),
	  array(
        "type" => "textfield",
        "heading" => __('Background Image', 'wpb'),
        "param_name" => "image",
        "description" => __("Set the background image", "wpb"),
        "edit_field_class" => 'col-md-6',
		"dependency"=>array('element'=>'bg_type','value'=>array('3'))
      ),
	  array(
        "type" => 'checkbox',
        "heading" => __("Overlay", "dt"),
        "param_name" => "overlay_option",
        "value" => Array(__("On", "dt") => '1'),
		"dependency"=>array('element'=>'bg_type','value'=>array('3', '4'))
      ),

		/*
   array(
        'type' => 'colorpicker',
        'heading' => __( 'Custom Background Color', 'wpb' ),
        'param_name' => 'bg_color',
        'description' => __( 'Select backgound color for your row', 'wpb' ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Padding', 'wpb' ),
        'param_name' => 'padding',
        'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', 'wpb' ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Bottom margin', 'wpb' ),
        'param_name' => 'margin_bottom',
        'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', 'wpb' ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'attach_image',
        'heading' => __( 'Background Image', 'wpb' ),
        'param_name' => 'bg_image',
        'description' => __( 'Select background image for your row', 'wpb' )
  ),
  array(
        'type' => 'dropdown',
        'heading' => __( 'Background Repeat', 'wpb' ),
        'param_name' => 'bg_image_repeat',
        'value' => array(
                          __( 'Default', 'wpb' ) => '',
                          __( 'Cover', 'wpb' ) => 'cover',
					  __('Contain', 'wpb') => 'contain',
					  __('No Repeat', 'wpb') => 'no-repeat'
					),
        'description' => __( 'Select how a background image will be repeated', 'wpb' ),
        'dependency' => array( 'element' => 'bg_image', 'not_empty' => true)
  ),
  */
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => __( 'Design options', 'js_composer' )
		)
	),
	'js_view' => 'VcRowView'
) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderMyBartag( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'foo' => 'something',
        'color' => '#FF0000'
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $output = "<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
      return $output;
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_extend_style', plugins_url('assets/vc_extend.css', __FILE__) );
      wp_enqueue_style( 'vc_extend_style' );

      // If you need any javascript files on front end, here is how you can load them.
      //wp_enqueue_script( 'vc_extend_js', plugins_url('assets/vc_extend.js', __FILE__), array('jquery') );
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