<?php

/**
 * Wir brauchen diese Klasse, wenn wir Einstellungen effizient handhaben wollen
 * Einstellungen werden nur bei Bedarf geladen
 *
 * Du kannst verschiedene Klassen für verschiedene Einstellungsgruppen erstellen
 * wenn dein Plugin zu groß ist 
 */

class Multisite_Content_Copier_Settings_Handler {

	static $instance;

	// Einstellungsslug für DB
	private $settings_slug = 'multisite_content_copier_settings';

	// Einstellungen für das Plugin
	private $settings = array();

	private $additional_settings = array();

	public function __construct() {
		$this->additional_settings = array(
			'post' => array(
				'copy_images'	=> __( 'Bilder kopieren (erforderlich für vorgestellte Bilder)', MULTISTE_CC_LANG_DOMAIN ),
				'update_date'	=> __( 'Erstellungsdatum des Beitrags aktualisieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_parents'	=> __( 'Eltern kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_comments' => __( 'Kommentare kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_terms' 	=> __( 'Bedingungen kopieren ( Tags & Kategorien )', MULTISTE_CC_LANG_DOMAIN )
			),
			'page' => array(
				'copy_images'	=> __( 'Bilder kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'update_date'	=> __( 'Erstellungsdatum der Seite aktualisieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_parents'	=> __( 'Eltern kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_comments' => __( 'Kommentare kopieren', MULTISTE_CC_LANG_DOMAIN )
			),
			'cpt' => array(
				'copy_images'	=> __( 'Bilder kopieren (erforderlich für vorgestellte Bilder)', MULTISTE_CC_LANG_DOMAIN ),
				'update_date'	=> __( 'Erstellungsdatum des Beitrags aktualisieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_parents'	=> __( 'Eltern kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_comments' => __( 'Kommentare kopieren', MULTISTE_CC_LANG_DOMAIN ),
				'copy_terms' 	=> __( 'Bedingungen kopieren ( Tags & Kategorien )', MULTISTE_CC_LANG_DOMAIN )
			),
			'user' => array(
				'default_role'	=> __( 'Wenn die Rolle im Zielblog nicht vorhanden ist, weise dem Benutzer diese Rolle zu' )
			)
		);
	}
	/**
	 * Holt sich die Standardeinstellungen
	 * 
	 * @return Array der Einstellungen
	 */
	public function get_default_settings() {
		return array(
			'blog_templates_integration' => false
		);
	}

	/**
	 * Eine Instanz der Klasse zurückgeben
	 * 
	 * @return Object
	 */
	public static function get_instance() {
		if ( self::$instance === null )
			self::$instance = new self();
            
        return self::$instance;
	}

	/**
	 * Holt sich die Plugin-Einstellungen
	 * 
	 * @return Array der Einstellungen
	 */
	public function get_settings() {
		if ( empty( $this->settings ) )
			$this->init_settings();

		return $this->settings;
	}

	/**
	 * Aktualisiert die Einstellungen
	 * 
	 * @param Array $new_settings
	 */
	public function update_settings( $new_settings ) {
		$this->settings = $new_settings;
		if ( ! get_site_option( $this->settings_slug ) )
			add_site_option( $this->settings_slug, $new_settings );
		else
			update_site_option( $this->settings_slug, $new_settings );
	}

	/**
	 * Initialisiert die Plugin-Einstellungen
	 * 
	 * @since 0.1
	 */
	private function init_settings() {
		$current_settings = get_site_option( $this->settings_slug );
		$this->settings = wp_parse_args( $current_settings, $this->get_default_settings() );
	}


	/**
	 * Holt sich den Einstellungsslug, der auf DB verwendet wird
	 * 
	 * @return Array Plugin-Einstellungen
	 */
	public function get_settings_slug() {
		return $this->settings_slug;
	}

	public function get_additional_settings( $type ) {
		if ( ! isset( $this->additional_settings[ $type ] ) )
			return array();
		else
			return $this->additional_settings[ $type ];
	}




}