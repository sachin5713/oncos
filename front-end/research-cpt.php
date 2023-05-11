<?php
/**/
// Register Custom Post Type
function custom_post_type_research() {
    $labels = array(
        'name' => 'Research',
        'singular_name' => 'Research',
        'menu_name' => 'Research',
        'name_admin_bar' => 'Research',
        'archives' => 'Research Archives',
        'attributes' => 'Research Attributes',
        'parent_item_colon' => 'Parent Research:',
        'all_items' => 'All Research',
        'add_new_item' => 'Add New Research',
        'add_new' => 'Add New',
        'new_item' => 'New Research',
        'edit_item' => 'Edit Research',
        'update_item' => 'Update Research',
        'view_item' => 'View Research',
        'view_items' => 'View Research',
        'search_items' => 'Search Research',
        'not_found' => 'Not found',
        'not_found_in_trash' => 'Not found in Trash',
        'featured_image' => 'Featured Image',
        'set_featured_image' => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image' => 'Use as featured image',
        'insert_into_item' => 'Insert into Research',
        'uploaded_to_this_item' => 'Uploaded to this Research',
        'items_list' => 'Research list',
        'items_list_navigation' => 'Research list navigation',
        'filter_items_list' => 'Filter Research list',
    );

    $args = array(
        'label' => 'Research',
        'description' => 'Custom post type for research',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-book-alt',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'research'),
    );
    register_post_type('research', $args);
}
add_action('init', 'custom_post_type_research');

// Register Custom Taxonomy
function custom_taxonomy_research_category() {
    $labels = array(
        'name' => 'Research Categories',
        'singular_name' => 'Research Category',
        'menu_name' => 'Research Categories',
        'all_items' => 'All Categories',
        'edit_item' => 'Edit Category',
        'view_item' => 'View Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'parent_item' => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'search_items' => 'Search Categories',
        'popular_items' => 'Popular Categories',
        'separate_items_with_commas' => 'Separate categories with commas',
        'add_or_remove_items' => 'Add or remove categories',
        'choose_from_most_used' => 'Choose from the most used categories',
        'not_found' => 'No categories found',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => 
        ('research'),
    );
register_taxonomy('research_category', 'research', $args);
}
add_action('init', 'custom_taxonomy_research_category');

function show_research_category_admin_bar($wp_admin_bar) {
    if (is_admin()) {
        $args = array(
            'taxonomy' => 'research_category',
            'show_option_all' => 'All Research Categories',
            'name' => 'Research Category',
            'id' => 'research_category',
            'class' => 'admin-bar-research-category',
        );

        $wp_admin_bar->add_node($args);
    }
}
add_action('admin_bar_menu', 'show_research_category_admin_bar', 999);