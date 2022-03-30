<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action("init", "hkh_timeline_item_post_type", 0);

function hkh_timeline_item_post_type() {
    $labels = [
        "name"                  => _x("Timeline Items", "Post Type General Name", "hkh"),
        "singular_name"         => _x("Timeline Item", "Post Type Singular Name", "hkh"),
        "menu_name"             => __("Timeline Items", "hkh"),
        "name_admin_bar"        => __("Timeline Items", "hkh"),
        "archives"              => __("Item Archives", "hkh"),
        "attributes"            => __("Item Attributes", "hkh"),
        "parent_item_colon"     => __("Parent Item:", "hkh"),
        "all_items"             => __("All Items", "hkh"),
        "add_new_item"          => __("Add New Item", "hkh"),
        "add_new"               => __("Add New", "hkh"),
        "new_item"              => __("New Item", "hkh"),
        "edit_item"             => __("Edit Item", "hkh"),
        "update_item"           => __("Update Item", "hkh"),
        "view_item"             => __("View Item", "hkh"),
        "view_items"            => __("View Items", "hkh"),
        "search_items"          => __("Search Item", "hkh"),
        "not_found"             => __("Not found", "hkh"),
        "not_found_in_trash"    => __("Not found in Trash", "hkh"),
        "featured_image"        => __("Featured Image", "hkh"),
        "set_featured_image"    => __("Set featured image", "hkh"),
        "remove_featured_image" => __("Remove featured image", "hkh"),
        "use_featured_image"    => __("Use as featured image", "hkh"),
        "insert_into_item"      => __("Insert into item", "hkh"),
        "uploaded_to_this_item" => __("Uploaded to this item", "hkh"),
        "items_list"            => __("Items list", "hkh"),
        "items_list_navigation" => __("Items list navigation", "hkh"),
        "filter_items_list"     => __("Filter items list", "hkh"),
    ];

    $args = [
        "label"                 => __("Timeline Item", "hkh"),
        "description"           => __("Timeline items for the [timeline] shortcode", "hkh"),
        "labels"                => $labels,
        "supports"              => ["title", "editor", "revisions", "page-attributes"],
        "taxonomies"            => [],
        "hierarchical"          => false,
        "public"                => true,
        "show_ui"               => true,
        "show_in_menu"          => true,
        "menu_position"         => 5,
        "menu_icon"             => "dashicons-ellipsis",
        "show_in_admin_bar"     => true,
        "show_in_nav_menus"     => true,
        "can_export"            => true,
        "has_archive"           => false,
        "exclude_from_search"   => true,
        "publicly_queryable"    => true,
        "rewrite"               => false,
        "capability_type"       => "post",
    ];

    register_post_type("timeline_item", $args);
}

// Add the columns to the post type's list
add_filter("manage_timeline_item_posts_columns", 'hkh_timeline_item_list_column');
function hkh_timeline_item_list_column($columns) {
    $columns_to_insert = [
        "order" => __("Order", "hkh"),
        "content" => __("Content", "hkh"),
    ];

    $insert_index = 2;

    return
        array_slice($columns, 0, $insert_index)
        + $columns_to_insert
        + array_slice($columns, $insert_index)
    ;
}

// Add the data to the custom columns for the new_member_form post type:
add_action("manage_timeline_item_posts_custom_column", "hkh_timeline_item_list_column_content", 10, 2);
function hkh_timeline_item_list_column_content($column, $post_id) {
    if ($column == 'order') {
        echo get_post($post_id)->menu_order;
    } elseif ($column == 'content') {
        echo get_post($post_id)->post_content;
    }
}

// Sorts by order
add_action("pre_get_posts", "hkh_timeline_item_list_orderby");

function hkh_timeline_item_list_orderby($query) {
    if (!is_admin() || $query->get("post_type") != "timeline_item") {
        return;
    }

    $query->set("orderby", "menu_order");
    $query->set("order", "ASC");
}