<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('IMG_DIR_UPLOAD', 'http://localhost/piscium/wp-content/uploads');
} else {
    define('IMG_DIR_UPLOAD', 'http://www.piscium.co/wp-content/uploads');
}

if (!function_exists('piscium_setup')) :

    function piscium_setup() {
        load_theme_textdomain('piscium', get_template_directory() . '/languages');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'piscium'),
            'header-menu' => esc_html__('Header Menu', 'header-menu'),
        ));

        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        add_theme_support('custom-background', apply_filters('piscium_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        add_theme_support('customize-selective-refresh-widgets');

        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }

endif;
add_action('after_setup_theme', 'piscium_setup');

function piscium_content_width() {
    $GLOBALS['content_width'] = apply_filters('piscium_content_width', 640);
}

add_action('after_setup_theme', 'piscium_content_width', 0);

function piscium_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'piscium'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'piscium'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

//add_action( 'widgets_init', 'piscium_widgets_init' );

function piscium_scripts() {
    wp_enqueue_style('montserrat-font-style', 'https://fonts.googleapis.com/css?family=Montserrat:200,200i,400,400i,600');
    wp_enqueue_style('font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style('hover-style', get_template_directory_uri() . '/assets/css/hover-min.css');
    wp_enqueue_style('piscium-style', get_stylesheet_uri());

    wp_enqueue_script('jquery-cdn', 'https://code.jquery.com/jquery-3.2.1.min.js');
    wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/assets/js/bootstrap.min.js');
    wp_enqueue_script('piscium-script', get_template_directory_uri() . '/assets/js/piscium.js');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'piscium_scripts');

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/jetpack.php';

add_action('init', 'type_post_solucoes');

function type_post_solucoes() {
    $labels = array(
        'name' => _x('Soluções', 'post type general name'),
        'singular_name' => _x('Solução', 'post type singular name'),
        'add_new' => _x('Adicionar Nova', 'Nova solução'),
        'add_new_item' => __('Nova Solução'),
        'edit_item' => __('Editar Solução'),
        'new_item' => __('Nova Solução'),
        'view_item' => __('Ver Solução'),
        'search_items' => __('Procurar Soluções'),
        'not_found' => __('Nenhuma solução encontrada'),
        'not_found_in_trash' => __('Nenhuma solução encontrada na lixeira'),
        'parent_item_colon' => '',
        'menu_name' => 'Soluções'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'public_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-store',
        'menu_position' => 20,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions')
    );

    register_post_type('solucoes', $args);
    register_taxonomy(
            "categorias", "solucoes", array(
        "label" => "Categorias de Soluções",
        "singular_label" => "Categoria",
        "rewrite" => true,
        "hierarchical" => true
            )
    );
    flush_rewrite_rules();
}

add_action('init', 'type_post_portfolio');

function type_post_portfolio() {
    $labels = array(
        'name' => _x('Portfólio', 'post type general name'),
        'singular_name' => _x('Portfólio', 'post type singular name'),
        'add_new' => _x('Adicionar Portfólio', 'Nova solução'),
        'add_new_item' => __('Nova Portfólio'),
        'edit_item' => __('Editar Portfólio'),
        'new_item' => __('Nova Portfólio'),
        'view_item' => __('Ver Portfólio'),
        'search_items' => __('Procurar Portfólio'),
        'not_found' => __('Nenhum Portfólio encontrado'),
        'not_found_in_trash' => __('Nenhum Portfólio encontrado na lixeira'),
        'parent_item_colon' => '',
        'menu_name' => 'Portfólio'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'public_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_icon' => 'dashicons-format-gallery',
        'menu_position' => 21,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions')
    );

    register_post_type('portfolio', $args);
    register_taxonomy(
            "categorias-portfolio", "portfolio", array(
        "label" => "Categorias de Portfolio",
        "singular_label" => "Categoria de Portfolio",
        "rewrite" => true,
        "hierarchical" => true
            )
    );
    flush_rewrite_rules();
}
