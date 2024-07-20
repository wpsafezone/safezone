<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://brunos.digital
 * @since      1.0.0
 *
 * @package    Safezone
 * @subpackage Safezone/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Safezone
 * @subpackage Safezone/includes
 * @author     Brunos Digital <hello@brunos.digital>
 */
class Safezone {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Safezone_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected Safezone_Loader $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected string $version;

    protected array $packages;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        $this->version = SAFEZONE_PLUGIN_VERSION;
		$this->plugin_name = SAFEZONE_PLUGIN_NAME;

        $this->packages = [];

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
        $this->set_features();
        $this->action_features();

        $this->loader->add_action('wp_head', $this,'add_comment_to_header', 1);

        if(get_option('sz_custom_dismiss_notice') === '1'){
            $this->loader->add_action('admin_notices', $this, 'sample_admin_notice__success');
        }
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Safezone_Loader. Orchestrates the hooks of the plugin.
	 * - Safezone_i18n. Defines internationalization functionality.
	 * - Safezone_Admin. Defines all hooks for the admin area.
	 * - Safezone_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->get_packages();

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/lib/class-safezone-firewall.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-safezone-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-safezone-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-safezone-admin.php';

        /**
         *
         * Features class
         *
         */
        require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-safezone-features.php';

		$this->loader = new Safezone_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Safezone_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale(): void
    {
		$plugin_i18n = new Safezone_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks(): void
    {
		$plugin_admin = new Safezone_Admin( $this->get_plugin_name(), $this->get_version(), $this->packages);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'safezone_menu' );
        $this->loader->add_action('wp_ajax_custom_dismiss_notice', $plugin_admin, 'custom_dismiss_notice');
        $this->loader->add_action('wp_ajax_update_option', $plugin_admin, 'update_option');
        $this->loader->add_action('wp_ajax_update_malware_scanning_period', $plugin_admin, 'update_malware_scanning_period');
        $this->loader->add_action('wp_ajax_system_check', $plugin_admin, 'system_check');
        $this->loader->add_action('wp_ajax_protection_change', $plugin_admin, 'protection_change');
        $this->loader->add_action('wp_ajax_add_license', $plugin_admin, 'add_license');
        $this->loader->add_action('wp_ajax_cancel_subscription', $plugin_admin, 'cancel_subscription');
        $this->loader->add_action('wp_ajax_resume_subscription', $plugin_admin, 'resume_subscription');
        $this->loader->add_action('wp_ajax_subscribe', $plugin_admin, 'subscribe');
        $this->loader->add_action('wp_ajax_get_logs_table', $plugin_admin, 'get_logs_table');
        $this->loader->add_action('wp_ajax_get_whitelist_table', $plugin_admin, 'get_whitelist_table');
        $this->loader->add_action('wp_ajax_get_firewall_table', $plugin_admin, 'get_firewall_table');
        $this->loader->add_action('wp_ajax_get_malware_table', $plugin_admin, 'get_malware_table');
        $this->loader->add_action('wp_ajax_malware_ignore', $plugin_admin, 'malware_ignore');
        $this->loader->add_action('wp_ajax_view_code', $plugin_admin, 'view_code');
        $this->loader->add_action('wp_ajax_delete_file', $plugin_admin, 'delete_file');
        $this->loader->add_action('wp_ajax_update_file', $plugin_admin, 'update_file');
        $this->loader->add_action('wp_ajax_get_events_table', $plugin_admin, 'get_events_table');
        $this->loader->add_action('wp_ajax_get_anti_spam_table', $plugin_admin, 'get_anti_spam_table');
        $this->loader->add_action('wp_ajax_add_whitelist', $plugin_admin, 'add_whitelist');
        $this->loader->add_action('wp_ajax_delete_whitelist', $plugin_admin, 'delete_whitelist');
        $this->loader->add_action('wp_ajax_whitelist_widget', $plugin_admin, 'whitelist_widget');
        $this->loader->add_action('wp_ajax_firewall_widget', $plugin_admin, 'firewall_widget');
        $this->loader->add_action('wp_ajax_live_settings', $plugin_admin, 'live_settings');
        $this->loader->add_action('wp_ajax_dashboard_data', $plugin_admin, 'dashboard_data');
        $this->loader->add_action('wp_ajax_malware_scanner', $plugin_admin, 'malware_scanner');
        $this->loader->add_action('wp_ajax_get_counter', $plugin_admin, 'get_counter');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run(): void
    {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name(): string
    {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Safezone_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
    {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
    {
		return $this->version;
	}

    public function add_comment_to_header(): void
    {
        echo '<!-- Professional Security & Firewall by Wp Safe Zone - https://wpsafezone.com/ -->';
    }

    public function installed_security_plugins() : array
    {
        $installed_plugins = get_plugins();
        $founded = [];
        foreach (SECURITY_PLUGINS as $plugin_name) {
            foreach ($installed_plugins as $plugin_data) {
                if (stripos($plugin_data['Name'], $plugin_name) !== false) {
                    $founded[] = $plugin_data['Name'];
                }
            }
        }

        return array_unique($founded);
    }

    public function sample_admin_notice__success(): void
    {
	    $screen = get_current_screen();
	    if (str_contains($screen->id, 'safezone')){

            $founded = $this->installed_security_plugins();
            if(count($founded) > 0){
                echo '<div class="notice notice-success is-dismissible custom-dismiss-notice-wrapper" style="margin: 25px 15px 2px 0 !important;">';
                echo '<p>We noticed that a different security plugin was active other than the Safe Zone plugin. In order for Safe Zone to work properly and to avoid conflicts, you should disable the plugins listed below.</p>';

                foreach ($founded as $plugin) {
                    echo '<p><strong>' . $plugin . '</strong></p>';
                }

                echo '<button type="button" class="notice-dismiss custom-notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                echo '</div>';
            }
	    }
    }

    public function set_features(): void
    {
        $plugin_features = new Safezone_Features();
        foreach(SAFEZONE_SETTINGS as $value){
            if (get_option($value['key']) === '1') {
                $func = str_replace('sz_', '', $value['key']);
                if (!method_exists($plugin_features, $func)) continue;
                $plugin_features->$func();
            }
        }
    }

    public function action_features() : void
    {
        if(get_option('sz_firewall') === '1'){
            Safezone_Firewall::add_htaccess_lines();
        } else {
            Safezone_Firewall::remove_htaccess_lines();
        }

    }

	public function get_packages(): void
	{
		$response = wp_remote_get(API_URL . '/plugin/prices');
		if(is_wp_error($response)){
			return;
		}
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		if(isset($data['success']) && $data['success'] === true){
			$this->packages = $data['data'];
		}
	}
}
