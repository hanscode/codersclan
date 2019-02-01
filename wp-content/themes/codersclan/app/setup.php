<?php

namespace App;

use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Container;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme version string.
 * Tries to read a "version.json" file in the "dist" folder - a JSON file with a "version" property.
 * If this file is not available it returns the WordPress version.
 *
 * @return string
 */
function theme_version()
{
    $version = get_bloginfo('version');
    $version_json_path = get_template_directory() . '/../dist/version.json';

    if (file_exists($version_json_path)) {
        $version_json = file_get_contents($version_json_path);

        if (!empty($version_json)) {
            $version_json_data = json_decode($version_json);

            if (!empty($version_json_data)) {
                if (!empty($version_json_data->version)) {
                    $version = $version_json_data->version;
                }
            }
        }
    }

    return $version;
}

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    $version = theme_version();
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/static/font-awesome.min.css', false, $version);
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, $version);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], $version, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];
    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);
    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});

/**
 * Allow SVG uploads.
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
});

/**
 * Add option pages.
 */
add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('CodersClan
             Theme Settings', 'CodersClan'),
            'menu_title' => __('CodersClan Settings', 'codersclan'),
            'menu_slug' => 'codersclan-settings',
        ));

        acf_add_options_sub_page(array(
            'page_title' => __('General Settings', 'codersclan'),
            'menu_title' => __('General', 'codersclan'),
            'parent_slug' => 'codersclan-settings',
        ));
    }
});

/**
 * Only show Custom Fields admin page on ".local" address.
 *
add_filter('acf/settings/show_admin', function () {
    $site_url = get_bloginfo('url');

    if (string_ends_with($site_url, '.local')) {
        return true;
    } else {
        return false;
    }
});*/

/**
 * Challenge: Create a CPT with a function to replace strings based on time
 */
add_action('init',function(){
	$args = array(
    'labels' => array(
              'name' => __( 'Time Posts' ),
              'all_items'=> __( 'All Time Posts' ),
              'singular_name' => __( 'TimePost' )
          ),
    'public' => true,
    );
    register_post_type( 'timepost', $args );

    $args = array(
      'labels' => array(
                'name' => __( 'Time Posts 2' ),
                'all_items'=> __( 'All Time Posts 2' ),
                'singular_name' => __( 'TimePost2' )
            ),
      'public' => true,
    );
    register_post_type( 'timepost2', $args );
});

add_filter('the_content', function( $content ){
	global $post;

	if($post->post_type == 'timepost' && current_time('G') < 11) {
    return str_ireplace('Hello', 'Good Morning!', $content);
  } else {
    return $content;
  }
});
/**
 * Challenge: Create an ACF Alert section
 */

if( function_exists('acf_add_local_field_group') ):

  //Section: Alerts
  acf_add_local_field_group(array(
   'key' => 'group_5c534b8ceaade',
   'title' => 'Section: Alerts',
   'fields' => array(
     array(
       'key' => 'field_5c534bea44eee',
       'label' => 'Select Alert',
       'name' => 'select_alert',
       'type' => 'radio',
       'instructions' => 'Select an alert type to display in homepage.',
       'required' => 0,
       'conditional_logic' => 0,
       'wrapper' => array(
       'width' => '',
       'class' => '',
       'id' => '',
       ),
       'choices' => array(
         'warning' => 'Warning',
         'success' => 'Success',
         'error' => 'Error',
       ),
       'allow_null' => 0,
       'other_choice' => 0,
       'default_value' => '',
       'layout' => 'vertical',
       'return_format' => 'value',
       'save_other_choice' => 0,
     ),
     array(
       'key' => 'field_5c534bc944eed',
       'label' => 'Success Alert',
       'name' => 'success_msg',
       'type' => 'text',
       'instructions' => 'Insert success message here.',
       'required' => 0,
       'conditional_logic' => array(
         array(
           array(
             'field' => 'field_5c534bea44eee',
             'operator' => '==',
             'value' => 'success',
           ),
         ),
       ),
       'wrapper' => array(
         'width' => '',
         'class' => '',
         'id' => '',
       ),
       'default_value' => '',
       'placeholder' => '',
       'prepend' => '',
       'append' => '',
       'maxlength' => '',
     ),
     array(
       'key' => 'field_5c534c6f44eef',
       'label' => 'Warning Alert',
       'name' => 'warning_msg',
       'type' => 'text',
       'instructions' => 'Insert warning message here.',
       'required' => 0,
       'conditional_logic' => array(
         array(
           array(
             'field' => 'field_5c534bea44eee',
             'operator' => '==',
             'value' => 'warning',
           ),
         ),
       ),
       'wrapper' => array(
         'width' => '',
         'class' => '',
         'id' => '',
       ),
       'default_value' => '',
       'placeholder' => '',
       'prepend' => '',
       'append' => '',
       'maxlength' => '',
     ),
     array(
       'key' => 'field_5c534ce044ef0',
       'label' => 'Error Alert',
       'name' => 'error_msg',
       'type' => 'text',
       'instructions' => 'Insert error message here.',
       'required' => 0,
       'conditional_logic' => array(
         array(
           array(
             'field' => 'field_5c534bea44eee',
             'operator' => '==',
             'value' => 'error',
           ),
         ),
       ),
       'wrapper' => array(
         'width' => '',
         'class' => '',
         'id' => '',
       ),
       'default_value' => '',
       'placeholder' => '',
       'prepend' => '',
       'append' => '',
       'maxlength' => '',
     ),
   ),
   'location' => array(
     array(
       array(
         'param' => 'post_type',
         'operator' => '==',
         'value' => 'post',
       ),
     ),
   ),
   'menu_order' => 0,
   'position' => 'normal',
   'style' => 'default',
   'label_placement' => 'top',
   'instruction_placement' => 'label',
   'hide_on_screen' => '',
   'active' => 0,
   'description' => '',
  ));

  //Partial: Sections
  acf_add_local_field_group(array(
   'key' => 'group_5badfc735aa1f',
   'title' => 'Partial: Sections',
   'fields' => array(
     array(
       'key' => 'field_5badfc78b1465',
       'label' => 'Sections',
       'name' => 'sections',
       'type' => 'flexible_content',
       'instructions' => '',
       'required' => 0,
       'conditional_logic' => 0,
       'wrapper' => array(
         'width' => '',
         'class' => '',
         'id' => '',
       ),
       'layouts' => array(
         '5badfc7de5e91' => array(
           'key' => '5badfc7de5e91',
           'name' => 'hello_world',
           'label' => 'Hello World',
           'display' => 'block',
           'sub_fields' => array(
             array(
               'key' => 'field_5badfc87b1466',
               'label' => 'Section: Hello World',
               'name' => 'section:_hello_world',
               'type' => 'clone',
               'instructions' => '',
               'required' => 0,
               'conditional_logic' => 0,
               'wrapper' => array(
                 'width' => '',
                 'class' => '',
                 'id' => '',
               ),
               'clone' => array(
                 0 => 'group_5badfc361d354',
               ),
               'display' => 'seamless',
               'layout' => 'block',
               'prefix_label' => 0,
               'prefix_name' => 0,
             ),
           ),
           'min' => '',
           'max' => '',
         ),
         'layout_5bc49a98959b1' => array(
           'key' => 'layout_5bc49a98959b1',
           'name' => 'broken_sidebar',
           'label' => 'Broken Sidebar',
           'display' => 'block',
           'sub_fields' => array(
             array(
               'key' => 'field_5bc49a98959b2',
               'label' => 'Section: Broken Sidebar',
               'name' => 'section:_broken_sidebar',
               'type' => 'clone',
               'instructions' => '',
               'required' => 0,
               'conditional_logic' => 0,
               'wrapper' => array(
                 'width' => '',
                 'class' => '',
                 'id' => '',
               ),
               'clone' => array(
                 0 => 'group_5bc49ab339a7b',
               ),
               'display' => 'seamless',
               'layout' => 'block',
               'prefix_label' => 0,
               'prefix_name' => 0,
             ),
           ),
           'min' => '',
           'max' => '',
         ),
         'layout_5bc49f364b3e0' => array(
           'key' => 'layout_5bc49f364b3e0',
           'name' => 'social_icons',
           'label' => 'Social Icons',
           'display' => 'block',
           'sub_fields' => array(
             array(
               'key' => 'field_5bc49f364b3e1',
               'label' => 'Section: Social Icons',
               'name' => 'section:_social_icons',
               'type' => 'clone',
               'instructions' => '',
               'required' => 0,
               'conditional_logic' => 0,
               'wrapper' => array(
                 'width' => '',
                 'class' => '',
                 'id' => '',
               ),
               'clone' => array(
                 0 => 'group_5bc49f199d19f',
               ),
               'display' => 'seamless',
               'layout' => 'block',
               'prefix_label' => 0,
               'prefix_name' => 0,
             ),
           ),
           'min' => '',
           'max' => '',
         ),
         'layout_5c5379b4dc4fd' => array(
           'key' => 'layout_5c5379b4dc4fd',
           'name' => 'alerts',
           'label' => 'Alerts',
           'display' => 'block',
           'sub_fields' => array(
             array(
               'key' => 'field_5c537a0edc4ff',
               'label' => 'Section: Alerts',
               'name' => 'section:_alerts',
               'type' => 'clone',
               'instructions' => '',
               'required' => 0,
               'conditional_logic' => 0,
               'wrapper' => array(
                 'width' => '',
                 'class' => '',
                 'id' => '',
               ),
               'clone' => array(
                 0 => 'group_5c534b8ceaade',
               ),
               'display' => 'seamless',
               'layout' => 'block',
               'prefix_label' => 0,
               'prefix_name' => 0,
             ),
           ),
           'min' => '',
           'max' => '',
         ),
       ),
       'button_label' => 'Add Row',
       'min' => '',
       'max' => '',
     ),
   ),
   'location' => array(
     array(
       array(
         'param' => 'post_type',
         'operator' => '==',
         'value' => 'post',
       ),
     ),
   ),
   'menu_order' => 0,
   'position' => 'normal',
   'style' => 'default',
   'label_placement' => 'top',
   'instruction_placement' => 'label',
   'hide_on_screen' => '',
   'active' => 0,
   'description' => '',
  ));

endif;

/**
 * Challenge: Local Jason setup
*/
add_filter('acf/settings/save_json', function ( $path ) {

    // update path
    $path = get_stylesheet_directory() . '/acf-json';

    // return
    return $path;

});