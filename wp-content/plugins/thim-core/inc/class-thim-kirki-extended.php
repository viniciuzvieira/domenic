<?php

/**
 * Class Thim_Kirki_Extended.
 *
 * @since 1.7.0
 */
class Thim_Kirki_Extended extends Thim_Singleton {

	/**
	 * Thim_Kirki_Extended constructor.
	 *
	 * @since 1.7.0
	 */
	protected function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->hooks();
	}

	/**
	 * Autoload.
	 *
	 * @since 1.7.0
	 *
	 * @param $class_name
	 */
	public function autoload( $class_name ) {
		switch ( $class_name ) {
			case 'Thim_Kirki_Control_Group':
				include_once THIM_CORE_INC_PATH . '/kirki-extended/controls/class-thim-kirki-control-group.php';
				break;

			case 'Kirki_Field_Accordion':
				include_once THIM_CORE_INC_PATH . '/kirki-extended/field/class-kirki-field-accordion.php';
				break;
		}
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.7.0
	 */
	private function hooks() {
		add_filter( 'kirki/control_types', array( $this, 'add_accordion_control' ) );
		//add_filter( 'kirki/enqueue_google_fonts', array( $this, 'filter_google_fonts' ) );
		add_filter( 'kirki/fonts/standard_fonts', array( $this, 'filter_standard_fonts' ) );
	}


	/**
	 * Filter standard fonts
	 *
	 * @since 1.8.5
	 *
	 * @param $standard_fonts
	 *
	 * @return array
	 */
	public function filter_standard_fonts( $standard_fonts ) {
		$standard_fonts = array(
			'serif'      => array(
				'label' => 'Serif',
				'stack' => 'Georgia, Times, Times New Roman, serif',
			),
			'sans-serif' => array(
				'label' => 'Sans Serif',
				'stack' => '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif',
			),
			'monospace'  => array(
				'label' => 'Monospace',
				'stack' => 'Monaco, Lucida Sans Typewriter, Lucida Typewriter, Courier New, Courier, monospace',
			),
		);

		return $standard_fonts;
	}

	/**
	 * Filter google fonts.
	 *
	 * @since 1.7.1
	 *
	 * @param $fonts
	 *
	 * @return mixed
	 */
	public function filter_google_fonts( $fonts ) {
		if ( ! is_array( $fonts ) ) {
			return $fonts;
		}

		$font_variants = array(
//			'100',
//			'100italic',
//			'200',
//			'200italic',
			'300',
			'300italic',
//			'400',
//			'400italic',
			'regular',
			'italic',
			'500',
			'500italic',
//			'600',
//			'600italic',
			'700',
			'700italic',
//			'800',
//			'800italic',
//			'900',
//			'900italic',
		);

		foreach ( $fonts as $font_family => $font_weights ) {
			$fonts[ $font_family ] = $font_variants;
		}

		return $fonts;
	}

	/**
	 * Add accordion field to Kirki.
	 *
	 * @since 1.7.0
	 *
	 * @param $controls
	 *
	 * @return mixed
	 */
	public function add_accordion_control( $controls ) {
		$controls['tc_group'] = 'Thim_Kirki_Control_Group';

		return $controls;
	}
}