<?php
/*
Plugin Name: RSS Feed Ticker
Description: Display a news ticker using an RSS feed.
Version: 1.0
Author: Kwaku Dzaho
*/

// Enqueue JavaScript and CSS
function rss_news_ticker_enqueue_scripts() {
    // Enqueue JavaScript
    wp_enqueue_script('news-ticker-script', plugins_url('news-ticker.js', __FILE__), array('jquery'), '1.0', true);
    // Enqueue CSS
    wp_enqueue_style('news-ticker-style', plugins_url('news-ticker.css', __FILE__), array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'rss_news_ticker_enqueue_scripts');

// Function to display the news ticker
function custom_rss_ticker_shortcode($atts) {
// Extract shortcode attributes
$atts = shortcode_atts(
array(
'feed_url' => '', // Default feed URL
'num_items' => 20, // Default number of items
'cache_duration' => 3600, // Default cache duration in seconds (1 hour)
),
$atts,
'rss_ticker'
);

// Initialize output variable
$output = '';

// Check if the transient exists and is valid
$cached_ticker = get_transient('rss_ticker_' . md5($atts['feed_url']));
if ($cached_ticker) {
// Use cached ticker content
$output .= $cached_ticker;
} else {
// Fetch RSS feed
$rss = fetch_feed($atts['feed_url']);

if (!is_wp_error($rss)) {
$maxitems = $rss->get_item_quantity($atts['num_items']);
$rss_items = $rss->get_items(0, $maxitems);

// Start constructing the ticker content
$output .= '<div class="rss-ticker-container">';
    $output .= '<ul class="rss-ticker">';

        // Loop through each feed item and add it to the ticker
        foreach ($rss_items as $item) {
        $output .= '<li><a href="' . esc_url($item->get_permalink()) . '" target="_blank" rel="noopener noreferrer">' . esc_html($item->get_title()) . '</a></li>';
        }

        // End ticker content
        $output .= '</ul>';
    $output .= '</div>';

// Cache the ticker content for future use
set_transient('rss_ticker_' . md5($atts['feed_url']), $output, $atts['cache_duration']);
} else {
// Error fetching RSS feed
$output .= '<p>Error fetching RSS feed: ' . $rss->get_error_message() . '</p>';
}
}

return $output;
}

// Register shortcode
add_shortcode('rss_ticker', 'custom_rss_ticker_shortcode');