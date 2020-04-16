<?php

namespace CV_Builder\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cv-builder-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cv-builder-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Callback for the admin menu
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {

        add_menu_page(__('CV Builder', $this->plugin_text_domain), //page title
            __('CV Builder', $this->plugin_text_domain), //menu title
            'manage_options', //capability
            $this->plugin_name, //menu_slug
            '',
            'dashicons-buddicons-buddypress-logo' // Logo
        );

        // Add a submenu page and save the returned hook suffix.
        $html_form_page_hook = add_submenu_page(
            $this->plugin_name, //parent slug
            __( 'CV Builder', $this->plugin_text_domain ), //page title
            __( 'Edit User', $this->plugin_text_domain ), //menu title
            'manage_options', //capability
            $this->plugin_name, //menu_slug
            array( $this, 'html_form_page_content' ) //callback for page content
        );

        // Add a submenu page and save the returned hook suffix.
        $html_form_page_hook = add_submenu_page(
            $this->plugin_name, //parent slug
            __( 'CV Builder', $this->plugin_text_domain ), //page title
            __( 'Add User', $this->plugin_text_domain ), //menu title
            'manage_options', //capability
            $this->plugin_name, //menu_slug
            array( $this, 'html_form_page_content' ) //callback for page content
        );

        /*
         * The $page_hook_suffix can be combined with the load-($page_hook) action hook
         * https://codex.wordpress.org/Plugin_API/Action_Reference/load-(page)
         *
         * The callback below will be called when the respective page is loaded
         */
        add_action( 'load-'.$html_form_page_hook, array( $this, 'loaded_html_form_submenu_page' ) );

    }

    /*
 * Callback for the add_submenu_page action hook
 *
 * The plugin's HTML form is loaded from here
 *
 * @since	1.0.0
 */
    public function html_form_page_content() {
        //show the form
        include_once('views/add-user.php');
    }

    /*
     * Callback for the load-($html_form_page_hook)
     * Called when the plugin's submenu HTML form page is loaded
     *
     * @since	1.0.0
     */
    public function loaded_html_form_submenu_page() {
        // called when the particular page is loaded.
    }

    /**
     *
     * @since    1.0.0
     */
    public function the_form_response() {
        global $wpdb;

        print_r($_POST);
        echo "HERE";
        die();

        $table_name = $wpdb->prefix . 'cv_jobs';
        $wpdb->insert(
            $table_name,
            array(
                'wp_user_id' => 1,
                'position' => 'scientist',
                'organization' => 'here',
            )
        );


        if( isset( $_POST['cv_add_user_meta_nonce'] ) && wp_verify_nonce( $_POST['cv_add_user_meta_nonce'], 'cv_add_user_meta_form_nonce') ) {
            $cv_user_meta_key = sanitize_key( $_POST['cv']['user_meta_key'] );
            $cv_user_meta_value = sanitize_text_field( $_POST['cv']['user_meta_value'] );
            $cv_user =  get_user_by( 'login',  $_POST['cv']['user_select'] );
            $cv_user_id = absint( $cv_user->ID ) ;


            // server response
            $admin_notice = "success";

            $this->custom_redirect( $admin_notice, $_POST );
            exit;
        }
        else {
            wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
                'response' 	=> 403,
                'back_link' => 'admin.php?page=' . $this->plugin_name,

            ) );
        }
    }

    /**
     * Redirect
     *
     * @since    1.0.0
     */
    public function custom_redirect( $admin_notice, $response ) {
        wp_redirect( esc_url_raw( add_query_arg( array(
            'cv_admin_add_notice' => $admin_notice,
            'cv_response' => $response,
        ),
            admin_url('admin.php?page='. $this->plugin_name )
        ) ) );

    }


    /**
     * Print Admin Notices
     *
     * @since    1.0.0
     */
    public function print_plugin_admin_notices() {
        if ( isset( $_REQUEST['cv_admin_add_notice'] ) ) {
            if( $_REQUEST['cv_admin_add_notice'] === "success") {
                $html =	'<div class="notice notice-success is-dismissible"> 
							<p><strong>The request was successful. </strong></p><br>';
                $html .= '<p>-'.$testvar.'-</p>';
                $html .= '<pre>' . htmlspecialchars( print_r( $_REQUEST['cv_response'], true) ) . '</pre></div>';
                echo $html;
            }

            // handle other types of form notices

        }
        else {
            return;
        }

    }


}