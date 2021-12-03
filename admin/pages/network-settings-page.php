<?php

class Multisite_Content_Copier_Network_Settings_Menu extends Multisite_Content_Copier_Admin_Page {

	private $settings;

	public function __construct( $menu_slug, $capability, $args ) {
 		parent::__construct( $menu_slug, $capability, $args );

 		$this->settings = mcc_get_settings();

 		add_action( 'admin_init', array( &$this, 'sanitize_settings' ) );
 	}

 	public function render_content() {

 		if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' )
 			Multisite_Content_Copier_Errors_Handler::show_updated_notice( __( 'Einstellungen gespeichert', MULTISTE_CC_LANG_DOMAIN ) );

 		mcc_show_errors();

 		?>
			<form action="" method="post">
				<h3><?php _e( 'Neue Blog-Vorlagen-Integration', MULTISTE_CC_LANG_DOMAIN ); ?></h3>
				<p>
					<?php _e('Um diese Option zu nutzen benötigst du das <a href="https://n3rds.work/piestingtal_source/neue-blog-vorlagen/" target="_blank" rel="noopener">Neue Blog Vorlagen Plugin</a>.'); ?>
				</p>
				<p>
					<?php _e('Wenn Du diese Option aktivierst, kannst Du eine neue Blog-Vorlagengruppe als Ziel für kopierte Inhalte auswählen.'); ?>
				</p>
				<table class="form-table">
					<?php $this->render_row( __( 'Aktivieren', MULTISTE_CC_LANG_DOMAIN ), array( &$this, 'render_nbt_integration_field' ) ); ?>
				</table>

				<?php wp_nonce_field( 'submit_mcc_settings', 'mcc_settings_nonce' ); ?>

				<?php submit_button( __( 'Änderungen speichern', MULTISTE_CC_LANG_DOMAIN ), 'primary', 'submit_mcc_settings' ); ?>
			</form>
 		<?php
 		
 	}

 	public function render_nbt_integration_field() {
 		?>
			<input type="checkbox" name="<?php echo mcc_get_settings_slug(); ?>[blog_templates_integration]" <?php checked( $this->settings['blog_templates_integration'] ); ?>>
 		<?php
 	}

 	

 	public function sanitize_settings() {

 		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->get_menu_slug() && isset( $_POST['submit_mcc_settings'] ) ) {

 			if ( ! check_admin_referer( 'submit_mcc_settings', 'mcc_settings_nonce' ) )
 				return;

 			$input = isset( $_POST[mcc_get_settings_slug()] ) ? $_POST[mcc_get_settings_slug()] : array();
 			$current_settings = mcc_get_settings();

 			if ( isset( $input['blog_templates_integration'] ) ) {
 				if ( ! mcc_is_nbt_active() ) {
 					mcc_add_error( 'nbt-not-active', __( 'Du musst zuerst das Neue Blog-Vorlagen Plugin im Netzwerk aktivieren', MULTISTE_CC_LANG_DOMAIN ) );
 				}
 				else {
 					$current_settings['blog_templates_integration'] = true;
 					$nbt_model = mcc_get_nbt_model();
 					$nbt_model->create_nbt_relationships_table();
 				}
 				
 			}
 			else {
 				$current_settings['blog_templates_integration'] = false;
 			}


 			if ( ! mcc_is_error() ) {

 				mcc_update_settings( $current_settings );

	 			$redirect_to = add_query_arg( 'updated', 'true', $this->get_permalink() );

	 			/**
				 * Filters the redirection URL when the settings are saved
				 * 
				 * @param String $redirect_to
				 */
	 			$redirect_to = apply_filters( 'mcc_update_settings_screen_redirect_url', $redirect_to );

	 			if ( ! empty( $redirect_to ) )
	 				wp_safe_redirect( $redirect_to );	
 			}
 			
 			
 		}

 	}
}