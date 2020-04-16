<?php

namespace CV_Builder\Inc\Core;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 **/
class Activator
{

    /**
     * Short Description.
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $min_php = '5.6.0';

        // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
        if (version_compare(PHP_VERSION, $min_php, '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('This plugin requires a minmum PHP Version of ' . $min_php);
        } else {
            global $wpdb;

            $table_name = $wpdb->prefix . 'cv_jobs';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE ". $table_name ." (
                `id` INT(11) NOT NULL AUTO_INCREMENT, 
                `wp_user_id` INT(11) NOT NULL, 
                `position` VARCHAR(255) DEFAULT NULL, 
                `organization` VARCHAR(255) DEFAULT NULL, 
                `location` BLOB, 
                `start_date` DATE DEFAULT NULL, 
                `end_date` DATE DEFAULT NULL, 
                PRIMARY KEY (`id`)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

    }

}
