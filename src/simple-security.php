<?php

/*
 * Plugin Name:       Simple Wordpress Security
 * Plugin URI:        https://github.com/posixpascal/simple-wordpress-security
 * Description:       Single-File plugin to make wordpress safer by disabling features not needed in modern websites
 * Version:           $VERSION
 * Requires at least: 6.0
 * Requires PHP:      8.2
 * Author:            posixpascal
 * Author URI:        https://github.com/posixpascal
 * License:           MIT
 */

if (!defined('ABSPATH')){
    exit;
}

/**
 * Security Settings
 */
const SimpleSecuritySettings = [
    "disable_rest_endpoints" => [
        "label" => "Disable REST API Endpoints",
        "description" => "Disables user enumeration and other sensitive data exposure via the WP REST API, enhancing security against unauthorized data access.",
        "input" => "settingsCheckbox",
    ],
    "disable_head_data" => [
        "label" => "Remove Meta Data from Header",
        "description" => "Removes unnecessary metadata from the site's head section, such as RSD, WLW, and REST API links, which can expose information to attackers.",
        "input" => "settingsCheckbox",
    ],
    "disable_plugin_update_mail" => [
        "label" => "Disable Plugin Update Notification Emails",
        "description" => "Stops WordPress from sending email notifications about plugin updates, reducing unnecessary email traffic.",
        "input" => "settingsCheckbox",
    ],
    "disable_pingbacks" => [
        "label" => "Disable XML-RPC Pingbacks",
        "description" => "Prevents pingback requests via XML-RPC, which can be used in DDoS attacks and other exploits.",
        "input" => "settingsCheckbox",
    ],
    "disable_debug" => [
        "label" => "Disable Debug Mode",
        "description" => "Disables WordPress debug mode to prevent sensitive error messages from being displayed to users, which can reveal vulnerabilities.",
        "input" => "settingsCheckbox",
    ],
    "disable_theme_update_mail" => [
        "label" => "Disable Theme Update Notification Emails",
        "description" => "Prevents WordPress from sending email notifications about theme updates, reducing administrative noise.",
        "input" => "settingsCheckbox",
    ],
    "disable_wp_embed" => [
        "label" => "Disable WordPress Embed Scripts",
        "description" => "Removes the functionality that allows embedding external content into posts, which reduces the risk of content injection vulnerabilities.",
        "input" => "settingsCheckbox",
    ],
    "disable_multisite_signup" => [
        "label" => "Disable Multisite Signup Feature",
        "description" => "Removes the ability for users to sign up for a new site on a WordPress multisite network, enhancing security by limiting public access.",
        "input" => "settingsCheckbox",
    ],
    "disable_file_editor" => [
        "label" => "Disable File Editors in Admin",
        "description" => "Prevents access to the theme and plugin editors in the WordPress dashboard, reducing the risk of malicious code injection through the admin panel.",
        "input" => "settingsCheckbox",
    ],
    "disable_feeds" => [
        "label" => "Disable RSS and Atom Feeds",
        "description" => "Disables WordPress-generated RSS and Atom feeds to prevent unwanted content scraping and reduce resource usage.",
        "input" => "settingsCheckbox",
    ],
    "disable_wp_version" => [
        "label" => "Remove WordPress Version Info",
        "description" => "Removes the WordPress version number from the site's HTML and scripts to reduce the risk of targeted attacks based on known vulnerabilities in specific versions.",
        "input" => "settingsCheckbox",
    ],
    "disable_wp_auto_update" => [
        "label" => "Disable Automatic WordPress Updates",
        "description" => "Stops automatic updates for WordPress core files, giving you full control over when updates are applied.",
        "input" => "settingsCheckbox",
    ]
];


/**
 * Business Logic
 */
/** @noinspection PhpUnused */
function disable_rest_endpoints(): void
{
    add_filter('rest_endpoints', function ($endpoints) {
        foreach ($endpoints as $route => $endpoint) {
            if (strpos($route, '/wp/v2/users') === 0) {
                unset($endpoints[$route]);
            }
        }
        return $endpoints;
    });
}

/** @noinspection PhpUnused */
function disable_head_data(): void
{
    // Remove post and comment feed link
    remove_action('wp_head', 'feed_links', 2);

    // Remove post category links
    remove_action('wp_head', 'feed_links_extra', 3);

    // Remove link to the Really Simple Discovery service endpoint
    remove_action('wp_head', 'rsd_link');

    // Remove the link to the Windows Live Writer manifest file
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove the XHTML generator that is generated on the wp_head hook, WP version
    remove_action('wp_head', 'wp_generator');

    // Remove start link
    remove_action('wp_head', 'start_post_rel_link');

    // Remove index link
    remove_action('wp_head', 'index_rel_link');

    // Remove previous link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);

    // Remove relational links for the posts adjacent to the current post
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

    // Remove relational links for the posts adjacent to the current post
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove Link header for REST API
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);

    // Remove Link header for shortlink
    remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
}

/** @noinspection PhpUnused */
function disable_pingbacks(): void
{
    add_filter('wp_xmlrpc_server_class', fn() => false) && add_filter('xmlrpc_enabled', fn() => false);
    add_filter('pings_open', fn() => false);
    add_filter('wp_headers', fn($headers) => _unset($headers['X-Pingback']));
}

/** @noinspection PhpUnused */
function disable_multisite_signup(): void
{
    add_action('signup_header', fn() => !is_user_logged_in() && wp_redirect(site_url()));
}

/** @noinspection PhpUnused */
function disable_file_editor(): void
{
    define('DISALLOW_FILE_EDIT', true);
}

/** @noinspection PhpUnused */
function disable_plugin_update_mail(): void
{
    add_filter('auto_plugin_update_send_email', fn() => false);
}

/** @noinspection PhpUnused */
function disable_theme_update_mail(): void
{
    add_filter('auto_theme_update_send_email', fn() => false);
}

/** @noinspection PhpUnused */
function disable_feeds(): void
{
    /**
     * List of feeds to disable
     */
    $feeds = [
        'do_feed',
        'do_feed_rdf',
        'do_feed_rss',
        'do_feed_rss2',
        'do_feed_atom',
        'do_feed_rss2_comments',
        'do_feed_atom_comments',
    ];

    foreach ($feeds as $feed) {
        add_action($feed, fn() => wp_die('Feed has been disabled.', '', ['response' => 403]), 1);
    }
}

/** @noinspection PhpUnused */
function disable_wp_version(): void
{
    add_filter('the_generator', fn() => "");
    add_filter('style_loader_src', fn($src) => remove_query_arg("ver", $src), PHP_INT_MAX);
    add_filter('script_loader_src', fn($src) => remove_query_arg("ver", $src), PHP_INT_MAX);
}

/** @noinspection PhpUnused */
function disable_wp_embed(): void
{
    add_action('wp_footer', fn() => wp_deregister_script('wp-embed'));
}


function _unset(&$what)
{
    unset($what);
}


/**
 * Wordpress Plugin
 */
if (!class_exists('SimpleSecurityPlugin')) {
    class SimpleSecurityPlugin
    {
        /**
         * Initializers
         */
        public static function initPages()
        {
            add_options_page(
                __("Simple Security", "ssp"),
                __("Simple Security Settings", "ssp"),
                "manage_options",
                "simple-security-settings-page",
                [SimpleSecurityPlugin::class, 'settingsPage']
            );
        }

        public static function initSettings()
        {
            register_setting("ssp", "ssp_settings");
            add_settings_section(
                "ssp_core_section",
                __("Simple Security Plugin", "ssp"),
                [SimpleSecurityPlugin::class, "settingsCoreSection"],
                'ssp'
            );

            foreach (SimpleSecuritySettings as $field => $args) {
                add_settings_field(
                    $field,
                    __($args['label'], 'ssp'),
                    [SimpleSecurityPlugin::class, $args['input']],
                    'ssp',
                    'ssp_core_section',
                    array_merge(["field" => $field], $args)
                );
            }
        }

        public static function settingsCoreSection(): void
        {
            ?>
            <p>
                These settings allow you to enhance the security of your WordPress installation by disabling or
                modifying certain features and behaviors.
            </p>
            <p>
                By adjusting these options, you can reduce the attack surface
                of your website, limit access to sensitive information, and improve overall privacy and performance.
            </p>
            <p>
                Please review each setting carefully and enable the options that align with your security needs. <br/>
                These changes are especially useful in hardening your WordPress site against common vulnerabilities and
                exploits.
            </p>
            <?php
        }

        /** @noinspection PhpUnused */
        public static function settingsCheckbox($args): void
        {
            $options = get_option('ssp_settings');
            ?>
            <input
                    id="<?= $args['field']; ?>"
                    type='checkbox'
                    name='ssp_settings[<?= $args['field'] ?>]'
            <?= isset($options[$args['field']]) && $options[$args['field']] ? "checked='on'" : ""; ?>'
            >
            <label for="<?= $args['field']; ?>"><?= $args['description']; ?></label>
            <?php
        }

        public static function settingsPage(): void
        {
            ?>
            <form action="options.php" method="post">
                <?php
                settings_fields('ssp');
                do_settings_sections('ssp');
                submit_button();
                ?>
            </form>
            <?php
        }

        public static function applySettings(): void
        {
            $options = get_option('ssp_settings');
            foreach ($options as $key => $value) {
                if ($value !== "on") {
                    continue;
                }

                if (function_exists($key)) {
                    call_user_func($key);
                }
            }
        }
    }

    add_action("admin_menu", [SimpleSecurityPlugin::class, 'initPages']);
    add_action("admin_init", [SimpleSecurityPlugin::class, 'initSettings']);
    add_action("init", [SimpleSecurityPlugin::class, 'applySettings']);
}