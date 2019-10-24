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
function sws_custom_sidebar_enqueue_script($hook) {   

 	if( 'post.php' === $hook || 'post-new.php' === $hook ) {
 	//	wp_enqueue_style( 'swsCustomSidebarCSS', plugin_dir_url(__FILE__).'assets/style.css');
        	//wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
        	//wp_enqueue_script( 'js-code-editor', plugin_dir_url( __FILE__ ) . 'assets/code-editor.js', array( 'jquery' ), '', true );
    	}	

}
add_action('admin_enqueue_scripts', 'sws_custom_sidebar_enqueue_script');



class SWS_Meta_Box
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
                [self::class, 'add_metabox_html'],   // Content callback, must be of type callable
                $screen,		 // post type
		'custom-metabox-holder'  // context
            );
//		add_meta_box( 'custom-sidebar', __( 'Custom Sidebar Content', 'sws_custom_sidebar' ), 'add_metabox_html', 'page', 'advanced' );

        }
    }
 
    public static function save($post_id)
    {
        if (array_key_exists('sws_cs_flds', $_POST)) {
            update_post_meta(
                $post_id,
                '_sws_cs_flds',
                $_POST['sws_cs_flds']
            );
        }
    }
 
    public static function add_metabox_html($post)
    {
	$post_id=$post->ID;
	$page_fields=get_post_meta($post_id,'_sws_cs_flds',true);
	if (!($page_fields)) {
		$page_fields=array(
			'top_title'=>'', 'top_html'=>'','bottom_html'=>''
		);
	}
        ?>
<div id='titlewrap'>
	<label for='sws_cs_title'>Sidebar Title</label>
	<input type='text' name="sws_cs_flds[top_title]" id='sws_cs_top_title' value="<?php echo wp_unslash($page_fields['top_title']); ?>" spellcheck='true'>
</div>
<!--<div id='contentdiv' class='wp-editor-wrap' data-toolbar='full'>
        <div id='contentwrap' class='wp-editor-container'>
		<textarea id="code_editor_top_html" rows='5' name="sws_cs_flds[top_html]" aria-hidden="true" class='widefat editor hide-if-no-js'><?php //echo wp_unslash($page_fields['top_html']); ?></textarea>
-	</div>
</div>
-->	<!--<div id='contentdiv' class='wp-editor-wrap' data-toolbar='full'>
	    <div id='contentwrap' class='wp-editor-container'>		<textarea name='sws_cs_content' id='sws_cs_content' spellcheck='true'></textarea>
	</div>
	</div>-->
<?php  wp_editor($page_fields['top_html'],"code_editor_top_html",array('textarea_rows'=>5,'textarea_name'=>'sws_cs_flds[top_html]','teeny'=>true));
    }
}
 
add_action('edit_form_after_title', ['SWS_Meta_Box','create']);
add_action('add_meta_boxes', ['SWS_Meta_Box', 'add']);
add_action('save_post', ['SWS_Meta_Box', 'save']);
	

?>
