<?php
/**
 * The file store Database Default Entry
 *
 * @link       https://posimyth.com/
 * @since      6.0.0
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpae_Dashboard_Meta' ) ) {

	/**
	 * Tpae_Dashboard_Meta
	 *
	 * @since 6.0.0
	 */
	class Tpae_Dashboard_Meta {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Option key, and option page slug
		 *
		 * @var string
		 */
		private $key = 'theplus_options';

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since 6.0.0
		 */
		public function __construct() {
			if ( current_user_can( 'manage_options' ) ) {
				add_action( 'admin_menu', array( $this, 'tpae_add_dashboard_menu' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'tpae_enqueue_scripts' ) );
				add_action( 'admin_menu', array( $this, 'tpae_theme_builder_redirect' ), 1 );
			}

			if ( defined( 'THEPLUS_VERSION' ) ) {
				$theplus_options = get_option( 'theplus_options' );
				$get_widget = ! empty( $theplus_options['check_elements'] ) ? $theplus_options['check_elements'] : [];

				if ( ! empty( $get_widget ) ) {
					if ( in_array( 'tp_plus_form', $get_widget ) ) {
						add_action( 'admin_init', function () {
							$export_path = THEPLUS_PATH . 'modules/extensions/form-submissions/class-tpaep-export-submissions.php';
							if ( file_exists( $export_path ) ) {
								include_once $export_path;
							}
							
							$edit_path = THEPLUS_PATH . 'modules/extensions/form-submissions/view-submission/class-tpaep-edit-submission.php';
							if ( file_exists( $edit_path ) ) {
								include_once $edit_path;
							}
						} );
					}
				}
			}

			if ( is_admin() ) {
				add_filter(
					'admin_body_class',
					function ( $classes ) {
						if ( isset( $_GET['page'] ) && $_GET['page'] === 'nxt_builder' ) {
							$classes .= ' post-type-nxt_builder nxt-page-nexter-builder ';
						}
						return $classes;
					},
					11
				);
			}
		}

		/**
		 * Dashboard Build File loaded.
		 *
		 * @since 6.0.0
		 * @version 6.4.2
		 *
		 * @param string $page use for check page type.
		 */
		public function tpae_enqueue_scripts( $page ) {

			if ( 'plugins.php' === $page ) {
				wp_enqueue_style( 'tpae-deactivation-design-system', L_THEPLUS_URL . 'assets/css/admin/design-system.generated.css', array(), L_THEPLUS_VERSION . '.' . filemtime( L_THEPLUS_PATH . 'assets/css/admin/design-system.generated.css' ) );
				wp_enqueue_style( 'tpae-deactivation-design', L_THEPLUS_URL . 'assets/css/admin/tpae-deactivation-design.css', array( 'tpae-deactivation-design-system' ), L_THEPLUS_VERSION . '.' . filemtime( L_THEPLUS_PATH . 'assets/css/admin/tpae-deactivation-design.css' ) );
				wp_enqueue_script( 'tpae-deactivation-design', L_THEPLUS_URL . 'assets/js/admin/tpae-deactivation-design.js', array( 'jquery', 'wp-i18n' ), L_THEPLUS_VERSION . '.' . filemtime( L_THEPLUS_PATH . 'assets/js/admin/tpae-deactivation-design.js' ), true );
			}

			wp_enqueue_style( 'tpae-db-icons-library', L_THEPLUS_ASSETS_URL . 'fonts/style.css', array(), L_THEPLUS_VERSION, false );

			$et_plugin_status    = apply_filters( 'tpae_get_plugin_status', 'template-kit-import/template-kit-import.php' );
			$wdkit_plugin_status = apply_filters( 'tpae_get_plugin_status', 'wdesignkit/wdesignkit.php' );

			$onbording_get = get_option( 'tpae_onbording_end' );

			$onbording_set = '';

			if ( $onbording_get || 'active' === $et_plugin_status || 'inactive' === $et_plugin_status ) {
				$onbording_set = 'hide';
			}

			/* active / inactive / not_installed: the Theme Builder tab offers to install or activate it. */
			$nexter_et_plugin_status = apply_filters( 'tpae_get_plugin_status', 'nexter-extension/nexter-extension.php' );

			$get_whats_new_notification = get_option( 'tpae_whats_new_notification' );

			$show_whats_new = '';

			if ( $get_whats_new_notification !== TPAE_WHATS_NEW_NOTIFICETIONS ) {
				$show_whats_new = true;
			} else {
				$show_whats_new = false;
			}

			/*
			 * With Nexter Extension active, the Theme Builder screen is Nexter's, and its back
			 * link and close button always lead to the Nexter dashboard - even for someone who
			 * opened it from The Plus Addons. Nexter enqueues it at priority 1, so by now its
			 * config exists and can be pointed back here for visits that started in TPAE.
			 *
			 * The origin is remembered per tab (sessionStorage), so moving between templates
			 * and back to the list keeps it; arriving from the Nexter dashboard clears it.
			 */
			if ( isset( $_GET['page'] ) && 'nxt_builder' === $_GET['page'] && defined( 'NEXTER_EXT' ) && wp_script_is( 'nexter-theme-builder', 'enqueued' ) ) {
				$tpae_dashboard = wp_json_encode( admin_url( 'admin.php?page=theplus_welcome_page' ) );

				wp_add_inline_script(
					'nexter-theme-builder',
					"(function(){try{var k='tpae_tb_return',p=new URLSearchParams(window.location.search),r=document.referrer||'';" .
					"if(p.get('tpae_from')||r.indexOf('page=theplus_welcome_page')>-1){sessionStorage.setItem(k,'1');}" .
					"else if(r.indexOf('page=nexter_welcome')>-1){sessionStorage.removeItem(k);}" .
					"if('1'===sessionStorage.getItem(k)&&window.nexter_theme_builder_config){window.nexter_theme_builder_config.dashboard_url={$tpae_dashboard};}" .
					'}catch(e){}})();',
					'before'
				);
			}

			if ( 'toplevel_page_theplus_welcome_page' === $page ) {

				$tpae_db_asset_file = L_THEPLUS_PATH . 'build/index.asset.php';
				$tpae_db_asset      = file_exists( $tpae_db_asset_file ) ? include $tpae_db_asset_file : array();
				$tpae_db_deps       = ( is_array( $tpae_db_asset ) && ! empty( $tpae_db_asset['dependencies'] ) )
					? $tpae_db_asset['dependencies']
					: array( 'react', 'react-dom', 'react-jsx-runtime', 'wp-i18n', 'wp-element', 'wp-components' );

				wp_enqueue_script( 'tpae-db-build', L_THEPLUS_URL . 'build/index.js', $tpae_db_deps, L_THEPLUS_VERSION . time(), true );
				wp_localize_script(
					'tpae-db-build',
					'tpae_db_object',
					array(
						'ajax_url'             => admin_url( 'admin-ajax.php' ),
						'nonce'                => wp_create_nonce( 'tpae-db-nonce' ),
						'tpae_nonce_old'       => wp_create_nonce( 'theplus-addons' ),
						'tpae_url'             => L_THEPLUS_URL,
						'tpae_version'         => defined( 'THEPLUS_VERSION' ) ? THEPLUS_VERSION : L_THEPLUS_VERSION,
						'tpae_wdkit_url'       => L_THEPLUS_WDKIT_URL,
						'tpae_wp_version'      => get_bloginfo( 'version' ),
						'tpae_pro'             => defined( 'THEPLUS_VERSION' ) ? 1 : 0,
						'tpae_whitelabel'      => get_option( 'theplus_white_label' ),
						'onboarding_setup'     => $onbording_set,
						'envato_plugin_status' => $et_plugin_status,
						'wdkit_plugin_status'  => $wdkit_plugin_status,
						'show_whats_new'       => $show_whats_new,
						'nexter_ext_status'    => $nexter_et_plugin_status,
					)
				);

				wp_set_script_translations( 'tpae-db-build', 'tpebl' );

				wp_enqueue_style( 'tpae-db-build', L_THEPLUS_URL . 'build/index.css', array(), L_THEPLUS_VERSION . time(), 'all' );

			}
		}

		/**
		 * Dashboard Build File loaded.
		 *
		 * @since 6.1.0
		 */
		public function tpae_add_dashboard_menu() {

			$setting_name = esc_html__( 'The Plus Addons', 'tpebl' );

			$plugin_ads   = '';
			$template_tab = '';
			if ( defined( 'THEPLUS_VERSION' ) ) {
				$options = get_option( 'theplus_white_label' );

				$setting_name = ! empty( $options['tp_plugin_name'] ) ? $options['tp_plugin_name'] : __( 'The Plus Addons', 'tpebl' );

				$plugin_ads   = isset( $options ) && ! empty( $options['plugin_ads'] ) ? $options['plugin_ads'] : '';
				$template_tab = isset( $options ) && ! empty( $options['template_tab'] ) ? $options['template_tab'] : '';
			}

			$et_plugin_status    = apply_filters( 'tpae_get_plugin_status', 'template-kit-import/template-kit-import.php' );
			$wdkit_plugin_status = apply_filters( 'tpae_get_plugin_status', 'wdesignkit/wdesignkit.php' );

			add_menu_page( $setting_name, $setting_name, 'manage_options', 'theplus_welcome_page', array( $this, 'tpae_admin_page_display' ), 'dashicons-plus-settings', 67.1 );

			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Widgets', 'tpebl' ), esc_html__( 'Widgets', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/widgets', array( $this, 'tpae_admin_page_display' ), );
			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Extensions', 'tpebl' ), esc_html__( 'Extensions', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/extension', array( $this, 'tpae_admin_page_display' ) );

			if ( empty( $options ) || 'on' !== $plugin_ads ) {
				/* Nexter Extension's own builder when it is active, otherwise the dashboard tab that offers it. */
				if ( defined( 'NEXTER_EXT' ) ) {
					add_submenu_page( 'theplus_welcome_page', esc_html__( 'Theme Builder', 'tpebl' ), esc_html__( 'Theme Builder', 'tpebl' ), 'manage_options', 'nxt_builder', array( $this, 'nexter_theme_builder_display' ) );
				} else {
					add_submenu_page( 'theplus_welcome_page', esc_html__( 'Theme Builder', 'tpebl' ), esc_html__( 'Theme Builder', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/theme_builder', array( $this, 'tpae_admin_page_display' ) );
				}
			}

			if ( ( 'not_installed' === $et_plugin_status || 'active' === $wdkit_plugin_status ) && ( empty( $options ) || 'on' !== $template_tab ) ) {
				add_submenu_page( 'theplus_welcome_page', esc_html__( 'Starter Templates', 'tpebl' ), esc_html__( 'Starter Templates', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/elementor_templates', array( $this, 'tpae_admin_page_display' ) );
			}

			if ( defined( 'THEPLUS_VERSION' ) ) {

				$theplus_options = get_option( 'theplus_options' );
				$get_widget = ! empty( $theplus_options['check_elements'] ) ? $theplus_options['check_elements'] : [];

				if ( ! empty( $get_widget ) ) {
					if ( in_array( 'tp_plus_form', $get_widget ) ) {
						global $wpdb;
						$table_name      = $wpdb->prefix . 'tpaep_formsmeta';
						$has_submissions = false;

						if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {
							$submission_count = (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$table_name}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table_name is built from $wpdb->prefix; avoids the %i placeholder (WP 6.2+).
							if ( $submission_count > 0 ) {
								$has_submissions = true;
							}
						}

						if ( $has_submissions ) {
							$submissions_title_menu = esc_html__( 'Submissions', 'tpebl' );
							if ( empty( $options ) ) {
								$submissions_title_menu .= ' <span style="background-color: #058645; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 5px; vertical-align: middle; line-height: 1; font-weight: 500;">' . esc_html__( 'NEW', 'tpebl' ) . '</span>';
							}

							$submissions_hook = add_submenu_page( 
								'theplus_welcome_page', 
								esc_html__( 'Submissions', 'tpebl' ), 
								$submissions_title_menu, 
								'manage_options', 
								'tpae-form-submissions', 
								array( $this, 'tpae_render_submissions_page' ) 
							);

							/* -------------------------------------------------
							* HANDLE ROW ACTIONS (Mark as read & Trash)
							* ------------------------------------------------- */
							add_action( "load-$submissions_hook", function () {

								global $wpdb;
								$table = $wpdb->prefix . 'tpaep_formsmeta';

								// ✅ MARK AS READ
								if ( isset( $_GET['mark_read'], $_GET['_wpnonce'] ) ) {

									check_admin_referer( 'tpae_mark_read' );

									$wpdb->update(
										$table,
										[ 'is_read' => 1 ],
										[ 'id' => absint( $_GET['mark_read'] ) ],
										[ '%d' ],
										[ '%d' ]
									);

									wp_safe_redirect(
										admin_url( 'admin.php?page=tpae-form-submissions' )
									);
									exit;
								}

								// ✅ MARK AS UNREAD
								if ( isset( $_GET['mark_unread'], $_GET['_wpnonce'] ) ) {

									check_admin_referer( 'tpae_mark_unread' );

									$wpdb->update(
										$table,
										[ 'is_read' => 0 ],
										[ 'id' => absint( $_GET['mark_unread'] ) ],
										[ '%d' ],
										[ '%d' ]
									);

									wp_safe_redirect(
										admin_url( 'admin.php?page=tpae-form-submissions' )
									);
									exit;
								}

								// ✅ TRASH (DELETE)
								if ( isset( $_GET['delete'], $_GET['_wpnonce'] ) ) {

									check_admin_referer( 'tpae_delete_submission' );

									$wpdb->delete(
										$table,
										[ 'id' => absint( $_GET['delete'] ) ],
										[ '%d' ]
									);

									wp_safe_redirect(
										admin_url( 'admin.php?page=tpae-form-submissions' )
									);
									exit;
								}
							});
						}
					}
				}
			}

			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Settings', 'tpebl' ), esc_html__( 'Settings', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/settings', array( $this, 'tpae_admin_page_display' ) );

			if ( ! defined( 'THEPLUS_VERSION' ) ) {
				add_submenu_page( 'theplus_welcome_page', esc_html__( 'Upgrade Now', 'tpebl' ), esc_html__( 'Upgrade Now', 'tpebl' ) . '<i class="theplus-i-crown path1 path2"></i>', 'manage_options', esc_url( 'https://theplusaddons.com/pricing?utm_source=wpbackend&utm_medium=dashboard&utm_campaign=plussettings' ) );
			}

			if ( defined( 'THEPLUS_VERSION' ) ) {

				$license_data = get_option( 'tpaep_licence_data' );

				if ( empty( $license_data ) && empty( $license_data['license_key'] ) ) {
					add_submenu_page( 'theplus_welcome_page', esc_html__( 'Activate', 'tpebl' ), esc_html__( 'Activate', 'tpebl' ) . '<i class="theplus-i-info-fill activate"></i>', 'manage_options', 'theplus_welcome_page#/activate_pro', array( $this, 'tpae_admin_page_display' ) );
				}
			}

			add_action( 'admin_footer', array( $this, 'tpae_link_in_new_tab' ) );

			// Hook to modify the submenu head title.
			add_action( 'admin_menu', array( $this, 'tpae_submenu_head_title' ), 101 );

			global $pagenow;

			if ( $pagenow === 'admin.php' && isset( $_GET['page'] ) && 'theplus_welcome_page' === $_GET['page'] ) {
				add_action( 'admin_footer', array( $this, 'tpae_highlight_submenu_js' ) );
			}
		}

		public function tpae_highlight_submenu_js() {
			?>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function() {
					function setActiveSubmenu() {
						var hash = window.location.hash;
						var menuLinks = document.querySelectorAll('#toplevel_page_theplus_welcome_page .wp-submenu a');

						menuLinks.forEach(function(link) {
							link.parentElement.classList.remove('current');

							if ( !hash ) {
								if (link.getAttribute('href') === 'admin.php?page=theplus_welcome_page' || link.getAttribute('href') === 'admin.php?page=theplus_welcome_page#/'){
									link.parentElement.classList.add('current');
								}
							} else if ( '#/' === hash ) {
								if (link.getAttribute('href') === 'admin.php?page=theplus_welcome_page' || link.getAttribute('href') === 'admin.php?page=theplus_welcome_page#/'){
									link.parentElement.classList.add('current');
								}
							} else if ('#/upgrade_now' === hash) {
								if (link.getAttribute('href') === 'https://theplusaddons.com/pricing?utm_source=wpbackend&utm_medium=dashboard&utm_campaign=plussettings'){
									link.parentElement.classList.add('current');
								}
							}else {
								let getLink = link.getAttribute('href').includes(hash);
								if (hash.includes('?')) {
									hash = hash.split('?')[0];
								}

								if (hash && link.getAttribute('href').includes(hash)) {
									link.parentElement.classList.add('current');
								}
							}
						});
					}

					setActiveSubmenu();
					window.addEventListener('hashchange', setActiveSubmenu);
				});
			</script>
			<?php
		}

		/**
		 * Parent Page Rename in Sub menu.
		 *
		 * @since 6.0.0
		 */
		public function tpae_submenu_head_title() {
			global $submenu;

			if ( isset( $submenu['theplus_welcome_page'] ) ) {
				$submenu['theplus_welcome_page'][0][0] = esc_html__( 'Dashboard', 'tpebl' );
				$submenu['theplus_welcome_page'][0][2] = 'admin.php?page=theplus_welcome_page#/';
			}
		}

		/**
		 * Open Link in New Tab WordPress Menu
		 *
		 * @since 6.0.0
		 */
		public function tpae_link_in_new_tab() {
			?>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function() {
					var upgradeLink = document.querySelector('a[href*="https://theplusaddons.com/pricing"]');
					if ( upgradeLink ) {
						upgradeLink.setAttribute('target', '_blank');
						upgradeLink.setAttribute('rel', 'noopener noreferrer');
					}
				});
			</script>
			<?php
		}

		/**
		 * Without Nexter Extension there is no nxt_builder screen: the Theme Builder is a
		 * dashboard tab. Old links and bookmarks to the screen land on that tab. Runs on
		 * admin_menu: WordPress refuses an unregistered page right after that hook, before
		 * admin_init.
		 *
		 * @since 6.6.0
		 */
		public function tpae_theme_builder_redirect() {
			if ( defined( 'NEXTER_EXT' ) || wp_doing_ajax() || ! isset( $_GET['page'] ) || 'nxt_builder' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			wp_safe_redirect( admin_url( 'admin.php?page=theplus_welcome_page#/theme_builder' ) );
			exit;
		}

		/**
		 * Theme Builder Render html
		 *
		 * @since 6.4.2
		 */
		public function nexter_theme_builder_display() {
			echo '<div id="nexter-theme-builder"></div>';
		}

		/**
		 * Add Dashboard HTML with js
		 *
		 * @since 6.0
		 */
		public function tpae_admin_page_display() {
			echo '<div id="theplus-app"></div>';
		}

		public function tpae_render_submissions_page() {

			if ( isset( $_GET['view'] ) ) {
				$view_path = THEPLUS_PATH . 'modules/extensions/form-submissions/view-submission/class-tpaep-view-submission.php';
				if ( file_exists( $view_path ) ) {
					include $view_path;
				}
				return;
			}

			$form_path = THEPLUS_PATH . 'modules/extensions/form-submissions/class-tpaep-form-submissions.php';
			if ( file_exists( $form_path ) ) {
				include $form_path;
			}

			if ( class_exists( 'Tpaep_Submission_List_Table' ) ) {
				$table = new Tpaep_Submission_List_Table();
				$table->prepare_items();

				echo '<div class="wrap" id="tpae-submissions-page">';
					echo '<h1 class="wp-heading-inline">';
						esc_html_e( 'Form Submissions', 'tpebl' );
					echo '</h1>';
					echo '<hr class="wp-header-end">';

					$table->views();

					echo '<form method="get">';
						echo '<input type="hidden" name="page" value="tpae-form-submissions" />';
						wp_nonce_field( 'bulk-submissions' );

						echo '<div class="tpae-table-card">';
							$table->display();
						echo '</div>';

					echo '</form>';
				echo '</div>';
			}
		}
	}

	Tpae_Dashboard_Meta::get_instance();
}