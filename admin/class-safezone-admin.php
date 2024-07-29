<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://brunos.digital
 * @since      1.0.0
 *
 * @package    Safezone
 * @subpackage Safezone/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Safezone
 * @subpackage Safezone/admin
 * @author     Brunos Digital <hello@brunos.digital>
 */
class Safezone_Admin
{
    private string $plugin_name;
    private string $version;
    public array $packages;
    public int $pending_update_count;
    public int $blocked_activities_count;
    public int $blocked_spams_count;
    public string $last_malware_scan;
    public string $next_malware_scan;
    public float $malware_score;
    public array $malware_steps;
    public bool $malware_status;
    public array $protection_status;
    public bool $is_pro;

    public array $license_info;

    public function __construct(string $plugin_name, string $version, array $packages)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->packages = $packages;
        $this->pending_update_count = 0;
        $this->blocked_spams_count = 0;
        $this->blocked_activities_count = 0;
        $this->last_malware_scan = $this->last_malware_scan_date();
        $this->next_malware_scan = $this->next_malware_scan_date();
        $this->malware_score = 0;
        $this->malware_steps = [];
        $this->malware_status = false;
        $this->is_pro = false;
        $this->license_info = [];
        $this->protection_status = $this->protection_status();

        $this->load_dependencies();
    }

    private function load_dependencies(): void
    {
        /**
         * The class responsible
         *  Functions for the admin area.
         */
        $this->system_check();
        $this->check_all_plugins_updates();
        $this->get_malware_overview();

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles(): void
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Safezone_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Safezone_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $screen = get_current_screen();
        if (str_contains($screen->id, 'safezone')) {
            wp_enqueue_style($this->plugin_name . '-main', plugin_dir_url(__FILE__) . 'css/safezone-main.css', [], $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '-toastify', plugin_dir_url(__FILE__) . 'css/toastify.css', [], $this->version, 'all');
            /* wp_enqueue_style($this->plugin_name . '-datatable', plugin_dir_url(__FILE__) . 'css/datatables.min.css', [], $this->version, 'all'); */
            wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/safezone-admin.css', [], $this->version, 'all');
        }

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(): void
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Safezone_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Safezone_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name . '-vendor', plugin_dir_url(__FILE__) . 'js/safezone-vendor.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-ace', plugin_dir_url(__FILE__) . 'js/ace.js', ['jquery'], $this->version, false);

        wp_enqueue_script($this->plugin_name . '-toastify', plugin_dir_url(__FILE__) . 'js/toastify.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-datatable', plugin_dir_url(__FILE__) . 'js/datatables.min.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-safezone-security', plugin_dir_url(__FILE__) . 'js/safezone-api.js', ['jquery'], $this->version, true);
        wp_localize_script($this->plugin_name . '-safezone-security', 'safezone', [
            'token' => wp_create_nonce('safezone-security'),
            'hostname' => str_replace(['http://', 'https://'], [], get_site_url()),
            'licence_key' => get_option('sz_license'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'is_pro' => $this->is_pro,
            'protection' => $this->protection_status,
            'sz_firewall' => get_option('sz_firewall'),
            'sz_anti_spam' => get_option('sz_anti_spam')
        ]);
        wp_enqueue_script($this->plugin_name . '-main', plugin_dir_url(__FILE__) . 'js/safezone-main.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/safezone-admin.js', ['jquery'], $this->version, false);

    }

    /**
     * Menu Functions
     */
    public function safezone_menu()
    {
        add_menu_page(
            'Safe Zone',
            'Safe Zone',
            'manage_options',
            'safezone-dashboard',
            [$this, 'safezone_router'],
            SAFEZONE_PLUGIN_URL . 'admin/images/icon.svg',
            61
        );

        add_submenu_page('safezone-dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'safezone-dashboard', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Events', 'Events', 'manage_options', 'safezone-events', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Lockouts', 'Lockouts', 'manage_options', 'safezone-lockouts', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Whitelist', 'Whitelist', 'manage_options', 'safezone-whitelist', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Firewall', 'Firewall', 'manage_options', 'safezone-firewall', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Anti-Spam', 'Anti-Spam', 'manage_options', 'safezone-anti-spam', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Malware Scanner', 'Malware Scanner', 'manage_options', 'safezone-malware', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Logs', 'Logs', 'manage_options', 'safezone-logs', [
            $this,
            'safezone_router'
        ]);
        add_submenu_page('safezone-dashboard', 'Settings', 'Settings', 'manage_options', 'safezone-settings', [
            $this,
            'safezone_settings_router'
        ]);
    }

    public function safezone_router(): void
    {
        ob_start();

        $defaultPage = 'dashboard';
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : $defaultPage;
        $page = str_replace('safezone-', '', $page);
        if (!in_array($page, VALID_PAGES, true)) {
            $page = esc_attr($defaultPage);
        }

        $this->menu = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'path' => admin_url('admin.php?page=safezone-dashboard'),
                'is_active' => $page === 'dashboard',
            ],
            [
                'name' => 'Events',
                'slug' => 'events',
                'path' => admin_url('admin.php?page=safezone-events'),
                'is_active' => $page === 'events',
            ],
            [
                'name' => 'Lockouts',
                'slug' => 'lockouts',
                'path' => admin_url('admin.php?page=safezone-lockouts'),
                'is_active' => $page === 'lockouts',
            ],
            [
                'name' => 'Whitelist',
                'slug' => 'whitelist',
                'path' => admin_url('admin.php?page=safezone-whitelist'),
                'is_active' => $page === 'whitelist',
            ],
            [
                'name' => 'Logs',
                'slug' => 'logs',
                'path' => admin_url('admin.php?page=safezone-logs'),
                'is_active' => $page === 'logs',
            ]
        ];

        include_once(SAFEZONE_PLUGIN_PATH . 'admin/partials/safezone-admin-' . $page . '.php');
        include_once(SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-admin-modal.php');
        $template = ob_get_contents();
        ob_end_clean();
        echo wp_kses($template, WPKSES);
    }

    public function safezone_settings_router(): void
    {
        ob_start();
        $defaultTab = 'firewall';
        $tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : $defaultTab;
        if (!in_array($tab, VALID_TABS, true)) {
            $tab = esc_attr($tab);
        }

        $this->menu = [
            [
                'name' => 'Firewall',
                'slug' => 'firewall',
                'path' => admin_url('admin.php?page=safezone-settings&tab=firewall'),
                'is_active' => $tab === 'firewall',
            ],
            [
                'name' => 'Anti-Spam',
                'slug' => 'anti-spam',
                'path' => admin_url('admin.php?page=safezone-settings&tab=anti-spam'),
                'is_active' => $tab === 'anti-spam',
            ],
            [
                'name' => 'Malware Scanner',
                'slug' => 'scanner',
                'path' => admin_url('admin.php?page=safezone-settings&tab=malware'),
                'is_active' => $tab === 'malware',
            ],
            [
                'name' => 'Notifications',
                'slug' => 'notifications',
                'path' => admin_url('admin.php?page=safezone-settings&tab=notifications'),
                'is_active' => $tab === 'notifications',
            ],
            [
                'name' => 'Licence',
                'slug' => 'licence',
                'path' => admin_url('admin.php?page=safezone-settings&tab=licence'),
                'is_active' => $tab === 'licence',
            ],
            [
                'name' => 'Changelog',
                'slug' => 'changelog',
                'path' => admin_url('admin.php?page=safezone-settings&tab=changelog'),
                'is_active' => $tab === 'changelog',
            ]

        ];

        include_once(SAFEZONE_PLUGIN_PATH . 'admin/partials/safezone-admin-settings-' . $tab . '.php');
        include_once(SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-admin-modal.php');
        $template = ob_get_contents();
        ob_end_clean();
        echo wp_kses($template, WPKSES);
    }

    public function protection_status(): array
    {
        $protection = [
            'type' => 'success',
            'message' => 'Providing Full Protection',
        ];
        if (get_option('sz_firewall') === '0' || get_option('sz_anti_spam') === '0') {
            $protection = [
                'type' => 'warning',
                'message' => 'Providing Standard Protection',
            ];
        }
        return $protection;
    }

    public function last_malware_scan_date(): string
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_malware';
        $last_scan = $wpdb->get_var("SELECT created_at FROM $table_name WHERE status = 1 ORDER BY id DESC LIMIT 1");

        return $last_scan ? date('Y-m-d H:i', strtotime($last_scan)) : 'Never';
    }

    public function update_malware_scanning_period(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $period = sanitize_text_field($request['period']);
        update_option('sz_autoscanning_period', $period);
        wp_send_json([
            'success' => true,
            'message' => 'Malware scanning period updated.',
            'data' => [
                'period' => $period
            ]
        ]);
    }

    public function next_malware_scan_date(): string
    {
        $last_scan = get_option('sz_autoscanning_date');
        if ($last_scan === '') {
            return 'It has not started yet';
        }

        $last_scan_period = get_option('sz_autoscanning_period');

        $next_scan = match ($last_scan_period) {
            'weekly' => strtotime($last_scan . ' +1 week'),
            'monthly' => strtotime($last_scan . ' +1 month'),
            default => strtotime($last_scan . ' +1 day'),
        };

        return date('Y-m-d H:i', $next_scan);
    }

    public function parse_changelog(): array
    {
        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        WP_Filesystem();
        global $wp_filesystem;

        $changelog_path = SAFEZONE_PLUGIN_PATH . '/CHANGELOG.txt';

        if (!$wp_filesystem->exists($changelog_path)) {
            return [];
        }

        $changelog = $wp_filesystem->get_contents($changelog_path);
        if (false === $changelog) {
            return [];
        }

        $entries = preg_split('/\n\n/', $changelog);
        $parsed_entries = [];

        foreach ($entries as $entry) {
            $lines = explode("\n", $entry);
            $version = str_replace('Version ', '', array_shift($lines));
            $date = str_replace('Date: ', '', array_shift($lines));
            $changes = array_filter($lines);

            $parsed_entries[] = [
                'version' => $version,
                'date' => $date,
                'changes' => $changes,
            ];
        }

        return $parsed_entries ?? [];
    }

    /**
     * Malware Scanner Functions
     */
    public function get_malware_overview(): void
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'sz_malware';

        $reports = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1 ORDER BY id DESC", ARRAY_A);

        $steps = range(1, 7);
        $overview = array_fill_keys(array_map(fn($n) => 'step_' . $n, $steps), false);

        foreach ($reports as $report) {
            $stepKey = 'step_' . $report['step'];
            if (array_key_exists($stepKey, $overview)) {
                $overview[$stepKey] = true;
            }
        }

        $completedSteps = array_reduce($overview, fn($carry, $step) => $carry + ($step ? 0 : 1), 0);
        $maxScore = 5;
        $percentComplete = ($completedSteps / count($overview)) * 100;
        $score = ($percentComplete / 100) * $maxScore;
        $roundedScore = round($score, 1);

        $this->malware_score = $roundedScore == 0 ? 5 : $roundedScore;

        $stepsData = [
            '1' => 'Core Files',
            '2' => 'Blacklist Check',
            '3' => 'Spam Check',
            '4' => 'Vulnerability Scan',
            '5' => 'Malware Scan',
            '6' => 'Public Files',
            '7' => 'Content Safety',
        ];

        $this->malware_steps = array_map(fn($step, $name) => [
            'name' => $name,
            'step' => $step,
            'status' => $overview['step_' . $step] ? 'failed' : 'success',
            'icon' => $overview['step_' . $step] ? 'info-outline' : 'yes'
        ], array_keys($stepsData), $stepsData);

        $this->malware_status = !in_array('failed', array_column($this->malware_steps, 'status'), true);

    }


    /**
     * System Functions
     */
    public function update_option(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $key = sanitize_text_field($request['key']);
        $key_info = $this->get_setting_name($key);
        $value = sanitize_text_field($request['value']);
        $setting_name = $key_info['title'];
        $setting_group = $key_info['group'];

        $activity = $value === '1' ? $setting_name . ' setting updated to enabled.' : $setting_name . ' setting updated to disabled.';

        if (!in_array($key, array_column(SAFEZONE_SETTINGS, 'key'), true)) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid setting key.',
                'data' => []
            ]);
        }

        update_option($key, $value);

        $notification_setting = get_option('sz_update_notifications');

        // Anti Spam All
        if ($key === 'sz_anti_spam') {
            $activity = $value === '1' ? $setting_name . ' updated to enabled.' : $setting_name . ' updated to disabled.';
            if ($value === '0') {
                foreach (SAFEZONE_SETTINGS as $value) {
                    if ($value['group'] === 'anti-spam') {
                        update_option($value['key'], 0);
                    }
                }
            }
        }

        // Firewall All
        if ($key === 'sz_firewall') {
            $activity = $value === '1' ? $setting_name . ' updated to enabled.' : $setting_name . ' updated to disabled.';
            if ($value === '0') {
                foreach (SAFEZONE_SETTINGS as $value) {
                    if ($value['group'] === 'firewall') {
                        update_option($value['key'], 0);
                    }
                }
            }
        }

        // Malware Scheduler
        if($key === 'sz_enable_autoscanning' && $value === '1'){
            update_option('sz_autoscanning_date', current_time('mysql'));
        }else{
            update_option('sz_autoscanning_date', '');
        }

        $this->insert_log_entry(wp_get_current_user()->user_login, $setting_group, $activity);

        if ($notification_setting === "1") {
            $admins = get_users(array('role' => 'administrator'));
            foreach ($admins as $admin) {
                $to = $admin->user_email;
                wp_mail($to, 'Safe Zone', $activity);
            }
        }

        wp_send_json([
            'success' => true,
            'message' => $activity,
            'data' => [
                'sz_firewall' => get_option('sz_firewall'),
                'sz_anti_spam' => get_option('sz_anti_spam')
            ]
        ]);
    }

    public function add_whitelist(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();

        if (!isset($request['ip'])) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid IP address.',
                'data' => []

            ]);
        }

        $ip = sanitize_text_field($request['ip']);
        $message = "$ip added to whitelist.";

        global $wpdb;
        $table_name = esc_sql($wpdb->prefix . 'sz_whitelist');
        $existing_ip = $wpdb->get_var($wpdb->prepare("SELECT ip FROM {$table_name} WHERE ip = %s", $ip));

        if (!$existing_ip) {
            $ip_info = $this->get_ip_info($ip);
            if ($ip_info['success']) {
                $wpdb->insert($table_name, $ip_info['data'], ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);
                if ($wpdb->insert_id) {
                    $this->insert_log_entry(wp_get_current_user()->user_login, 'whitelist', $message);
                    wp_send_json([
                        'success' => true,
                        'message' => $message,
                        'data' => array_merge([
                            'id' => $wpdb->insert_id,
                            'created_at' => current_time('mysql')
                        ], $ip_info['data'])
                    ]);
                } else {
                    wp_send_json([
                        'success' => false,
                        'message' => 'Error adding IP to whitelist.',
                        'data' => []
                    ]);
                }
            } else {
                wp_send_json([
                    'success' => false,
                    'message' => 'IP not found. Please try again.',
                    'data' => []
                ]);
            }
        } else {
            wp_send_json([
                'success' => false,
                'message' => 'IP already exists in whitelist.',
                'data' => []
            ]);
        }
    }

    public function delete_whitelist(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();

        if (!isset($request['id'])) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid ID.',
                'data' => []
            ]);
        }

        $id = sanitize_text_field($request['id']);

        // delete db
        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_whitelist';
        $ip = $wpdb->get_var($wpdb->prepare("SELECT ip FROM $table_name WHERE id = %s", $id));
        $wpdb->delete($table_name, ['id' => $id], ['%d']);

        $message = "$ip deleted from whitelist.";
        $this->insert_log_entry(wp_get_current_user()->user_login, 'whitelist', $message);
        wp_send_json([
            'success' => true,
            'message' => $message,
            'data' => [
                'id' => $id,
                'ip' => $ip
            ]
        ]);
    }

    public function get_anti_spam_dashboard(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();

        $periods = ['today', 'week', 'month'];
        $period = in_array($request['period'], $periods) ? $request['period'] : 'today';

        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_anti_spams';

        $date_condition = match ($period) {
            'today' => "created_at >= CURDATE()",
            'week' => "YEARWEEK(created_at) = YEARWEEK(NOW())",
            'month' => "MONTH(created_at) = MONTH(NOW())",
            default => "created_at >= CURDATE()",
        };

        $spam_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $date_condition");

        wp_send_json([
            'success' => true,
            'message' => 'Blocked spams retrieved.',
            'data' => [
                'count' => $spam_count
            ]
        ]);
    }

    public function get_counter(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $type = $request['type'];

        global $wpdb;

        $table_name = '';
        $additional_condition = '';

        switch ($type) {
            case 'firewall':
                $table_name = $wpdb->prefix . 'sz_firewall';
                break;
            case 'anti_spam':
                $table_name = $wpdb->prefix . 'sz_anti_spams';
                break;
            case 'bad_bots':
                $table_name = $wpdb->prefix . 'sz_firewall';
                $additional_condition = 'firewall_type = "Bad Bots"';
                break;
            case 'login_protection':
                $table_name = $wpdb->prefix . 'sz_anti_spams';
                $additional_condition = 'spam_type = "Login"';
                break;
            default:
                wp_send_json([
                    'success' => false,
                    'message' => 'Invalid type provided.',
                    'data' => []
                ]);

                return;
        }

        $date_condition = match ($request['period']) {
            'today' => "created_at >= CURDATE()",
            'week' => "YEARWEEK(created_at) = YEARWEEK(NOW())",
            'month' => "MONTH(created_at) = MONTH(NOW())",
            default => '',
        };

        $where_conditions = [];
        if ($date_condition) {
            $where_conditions[] = $date_condition;
        }
        if ($additional_condition) {
            $where_conditions[] = $additional_condition;
        }

        $where_sql = '';
        if (!empty($where_conditions)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
        }

        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} {$where_sql}");
        $total = $wpdb->get_var($query);

        wp_send_json([
            'success' => true,
            'message' => 'Blocked spams retrieved.',
            'data' => [
                'count' => (int)$total
            ]
        ]);
    }

    public function cancel_subscription(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $license = sanitize_text_field($request['license']);
        $hostname = str_replace(['http://', 'https://'], [], get_site_url());
        $response = wp_remote_post(API_URL . '/plugin/cancel-subscription', [
            'body' => wp_json_encode(['license_key' => $license, 'hostname' => $hostname]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
        if (is_wp_error($response)) {
            wp_send_json([
                'success' => false,
                'message' => 'Error cancelling subscription.',
                'data' => []
            ]);
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (!$data['success']) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid licence key.',
                'data' => []
            ]);
        }
        wp_send_json($data);
    }

    public function resume_subscription(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $license = sanitize_text_field($request['license']);
        $hostname = str_replace(['http://', 'https://'], [], get_site_url());
        $response = wp_remote_post(API_URL . '/plugin/resume-subscription', [
            'body' => wp_json_encode(['license_key' => $license, 'hostname' => $hostname]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
        if (is_wp_error($response)) {
            wp_send_json([
                'success' => false,
                'message' => 'Error cancelling subscription.',
                'data' => []
            ]);
        }
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (!$data['success']) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid licence key.',
                'data' => $data
            ]);
        }
        wp_send_json($data);
    }

    public function add_license(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $license = sanitize_text_field($request['license']);
        $hostname = str_replace(['http://', 'https://'], [], get_site_url());
        $response = wp_remote_post(API_URL . '/plugin/info', [
            'body' => wp_json_encode(['license_key' => $license, 'hostname' => $hostname]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if (is_wp_error($response)) {
            wp_send_json([
                'success' => false,
                'message' => 'Error checking licence.',
                'data' => []
            ]);
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data['success']) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid licence key.',
                'data' => []
            ]);
        }

        update_option('sz_license', $license);

        wp_send_json($data);
    }

    public function check_all_plugins_updates(): void
    {
        $total = 0;
        $update_plugins = get_site_transient('update_plugins');
        if (!empty($update_plugins->response)) {
            $total += count($update_plugins->response);
        }
        $update_themes = get_site_transient('update_themes');
        if (!empty($update_themes->response)) {
            $total += count($update_themes->response);
        }
        $this->pending_update_count = $total;
    }

    private function ajax_security(): void
    {
        if (!check_ajax_referer('safezone-security', 'security', false)) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid security token sent.',
                'data' => []
            ]);
            wp_die();
        }
    }

    private function check_payload(): array
    {
        if (!isset($_POST['payload'])) {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid payload sent.',
                'data' => []
            ]);
            wp_die();
        }

        return $_POST['payload'];
    }

    private function get_setting_name($key): array
    {
        $settings = array_filter(SAFEZONE_SETTINGS, function ($setting) use ($key) {
            return $setting['key'] === $key;
        });

        return array_shift($settings);
    }

    private function insert_log_entry($username, $category, $activity): void
    {
        global $wpdb;
        $table_name = esc_sql($wpdb->prefix . 'sz_logs');
        $wpdb->insert(
            $table_name,
            [
                'username' => sanitize_text_field($username),
                'category' => sanitize_text_field($category),
                'activity' => sanitize_text_field($activity),
                'created_at' => current_time('mysql')
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s'
            ]
        );

        if ($wpdb->last_error) {
            error_log('Database insert error: ' . $wpdb->last_error);
        }
    }

    public function get_whitelist_table(): void
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $result = $this->get_table('sz_whitelist', $page, $per_page);
        wp_send_json($result);
    }


    public function get_logs_table(): void
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $category_filter = isset($_POST['category_filter']) ? sanitize_text_field($_POST['category_filter']) : '';
        $result = $this->get_table('sz_logs', $page, $per_page, $search, $category_filter);

        wp_send_json($result);
    }

    public function get_anti_spam_table(): void
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $result = $this->get_table('sz_anti_spams', $page, $per_page);
        wp_send_json($result);
    }

    public function get_firewall_table(): void
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $result = $this->get_table('sz_firewall', $page, $per_page);
        wp_send_json($result);
    }

    public function get_malware_table(): void
    {
        $this->manuel_file_delete_detect();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $result = $this->get_table('sz_malware', $page, $per_page, '', '', 1);
        wp_send_json($result);
    }

    public function get_events_table(): void
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
        $result = $this->get_table('sz_firewall', $page, $per_page);
        wp_send_json($result);
    }

    private function get_table($tn, $page = 1, $per_page = 10, $search = '', $category_filter = '', $status = ''): array
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $tn;
        $offset = ($page - 1) * $per_page;
        $where_clauses = [];
        $params = [];

        if ($search) {
            $where_clauses[] = "(username LIKE %s OR activity LIKE %s)";
            $params[] = '%' . $wpdb->esc_like($search) . '%';
            $params[] = '%' . $wpdb->esc_like($search) . '%';
        }

        if ($category_filter && $category_filter !== 'All') {
            $where_clauses[] = "category = %s";
            $params[] = $category_filter;
        }

        if ($status) {
            $where_clauses[] = "status = %s";
            $params[] = $status;
        }

        $where_sql = '';
        if (!empty($where_clauses)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        }

        // Toplam öğe sayısını almak için SQL sorgusu
        $total_sql = "SELECT COUNT(*) FROM {$table_name} {$where_sql}";
        $total_items = $wpdb->get_var($wpdb->prepare($total_sql, ...$params));

        // Verileri almak için SQL sorgusu
        $data_sql = "SELECT * FROM {$table_name} {$where_sql} ORDER BY created_at DESC LIMIT %d OFFSET %d";
        array_push($params, $per_page, $offset);
        $logs = $wpdb->get_results($wpdb->prepare($data_sql, ...$params), ARRAY_A);

        // Logları biçimlendirme
        $logs = array_map(function ($log) {
            $log['created_at'] = date('Y-m-d H:i:s', strtotime($log['created_at']));
            $log['category'] = ucfirst($log['category']);
            return $log;
        }, $logs);

        return [
            'data' => $logs,
            'recordsTotal' => $total_items,
            'recordsFiltered' => $total_items,
            'total_pages' => ceil($total_items / $per_page),
            'current_page' => $page,
        ];
    }

    private function get_ip_info($ip): array
    {
        $response = wp_remote_post(API_URL . '/plugin/ip', [
            'body' => wp_json_encode(['ip' => $ip]),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
        $body = wp_remote_retrieve_body($response);
        if (is_wp_error($response)) {
            wp_die('Error: ' . $response->get_error_message());
        }

        return json_decode($body, true);
    }

    public function live_settings(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();

        $settings = [];


        global $wpdb;
        $table_name_anti_spams = $wpdb->prefix . 'sz_anti_spams';
        $table_name_firewall = $wpdb->prefix . 'sz_firewall';
        $anti_spam_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_anti_spams");
        $login_protection_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_anti_spams WHERE spam_type='Login'");
        $firewall_bad_bots_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_firewall WHERE firewall_type='Bad Bots'");
        $firewall_blocked_ips_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_firewall");


        if ($request['type'] === 'anti-spam') {
            $settings = array_filter(SAFEZONE_SETTINGS, function ($setting) {
                return $setting['group'] === 'anti-spam' && get_option($setting['key']) === "0" && $setting['key'] !== 'sz_anti_spam';
            });
        } elseif ($request['type'] === 'firewall') {
            $settings = array_filter(SAFEZONE_SETTINGS, function ($setting) {
                return $setting['group'] === 'firewall' && get_option($setting['key']) === "0" && $setting['key'] !== 'sz_firewall';
            });
        }

        wp_send_json([
            'success' => true,
            'message' => 'Checked successfully.',
            'data' => [
                'blocked_ips_count' => (int)$firewall_blocked_ips_count,
                'blocked_spams_count' => (int)$anti_spam_count,
                'bad_bots_count' => (int)$firewall_bad_bots_count,
                'login_protections_count' => (int)$login_protection_count,
                'settings' => array_values($settings)
            ]
        ]);
    }

    public function whitelist_widget(): void
    {
        $this->ajax_security();

        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_whitelist';
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $last_items = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 5", ARRAY_A);
        $last_item_date = isset($last_items[0]) ? $last_items[0]['created_at'] : null;
        wp_send_json([
            'success' => true,
            'message' => 'Whitelist widget data retrieved.',
            'data' => [
                'total_items' => $total_items,
                'last_item_date' => $last_item_date,
                'last_items' => array_map(function ($item) {
                    $item['created_at'] = date('Y-m-d', strtotime($item['created_at']));

                    return $item;
                }, $last_items)
            ]
        ]);
    }

    public function firewall_widget(): void
    {
        $this->ajax_security();

        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_firewall';
        $last_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} ORDER BY created_at DESC LIMIT %d", 5));
        wp_send_json([
            'success' => true,
            'message' => 'Whitelist widget data retrieved.',
            'data' => array_map(function ($item) {
                $item['created_at'] = date('Y-m-d', strtotime($item['created_at']));

                return $item;
            }, $last_items)
        ]);
    }

    private function getLast7Days(): array
    {
        $daysArray = [];
        $today = new DateTime();

        for ($i = 6; $i >= 0; $i--) {
            $day = clone $today;
            $day->modify("-{$i} days");
            $daysArray[] = $day->format('D');
        }

        return $daysArray;
    }

    public function malware_table_cleanup(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_malware';
        $wpdb->prepare("DELETE FROM $table_name WHERE status = %d", "1");
    }

    public function read_file_content($file_path)
    {
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }

        if (!$wp_filesystem->exists($file_path)) {
            return "Dosya bulunamadı: $file_path";
        }

        $file_contents = $wp_filesystem->get_contents($file_path);

        if (false === $file_contents) {
            return "Dosya okunurken bir hata oluştu.";
        }

        return $file_contents;
    }

    public function delete_file(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $malware_id = sanitize_text_field($request['id']);
        if ($malware_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sz_malware';
            $file_path = $wpdb->get_var($wpdb->prepare("SELECT file_path FROM $table_name WHERE id = %s", $malware_id));
            if (file_exists($file_path)) {

                if (!function_exists('WP_Filesystem')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                WP_Filesystem();
                global $wp_filesystem;

                if ($wp_filesystem->delete($file_path)) {
                    $wpdb->delete($table_name, ['id' => $malware_id], ['%d']);
                    wp_send_json([
                        'success' => true,
                        'message' => 'Malware deleted.',
                        'data' => []
                    ]);
                } else {
                    wp_send_json([
                        'success' => false,
                        'message' => 'Error deleting malware.',
                        'data' => []
                    ]);
                }
            }
        }
    }

    public function update_file(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $malware_id = sanitize_text_field($request['id']);
        $code = $request['code'];

        if ($malware_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sz_malware';
            $file_path = $wpdb->get_var($wpdb->prepare("SELECT file_path FROM $table_name WHERE id = %s", $malware_id));
            if (file_exists($file_path)) {

                if (!function_exists('WP_Filesystem')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                WP_Filesystem();
                global $wp_filesystem;

                $code = wp_unslash($code);

                if ($wp_filesystem->put_contents($file_path, $code)) {

                    $wpdb->update($table_name, ['updated_at' => current_time('mysql')], ['id' => $malware_id], ['%s'], ['%d']);

                    wp_send_json([
                        'success' => true,
                        'message' => 'Malware updated.',
                        'data' => []
                    ]);
                } else {
                    wp_send_json([
                        'success' => false,
                        'message' => 'Error updating malware.',
                        'data' => []
                    ]);
                }
            }
        }
    }

    private function manuel_file_delete_detect() : void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_malware';
        $malware_files = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1", ARRAY_A);
        foreach ($malware_files as $malware_file) {
            if (!file_exists($malware_file['file_path'])) {
                $wpdb->delete($table_name, ['id' => $malware_file['id']], ['%d']);
            }
        }
    }

    public function malware_ignore(): void
    {
        sleep(2);
        $this->ajax_security();
        $request = $this->check_payload();
        $malware_id = sanitize_text_field($request['id']);
        if ($malware_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sz_malware';
            $wpdb->update($table_name, ['status' => 0], ['id' => $malware_id], ['%s'], ['%d']);
            wp_send_json([
                'success' => true,
                'message' => 'Malware ignored.',
                'data' => []
            ]);
        } else {
            wp_send_json([
                'success' => false,
                'message' => 'Invalid malware ID.',
                'data' => []
            ]);
        }

    }

    public function view_code(): void
    {
        $this->ajax_security();
        $request = $this->check_payload();
        $malware_id = sanitize_text_field($request['id']);
        if ($malware_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sz_malware';
            $get_malware = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s", $malware_id), ARRAY_A);
            $file_path = $get_malware['file_path'];
            $file_info = pathinfo($file_path);
            $data = [
                'extension' => $file_info['extension'],
                'dirname' => $file_info['dirname'],
                'file' => $this->read_file_content($file_path)
            ];

            wp_send_json([
                'success' => true,
                'message' => 'Malware code retrieved.',
                'data' => $data
            ]);
        }
    }


    public function dashboard_data(): void
    {
        $this->ajax_security();

        global $wpdb;

        $sz_malware = $wpdb->prefix . 'sz_malware';
        $sz_firewall = $wpdb->prefix . 'sz_firewall';
        $sz_anti_spams = $wpdb->prefix . 'sz_anti_spams';

        $current_time = current_time('Y-m-d H:i:s');

        $date_ranges = [];
        for ($i = 0; $i < 7; $i++) {
            $start_date = gmdate('Y-m-d 00:00:00', strtotime("-$i days", strtotime($current_time)));
            $end_date = gmdate('Y-m-d 23:59:59', strtotime("-$i days", strtotime($current_time)));
            $date_ranges[] = [
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];
        }

        $malware_counts = [];
        $firewall_counts = [];
        $anti_spam_counts = [];

        foreach ($date_ranges as $date_range) {
            $malware_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $sz_malware WHERE created_at BETWEEN %s AND %s",
                $date_range['start_date'], $date_range['end_date']
            ));
            $firewall_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $sz_firewall WHERE created_at BETWEEN %s AND %s",
                $date_range['start_date'], $date_range['end_date']
            ));
            $anti_spam_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $sz_anti_spams WHERE created_at BETWEEN %s AND %s",
                $date_range['start_date'], $date_range['end_date']
            ));

            $malware_counts[] = (int)$malware_count;
            $firewall_counts[] = (int)$firewall_count;
            $anti_spam_counts[] = (int)$anti_spam_count;
        }

        wp_send_json([
            'success' => true,
            'message' => 'Dashboard data retrieved.',
            'data' => [
                'days' => $this->getLast7Days(),
                'firewall' => array_reverse($firewall_counts),
                'malware' => array_reverse($malware_counts),
                'spam' => array_reverse($anti_spam_counts)
            ]
        ]);
    }

    public function malware_scanner(): void
    {
        sleep(1);
        $this->ajax_security();
        $request = $this->check_payload();

        global $wpdb;
        $table_name = $wpdb->prefix . 'sz_malware';

        if ($request['step'] === "1") {
            $this->malware_table_cleanup();
            $this->download_wordpress();
            $this->compare_directories();
            $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 1));
            wp_send_json([
                'success' => true,
                'message' => 'Malware scan started.',
                'data' => [
                    'status' => $control > 0 ? 'failed' : 'success'
                ]
            ]);

        } elseif ($request['step'] === "2") {
            if($this->is_pro){
                $this->blacklist_ip_check();
                $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 2));
                wp_send_json([
                    'success' => true,
                    'message' => 'Malware scan started.',
                    'data' => [
                        'status' => $control > 0 ? 'failed' : 'success'
                    ]
                ]);
            }else{
                wp_send_json([
                    'success' => true,
                    'message' => 'Only pro!',
                    'data' => []
                ]);
            }

        } elseif ($request['step'] === "3") {
            $this->checkImageFiles();
            $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 3));
            wp_send_json([
                'success' => true,
                'message' => 'Malware scan started.',
                'data' => [
                    'status' => $control > 0 ? 'failed' : 'success'
                ]
            ]);
        } elseif ($request['step'] === "4") {
            $this->vulnerability_check();
            $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 4));
            wp_send_json([
                'success' => true,
                'message' => 'Malware scan started.',
                'data' => [
                    'status' => $control > 0 ? 'failed' : 'success'
                ]
            ]);
        } elseif ($request['step'] === "5") {
            $this->bad_extensions_check();
            $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 5));
            wp_send_json([
                'success' => true,
                'message' => 'Malware scan started.',
                'data' => [
                    'status' => $control > 0 ? 'failed' : 'success'
                ]
            ]);
        } elseif ($request['step'] === "6") {
            $this->bad_functions_check();
            $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 6));
            wp_send_json([
                'success' => true,
                'message' => 'Malware scan started.',
                'data' => [
                    'status' => $control > 0 ? 'failed' : 'success'
                ]
            ]);
        } elseif ($request['step'] === "7") {
            if($this->is_pro){
                $this->blacklisted_usernames_check();
                $this->delete_directory(ABSPATH . 'wp-content/uploads/wordpress');
                unlink(ABSPATH . 'wp-content/uploads/wordpress.zip');
                update_option('sz_autoscanning_date', $this->next_malware_scan);
                $control = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %d AND step = %d", 1, 7));
                wp_send_json([
                    'success' => true,
                    'message' => 'Malware scan started.',
                    'data' => [
                        'status' => $control > 0 ? 'failed' : 'success'
                    ]
                ]);
            }else{
                wp_send_json([
                    'success' => true,
                    'message' => 'Only pro!',
                    'data' => []
                ]);
            }
        } else {
            wp_send_json([
                'success' => false,
                'message' => 'Malware scan failed.',
                'data' => []
            ]);
        }

    }

    private function is_excluded($file_path): bool
    {
        $excluded_paths = [
            'wp-content/uploads/wordpress',
            'wp-content/plugins/safezone',
            '.wp-cli'
        ];

        foreach ($excluded_paths as $excluded) {
            if (str_contains($file_path, $excluded)) {
                return true;
            }
        }
        return false;
    }

    public function compare_directories(): void
    {
        $dir1 = ABSPATH;
        $dir2 = ABSPATH . 'wp-content/uploads/wordpress/';
        $excluded_files = [
            'wp-config.php',
            '.htaccess',
            'robots.txt',
            'wp-cli.yml',
            'gd-config.php',
            'gd-preload-cli.php',
            'install.php',
            'wp-config-sample.php',
            'error_log',
            '.litespeed_flag',
            'default.php',
            'favicon.ico'
        ];

        $iterator1 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir1), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator1 as $item) {
            $file_path = str_replace($dir1, '', $item->getPathname());

            if (str_contains($file_path, 'wp-content') || in_array(basename($file_path), $excluded_files)) {
                continue;
            }

            if ($this->is_excluded($file_path)) {
                continue;
            }

            if (!$item->isDir()) {
                $dir2_file_path = $dir2 . $file_path;

                if (!file_exists($dir2_file_path)) {
                    $activity = 'Suspicious extra file detected in WordPress Core files: <b>' . $file_path . '</b>';
                    $this->insert_malware_scanner_entry($activity, $dir1 . $file_path, 'Suspicious', 1);
                } else {
                    $file1_content = file_get_contents($item->getPathname());
                    $file2_content = file_get_contents($dir2_file_path);
                    if ($file1_content !== $file2_content) {
                        $activity = 'Change detected in WordPress Core file: <b>' . $file_path . '</b>';
                        $this->insert_malware_scanner_entry($activity, $dir1 . $file_path, 'Suspicious', 1);
                    }
                }
            }
        }

        $iterator2 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir2), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator2 as $item) {
            $file_path = str_replace($dir2, '', $item->getPathname());

            if (str_contains($file_path, 'wp-content') || in_array(basename($file_path), $excluded_files)) {
                continue;
            }

            if ($this->is_excluded($file_path)) {
                continue;
            }

            $dir1_file_path = $dir1 . $file_path;

            if (!file_exists($dir1_file_path)) {
                $activity = 'WordPress core file missing: <b>' . $file_path . '</b>';
                $this->insert_malware_scanner_entry($activity, $item->getPathname(), 'Critical', 1);
            }
        }
    }

    public function blacklisted_usernames_check(): void
    {
        $response = wp_remote_get(API_URL . '/plugin/blacklist-usernames');
        if (!is_wp_error($response)) {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['success']) {
                $blacklistedUsernames = $responseData['data'] ?? [];
                $allUsers = get_users();
                foreach ($allUsers as $user) {
                    foreach ($blacklistedUsernames as $blacklistedUsername) {
                        if ($user->user_login === trim($blacklistedUsername['username'])) {
                            $activity = 'A username has been marked as unsafe: <b>' . $user->user_login . '</b>';
                            $this->insert_malware_scanner_entry($activity, '', 'Low', 7);
                        }
                    }
                }
            }
        }
    }

    private function bad_function_excludes($file_path)
    {
        $excludes_folders = [
            'wp-admin/includes',
            'wp-includes',
            'wp-includes/js/plupload/',
            'wp-includes/js',
            'wp-includes/js/dist',
            'wp-includes/js/tinymce'
        ];

        foreach ($excludes_folders as $folder) {
            if (str_contains($file_path, $folder)) {
                return true;
            }
        }
    }

    public function bad_functions_check(): void
    {
        $response = wp_remote_get(API_URL . '/plugin/bad-functions');
        if (!is_wp_error($response)) {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($responseData['success']) && $responseData['success']) {
                $badFunctions = $responseData['data'] ?? [];

                if (!function_exists('WP_Filesystem')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                WP_Filesystem();
                global $wp_filesystem;

                $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABSPATH));

                foreach ($allFiles as $file) {
                    $file_path = str_replace(ABSPATH, '', $file->getPathname());

                    if ($file->isFile() && !$this->is_excluded($file_path) && !$this->bad_function_excludes($file_path)) {
                        $content = $wp_filesystem->get_contents($file->getRealPath());

                        if ($content !== false) {
                            foreach ($badFunctions as $badFunction) {
                                if (str_contains($content, ' ' . $badFunction)) {
                                    $activity = 'The <b>' . $badFunction . '</b> function was found in the file: <b>' . esc_html($file->getRealPath()) . '</b>';
                                    $this->insert_malware_scanner_entry($activity, $file->getRealPath(), 'Critical', 6);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function bad_extensions_check(): void
    {
        $plugin_dir = WP_PLUGIN_DIR;
        $response = wp_remote_get(API_URL . '/plugin/bad-extensions');
        if (!is_wp_error($response)) {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['success']) {
                $badExtensions = $responseData['data'] ?? [];
                $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABSPATH));
                foreach ($allFiles as $file) {
                    $file_path = str_replace(ABSPATH, '', $file->getPathname());
                    // Check if file is within the plugin directory
                    if (str_starts_with($file->getPath(), $plugin_dir)) {
                        continue;
                    }
                    if ($file->isFile() && !$this->is_excluded($file_path)) {
                        $extension = $file->getExtension();
                        if (in_array($extension, $badExtensions)) {
                            $activity = 'An extension that should not be present was found in the file directory: <b>' . $file->getRealPath() . '</b>';
                            $this->insert_malware_scanner_entry($activity, $file->getRealPath(), 'Medium', 5);
                        }
                    }
                }
            }
        }
    }

    public function vulnerability_check(): void
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        if (!function_exists('wp_get_theme')) {
            require_once(ABSPATH . 'wp-includes/theme.php');
        }

        $active_plugins = get_option('active_plugins');
        $active_themes = wp_get_themes();

        $plugins_info = [];
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugin_info = [
                'name' => $plugin_data['Name'],
                'slug' => $plugin_data['TextDomain'],
                'version' => $plugin_data['Version']
            ];
            $plugins_info[] = $plugin_info;
        }

        $themes_info = [];
        foreach ($active_themes as $theme) {
            $theme_info = [
                'name' => $theme->get('Name'),
                'slug' => $theme->get_stylesheet(),
                'version' => $theme->get('Version')
            ];
            $themes_info[] = $theme_info;
        }

        $request_data = [
            'plugins' => $plugins_info,
            'themes' => $themes_info,
            'core_version' => get_bloginfo('version')
        ];

        $response = wp_remote_post(
            API_URL . '/plugin/vulnerabilities',
            [
                'body' => wp_json_encode($request_data),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        if (!is_wp_error($response)) {
            $responseData = json_decode(wp_remote_retrieve_body($response), true);
            if ($responseData['success']) {
                foreach ($responseData['data'] as $vulnerability) {
                    if ($vulnerability['cvss_score'] < 4) {
                        $malware_type = 'Low';
                    } elseif ($vulnerability['cvss_score'] < 7) {
                        $malware_type = 'Medium';
                    } else {
                        $malware_type = 'Critical';
                    }
                    $activity = $vulnerability['title'];
                    $this->insert_malware_scanner_entry($activity, '', $malware_type, 4);
                }
            }
        }
    }

    public function checkImageFiles(): void
    {
        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABSPATH));
        foreach ($allFiles as $file) {
            $file_path = str_replace(ABSPATH, '', $file->getPathname());
            if ($file->isFile() && !$this->is_excluded($file_path)) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, IMAGE_EXTENSIONS)) {
                    $imageInfo = @getimagesize($file->getPathname());
                    if ($imageInfo === false) {
                        $activity = 'The file is not an image file: <b>' . $file->getPathname() . '</b>';
                        $this->insert_malware_scanner_entry($activity, $file->getPathname(), 'Medium', 3);
                    }
                }
            }
        }
    }

    public function blacklist_ip_check(): void
    {
//		$response = wp_remote_post( API_URL . '/plugin/blacklist-ips', [
//			'body'    => [ 'ip' => $this->ip ],
//			'timeout' => 20
//		] );
//		if ( ! is_wp_error( $response ) ) {
//			$responseData = json_decode( wp_remote_retrieve_body( $response ), true );
//			if ( $responseData['success'] ) {
//				$activity = 'IP:' . $this->ip . ' is marked as unsafe.';
//				$this->insert_malware_scanner_entry( $activity, '', 'Medium', 2 );
//			}
//		}
    }

    public function download_wordpress(): void
    {
        $zipUrl = "https://wordpress.org/wordpress-" . get_bloginfo('version') . ".zip";
        $zipFile = ABSPATH . 'wp-content/uploads/wordpress.zip';
        $extractionDirectory = ABSPATH . 'wp-content/uploads/wordpress';
        if (is_dir($extractionDirectory)) {
            self::delete_directory($extractionDirectory);
        }
        $zipContent = file_get_contents($zipUrl);
        file_put_contents($zipFile, $zipContent);
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            $zip->extractTo(ABSPATH.'wp-content/uploads');
            $zip->close();
        }
    }

    public function delete_directory($dir): void
    {
        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        WP_Filesystem();
        global $wp_filesystem;

        if ($wp_filesystem->is_dir($dir)) {
            $dir_list = $wp_filesystem->dirlist($dir);
            foreach ($dir_list as $object => $details) {
                if ($details['type'] === 'd') {
                    $this->delete_directory(trailingslashit($dir) . $object);
                } else {
                    $wp_filesystem->delete(trailingslashit($dir) . $object);
                }
            }
            $wp_filesystem->delete($dir, true);
        }
    }

    private function insert_malware_scanner_entry($activity, $file_path, $malware_type, $step): void
    {
        global $wpdb;
        $table_name = esc_sql($wpdb->prefix . 'sz_malware');

        if ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE file_path = %s AND malware_type = %s AND step = %s", $file_path, $malware_type, $step)) > 0) {
            // updated_at
            $wpdb->update(
                $table_name,
                [
                    'updated_at' => current_time('mysql')
                ],
                [
                    'file_path' => $file_path,
                    'malware_type' => $malware_type,
                    'step' => $step
                ],
                [
                    '%s'
                ],
                [
                    '%s',
                    '%s',
                    '%s'
                ]
            );
        } else {
            $wpdb->insert(
                $table_name,
                [
                    'file_path' => sanitize_text_field($file_path),
                    'malware_type' => sanitize_text_field($malware_type),
                    'step' => $step,
                    'activity' => $activity,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ],
                [
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ]
            );

            if ($wpdb->last_error) {
                error_log('Database insert error: ' . $wpdb->last_error);
            }
        }
    }

    public function subscribe(): void
    {
        sleep(1);
        $this->ajax_security();
        $request = $this->check_payload();

        $firstname = sanitize_text_field($request['firstname']);
        $lastname = sanitize_text_field($request['lastname']);
        $email = sanitize_email($request['email']);
        $hostname = sanitize_text_field($request['hostname']);
        $price = sanitize_text_field($request['price']);

        if ($firstname === '') {
            wp_send_json([
                'success' => false,
                'message' => 'First name is required.',
                'data' => []
            ]);
        }

        if ($lastname === '') {
            wp_send_json([
                'success' => false,
                'message' => 'Last name is required.',
                'data' => []
            ]);
        }

        if ($email === '') {
            wp_send_json([
                'success' => false,
                'message' => 'Email is required.',
                'data' => []
            ]);
        }

        $payload = [
            'hostname' => $hostname,
            'price' => $price,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email
        ];

        $request = wp_remote_post(API_URL . '/plugin/checkout', [
            'body' => wp_json_encode($payload),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);

        if (is_wp_error($request)) {
            wp_send_json([
                'success' => false,
                'message' => 'Error subscribing.',
                'data' => []
            ]);
        }

        $response = json_decode(wp_remote_retrieve_body($request), true);
        if (!$response['success']) {
            wp_send_json([
                'success' => false,
                'message' => 'API error.',
                'data' => [$response, $payload]
            ]);
        }

        wp_send_json($response);
    }

    private function system_check(): void
    {
        $payload = [];
        $payload['hostname'] = str_replace(['http://', 'https://'], [], get_site_url());

        if (get_option('sz_license') !== '') {
            $payload['license_key'] = get_option('sz_license');
        }

        $request = wp_remote_post(API_URL . '/plugin/info', [
            'body' => wp_json_encode($payload),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if (is_wp_error($request)) {
            return;
        }

        $body = wp_remote_retrieve_body($request);
        $data = json_decode($body, true);

        if (isset($data['success']) && $data['success'] === true) {
            $this->is_pro = true;
            $this->license_info = $data['data'];
        } else {
            $this->is_pro = false;
            $this->license_info = [];
        }
    }

    public function custom_dismiss_notice(): void
    {
        $this->ajax_security();
        update_option("sz_custom_dismiss_notice", "0");
    }
}
