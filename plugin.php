<?php
/*
Plugin Name: Domain Swap
Plugin URI: https://github.com/gmolop/yourls-domain-swap
Description: Allows switching domains (for the same instance of YOURLS) when working with domain aliases.
Version: 1.0
Author: gmolop
Author URI: https://github.com/search?q=user%3Agmolop+yourls
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

/**
 * Admin manage
 */
yourls_add_action( 'plugins_loaded', 'gmo_domain_swap_add_page' );
function gmo_domain_swap_add_page() {
    yourls_register_plugin_page( 'domain_swap', 'Domain Swap', 'gmo_domain_swap_do_page' );
}

// Display admin page
function gmo_domain_swap_do_page() {

    // Check if a form was submitted
    if( isset( $_POST['domain_swap_values'] ) ) {
        // Check nonce
        yourls_verify_nonce( 'domain_swap' );

        // Process form
        gmo_domain_swap_update_option();
    }

    // Get value from database
    $domain_swap_values      = yourls_get_option( 'domain_swap_values' );
    $domain_swap_values_json = json_decode( $domain_swap_values );

    $domain_swap_values_list = '';
    $count_domains = count($domain_swap_values_json->domains) + 1;

    foreach ($domain_swap_values_json->domains as $domain) {
        $domain_swap_values_list .= $domain . PHP_EOL;
    }
    $domain_swap_values_list = trim($domain_swap_values_list);

    // Create nonce
    $nonce = yourls_create_nonce( 'domain_swap' );

    echo <<<HTML
        <h2>Domain Swap Configuration Page</h2>
        <p>Enter here a list with domain names you want to swap from.</p>
        <form method="post">
        <input type="hidden" name="nonce" value="$nonce" />
        <p><label for="domain_swap_values">Domains: </label></p>
        <P><textarea rows="{$count_domains}" cols="50" name="domain_swap_values">{$domain_swap_values_list}</textarea></p>
        <p>Notes:</p>
        <ul>
            <li>One entry per line</li>
            <li>No trailing slash</li>
            <li>No protocol</li>
            <li>e.g.
                <ul>
                    <li>[ok] example.com</li>
                    <li>[ok] sub.example.com</li>
                    <li>[bad] http://example.com</li>
                    <li>[bad] example.com/</li>
                </ul>
            </li>
        </ul>
        <p><input type="submit" value="Update value" /></p>
        </form>

HTML;
}

function gmo_domain_trim_value(&$value) {
    $value = trim($value);
}

// Update option in database
function gmo_domain_swap_update_option() {


    $in = $_POST['domain_swap_values'];

    if ( !empty($in) ) {

        $in   = preg_split('/(\r?\n)+/', trim($in));
        array_walk($in, "gmo_domain_trim_value");

        $arr  = array( 'domains' => $in );
        $json = json_encode( $arr );

        yourls_update_option( 'domain_swap_values', $json );
    }

}

/**
 * User action
 */
if ( basename($_SERVER['PHP_SELF']) == 'index.php' ) yourls_add_action( 'admin_menu', 'gmo_domain_swap_add_menu' );

function gmo_domain_swap_add_menu() {
    echo '<li>';
    echo 'Active domain: <select onchange="window.location.hostname = this.value;">';

    $domain_swap_values = json_decode( yourls_get_option( 'domain_swap_values' ) );
    foreach ($domain_swap_values->domains as $domain) {
        $selected = ($_SERVER["SERVER_NAME"] == $domain ? 'selected' : '');
        echo '<option ' . $selected . ' value="' . $domain . '"/>//' . $domain . '/';
    }
    echo '</select>';
    echo '</li>';
}
