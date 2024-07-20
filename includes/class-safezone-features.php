<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Safezone_Features' ) ) {
	class Safezone_Features {
		public function __construct() {
		}

		/**
		 *
		 * Block Blacklisted IPs
		 * @sz_block_blacklisted_ips
		 */

		public function block_blacklisted_ips(): void {
			$blacklist = [];
		}

		/**
		 *
		 * Block Bad Bots
		 * @sz_block_bad_bots
		 *
		 * */
		public function block_bad_bots(): void {
			$bad_bots = [];
		}

		/**
		 *
		 * Preventing Unwanted Attempts
		 * @sz_prevent_unwanted_attempts
		 *
		 */
		public function prevent_unwanted_attempts(): void {
			$attempts = [];
		}

		/**
		 *
		 * Disable Embeds
		 * @sz_disable_embeds
		 *
		 * */
		public function disable_embeds(): void {
			add_action( 'init', [ $this, 'disable_embeds_code_init' ], 9999 );
			add_action( 'wp_footer', [ $this, 'my_deregister_scripts' ] );
		}

		public function disable_embeds_code_init(): void {
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
			add_filter( 'embed_oembed_discover', '__return_false' );
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'tiny_mce_plugins', [ $this, 'disable_embeds_tiny_mce_plugin' ] );
			add_filter( 'rewrite_rules_array', [ $this, 'disable_embeds_rewrites' ] );
			remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
		}

		public function disable_embeds_tiny_mce_plugin( $plugins ): array {
			return array_diff( $plugins, [ 'wpembed' ] );
		}

		public function disable_embeds_rewrites( $rules ) {
			foreach ( $rules as $rule => $rewrite ) {
				if ( str_contains( $rewrite, 'embed=true' ) ) {
					unset( $rules[ $rule ] );
				}
			}

			return $rules;
		}

		public function my_deregister_scripts(): void {
			wp_dequeue_script( 'wp-embed' );
		}

		/**
		 *
		 * Disable XML-RPC
		 * @sz_disable_xml
		 *
		 * */
		public function disable_xml(): void {
			add_filter( 'xmlrpc_enabled', [ $this, '__return_false' ] );
			add_filter( 'wp_headers', [ $this, 'disable_x_pingback' ] );
		}

		public function disable_x_pingback( $headers ): array {
			unset( $headers['X-Pingback'] );

			return $headers;
		}

		/**
		 *
		 * Hide WP Version
		 * @sz_hide_wp_version
		 *
		 */

		public function hide_wp_version(): void {
			remove_action( 'wp_head', 'wp_generator' );
			add_filter( 'the_generator', '__return_false' );
		}

		/**
		 *
		 * Disable Self Pingbacks
		 * @sz_disable_self_pingbacks
		 *
		 * */
		public function disable_self_pingbacks(): void {
			add_action( 'pre_ping', [ $this, 'disable_self_pingbacks_hook' ] );
		}

		function disable_self_pingbacks_hook( &$links ): void {
			foreach ( $links as $l => $link ) {
				if ( str_starts_with( $link, get_option( 'home' ) ) ) {
					unset( $links[ $l ] );
				}
			}
		}

		/**
		 *
		 * Disable REST API
		 * @sz_disable_rest_api
		 *
		 * */
		public function disable_rest_api(): void {
			add_filter( 'rest_jsonp_enabled', '__return_false' );
			add_filter( 'rest_authentication_errors', [ $this, 'authentication_errors' ] );
		}

		public function authentication_errors( $result ): mixed {
			if ( true === $result || is_wp_error( $result ) ) {
				return $result;
			}

			if ( ! is_user_logged_in() ) {
				return new WP_Error(
					'rest_not_logged_in',
					'Safe Zone Protection: You are not logged in.',
					[ 'status' => 401 ]
				);
			}

			return $result;
		}

		/**
		 *
		 * Ignore Logged Users
		 * @sz_ignore_logged
		 *
		 * */

		public function ignore_logged(): void {

		}

		/**
		 *
		 * XSS Check
		 * @sz_xss_check
		 *
		 * */
		public function xss_check(): void {

		}

		/**
		 *
		 * Login Protection
		 * @sz_login_protection
		 *
		 * */
		public function login_protection(): void {

			add_action( 'wp_login_failed', [ $this, 'login_failed' ], 10, 3 );
			add_filter( 'authenticate', [ $this, 'authentication' ], 30, 3 );

			add_action( 'login_form', [ $this, 'add_math_problem_to_login' ] );
			add_filter( 'wp_authenticate_user', [ $this, 'validate_math_problem_on_login' ], 10, 1 );
			add_filter( 'login_errors', [ $this, 'customize_error_message_for_incorrect_math_answer' ] );

			add_action( 'wp_login_failed', [ $this, 'login_failed' ], 10, 3 );
		}

		public function authentication( $user, $username, $password ) {
			$transient = get_transient( 'limit_login_attempt' );
			if ( $transient && $transient > LOGIN_ATTEMPTS ) {
				$transient_expiration = get_option( '_transient_timeout_limit_login_attempt' );
				$waiting_seconds      = abs( $transient_expiration - time() );


				$ip   = $this->get_ip_info();
				$data = [
					'ip'           => $ip['ip'],
					'country_code' => $ip['country_code'],
					'country'      => $ip['country'],
					'user_agent'   => $ip['user_agent'],
					'spam_type'    => 'Login',
					'activity'     => 'IP blocked by login attempts: ' . $ip['ip'],
				];
				$this->add_spam_report( $data );


				return new WP_Error( 'limit_login_attempt', 'You are blocked for ' . $waiting_seconds . ' seconds' );
			}

			return $user;
		}

		public function login_failed( $username ): void {
			$transient = get_transient( 'limit_login_attempt' );
			if ( $transient ) {
				$attempts = $transient + 1;
				//TODO: API blacklist store
				set_transient( 'limit_login_attempt', $attempts );
			} else {
				set_transient( 'limit_login_attempt', 1, SECOND_LOCKED );
			}
		}

		public function add_math_problem_to_login(): void {
			$num1   = wp_rand( 1, 30 );
			$num2   = wp_rand( 1, 30 );
			$result = $num1 + $num2;

			echo '<p>';
			echo '<label for="math_answer">Math problem for security</label>';
			echo '<input placeholder="' . esc_attr( intval( $num1 ) . ' + ' . intval( $num2 ) . ' = ?' ) . '" type="number" name="math_answer" id="math_answer" class="input" value="" size="20" autocapitalize="off" autocorrect="off" />';
			echo '<input type="hidden" name="math_result" value="' . esc_attr( intval( $result ) ) . '">';
			echo '</p>';
			wp_nonce_field( 'math_problem_nonce_action', 'math_problem_nonce' );
		}

		public function validate_math_problem_on_login( $user ) {
			if ( ! isset( $_POST['math_problem_nonce'] ) || ! wp_verify_nonce( $_POST['math_problem_nonce'], 'math_problem_nonce_action' ) ) {
				return new WP_Error( 'invalid_nonce', __( '<strong>Error:</strong> Invalid form submission. Please try again.', 'safezone' ) );
			}

			if ( ! isset( $_POST['math_answer'] ) || ! isset( $_POST['math_result'] ) ) {
				return $user;
			}

			$math_answer    = (int) $_POST['math_answer'];
			$correct_answer = (int) $_POST['math_result'];

			if ( $math_answer != $correct_answer ) {
				return new WP_Error( 'incorrect_math_answer', __( '<strong>Error:</strong> Incorrect answer to the math problem. Please try again.', 'safezone' ) );
			}

			return $user;
		}

		public function customize_error_message_for_incorrect_math_answer( $errors, $redirect_to = null ) {
			if ( isset( $errors->errors['incorrect_math_answer'] ) ) {
				$errors->add( 'incorrect_math_answer', __( '<strong>Error:</strong> Incorrect answer to the math problem. Please try again.', 'safezone' ) );
			}

			return $errors;
		}

		/**
		 *
		 * Disable Comments
		 * @sz_disable_comments
		 *
		 * */
		public function disable_comments(): void {
			add_action( 'admin_init', [ $this, 'redirect' ] );
			add_filter( 'comments_open', '__return_false', 20, 2 );
			add_filter( 'pings_open', '__return_false', 20, 2 );
			add_filter( 'comments_array', '__return_empty_array', 10, 2 );
			add_action( 'admin_menu', [ $this, 'remove_edit_comments' ] );
			add_action( 'init', [ $this, 'admin_bar_show' ] );
		}

		public function remove_edit_comments(): void {
			remove_menu_page( 'edit-comments.php' );
		}

		public function admin_bar_show(): void {
			if ( is_admin_bar_showing() ) {
				remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			}
		}

		public function redirect(): void {
			global $pagenow;
			if ( $pagenow === 'edit-comments.php' ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		}

		/**
		 *
		 * Disable Heartbeat
		 * @sz_disable_heartbeat
		 *
		 * */

		public function disable_heartbeat(): void {
			add_action( 'init', [ $this, 'stop_heartbeat' ], 1 );
		}

		public function stop_heartbeat(): void {
			wp_deregister_script( 'heartbeat' );
		}

		/**
		 *
		 * Remove RSD Link
		 * @sz_remove_rsd_link
		 *
		 */
		public function remove_rsd_link(): void {
            remove_action ('wp_head', 'rsd_link');
            remove_action ('wp_head', 'wlwmanifest_link');
		}

		/**
		 *
		 * Remove Shortlink
		 * @sz_remove_shortlink
		 *
		 * */
		public function remove_shortlink(): void {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		}

		/**
		 *
		 * Disable RSS Feeds
		 * @sz_disable_rss_feeds
		 *
		 * */
		public function disable_rss_feeds(): void {
			add_action( 'do_feed', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_rdf', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_rss', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_rss2', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_atom', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_rss2_comments', [ $this, 'disable_feed' ], 1 );
			add_action( 'do_feed_atom_comments', [ $this, 'disable_feed' ], 1 );

			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'feed_links', 2 );
		}

		public function disable_feed(): WP_Error {
			return new WP_Error(
				'no_feed',
				'No feed available, please visit the homepage!',
				[ 'status' => 404 ]
			);
		}

		/**
		 *
		 * Aggressive Search
		 * @sz_aggressive_search
		 *
		 * */
		public function aggressive_search(): void {

		}

		/**
		 *
		 * Importing File Monitoring
		 * @sz_importing_file_monitoring
		 *
		 * */
		public function importing_file_monitoring(): void {

		}

		/**
		 *
		 * Enable Auto Scanning
		 * @sz_enable_autoscanning
		 *
		 * */
		public function enable_autoscanning(): void {

		}

		/**
		 *
		 * Anti-Spam
		 *
		 */
		public function anti_spam(): void {
			add_action( 'transition_comment_status', [ $this, 'my_comment_spam_detection' ], 10, 3 );
			add_action( 'manage_users_columns', [ $this, 'new_modify_user_table' ] );
			add_filter( 'manage_users_custom_column', [ $this, 'new_modify_user_table_row' ], 10, 3  );
		}

		public function my_comment_spam_detection( $new_status, $old_status, $comment ): void {
			if ( $new_status === 'spam' ) {
				$ip   = $this->get_ip_info();
				$data = [
					'ip'           => $ip['ip'],
					'country_code' => $ip['country_code'],
					'country'      => $ip['country'],
					'user_agent'   => $ip['user_agent'],
					'spam_type'    => 'Comment',
					'activity'     => 'Spam comment blocked: #' . $comment->comment_ID,
				];
				$this->add_spam_report( $data );
			}
		}


		public function new_modify_user_table( $column ) {
			$column['login_at'] = 'Login At';

			return $column;
		}

        public function new_modify_user_table_row( $val, $column_name, $user_id ) {
            if ( 'login_at' === $column_name ) {
                return get_the_author_meta( 'login_at', $user_id );
            }
            return $val;
        }

		/**
		 *
		 *  Firewall
		 * @sz_firewall
		 *
		 */

		public function firewall(): void {
			add_action( 'init', [ $this, 'check_user_in_door' ] );
		}

		public function check_user_in_door(): void {
			$this->get_user_info_check();
		}

		public function get_user_info_check(): void {
			if ( get_option( 'sz_firewall' ) === '1' ) {
				$response = $this->check_api();
				if ( isset($response['success']) && $response['success'] && count( $response['data'] ) > 0 ) {
					foreach ( $response['data'] as $data ) {
						if ( $data['firewall_type'] === 'Blocked' && get_option( 'sz_block_blacklisted_ips	' ) === '1' ) {
							$this->add_firewall_report( $data );
							wp_redirect( 'https://www.google.com' );
						}
						if ( $data['firewall_type'] === 'Bad Bots' && get_option( 'sz_block_bad_bots' ) === '1' ) {
							$this->add_firewall_report( $data );
							wp_redirect( 'https://www.google.com' );
						}
						if ( $data['firewall_type'] === 'Bad Referer' && get_option( 'sz_block_bad_referer' ) === '1' ) {
							$this->add_firewall_report( $data );
							wp_redirect( 'https://www.google.com' );
						}
					}
				}
			}
		}

		private function check_api(): array {
			$site_info = $this->get_ip_info();
			$response  = wp_remote_post( API_URL . '/plugin/firewall', [
				'body'    => wp_json_encode( $site_info ),
				'headers' => [
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json'
				],
			] );
			$body      = wp_remote_retrieve_body( $response );
			if ( is_wp_error( $response ) ) {
				wp_die( 'Error: ' . esc_html( $response->get_error_message() ) );
			}

			return json_decode( $body, true );
		}

		/**
		 *
		 * Add Spam Report
		 *
		 */
		private function add_spam_report( $data ): void {
			global $wpdb;
			$table_name = $wpdb->prefix . 'sz_anti_spams';

			$check = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE ip = %s", $table_name, $data["ip"] ) );
			if ( count( $check ) > 0 ) {
				return;
			}

			$wpdb->insert(
				$table_name,
				[
					'ip'           => $data["ip"],
					'country_code' => $data["country_code"],
					'country'      => $data["country"],
					'spam_type'    => $data['spam_type'],
					'user_agent'   => $data["user_agent"],
					'activity'     => $data["activity"],
					'created_at'   => current_time( 'mysql' )
				],
				[
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				]
			);

			if ( $wpdb->last_error ) {
				error_log( 'Database insert error: ' . $wpdb->last_error );
			}
		}

		/**
		 *
		 * Add Firewall Report
		 *
		 */
		private function add_firewall_report( $data ): void {
			global $wpdb;
			$table_name = $wpdb->prefix . 'sz_firewall';

			// prepare ile check et
			$check = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE ip = %s AND user_agent = %s", $table_name, $data["ip"], $data["user_agent"] ) );
			if ( count( $check ) > 0 ) {
				wp_redirect( 'https://www.google.com' );

				return;
			}

			$wpdb->insert(
				$table_name,
				[
					'ip'            => $data["ip"],
					'country_code'  => $data["country_code"],
					'country'       => $data["country"],
					'firewall_type' => $data["firewall_type"],
					'user_agent'    => $data["user_agent"],
					'activity'      => $data["activity"],
					'created_at'    => current_time( 'mysql' )
				],
				[
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				]
			);
		}

		private function get_ip_info(): array {
			$response = wp_remote_get( $url = "https://1.1.1.1/cdn-cgi/trace" );
			$body     = wp_remote_retrieve_body( $response );
			if ( is_wp_error( $response ) ) {
				wp_die( 'Error: ' . esc_html( $response->get_error_message() ) );
			}
			$lines  = explode( "\n", $body );
			$result = [];
			foreach ( $lines as $line ) {
				if ( ! empty( $line ) ) {
					list( $key, $value ) = explode( '=', $line, 2 );
					$result[ $key ] = $value;
				}
			}
			$site_info                 = [];
			$site_info['hostname']     = match ( true ) {
				isset( $_SERVER['HTTP_X_FORWARDED_HOST'] ) => $_SERVER['HTTP_X_FORWARDED_HOST'],
				isset( $_SERVER['HTTP_HOST'] ) => $_SERVER['HTTP_HOST'],
				default => $_SERVER['SERVER_NAME']
			};
			$site_info['ip']           = $result['ip'];
			$site_info['user_agent']   = $_SERVER['HTTP_USER_AGENT'];
			$site_info['http_referer'] = $_SERVER['HTTP_REFERER'] ?? '';
			$site_info['country_code'] = $result['loc'];
			$site_info['country']      = COUNTRIES[ $result['loc'] ];

			return $site_info;
		}

	}
}