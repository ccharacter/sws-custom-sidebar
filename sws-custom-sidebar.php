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

function sws_customSidebar_metabox( $post ) {
    
    do_meta_boxes( null, 'custom-metabox-holder', $post );
}
add_action( 'edit_form_after_title', 'sws_customSidebar_metabox' );


function sws_customSidebar_add() {
 
        add_meta_box(
            'awesome_metabox_id',
            __( 'This Is Awesome', 'sws-custom-sidebar' ),
            'sws_customSidebar_render',
            'post',
            'custom-metabox-holder'    //Look what we have here, a new context
        );
    
}
add_action( 'add_meta_boxes', 'sws_customSidebar_add' );
 
function sws_customSidebar_render( $post ) {
    
    ?>
    <div class="awesome-meta-admin">
        <?php //show something here
            echo "Having fun!";
        ?>
    </div>
<?php 
}
	

?>
