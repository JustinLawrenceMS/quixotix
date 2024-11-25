<?php
/*
Plugin Name: Quixotix
Plugin URI: http://quixote.at.chiclay.com
Description: Generates ipsum text based on English translation of Don Quixote.
Version: 1.0.0
Author: justinlawrencems
Author URI: http://justin.at.chiclay.com
License: GPL2 or later
*/

require __DIR__ . '/vendor/autoload.php';

add_action('admin_post_quixotix_fake_blogpost', 'create_fake_blogpost_callback');

function create_fake_blogpost_callback(): void
{
    if (!isset($_POST['quixotix_fake_blogpost_nonce'])) {
        throw new Exception("Nonce request element is not set");
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['quixotix_fake_blogpost_nonce']));
    if (empty($nonce) || !wp_verify_nonce($nonce, 'quixotix_fake_blogpost')) {
        throw new Exception("Invalid nonce");
    }

    if (!empty($_POST['number'])) {
        $number = sanitize_text_field(wp_unslash($_POST['number']));
    }

    for ($i = 0; $i < $number; $i++) {
        $controller = new \Quixotify\Controller();
        $quixotify = new \Quixotify\Generator($controller);

        if (!isset($_POST['floor']) && !isset($_POST['ceiling']) && !is_int($_POST['floor']) && !is_int($_POST['ceiling']) && ($_POST['floor'] < 1 || $_POST['ceiling'] > 50)) {
            throw new Exception("Invalid bounds on blog post form");
        }
        $controller = new Quixotix\Controllers\FakedDataController(sanitize_text_field(wp_unslash($_POST['floor'])), sanitize_text_field(wp_unslash($_POST['ceiling'])), 'English', $quixotify, $controller);
        $post = $controller->generateBlogPost();

        $new_post = array('post_title' => $post['title'], 'post_content' => $post['content'], 'post_status' => 'publish', 'post_type' => 'post');

        wp_insert_post($new_post);
    }
    wp_redirect(home_url('quixotix'));
}

function quixotix_shortcode_handler($atts = [], $content = null, $tag = ''): string
{
    $atts[0] = $atts[0] ?? '50';
    $atts[1] = $atts[1] ?? 'characters';

    if (in_array($atts[0], ['characters', 'words', 'sentences'])) {
        $isAmountPositive = isset($atts[1]) && (int)$atts[1] > 0;

        $amount = $isAmountPositive ? (int)sanitize_text_field($atts[1]) : '50';

        $atts[1] = $atts[0];
        $atts[0] = $amount;
    }

    $output = '<div class="quixote-' . esc_attr($tag) . '">';

    $controller = new \Quixotify\Controller();
    $quixotify = new \Quixotify\Generator($controller);

    $output .= match ($atts[1]) {
        'words' => "<p>" . $quixotify->generate('words', $atts[0]) . "</p>",
        'sentences' => "<p>" . $quixotify->generate('sentences', $atts[0]) . "</p>",
        default => "<p>" . $quixotify->generate('characters', $atts[0]) . "</p>",
    };

    return $output;
}

// WordPress hook that connects shortcode to handler function
add_shortcode('quixotix', 'quixotix_shortcode_handler');

// Use the admin_menu action to define the custom pages
add_action('admin_menu', 'menu_page');

function menu_page(): void
{
    add_menu_page('Quixotix', // page title
        'Quixotix', // menu title
        'edit_pages', // capability
        'quixotix', // menu slug
        'quixotix_dashboard' // callback function
    );
}

// Callback function defines the content of the page
function quixotix_dashboard(): void
{
    include __DIR__ . '/views/dashboard.php';
}

function enqueue_quixotix_stylesheet(): void
{
    wp_enqueue_style('enqueue_quixotix_stylesheet', plugin_dir_url(__FILE__) . '/assets/css/quixotix.css', false); //Provide the actual path to your CSS file
}

add_action('admin_enqueue_scripts', 'enqueue_quixotix_stylesheet');

function enqueue_boxicons_stylesheet(): void
{
    wp_enqueue_style('boxicons_style', plugin_dir_url(__FILE__) . 'assets/css/boxicons.min.css', false); //Provide the actual path to your CSS file
}

add_action('admin_enqueue_scripts', 'enqueue_boxicons_stylesheet');