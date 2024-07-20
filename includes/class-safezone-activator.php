<?php

/**
 * Fired during plugin activation
 *
 * @link       https://brunos.digital
 * @since      1.0.0
 *
 * @package    Safezone
 * @subpackage Safezone/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Safezone
 * @subpackage Safezone/includes
 * @author     Brunos Digital <hello@brunos.digital>
 */
class Safezone_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        foreach(SAFEZONE_SETTINGS as $value){
            add_option($value['key'], $value['default']);
        }

        add_option('sz_custom_dismiss_notice', "1");

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
		$sz_whitelist = preg_replace('/[^a-zA-Z0-9_]/', '', $wpdb->prefix . "sz_whitelist");
		$sz_anti_spams = preg_replace('/[^a-zA-Z0-9_]/', '', $wpdb->prefix . "sz_anti_spams");
		$sz_firewall = preg_replace('/[^a-zA-Z0-9_]/', '', $wpdb->prefix . "sz_firewall");
		$sz_logs = preg_replace('/[^a-zA-Z0-9_]/', '', $wpdb->prefix . "sz_logs");
		$sz_malware = preg_replace('/[^a-zA-Z0-9_]/', '', $wpdb->prefix . "sz_malware");

		$sql_malware = "
    CREATE TABLE IF NOT EXISTS $sz_malware (
        id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
        file_path varchar(255) DEFAULT NULL,
        malware_type varchar(255) DEFAULT NULL,
        activity TEXT DEFAULT NULL,
        step int(2),
        status int(1) DEFAULT 1,
        updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;
";

		$sql_firewall = "
    CREATE TABLE IF NOT EXISTS $sz_firewall (
        id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
        ip varchar(255) NOT NULL,
        country_code varchar(255) DEFAULT NULL,
        country varchar(255) DEFAULT NULL,
        firewall_type varchar(255) DEFAULT NULL,
        user_agent varchar(255) DEFAULT NULL,
        activity TEXT DEFAULT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;
";

		$sql_anti_spams = "
    CREATE TABLE IF NOT EXISTS $sz_anti_spams (
        id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
        ip varchar(255) NOT NULL,
        country_code varchar(255) DEFAULT NULL,
        country varchar(255) DEFAULT NULL,
        spam_type varchar(255) DEFAULT NULL,
        user_agent varchar(255) DEFAULT NULL,
        activity TEXT DEFAULT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;
";

		$sql_whitelist = "
    CREATE TABLE IF NOT EXISTS $sz_whitelist (
        id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
        ip varchar(255) NOT NULL,
        country_code varchar(255) DEFAULT NULL,
        country varchar(255) DEFAULT NULL,
        hostname varchar(255) DEFAULT NULL,
        location varchar(255) DEFAULT NULL,
        ip_version varchar(255) DEFAULT NULL,
        isp varchar(255) DEFAULT NULL,
        timezone varchar(255) DEFAULT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;
";

		$sql_logs = "
    CREATE TABLE IF NOT EXISTS $sz_logs (
        id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
        username varchar(255) NOT NULL,
        category varchar(255) NOT NULL,
        activity varchar(255) NOT NULL,
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;
";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql_firewall);
		dbDelta($sql_whitelist);
		dbDelta($sql_anti_spams);
		dbDelta($sql_logs);
		dbDelta($sql_malware);

	}

}
