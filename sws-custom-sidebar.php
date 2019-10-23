<?php

/**
 * Plugin Name:       SWS Custom Sidebar
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-custom-sidebar/
 * Description:       Create page-specific sidebar content
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      5.5
 * Author:            Sharon Stromberg
 * Author URI:        https://ccharacter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sws-custom-sidebar/
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__).'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/ccharacter/sws-custom-sidebar/master/plugin.json',
	__FILE__,
	'sws-custom-sidebar'
);


// add stylesheets
function sws_custom_sidebar_enqueue_script() {   
    wp_enqueue_script( 'swsCustomSidebarCSS', plugin_dir_url(__FILE__).'assets/style.css',array(),'1.0');
}
add_action('admin_enqueue_scripts', 'sws_custom_sidebar_enqueue_script');



class swsCustomSidebar
{
	public function showTag($content) {
		if ( (is_page('home')) || (is_page('about'))) {
			return $content.'<span style="opacity:0.02">'.gethostname().'</span>';
		} else { 
			return $content;
		}
	}
	
    public function register($atts, $content = null)
    {
        return '<span style="opacity:0.02">'.gethostname().'</span>';
    }
    
	public function init()
    {
        add_shortcode('sws_server_tag', array($this, 'register'));
		add_action('the_content',array($this,'showTag'));
    }
}


$shortcode=new swsCustomSidebar();
$shortcode->init();

/*function sws_customSidebar_metabox($post) {
    
    do_meta_boxes( null, 'custom-metabox-holder', $post );
}
add_action( 'edit_form_after_title', 'sws_customSidebar_metabox' );

function sws_customSidebar_add() {
 
        add_meta_box(
            'sws_sidebar_metabox',
            __( 'CUSTOM SIDEBAR CONTENT', 'sws-custom-sidebar' ),
            'sws_customSidebar_render',
            'page'
        );
    
}
add_action( 'add_meta_boxes', 'sws_customSidebar_add' );
 
function sws_customSidebar_render( $post ) {
    
    ?>
    <div class="sws-custom-sidebar">
        <label for 'sws-cs-title' id='sws-cs-title' class='
    </div>
<?php 
}
*/



abstract class WPOrg_Meta_Box
{

    public static function create($post) {

	do_meta_boxes( null, 'custom-metabox-holder',$post);
    }

    public static function add()
    {
        $screens = ['post','page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'sws-custom-sidebar-id',          // Unique ID
                'CUSTOM SIDEBAR CONTENT', // Box title
                [self::class, 'html'],   // Content callback, must be of type callable
                $screen,		 // post type
		'custom-metabox-holder'  // context
            );
        }
    }
 
    public static function save($post_id)
    {
        if (array_key_exists('wporg_field', $_POST)) {
            update_post_meta(
                $post_id,
                '_wporg_meta_key',
                $_POST['wporg_field']
            );
        }
    }
 
    public static function html($post)
    {
        $value = get_post_meta($post->ID, '_wporg_meta_key', true);
        ?>
	<div id='titlediv'>
	    <div id='titlewrap'>
		<label for='sws_cs_title'>Sidebar Title</label>
		<input type='text' name='sws_cs_title' id='sws_cs_title'  spellcheck='true'>
	    </div>
	<div id='contentdiv' class='wp-editor-wrap' data-toolbar='full'>
	    <div id='contentwrap' class='wp-editor-container'>
		<textarea name='sws_cs_content' id='sws_cs_content' spellcheck='true'></textarea>
	</div></div>
<?php  wp_editor("","sws_cs_content");
    }
}
 
add_action('edit_form_after_title', ['WPOrg_Meta_Box','create']);
add_action('add_meta_boxes', ['WPOrg_Meta_Box', 'add']);
add_action('save_post', ['WPOrg_Meta_Box', 'save']);
	

?>
