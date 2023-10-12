<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.getuwired.com/
 * @since      1.0.0
 *
 * @package    Page_Visibility
 * @subpackage Page_Visibility/includes
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
 * @package    Page_Visibility
 * @subpackage Page_Visibility/includes
 * @author     Cody Brant (GetUWired) <cbrant@getuwired.com>
 */
class Page_Visibility {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Page_Visibility_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

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
		if ( defined( 'PAGE_VISIBILITY_VERSION' ) ) {
			$this->version = PAGE_VISIBILITY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'page-visibility';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Page_Visibility_Loader. Orchestrates the hooks of the plugin.
	 * - Page_Visibility_i18n. Defines internationalization functionality.
	 * - Page_Visibility_Admin. Defines all hooks for the admin area.
	 * - Page_Visibility_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-page-visibility-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-page-visibility-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-page-visibility-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-page-visibility-public.php';

		$this->loader = new Page_Visibility_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Page_Visibility_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Page_Visibility_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Page_Visibility_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('add_meta_boxes', $this, 'page_visibility_create_metaboxes');
		$this->loader->add_action('save_post', $this, 'page_visibility_save_metabox', 1, 2);
		$this->loader->add_action('save_post', $this, 'page_visibility_save_revisions');
		$this->loader->add_action('wp_restore_post_revision', $this, 'page_visibility_restore_revisions', 10, 2);
		$this->loader->add_filter('_wp_post_revision_fields', $this, 'page_visibility_get_revisions_fields');
		$this->loader->add_filter('_wp_post_revision_field_my_meta', $this, 'page_visibility_display_revisions_fields', 10, 2);;
		$this->loader->add_action('save_post', $this, 'page_redirect_save_metabox', 1, 2);
		$this->loader->add_action('save_post', $this, 'page_redirect_save_revisions');
		$this->loader->add_action('wp_restore_post_revision', $this, 'page_redirect_restore_revisions', 10, 2);
		$this->loader->add_filter('_wp_post_revision_fields', $this, 'page_redirect_get_revisions_fields');
		$this->loader->add_filter('_wp_post_revision_field_my_meta', $this, 'page_redirect_display_revisions_fields', 10, 2);
		$this->loader->add_action('save_post', $this, 'page_password_save_metabox', 1, 2);
		$this->loader->add_action('save_post', $this, 'page_password_save_revisions');
		$this->loader->add_action('wp_restore_post_revision', $this, 'page_password_restore_revisions', 10, 2);
		$this->loader->add_filter('_wp_post_revision_fields', $this, 'page_password_get_revisions_fields');
		$this->loader->add_filter('_wp_post_revision_field_my_meta', $this, 'page_password_display_revisions_fields', 10, 2);
		$this->loader->add_action('bulk_edit_custom_box', $this, 'quick_edit_custom_box_page', 10, 2 );
		$this->loader->add_action('quick_edit_custom_box', $this, 'quick_edit_custom_box_page', 10, 2 );
		$this->loader->add_action('save_post', $this, 'save_page_meta');
		$this->loader->add_action('admin_print_scripts-edit.php', $this, 'admin_edit_page_foot');
		$this->loader->add_action('wp_ajax_save_bulk_edit_page', $this, 'save_bulk_edit_page');
		$this->loader->add_filter('manage_pages_custom_column', $this, 'page_visibility_columns_values', 10, 2);
		$this->loader->add_filter('manage_pages_columns', $this, 'page_visibility_add_columns');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Page_Visibility_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_filter('template_include', $this, 'password_protect_page');
		$this->loader->add_action('template_redirect', $this, 'page_visibility_redirects');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Page_Visibility_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Create the metabox
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	function page_visibility_create_metaboxes() {
		add_meta_box(
			'page_visibility_metabox', // Metabox ID
			'Page Visibility', // Title to display
			array($this, 'page_visibility_render_metabox'), // Function to call that contains the metabox content
			'page', // Post type to display metabox on
			'side', // Where to put it (normal = main colum, side = sidebar, etc.)
			'default' // Priority relative to other metaboxes
		);
		
		add_meta_box(
			'page_redirect_metabox', // Metabox ID
			'Page Redirect', // Title to display
			array($this, 'page_redirect_render_metabox'), // Function to call that contains the metabox content
			'page', // Post type to display metabox on
			'side', // Where to put it (normal = main colum, side = sidebar, etc.)
			'default' // Priority relative to other metaboxes
		);
		
		add_meta_box(
			'page_password_metabox', // Metabox ID
			'Page Password', // Title to display
			array($this, 'page_password_render_metabox'), // Function to call that contains the metabox content
			'page', // Post type to display metabox on
			'side', // Where to put it (normal = main colum, side = sidebar, etc.)
			'default' // Priority relative to other metaboxes
		);
	}

	/**
	 * Render the metabox markup
	 * This is the function called in `page_visibility_create_metabox()`
	 */
	function page_visibility_render_metabox() {

		// Variables
		global $post; // Get the current post data
		$details = get_post_meta($post->ID, 'page_visibility', true); // Get the saved values
		?>
			<fieldset>
				<div>
					<label for="page_visibility_custom_metabox">
						<?php
							// This runs the text through a translation and echoes it (for internationalization)
							_e('Page Visibility', 'page_visibility');
						?>
					</label>
					<?php
						// The `esc_attr()` function here escapes the data for
						// HTML attribute use to avoid unexpected issues
					?>
					<select style="width: calc(100% - 32px);" name="page_visibility_custom_metabox" id="page_visibility_custom_metabox">
						<option value="public"<?php echo ($details == "public" ? " selected" : "") ?>>Public</option>
						<option value="logged-in"<?php echo ($details == "logged-in" ? " selected" : "") ?>>Logged In Users</option>
						<option value="admin"<?php echo ($details == "admin" ? " selected" : "") ?>>Admins & Editors Only</option>
						<option value="password"<?php echo ($details == "password" ? " selected" : "") ?>>Password Protected</option>
						<option value="unlisted"<?php echo ($details == "unlisted" ? " selected" : "") ?>>Unlisted</option>
					</select>
				</div>
			</fieldset>

		<?php

		// Security field
		// This validates that submission came from the
		// actual dashboard and not the front end or
		// a remote server.
		wp_nonce_field('page_visibility_form_metabox_nonce', 'page_visibility_form_metabox_process');
	}

	/**
	 * Render the metabox markup
	 * This is the function called in `page_redirect_create_metabox()`
	 */
	function page_redirect_render_metabox() {

		// Variables
		global $post; // Get the current post data
		$details = get_post_meta($post->ID, 'page_redirect', true); // Get the saved values
		?>
			<fieldset>
				<div>
					<label for="page_redirect_custom_metabox">
						<?php
							// This runs the text through a translation and echoes it (for internationalization)
							_e('Page Redirect', 'page_redirect');
						?>
					</label>
					<?php
						// The `esc_attr()` function here escapes the data for
						// HTML attribute use to avoid unexpected issues
					?>
					<input type="text" style="width: 100%;" name="page_redirect_custom_metabox" id="page_redirect_custom_metabox" value="<?php echo esc_attr($details); ?>" />
				</div>
			</fieldset>

		<?php

		// Security field
		// This validates that submission came from the
		// actual dashboard and not the front end or
		// a remote server.
		wp_nonce_field('page_redirect_form_metabox_nonce', 'page_redirect_form_metabox_process');
	}

	/**
	 * Render the metabox markup
	 * This is the function called in `page_password_render_metabox()`
	 */
	function page_password_render_metabox() {

		// Variables
		global $post; // Get the current post data
		$details = get_post_meta($post->ID, 'page_password', true); // Get the saved values
		?>
			<fieldset>
				<div>
					<label for="page_password_custom_metabox">
						<?php
							// This runs the text through a translation and echoes it (for internationalization)
							_e('Page Password', 'page_password');
						?>
					</label>
					<?php
						// The `esc_attr()` function here escapes the data for
						// HTML attribute use to avoid unexpected issues
					?>
					<input type="text" style="width: 100%;" name="page_password_custom_metabox" id="page_password_custom_metabox" value="<?php echo esc_attr($details); ?>" />
				</div>
				<style>
					div.components-panel__row.edit-post-post-visibility {
						display: none !important;
					}
					
					#page_password_metabox,
					#page_redirect_metabox {
						max-height: 0px;
						overflow: hidden;
						transition-property: all;
						transition-duration: 0.5s;
					}

					#page_visibility_metabox .postbox-header {
						border-top: 0px !important;
					}

					#page_password_metabox div.postbox-header,
					#page_redirect_metabox div.postbox-header {
						display: none !important;
					}

					#page_password_metabox div.postbox-header > div,
					#page_redirect_metabox div.postbox-header > div {
						margin-top: 0px !important;
					}

					#page_visibility_metabox .postbox-header .handle-actions button:not(.handlediv) {
						display: none !important;
					}
				</style>
				<script>
					var intervalCount = 0;
					var interval = null;

					function showPwBox() {
						var select = document.querySelector("#page_visibility_custom_metabox");
						var pwBox = document.querySelector("#page_password_metabox");
						var rdBox = document.querySelector("#page_redirect_metabox");
						if (select.options[select.selectedIndex].value == "password") {
							pwBox.style.maxHeight = "125px";
							rdBox.style.maxHeight = "0px";
						} else {
							pwBox.style.maxHeight = "0px";
							if (select.options[select.selectedIndex].value != "public") rdBox.style.maxHeight = "125px";
							else rdBox.style.maxHeight = "0px";
						}
					}

					function hideAllMetasOnCollapse() {
						var handle = document.querySelector('#page_visibility_metabox .postbox-header .handle-actions button.handlediv');
						var pwBox = document.querySelector("#page_password_metabox");
						var rdBox = document.querySelector("#page_redirect_metabox");
						if (handle.getAttribute('aria-expanded') == "true") {
							rdBox.style.maxHeight = "0px";
							pwBox.style.maxHeight = "0px";
						} else {
							showPwBox();
						}
					}

					function hideAllMetasOnLoad() {
						if (intervalCount < 10) {
							var handle = document.querySelector('#page_visibility_metabox .postbox-header .handle-actions button.handlediv');
							var pwBox = document.querySelector("#page_password_metabox");
							var rdBox = document.querySelector("#page_redirect_metabox");
							if (handle.getAttribute('aria-expanded') == "false") {
								rdBox.style.maxHeight = "0px";
								pwBox.style.maxHeight = "0px";
							} else {
								showPwBox();
							}
						} else {
							clearInterval(interval);
						}
					}

					document.querySelector("#page_visibility_custom_metabox").addEventListener("input", showPwBox);
					document.querySelector("#page_visibility_metabox .postbox-header .handle-actions button.handlediv").addEventListener("click", hideAllMetasOnCollapse);
					interval = setInterval(hideAllMetasOnLoad, 1000);
				</script>
			</fieldset>

		<?php

		// Security field
		// This validates that submission came from the
		// actual dashboard and not the front end or
		// a remote server.
		wp_nonce_field('page_password_form_metabox_nonce', 'page_password_form_metabox_process');
	}

	/**
	 * Save the metabox
	 * @param  Number $post_id The post ID
	 * @param  Array  $post    The post data
	 */
	function page_visibility_save_metabox($post_id, $post) {
		$post = (object)$post;

		// Verify that our security field exists. If not, bail.
		if (!isset($_POST['page_visibility_form_metabox_process'])) return;

		// Verify data came from edit/dashboard screen
		if (!wp_verify_nonce($_POST['page_visibility_form_metabox_process'], 'page_visibility_form_metabox_nonce')) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if (!current_user_can( 'edit_post', $post->ID)) {
			return $post->ID;
		}

		// Check that our custom fields are being passed along
		// This is the `name` value array. We can grab all
		// of the fields and their values at once.
		if (!isset($_POST['page_visibility_custom_metabox'])) {
			return $post->ID;
		}

		/**
		 * Sanitize the submitted data
		 * This keeps malicious code out of our database.
		 * `wp_filter_post_kses` strips our dangerous server values
		 * and allows through anything you can include a post.
		 */
		$sanitized = wp_filter_post_kses($_POST['page_visibility_custom_metabox']);

		// Save our submissions to the database
		update_post_meta($post->ID, 'page_visibility', $sanitized);
	}

	/**
	 * Save the metabox
	 * @param  Number $post_id The post ID
	 * @param  Array  $post    The post data
	 */
	function page_redirect_save_metabox($post_id, $post) {
		$post = (object)$post;

		// Verify that our security field exists. If not, bail.
		if (!isset($_POST['page_redirect_form_metabox_process'])) return;

		// Verify data came from edit/dashboard screen
		if (!wp_verify_nonce($_POST['page_redirect_form_metabox_process'], 'page_redirect_form_metabox_nonce')) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if (!current_user_can( 'edit_post', $post->ID)) {
			return $post->ID;
		}

		// Check that our custom fields are being passed along
		// This is the `name` value array. We can grab all
		// of the fields and their values at once.
		if (!isset($_POST['page_redirect_custom_metabox'])) {
			return $post->ID;
		}

		/**
		 * Sanitize the submitted data
		 * This keeps malicious code out of our database.
		 * `wp_filter_post_kses` strips our dangerous server values
		 * and allows through anything you can include a post.
		 */
		$sanitized = wp_filter_post_kses($_POST['page_redirect_custom_metabox']);

		// Save our submissions to the database
		update_post_meta($post->ID, 'page_redirect', $sanitized);
	}

	/**
	 * Save the metabox
	 * @param  Number $post_id The post ID
	 * @param  Array  $post    The post data
	 */
	function page_password_save_metabox($post_id, $post) {
		$post = (object)$post;

		// Verify that our security field exists. If not, bail.
		if (!isset($_POST['page_password_form_metabox_process'])) return;

		// Verify data came from edit/dashboard screen
		if (!wp_verify_nonce($_POST['page_password_form_metabox_process'], 'page_password_form_metabox_nonce')) {
			return $post->ID;
		}

		// Verify user has permission to edit post
		if (!current_user_can( 'edit_post', $post->ID)) {
			return $post->ID;
		}

		// Check that our custom fields are being passed along
		// This is the `name` value array. We can grab all
		// of the fields and their values at once.
		if (!isset($_POST['page_password_custom_metabox'])) {
			return $post->ID;
		}

		/**
		 * Sanitize the submitted data
		 * This keeps malicious code out of our database.
		 * `wp_filter_post_kses` strips our dangerous server values
		 * and allows through anything you can include a post.
		 */
		$sanitized = wp_filter_post_kses($_POST['page_password_custom_metabox']);

		// Save our submissions to the database
		update_post_meta($post->ID, 'page_password', $sanitized);
	}


	/**
	 * Save events data to revisions
	 * @param  Number $post_id The post ID
	 */
	function page_visibility_save_revisions($post_id) {

		// Check if it's a revision
		$parent_id = wp_is_post_revision($post_id);

		// If is revision
		if ($parent_id) {

			// Get the saved data
			$parent = get_post($parent_id);
			$details = get_post_meta($parent->ID, 'page_visibility', true);

			// If data exists and is an array, add to revision
			if (!empty($details)) {
				add_metadata('post', $post_id, 'page_visibility', $details);
			}
		}
	}


	/**
	 * Save events data to revisions
	 * @param  Number $post_id The post ID
	 */
	function page_redirect_save_revisions($post_id) {

		// Check if it's a revision
		$parent_id = wp_is_post_revision($post_id);

		// If is revision
		if ($parent_id) {

			// Get the saved data
			$parent = get_post($parent_id);
			$details = get_post_meta($parent->ID, 'page_redirect', true);

			// If data exists and is an array, add to revision
			if (!empty($details)) {
				add_metadata('post', $post_id, 'page_redirect', $details);
			}
		}
	}


	/**
	 * Save events data to revisions
	 * @param  Number $post_id The post ID
	 */
	function page_password_save_revisions($post_id) {

		// Check if it's a revision
		$parent_id = wp_is_post_revision($post_id);

		// If is revision
		if ($parent_id) {

			// Get the saved data
			$parent = get_post($parent_id);
			$details = get_post_meta($parent->ID, 'page_password', true);

			// If data exists and is an array, add to revision
			if (!empty($details)) {
				add_metadata('post', $post_id, 'page_password', $details);
			}
		}
	}

	/**
	 * Restore events data with post revisions
	 * @param  Number $post_id     The post ID
	 * @param  Number $revision_id The revision ID
	 */
	function page_visibility_restore_revisions($post_id, $revision_id) {

		// Variables
		$post = get_post($post_id); // The post
		$revision = get_post($revision_id); // The revision
		$details = get_metadata('post', $revision->ID, 'page_visibility', true); // The historic version

		// Replace our saved data with the old version
		update_post_meta($post_id, 'page_visibility', $details);
	}

	/**
	 * Restore events data with post revisions
	 * @param  Number $post_id     The post ID
	 * @param  Number $revision_id The revision ID
	 */
	function page_redirect_restore_revisions($post_id, $revision_id) {

		// Variables
		$post = get_post($post_id); // The post
		$revision = get_post($revision_id); // The revision
		$details = get_metadata('post', $revision->ID, 'page_redirect', true); // The historic version

		// Replace our saved data with the old version
		update_post_meta($post_id, 'page_redirect', $details);
	}

	/**
	 * Restore events data with post revisions
	 * @param  Number $post_id     The post ID
	 * @param  Number $revision_id The revision ID
	 */
	function page_password_restore_revisions($post_id, $revision_id) {

		// Variables
		$post = get_post($post_id); // The post
		$revision = get_post($revision_id); // The revision
		$details = get_metadata('post', $revision->ID, 'page_password', true); // The historic version

		// Replace our saved data with the old version
		update_post_meta($post_id, 'page_password', $details);
	}

	/**
	 * Get the data to display on the revisions page
	 * @param  Array $fields The fields
	 * @return Array The fields
	 */
	function page_visibility_get_revisions_fields($fields) {
		// Set a title
		$fields['page_visibility'] = 'Page Visibility';
		return $fields;
	}

	/**
	 * Get the data to display on the revisions page
	 * @param  Array $fields The fields
	 * @return Array The fields
	 */
	function page_redirect_get_revisions_fields($fields) {
		// Set a title
		$fields['page_redirect'] = 'Page Redirect';
		return $fields;
	}

	/**
	 * Get the data to display on the revisions page
	 * @param  Array $fields The fields
	 * @return Array The fields
	 */
	function page_password_get_revisions_fields($fields) {
		// Set a title
		$fields['page_password'] = 'Page Password';
		return $fields;
	}

	/**
	 * Display the data on the revisions page
	 * @param  String|Array $value The field value
	 * @param  Array        $field The field
	 */
	function page_visibility_display_revisions_fields($value, $field) {
		global $revision;
		return get_metadata('post', $revision->ID, $field, true);
	}

	/**
	 * Display the data on the revisions page
	 * @param  String|Array $value The field value
	 * @param  Array        $field The field
	 */
	function page_redirect_display_revisions_fields($value, $field) {
		global $revision;
		return get_metadata('post', $revision->ID, $field, true);
	}

	/**
	 * Display the data on the revisions page
	 * @param  String|Array $value The field value
	 * @param  Array        $field The field
	 */
	function page_password_display_revisions_fields($value, $field) {
		global $revision;
		return get_metadata('post', $revision->ID, $field, true);
	}

	/**
	 * Password protect pages which have been specified as password protected by swapping served template
	 * @param  mixed		$template The template that is to be shown on the page
	 * @return mixed		The template to be shown on the page
	 */
	function password_protect_page($template) {
		global $post;
		if ($post === null) return $template;
		$page_visibility = get_post_meta($post->ID, 'page_visibility', true);
		$page_password = get_post_meta($post->ID, 'page_password', true);

		if ($page_visibility != false && $page_visibility == "password" && $page_password != false) {
			if (!$_POST['pv_page_password']) {
				$template = plugin_dir_path( __FILE__ ) . '../public/partials/template-password-protect.php';	
			} else if ($_POST['pv_page_password'] != $page_password) {
				$template = plugin_dir_path( __FILE__ ) . '../public/partials/template-password-protect.php';	
			}
		}
	
		return $template;
	}

	/**
	 * Redirects users according to the redirect behavior saved on the page if they don't have access
	 * @return void
	 */
	function page_visibility_redirects() {
		global $post;
		if ($post !== null) {
			$page_visibility = get_post_meta($post->ID, 'page_visibility', true);
			$page_redirect = get_post_meta($post->ID, 'page_redirect', true);
			if ($page_redirect == false || $page_redirect == "") {
				$page_redirect = wp_get_referer();
				if ($page_redirect == false) {
					$page_redirect = home_url();
				}
			}

			if ($page_visibility != false) {
				switch($page_visibility) {
					case "logged-in": 
					if (!is_user_logged_in()) { 
						if (str_starts_with(strtolower($page_redirect), "http")) {
							wp_redirect($page_redirect);
							die;
						} else {
							wp_redirect(home_url($page_redirect));
							die;
						}
					}
					break; 

					case "admin": 
					if (!is_user_logged_in() || (!current_user_can('administrator') && !current_user_can('editor'))) { 
						if (str_starts_with(strtolower($page_redirect), "http")) {
							wp_redirect($page_redirect);
							die;
						} else {
							wp_redirect(home_url($page_redirect));
							die;
						}
					}
					break; 

					case "unlisted": 
					if (str_starts_with(strtolower($page_redirect), "http")) {
						wp_redirect($page_redirect);
						die;
					} else {
						wp_redirect(home_url($page_redirect));
						die;
					}
				}
			}
		}
	}

	/**
	 * Adds Page Visibility column to quick edit / bulk edit
	 * @return void
	 */
	function page_visibility_add_columns($columns) {

		/** Add a Column **/
		$myCustomColumns = array(
			'page_visibility' => __('Page Visibility'),
			'page_redirect' => __('Page Redirect'),
			'page_password' => __('Page Password')
		);
		$columns = array_merge($columns, $myCustomColumns);
	
		return $columns;
	}

	/**
	 * Adds Quick Edit Custom Meta Boxes to the quick edit box for pages
	 * @return void
	 */
	function quick_edit_custom_box_page($column_name, $post_type) {
		$slug = 'page';
		if ($slug !== $post_type)
			return;

		if (!in_array($column_name, array('page_visibility', 'page_redirect', 'page_password')))
			return;

		static $printNonce = true;
		if ($printNonce) {
			$printNonce = false;
			wp_nonce_field(plugin_basename( __FILE__ ), 'page_edit_nonce');
		}

	?>
		<style>
			.inline-edit-col .inline-edit-group.wp-clearfix,
			th#page_visibility, th#page_redirect, th#page_password,
			th.column-page_visibility, th.column-page_redirect, th.column-page_password,
			td.page_visibility, td.page_redirect, td.page_password {
				display: none !important;
			}
		</style>
		<fieldset class="inline-edit-col-right inline-edit-page" style="margin: 0px;">
		<div class="inline-edit-col inline-edit-<?php echo $column_name ?>">
			<label class="inline-edit-group" style="margin-top: 1rem;">
			<?php
		switch ( $column_name ) {
		case 'page_visibility':
	?>
				<span class="title" style="line-height: 1.2rem; display: block;">Page Visibility</span>
				<select style="width: fit-content;" name="page_visibility" id="page_visibility">
					<option value="dnc" selected>Do Not Change</option>
					<option value="public">Public</option>
					<option value="logged-in">Logged In Users</option>
					<option value="admin">Admins & Editors Only</option>
					<option value="password">Password Protected</option>
					<option value="unlisted">Unlisted</option>
				</select>
				<?php
			break;
		case 'page_redirect':
	?>
				<span class="title" style="line-height: 1.2rem; display: block;">Page Redirect</span>
				<input type="text" style="width: fit-content;" name="page_redirect" id="page_redirect" value="" />
				<?php
			break;
		case 'page_password':
	?>
				<span class="title" style="line-height: 1.2rem; display: block;">Page Password</span>
				<input type="text" style="width: fit-content;" name="page_password" id="page_password" value="" />
				<?php
			break;
		}
	?>
			</label>
		</div>
		</fieldset>
		<?php
	}

	/**
	 * 
	 * @return void
	 */
	function page_visibility_columns_values($column, $post_id) {
		switch ($column) {
		  case 'page_visibility':
			echo get_post_meta($post_id, 'page_visibility', true);
			break;
	
		  case 'page_redirect':
			echo get_post_meta($post_id, 'page_redirect', true);
			break;
		
		  case 'page_password':
			echo get_post_meta($post_id, 'page_password', true);
			break;
		}
	}

	/**
	 * Adds Quick Edit Custom Meta Boxes to the quick edit box for pages
	 * @return void
	 */
	function save_page_meta( $post_id ) {
		// TODO make $slug static
		$slug = 'page';
		global $post;
		if ($slug !== (array_key_exists('post_type', $_POST) ? $_POST['post_type'] : ''))
			return;

		if (!current_user_can('edit_post', $post_id))
			return;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (isset($post->post_type) && 'revision' == $post->post_type)
			return;

		$_POST += array("{$slug}_edit_nonce" => '');
		if (!wp_verify_nonce($_POST["{$slug}_edit_nonce"], plugin_basename( __FILE__ )))
			return;

		if (isset($_REQUEST['page_visibility']) && $_REQUEST['page_visibility'] != 'dnc')
			update_post_meta($post_id, 'page_visibility', wp_kses_post($_REQUEST['page_visibility']));

		if (isset($_REQUEST['page_redirect']))
			update_post_meta($post_id, 'page_redirect', wp_kses_post($_REQUEST['page_redirect']));

		if (isset($_REQUEST['page_password']))
			update_post_meta($post_id, 'page_password', wp_kses_post($_REQUEST['page_password']));
	}

	/**
	 * Adds javascript to the footer of the admin page
	 * @return void
	 */
	function admin_edit_page_foot() {
		$slug = 'page';
		// load only when editing a page
		if ((isset($_GET['page']) && $slug == $_GET['page']) || (isset($_GET['post_type']) && $slug == $_GET['post_type'])) {
			wp_enqueue_script('admin-quick-edit-page-visibility', plugin_dir_url( __FILE__ ) . '../admin/js/page-visibility-admin.js', array('jquery', 'inline-edit-post'), '', true);
		}
	}

	/**
	 * Ensures bulk actions are saved by Admin AJAX call
	 * @return void
	 */
	function save_bulk_edit_page() {
		$post_ids          = (!empty($_POST['post_ids'])) ? $_POST['post_ids'] : array();
		$page_visibility   = (!empty($_POST['page_visibility'])) ? wp_kses_post($_POST['page_visibility']) : 'dnc';
		$page_redirect     = (!empty($_POST['page_redirect'])) ? wp_kses_post($_POST['page_redirect']) : '';
		$page_password     = (!empty($_POST['page_password'])) ? wp_kses_post($_POST['page_password']) : '';

		if (!empty($post_ids) && is_array($post_ids)) {
			foreach ($post_ids as $post_id) {
				if ($page_visibility != 'dnc') update_post_meta($post_id, 'page_visibility', $page_visibility);
				if ($page_redirect != '') update_post_meta($post_id, 'page_redirect', $page_redirect);
				if ($page_password != '') update_post_meta($post_id, 'page_password', $page_password);
			}
		}

		die();
	}
}
