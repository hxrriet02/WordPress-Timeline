<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_shortcode("timeline", "hkh_timeline_shortcode");

add_action("wp_enqueue_scripts", "hkh_timeline_scripts", 1000);

function hkh_timeline_scripts() {
    wp_enqueue_style("hkh-timeline-style", plugin_dir_url(__FILE__) . "style.css");
}

function hkh_timeline_shortcode($attrs) {
    $items = get_posts([
        "post_type" => "timeline_item",
        "limit" => -1,
        "orderby" => "menu_order",
        "order" => "ASC"
    ]);

    $output = "<div class=\"hkh_timeline\">";

    $left_or_right = "left";

    foreach ($items as $item) {
        $output .= '<div class="row ' . $left_or_right . '">
        <div class="col-md title">
            <h4>' . $item->post_title . '</h4>
        </div>
        
        <div class="col-md">
            <span>' . $item->post_content . '</span>
        </div>
    </div>';

        $left_or_right = $left_or_right == "left" ? "right" : "left";
    }

    $output .= "</div>";

    return $output;
}