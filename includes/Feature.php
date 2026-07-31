<?php
declare(strict_types=1);
namespace SangPortfolio;
if (! defined('ABSPATH')) { exit; }
final class WordpressMultisiteManagerFeature {
    private const OPTION = 'wordpress_multisite_manager_enabled';
    private const SLUG = 'wordpress-multisite-manager';
    private const TITLE = 'WordPress Multisite Manager';
    public function register(): void {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_menu', [$this, 'registerPage']);
        if (Support::enabled(self::OPTION)) { $this->registerFeature(); }
    }
    public function registerSettings(): void { register_setting(self::SLUG, self::OPTION, ['sanitize_callback' => static fn($value): string => empty($value) ? '0' : '1']); }
    public function registerPage(): void { add_options_page(self::TITLE, self::TITLE, 'manage_options', self::SLUG, [$this, 'renderPage']); }
    public function renderPage(): void { if (! current_user_can('manage_options')) { return; } echo '<div class="wrap"><h1>' . esc_html(self::TITLE) . '</h1><form method="post" action="options.php">'; settings_fields(self::SLUG); echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION) . '" value="1" ' . checked(Support::enabled(self::OPTION), true, false) . '> ' . esc_html__('Enable feature', 'sang-portfolio') . '</label>'; submit_button(); echo '</form></div>'; }
    private function registerFeature(): void { if (is_multisite()) { add_action('network_admin_notices', [$this, 'networkNotice']); } }
    public function networkNotice(): void { echo '<div class="notice notice-info"><p>' . esc_html__('Multisite manager is active. Network-level policies can be added through this extension point.', 'sang-portfolio') . '</p></div>'; }
}
