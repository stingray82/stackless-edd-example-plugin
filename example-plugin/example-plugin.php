<?php
/**
 * Plugin Name:       Example Plugin
 * Description:       A test plugin demonstrating UUPD_Updater integration.
 * Tested up to:      7.1.3
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Version:           1.0.0.4
 * Author:            Nathan Foley
 * Author URI:        https://reallyusefulplugins.com
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       example-plugin
 * Website:           https://reallyusefulplugins.com
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'UUPD_RUP_EXAMPLE_PLUGIN2_VENDOR', 'rup' );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_VERSION', '1.0.0.4' );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_MAIN_FILE', __FILE__ );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_ITEM_NUMBER', 92 );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_FRIENDLY_NAME', 'Example Plugin 2' );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_SLUG', 'example-plugin2' );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_SERVER_BASE', 'https://uupd.tdlab.uk' );
	define( 'UUPD_RUP_EXAMPLE_PLUGIN2_MENU_SLUG', 'esp-example-setting-page' );

	add_action( 'plugins_loaded', function() {
	    require_once __DIR__ . '/inc/updater.php';
	    require_once __DIR__ . '/inc/class-uupd-license-ui.php';

	    \UUPD\UI\License\V1\UUPD_License_UI::register([
	        'vendor'         => UUPD_RUP_EXAMPLE_PLUGIN2_VENDOR,
	        'slug'           => UUPD_RUP_EXAMPLE_PLUGIN2_SLUG,
	        'item_id'        => UUPD_RUP_EXAMPLE_PLUGIN2_ITEM_NUMBER,
	        'license_server' => UUPD_RUP_EXAMPLE_PLUGIN2_SERVER_BASE,
	        'metadata_base'  => UUPD_RUP_EXAMPLE_PLUGIN2_SERVER_BASE,
	        'plugin_name'    => UUPD_RUP_EXAMPLE_PLUGIN2_FRIENDLY_NAME,
	        'menu_parent'    => false,
	        'menu_slug'      => UUPD_RUP_EXAMPLE_PLUGIN2_MENU_SLUG,
	        'return_url'     => admin_url( 'admin.php?page=esp-example-setting-page#esp-licence' ),
        'page_title'     => 'Example Plugin 2 License',
	        'menu_title'     => 'License',
	        'capability'     => 'manage_options',
	        'pass_token'     => false,
	        'token_param'    => 'token',
	    ]);

	    \UUPD\V2\UUPD_Updater_V2::register([
	        'vendor'      => UUPD_RUP_EXAMPLE_PLUGIN2_VENDOR,
	        'plugin_file' => plugin_basename( UUPD_RUP_EXAMPLE_PLUGIN2_MAIN_FILE ),
	        'slug'        => UUPD_RUP_EXAMPLE_PLUGIN2_SLUG,
	        'name'        => UUPD_RUP_EXAMPLE_PLUGIN2_FRIENDLY_NAME,
	        'version'     => UUPD_RUP_EXAMPLE_PLUGIN2_VERSION,
	        'server'      => '',
	        'license_key_transport' => 'header',
	    ]);
	}, 1 );

/**
 * Plugin Control - Premium Test Settings Page
 *
 * Uses your existing UUPD License UI instance.
 * Add to your test plugin or Code Snippets.
 */

/* ------------------------------------------
 * 1. ADMIN MENU
 * ------------------------------------------ */

add_action('admin_menu', 'esp_add_settings_page');

function esp_add_settings_page() {
    add_menu_page(
        'Plugin Control',
        'Plugin Control',
        'manage_options',
        'esp-example-setting-page',
        'esp_render_settings_page',
        'dashicons-admin-generic',
        58
    );
}

/* ------------------------------------------
 * 2. REGISTER SETTINGS
 * ------------------------------------------ */

add_action('admin_init', 'esp_register_settings');

function esp_register_settings() {

    register_setting('esp_settings_group', 'esp_text_option', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => ''
    ));

    register_setting('esp_settings_group', 'esp_enable_feature', array(
        'type'              => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default'           => false
    ));

    register_setting('esp_settings_group', 'esp_debug_mode', array(
        'type'              => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default'           => false
    ));

    register_setting('esp_settings_group', 'esp_notification_email', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_email',
        'default'           => ''
    ));

}

/* ------------------------------------------
 * 3. ADMIN PAGE STYLES
 * ------------------------------------------ */

add_action('admin_enqueue_scripts', 'esp_admin_styles');

function esp_admin_styles($hook) {

    if ($hook !== 'toplevel_page_esp-example-setting-page') {
        return;
    }

    wp_register_style('esp-admin-style', false, array(), '1.0.0');
    wp_enqueue_style('esp-admin-style');

    $css = <<<'CSS'

#wpcontent {
    background: #f6f8fc;
}

.esp-app {
    max-width: 1250px;
    margin: 30px 30px 40px 10px;
    color: #1e293b;
}

.esp-app * {
    box-sizing: border-box;
}

.esp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 26px;
}

.esp-brand {
    display: flex;
    align-items: center;
    gap: 15px;
}

.esp-logo {
    width: 54px;
    height: 54px;
    border-radius: 15px;
    background: linear-gradient(135deg, #6366f1, #4338ca);
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 8px 20px #4f46e533;
}

.esp-logo .dashicons {
    color: white;
    font-size: 29px;
    width: 29px;
    height: 29px;
}

.esp-header h1 {
    padding: 0;
    margin: 0 0 5px;
    font-size: 25px;
    font-weight: 700;
    color: #0f172a;
}

.esp-subtitle {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.esp-version {
    border: 1px solid #e2e8f0;
    padding: 8px 13px;
    border-radius: 30px;
    background: white;
    font-size: 12px;
    color: #475569;
    font-weight: 600;
}

.esp-hero {
    border-radius: 17px;
    background: linear-gradient(110deg, #312e81, #4f46e5);
    padding: 32px;
    color: white;
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.esp-hero:after {
    content: '';
    position: absolute;
    right: -70px;
    top: -110px;
    width: 320px;
    height: 320px;
    border: 65px solid #ffffff12;
    border-radius: 50%;
    pointer-events: none;
}

.esp-hero h2 {
    color: white;
    margin: 0 0 10px;
    font-size: 25px;
    font-weight: 700;
}

.esp-hero p {
    color: #e0e7ff;
    font-size: 14px;
    max-width: 570px;
    line-height: 1.7;
    margin: 0;
}

.esp-hero-tag {
    display: inline-block;
    background: #ffffff22;
    border: 1px solid #ffffff33;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 14px;
}

.esp-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.esp-stat,
.esp-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 14px;
    box-shadow: 0 4px 20px #0f172a05;
}

.esp-stat {
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.esp-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #eef2ff;
    color: #4f46e5;
}

.esp-stat-icon.green {
    background: #dcfce7;
    color: #16a34a;
}

.esp-stat-icon.amber {
    background: #fef3c7;
    color: #d97706;
}

.esp-stat-icon .dashicons {
    font-size: 23px;
    width: 23px;
    height: 23px;
}

.esp-stat-label {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 5px;
}

.esp-stat-value {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
}

.esp-layout {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr);
    gap: 22px;
    align-items: start;
}

.esp-sidebar {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 14px;
    padding: 14px;
    position: sticky;
    top: 48px;
}

.esp-sidebar-label {
    font-size: 10px;
    letter-spacing: 1.2px;
    font-weight: 700;
    color: #94a3b8;
    padding: 12px 11px;
}

.esp-nav-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 12px;
    border-radius: 9px;
    color: #475569;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 5px;
    font-size: 13px;
}

.esp-nav-item:hover,
.esp-nav-item:focus {
    background: #f1f5f9;
    color: #4338ca;
}

.esp-nav-item.active {
    background: #eef2ff;
    color: #4338ca;
}

.esp-nav-item .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

.esp-main {
    min-width: 0;
}

.esp-card {
    margin-bottom: 22px;
    overflow: hidden;
}

.esp-card-head {
    padding: 22px 25px;
    border-bottom: 1px solid #edf2f7;
}

.esp-card-head h2 {
    margin: 0 0 7px;
    padding: 0;
    color: #0f172a;
    font-weight: 700;
    font-size: 17px;
}

.esp-card-head p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.esp-card-body {
    padding: 25px;
}

.esp-field {
    padding: 0 0 23px;
    margin-bottom: 23px;
    border-bottom: 1px solid #f1f5f9;
}

.esp-field:last-child {
    padding: 0;
    margin: 0;
    border-bottom: 0;
}

.esp-field label.esp-field-title {
    display: block;
    color: #1e293b;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
}

.esp-field-description {
    color: #64748b;
    font-size: 12px;
    line-height: 1.6;
    margin: 7px 0 0;
}

.esp-input {
    width: 100%;
    max-width: 460px;
    padding: 11px 13px !important;
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
    background: #fff !important;
}

.esp-input:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px #6366f122 !important;
}

.esp-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.esp-toggle-row strong {
    display: block;
    font-size: 13px;
    color: #1e293b;
}

.esp-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.esp-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.esp-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 30px;
    cursor: pointer;
    transition: .2s;
}

.esp-slider:before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .2s;
    box-shadow: 0 2px 4px #0002;
}

.esp-switch input:checked + .esp-slider {
    background: #4f46e5;
}

.esp-switch input:checked + .esp-slider:before {
    transform: translateX(20px);
}

.esp-switch input:focus-visible + .esp-slider {
    outline: 3px solid #a5b4fc;
    outline-offset: 3px;
}

.esp-card-footer {
    border-top: 1px solid #edf2f7;
    padding: 18px 25px;
    background: #fafbff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.esp-save {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    border-radius: 8px !important;
    padding: 7px 20px !important;
    font-weight: 600 !important;
    min-height: 39px;
}

.esp-save:hover {
    background: #4338ca !important;
}

.esp-muted {
    color: #94a3b8;
    font-size: 12px;
}

.esp-license-area {
    min-width: 0;
}

.esp-license-area .wrap {
    margin: 0;
}

.esp-license-warning {
    background: #fffbeb;
    border: 1px solid #fde68a;
    padding: 15px;
    border-radius: 9px;
    color: #92400e;
}

.esp-system-table {
    width: 100%;
    border-collapse: collapse;
}

.esp-system-table td {
    padding: 13px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}

.esp-system-table tr:last-child td {
    border-bottom: 0;
}

.esp-system-table td:first-child {
    color: #64748b;
}

.esp-system-table td:last-child {
    text-align: right;
    font-weight: 600;
}

.esp-pill {
    display: inline-block;
    background: #dcfce7;
    color: #166534;
    padding: 5px 10px;
    font-size: 11px;
    border-radius: 20px;
    font-weight: 700;
}

.esp-footer {
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 25px;
}

html {
    scroll-behavior: smooth;
}

.esp-card {
    scroll-margin-top: 45px;
}

@media (max-width: 1000px) {
    .esp-layout {
        grid-template-columns: 1fr;
    }

    .esp-sidebar {
        position: static;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .esp-sidebar-label {
        display: none;
    }

    .esp-nav-item {
        margin: 0;
    }
}

@media (max-width: 782px) {
    .esp-app {
        margin: 20px 12px 30px 0;
    }

    .esp-stats {
        grid-template-columns: 1fr;
    }

    .esp-header,
    .esp-card-footer {
        flex-wrap: wrap;
    }

    .esp-hero {
        padding: 24px;
    }

    .esp-card-body {
        padding: 18px;
    }
}

CSS;

    wp_add_inline_style('esp-admin-style', $css);
}

/* ------------------------------------------
 * 4. RENDER DASHBOARD
 * ------------------------------------------ */

function esp_render_settings_page() {

    if (!current_user_can('manage_options')) {
        return;
    }

    $text_value = get_option('esp_text_option', '');
    $email = get_option('esp_notification_email', '');
    $feature = (bool) get_option('esp_enable_feature', false);
    $debug = (bool) get_option('esp_debug_mode', false);

    $wp_version = get_bloginfo('version');
    $php_version = PHP_VERSION;

    ?>

    <div class="esp-app">

        <!-- HEADER -->
        <header class="esp-header">

            <div class="esp-brand">

                <div class="esp-logo">
                    <span class="dashicons dashicons-admin-plugins"></span>
                </div>

                <div>
                    <h1>Plugin Control</h1>
                    <p class="esp-subtitle">
                        Manage your plugin, licence and configuration.
                    </p>
                </div>

            </div>

            <span class="esp-version">Version 1.0.0</span>

        </header>

        <!-- HERO -->
        <section class="esp-hero">

            <span class="esp-hero-tag">
                PLUGIN MANAGEMENT
            </span>

            <h2>Welcome to your dashboard</h2>

            <p>
                Manage your plugin preferences, configure its
                features and control licence activation from
                one convenient location.
            </p>

        </section>

        <!-- STATS -->
        <div class="esp-stats">

            <div class="esp-stat">
                <div class="esp-stat-icon green">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div>
                    <div class="esp-stat-label">Plugin Status</div>
                    <div class="esp-stat-value">Installed</div>
                </div>
            </div>

            <div class="esp-stat">
                <div class="esp-stat-icon">
                    <span class="dashicons dashicons-admin-network"></span>
                </div>
                <div>
                    <div class="esp-stat-label">Licence Manager</div>
                    <div class="esp-stat-value">UUPD Integration</div>
                </div>
            </div>

            <div class="esp-stat">
                <div class="esp-stat-icon amber">
                    <span class="dashicons dashicons-admin-settings"></span>
                </div>
                <div>
                    <div class="esp-stat-label">Feature Mode</div>
                    <div class="esp-stat-value">
                        <?php echo $feature ? 'Enabled' : 'Disabled'; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- CONTENT LAYOUT -->
        <div class="esp-layout">

            <!-- SIDEBAR -->
            <aside class="esp-sidebar">

                <div class="esp-sidebar-label">MANAGEMENT</div>

                <a href="#esp-general" class="esp-nav-item active">
                    <span class="dashicons dashicons-admin-generic"></span>
                    General Settings
                </a>

                <a href="#esp-licence" class="esp-nav-item">
                    <span class="dashicons dashicons-admin-network"></span>
                    Licence Manager
                </a>

                <a href="#esp-system" class="esp-nav-item">
                    <span class="dashicons dashicons-dashboard"></span>
                    System Information
                </a>

                <div class="esp-sidebar-label">WORDPRESS</div>

                <a href="<?php echo esc_url(admin_url('plugins.php')); ?>"
                   class="esp-nav-item">
                    <span class="dashicons dashicons-admin-plugins"></span>
                    Installed Plugins
                </a>

            </aside>

            <!-- MAIN CONTENT -->
            <main class="esp-main">

                <!-- GENERAL SETTINGS -->
                <section id="esp-general" class="esp-card">

                    <div class="esp-card-head">
                        <h2>General Settings</h2>
                        <p>
                            Configure the core behaviour of your plugin.
                        </p>
                    </div>

                    <form method="post" action="options.php">

                        <?php settings_fields('esp_settings_group'); ?>

                        <div class="esp-card-body">

                            <div class="esp-field">

                                <label class="esp-field-title"
                                       for="esp_text_option">
                                    Plugin Display Name
                                </label>

                                <input
                                    type="text"
                                    id="esp_text_option"
                                    name="esp_text_option"
                                    class="esp-input"
                                    placeholder="Enter a display name"
                                    value="<?php echo esc_attr($text_value); ?>"
                                >

                                <p class="esp-field-description">
                                    A custom name used to identify this
                                    plugin configuration.
                                </p>

                            </div>

                            <div class="esp-field">

                                <label class="esp-field-title"
                                       for="esp_notification_email">
                                    Notification Email
                                </label>

                                <input
                                    type="email"
                                    id="esp_notification_email"
                                    name="esp_notification_email"
                                    class="esp-input"
                                    placeholder="admin@example.com"
                                    value="<?php echo esc_attr($email); ?>"
                                >

                                <p class="esp-field-description">
                                    Stores the email address for future
                                    notification functionality.
                                </p>

                            </div>

                            <div class="esp-field">

                                <div class="esp-toggle-row">

                                    <div>
                                        <strong>Enable Plugin Features</strong>
                                        <p class="esp-field-description">
                                            Toggle the example plugin feature flag.
                                        </p>
                                    </div>

                                    <label class="esp-switch">
                                        <input type="hidden"
                                               name="esp_enable_feature"
                                               value="0">

                                        <input type="checkbox"
                                               name="esp_enable_feature"
                                               value="1"
                                               aria-label="Enable plugin features"
                                               <?php checked($feature); ?>>

                                        <span class="esp-slider"></span>
                                    </label>

                                </div>

                            </div>

                            <div class="esp-field">

                                <div class="esp-toggle-row">

                                    <div>
                                        <strong>Debug Mode</strong>
                                        <p class="esp-field-description">
                                            Store a flag for optional debugging.
                                        </p>
                                    </div>

                                    <label class="esp-switch">
                                        <input type="hidden"
                                               name="esp_debug_mode"
                                               value="0">

                                        <input type="checkbox"
                                               name="esp_debug_mode"
                                               value="1"
                                               aria-label="Enable debug mode"
                                               <?php checked($debug); ?>>

                                        <span class="esp-slider"></span>
                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="esp-card-footer">

                            <span class="esp-muted">
                                Preferences are saved in WordPress.
                            </span>

                            <button type="submit"
                                    class="button button-primary esp-save">
                                Save Settings
                            </button>

                        </div>

                    </form>

                </section>

                <!-- LICENCE MANAGEMENT -->
                <section id="esp-licence" class="esp-card">

                    <div class="esp-card-head">
                        <h2>Licence Management</h2>
                        <p>
                            Activate, verify and manage your plugin licence.
                        </p>
                    </div>

                    <div class="esp-card-body esp-license-area">

                        <?php

                        // Your ORIGINAL licence integration.
                        if (
                            class_exists('\UUPD\UI\License\V1\UUPD_License_UI')
                            && defined('UUPD_RUP_EXAMPLE_PLUGIN2_VENDOR')
                            && defined('UUPD_RUP_EXAMPLE_PLUGIN2_SLUG')
                        ) {

                            $inst = \UUPD\UI\License\V1\UUPD_License_UI::get_instance(
                                UUPD_RUP_EXAMPLE_PLUGIN2_VENDOR,
                                UUPD_RUP_EXAMPLE_PLUGIN2_SLUG
                            );

                            if ($inst) {
                                $inst->render_page();
                            } else {
                                echo '<div class="esp-license-warning">';
                                echo 'The licence UI instance could not be loaded.';
                                echo '</div>';
                            }

                        } else {

                            echo '<div class="esp-license-warning">';
                            echo '<strong>Licence Manager Unavailable</strong>';
                            echo '<p>The UUPD licence class or required plugin constants are not available.</p>';
                            echo '</div>';

                        }

                        ?>

                    </div>

                </section>

                <!-- SYSTEM INFORMATION -->
                <section id="esp-system" class="esp-card">

                    <div class="esp-card-head">
                        <h2>System Information</h2>
                        <p>
                            Basic details about your WordPress environment.
                        </p>
                    </div>

                    <div class="esp-card-body">

                        <table class="esp-system-table">

                            <tr>
                                <td>WordPress Version</td>
                                <td><?php echo esc_html($wp_version); ?></td>
                            </tr>

                            <tr>
                                <td>PHP Version</td>
                                <td><?php echo esc_html($php_version); ?></td>
                            </tr>

                            <tr>
                                <td>Plugin Configuration</td>
                                <td>
                                    <span class="esp-pill">Available</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Debug Preference</td>
                                <td><?php echo $debug ? 'Enabled' : 'Disabled'; ?></td>
                            </tr>

                            <tr>
                                <td>Platform</td>
                                <td>WordPress</td>
                            </tr>

                        </table>

                    </div>

                </section>

            </main>

        </div>

        <footer class="esp-footer">
            Plugin Control &bull; Version 1.0.0
        </footer>

    </div>

    <?php
}
