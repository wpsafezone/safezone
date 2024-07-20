<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://brunos.digital
 * @since             1.0.0
 * @package           Safezone
 *
 * @wordpress-plugin
 * Plugin Name:       Safe Zone
 * Plugin URI:        https://wpsafezone.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Brunos Digital
 * Author URI:        https://brunos.digital/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       safezone
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Constants
define("SAFEZONE_PLUGIN_URL", plugin_dir_url(__FILE__));
define("SAFEZONE_PLUGIN_PATH", plugin_dir_path(__FILE__));

const SAFEZONE_PLUGIN_VERSION = '1.0.0';
const SAFEZONE_PLUGIN_NAME = 'Safe Zone';
const SAFEZONE_PLUGIN_SLUG = 'safezone';
const API_URL = 'https://api.wpsafezone.com/v1';
const CLIENT_URL = 'https://wpsafezone.com';

const AUTOSCANNING_HOURS = ['09:00', '10:00'];
const AUTOSCANNING_PERIODS = [
    [
        'name' => 'Daily',
        'slug' => 'daily',
    ],
    [
        'name' => 'Weekly',
        'slug' => 'weekly',
    ],
    [
        'name' => 'Monthly',
        'slug' => 'monthly',
    ]
];
const LOGIN_ATTEMPTS = 10;
const SECOND_LOCKED = 60;

const FIREWALL_STATES = [
    [
        'name' => 'Allowed',
        'slug' => 'allowed',
    ],
    [
        'name' => 'Bad Bots',
        'slug' => 'bad-bots',
    ],
	[
        'name' => 'Bad Referrer',
        'slug' => 'bad-referrer',
    ],
    [
        'name' => 'Blocked',
        'slug' => 'blocked',
    ],
    [
        'name' => 'Whitelisted',
        'slug' => 'whitelisted',
    ]
];

const LOCKOUTS_STATES = [
    [
        'name' => 'Blocked',
        'slug' => 'blocked',
    ]
];

const ANTI_SPAM_STATES = [
    [
        'name' => 'Comment',
        'slug' => 'comment',
    ],
    [
        'name' => 'Signup',
        'slug' => 'signup',
    ],
    [
        'name' => 'Forms',
        'slug' => 'forms',
    ]
];

const MALWARE_STATES = [
    [
        'name' => 'Low',
        'slug' => 'low'
    ],
    [
        'name' => 'Medium',
        'slug' => 'medium'
    ],
    [
        'name' => 'Suspicious',
        'slug' => 'suspicious'
    ],
    [
        'name' => 'Critical',
        'slug' => 'critical'
    ]
];

const LOG_STATES = [
    [
        'name' => 'Firewall',
        'slug' => 'firewall'
    ],
    [
        'name' => 'Malware',
        'slug' => 'malware'
    ],
    [
        'name' => 'Anti Spam',
        'slug' => 'anti-spam'
    ],
    [
        'name' => 'Whitelist',
        'slug' => 'whitelist'
    ],
    [
        'name' => 'Notifications',
        'slug' => 'notifications'
    ]
];

const VALID_PAGES = ['firewall', 'malware', 'anti-spam', 'events', 'lockouts', 'whitelist', 'logs', 'settings'];
const VALID_TABS = ['firewall', 'malware', 'anti-spam', 'notifications', 'changelog', 'licence'];

const WPKSES = [
	'li'     => [ 'class' => [] ],
	'span'   => [ 'class' => [], 'id' => [] ],
	'a'      => [ 'class' => [], 'href' => [], 'id' => [] ],
	'button' => [ 'class' => [], 'href' => [], 'id' => [] ],
	'ul'     => [ 'class' => [] ],
	'div'    => [ 'class' => [], 'id' => [], 'data-type' => [], 'style' => [] ],
	'b'      => [ 'class' => [] ],
	'strong' => [ 'class' => [] ],
	'label'  => [ 'class' => [] ],
	'table'  => [ 'class' => [], 'id' => [] ],
	'thead'  => [ 'class' => [] ],
	'tbody'  => [ 'class' => [] ],
	'tr'     => [ 'class' => [] ],
	'td'     => [ 'class' => [] ],
	'th'     => [ 'class' => [] ],
	'input'  => [
		'class'         => [],
		'id'            => [],
		'name'          => [],
		'value'         => [],
		'placeholder'   => [],
		'type'          => [],
		'data-price'    => [],
		'data-id'       => [],
		'data-type'     => [],
		'data-interval' => [],
		'data-result'   => [],
		'disabled' => [],
		'aria-label' => [],
		'role' => [],
		'checked' => [],
		'data-key' => []
	],
	'select' => [ 'class' => [], 'id' => [], 'value' => [] ],
	'option' => [ 'class' => [] ],
	'svg'    => [ 'class' => [] ],
	'use'    => [ 'xlink:href' => [] ],
	'img'    => [ 'src' => [], 'class' => [] ]
];

const IMAGE_EXTENSIONS = [ 'jpg', 'jpeg', 'png', 'gif' ];

const SAFEZONE_SETTINGS = [
    [
        'key' => 'sz_uptime_monitoring',
        'title' => 'Uptime Monitoring',
        'description' => 'Enable or disable the uptime monitoring notification.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'notifications',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_weekly_reports',
        'title' => 'Weekly Reports',
        'description' => 'Enable or disable the weekly reports notification.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'notifications',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_update_notifications',
        'title' => 'Update Notifications',
        'description' => 'Enable or disable the update notifications notification.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'notifications',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_license',
        'title' => 'License',
        'description' => 'Enter your license key to activate the plugin.',
        'type' => 'text',
        'default' => '',
        'group' => '',
        'is_pro' => false,
        'listed' => false
    ],
    [
        'key' => 'sz_firewall',
        'title' => 'Firewall',
        'description' => 'Enable or disable the firewall feature.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => false,
        'listed' => false
    ],
    [
        'key' => 'sz_anti_spam',
        'title' => 'Anti Spam',
        'description' => 'Enable or disable the anti-spam feature.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => false,
    ],
    [
        'key' => 'sz_login_protection',
        'title' => 'Login Protection',
        'description' => 'Protects the login page from brute force attacks. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_block_blacklisted_ips',
        'title' => 'Block Blacklisted IP\'s',
        'description' => 'Block IP addresses that are known to be malicious. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
//    [
//        'key' => 'sz_block_bad_bots',
//        'title' => 'Block Bad Bots',
//        'description' => 'Block bad bots that are known to cause problems for websites. (Read on Docs)',
//        'type' => 'checkbox',
//        'default' => '0',
//        'group' => 'firewall',
//        'is_pro' => true,
//        'listed' => true
//    ],
//    [
//        'key' => 'sz_preventing_unwanted_attempts',
//        'title' => 'Preventing Unwanted Attempts',
//        'description' => 'Prevents unwanted attempts to access the site. (Read on Docs)',
//        'type' => 'checkbox',
//        'default' => '0',
//        'group' => 'firewall',
//        'is_pro' => true,
//        'listed' => true
//    ],
    [
        'key' => 'sz_xss_check',
        'title' => 'XSS Check',
        'description' => 'Cross-site scripting attacks are one of the more common types of website attacks. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_embeds',
        'title' => 'Disable Embeds',
        'description' => 'If you don’t need the oEmbed feature for the site, you can disable the feature to improve the site’s load time. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_xml',
        'title' => 'Disable XML-RPC',
        'description' => 'The file, which can be helpful for certain functions, can also pose security risks if not managed correctly. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_hide_wp_version',
        'title' => 'Hide WP Version',
        'description' => 'If you leave the WordPress version publicly available, you will give this information to attackers.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_self_pingbacks',
        'title' => 'Disable Self Pingbacks',
        'description' => 'Self Pingbacks are nothing but a waste of resources. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_rest_api',
        'title' => 'Disable REST API',
        'description' => 'It poses a security risk, as some data is accessible by going to certain REST API paths.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_ignore_logged',
        'title' => 'Ignore Logged Users',
        'description' => 'Disables the Firewall feature for all logged in users. (For better site performance)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'firewall',
        'is_pro' => true,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_comments',
        'title' => 'Disable Comments',
        'description' => 'The setting removes all comment fields and disables the comment feature on your website.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_heartbeat',
        'title' => 'Disable Heartbeat',
        'description' => 'WordPress Heartbeat should be disabled (or limited) since it increases CPU usage. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_remove_rsd_link',
        'title' => 'Remove RSD Link',
        'description' => 'RSD/WLW are used if you plan on using Windows Live Writer to write to your wordpress site. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_remove_shortlink',
        'title' => 'Remove Shortlink',
        'description' => 'If you are using permalinks, such as domain.com/example, it is just unnecessary code.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_disable_rss_feeds',
        'title' => 'Disable RSS Feeds',
        'description' => 'By default WordPress creates all kinds of RSS feeds built-in. (Read on Docs)',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'anti-spam',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_enable_autoscanning',
        'title' => 'Enable Autoscanning & Schedule',
        'description' => 'If you want automatic scans on the site, please enable',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_autoscanning_period',
        'title' => 'Schedule Autoscanning Period',
        'description' => 'Select an interval for automatic scanning',
        'type' => 'select',
        'default' => 'monthly',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => false
    ],
    [
        'key' => 'sz_autoscanning_date',
        'title' => 'Schedule Autoscanning Date',
        'description' => 'Enable it if you are sure that your site has severe attacks. Disable for standard scans.',
        'type' => 'radio',
        'default' => '',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => false
    ],
    [
        'key' => 'sz_importing_file_monitoring',
        'title' => 'Importing File Monitoring',
        'description' => 'Scans for changes in critical files such as WordPress core and notifies you of the changes.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_scan_html_code',
        'title' => 'Scan HTML Code',
        'description' => 'Includes additional HTML files and codes in your site files into the scan.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_heuristic_analysis',
        'title' => 'Heuristic Analysis',
        'description' => 'Strengthens malicious file scanning by performing intuitive analysis with AI.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_check_plugin',
        'title' => 'Check Plugin',
        'description' => 'Scans all plugins installed on your site for malware.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ],
    [
        'key' => 'sz_check_theme',
        'title' => 'Check Theme',
        'description' => 'Scans all themes installed on your site for malware.',
        'type' => 'checkbox',
        'default' => '0',
        'group' => 'malware',
        'is_pro' => false,
        'listed' => true
    ]
];

const COUNTRIES = [
	"AF" => "Afghanistan",
	"AL" => "Albania",
	"DZ" => "Algeria",
	"AS" => "American Samoa",
	"AD" => "Andorra",
	"AO" => "Angola",
	"AI" => "Anguilla",
	"AQ" => "Antarctica",
	"AG" => "Antigua and Barbuda",
	"AR" => "Argentina",
	"AM" => "Armenia",
	"AW" => "Aruba",
	"AU" => "Australia",
	"AT" => "Austria",
	"AZ" => "Azerbaijan",
	"BS" => "Bahamas",
	"BH" => "Bahrain",
	"BD" => "Bangladesh",
	"BB" => "Barbados",
	"BY" => "Belarus",
	"BE" => "Belgium",
	"BZ" => "Belize",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BT" => "Bhutan",
	"BO" => "Bolivia",
	"BA" => "Bosnia and Herzegovina",
	"BW" => "Botswana",
	"BR" => "Brazil",
	"IO" => "British Indian Ocean Territory",
	"BN" => "Brunei Darussalam",
	"BG" => "Bulgaria",
	"BF" => "Burkina Faso",
	"BI" => "Burundi",
	"CV" => "Cabo Verde",
	"KH" => "Cambodia",
	"CM" => "Cameroon",
	"CA" => "Canada",
	"KY" => "Cayman Islands",
	"CF" => "Central African Republic",
	"TD" => "Chad",
	"CL" => "Chile",
	"CN" => "China",
	"CO" => "Colombia",
	"KM" => "Comoros",
	"CG" => "Congo",
	"CD" => "Congo, Democratic Republic of the",
	"CR" => "Costa Rica",
	"CI" => "Cote d'Ivoire",
	"HR" => "Croatia",
	"CU" => "Cuba",
	"CY" => "Cyprus",
	"CZ" => "Czechia",
	"DK" => "Denmark",
	"DJ" => "Djibouti",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"EC" => "Ecuador",
	"EG" => "Egypt",
	"SV" => "El Salvador",
	"GQ" => "Equatorial Guinea",
	"ER" => "Eritrea",
	"EE" => "Estonia",
	"SZ" => "Eswatini",
	"ET" => "Ethiopia",
	"FJ" => "Fiji",
	"FI" => "Finland",
	"FR" => "France",
	"GA" => "Gabon",
	"GM" => "Gambia",
	"GE" => "Georgia",
	"DE" => "Germany",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GR" => "Greece",
	"GL" => "Greenland",
	"GD" => "Grenada",
	"GU" => "Guam",
	"GT" => "Guatemala",
	"GN" => "Guinea",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HT" => "Haiti",
	"HN" => "Honduras",
	"HK" => "Hong Kong",
	"HU" => "Hungary",
	"IS" => "Iceland",
	"IN" => "India",
	"ID" => "Indonesia",
	"IR" => "Iran",
	"IQ" => "Iraq",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IT" => "Italy",
	"JM" => "Jamaica",
	"JP" => "Japan",
	"JO" => "Jordan",
	"KZ" => "Kazakhstan",
	"KE" => "Kenya",
	"KI" => "Kiribati",
	"KP" => "Korea, Democratic People's Republic of",
	"KR" => "Korea, Republic of",
	"KW" => "Kuwait",
	"KG" => "Kyrgyzstan",
	"LA" => "Lao People's Democratic Republic",
	"LV" => "Latvia",
	"LB" => "Lebanon",
	"LS" => "Lesotho",
	"LR" => "Liberia",
	"LY" => "Libya",
	"LI" => "Liechtenstein",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"MO" => "Macao",
	"MG" => "Madagascar",
	"MW" => "Malawi",
	"MY" => "Malaysia",
	"MV" => "Maldives",
	"ML" => "Mali",
	"MT" => "Malta",
	"MH" => "Marshall Islands",
	"MR" => "Mauritania",
	"MU" => "Mauritius",
	"MX" => "Mexico",
	"FM" => "Micronesia",
	"MD" => "Moldova",
	"MC" => "Monaco",
	"MN" => "Mongolia",
	"ME" => "Montenegro",
	"MS" => "Montserrat",
	"MA" => "Morocco",
	"MZ" => "Mozambique",
	"MM" => "Myanmar",
	"NA" => "Namibia",
	"NR" => "Nauru",
	"NP" => "Nepal",
	"NL" => "Netherlands",
	"NC" => "New Caledonia",
	"NZ" => "New Zealand",
	"NI" => "Nicaragua",
	"NE" => "Niger",
	"NG" => "Nigeria",
	"NU" => "Niue",
	"MK" => "North Macedonia",
	"NO" => "Norway",
	"OM" => "Oman",
	"PK" => "Pakistan",
	"PW" => "Palau",
	"PS" => "Palestine",
	"PA" => "Panama",
	"PG" => "Papua New Guinea",
	"PY" => "Paraguay",
	"PE" => "Peru",
	"PH" => "Philippines",
	"PL" => "Poland",
	"PT" => "Portugal",
	"PR" => "Puerto Rico",
	"QA" => "Qatar",
	"RO" => "Romania",
	"RU" => "Russian Federation",
	"RW" => "Rwanda",
	"KN" => "Saint Kitts and Nevis",
	"LC" => "Saint Lucia",
	"VC" => "Saint Vincent and the Grenadines",
	"WS" => "Samoa",
	"SM" => "San Marino",
	"ST" => "Sao Tome and Principe",
	"SA" => "Saudi Arabia",
	"SN" => "Senegal",
	"RS" => "Serbia",
	"SC" => "Seychelles",
	"SL" => "Sierra Leone",
	"SG" => "Singapore",
	"SK" => "Slovakia",
	"SI" => "Slovenia",
	"SB" => "Solomon Islands",
	"SO" => "Somalia",
	"ZA" => "South Africa",
	"SS" => "South Sudan",
	"ES" => "Spain",
	"LK" => "Sri Lanka",
	"SD" => "Sudan",
	"SR" => "Suriname",
	"SE" => "Sweden",
	"CH" => "Switzerland",
	"SY" => "Syrian Arab Republic",
	"TW" => "Taiwan",
	"TJ" => "Tajikistan",
	"TZ" => "Tanzania",
	"TH" => "Thailand",
	"TL" => "Timor-Leste",
	"TG" => "Togo",
	"TO" => "Tonga",
	"TT" => "Trinidad and Tobago",
	"TN" => "Tunisia",
	"TR" => "Turkey",
	"TM" => "Turkmenistan",
	"TV" => "Tuvalu",
	"UG" => "Uganda",
	"UA" => "Ukraine",
	"AE" => "United Arab Emirates",
	"GB" => "United Kingdom",
	"US" => "United States of America",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VU" => "Vanuatu",
	"VE" => "Venezuela",
	"VN" => "Viet Nam",
	"YE" => "Yemen",
	"ZM" => "Zambia",
	"ZW" => "Zimbabwe"
];

const DOCUMENTATION = [
    [
        'name' => 'Quick Start Guide',
        'url' => CLIENT_URL . '/docs-category/getting-started'
    ],
    [
        'name' => 'Configuration Manual',
        'url' => CLIENT_URL . '/docs-category/features'
    ],
    [
        'name' => 'Update Instructions',
        'url' => CLIENT_URL . '/docs-category/additional-settings'
    ]
];

const SECURITY_PLUGINS = [
    'Sucuri Security',
    'Wordfence',
    'CleanTalk',
    'All-in-one Security',
    'Solid Security',
    'Shield Security',
    'BulletProof',
    'Security',
    'Malcare Wordpress Security Plugin',
    'BBQ Firewall',
    'Security Ninja',
    'SecuPress',
    'WP Security Safe',
    'Titan Anti-spam & Security',
];

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-safezone-activator.php
 */
function activate_safezone(): void
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-safezone-activator.php';
    Safezone_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-safezone-deactivator.php
 */
function deactivate_safezone(): void
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-safezone-deactivator.php';
    Safezone_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_safezone');
register_deactivation_hook(__FILE__, 'deactivate_safezone');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-safezone.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_safezone(): void
{
    $plugin = new Safezone();
    $plugin->run();
}

run_safezone();
