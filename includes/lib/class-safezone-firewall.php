<?php

if (!class_exists('Safezone_Firewall')) {
    class Safezone_Firewall
    {
        public static function add_htaccess_lines() {
            $htaccess_file = ABSPATH . '.htaccess';
            $htaccess_content = "
<Files .htaccess>
<IfModule mod_authz_core.c>
Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
</Files>
ServerSignature Off
LimitRequestBody 104857600
<Files wp-config.php>
<IfModule mod_authz_core.c>
Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
</Files>
";
	        global $wp_filesystem;

	        // WP_Filesystem API'sini başlat
	        if ( ! function_exists( 'WP_Filesystem' ) ) {
		        require_once ABSPATH . 'wp-admin/includes/file.php';
	        }

	        if (WP_Filesystem()) {
		        // .htaccess dosyasını oku
		        $current_content = $wp_filesystem->get_contents($htaccess_file);

		        // Eğer eklemek istediğiniz satırlar zaten dosyada bulunmuyorsa ekle
		        if (strpos($current_content, $htaccess_content) === false) {
			        $new_content = $current_content . "\n" . $htaccess_content;

			        // .htaccess dosyasını güncelle
			        if ($wp_filesystem->put_contents($htaccess_file, $new_content, FS_CHMOD_FILE)) {
				        return true;
			        } else {
				        return false;
			        }
		        } else {
			        return true;
		        }
	        } else {
		        return false;
	        }
        }

        public static function remove_htaccess_lines() {
            $htaccess_file = ABSPATH . '.htaccess';
            $htaccess_content = "
<Files .htaccess>
<IfModule mod_authz_core.c>
Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
</Files>
ServerSignature Off
LimitRequestBody 104857600
<Files wp-config.php>
<IfModule mod_authz_core.c>
Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
</Files>
";
	        global $wp_filesystem;

	        // WP_Filesystem API'sini başlat
	        if ( ! function_exists( 'WP_Filesystem' ) ) {
		        require_once ABSPATH . 'wp-admin/includes/file.php';
	        }

	        WP_Filesystem();

	        // .htaccess dosyasını oku
	        $current_content = $wp_filesystem->get_contents($htaccess_file);

            if (str_contains($current_content, $htaccess_content)) {
                $new_content = str_replace($htaccess_content, '', $current_content);
	            if ($wp_filesystem->put_contents($htaccess_file, $new_content)) {
		            return true;
	            } else {
		            return false;
	            }
            } else {
                return true;
            }
        }
    }
}