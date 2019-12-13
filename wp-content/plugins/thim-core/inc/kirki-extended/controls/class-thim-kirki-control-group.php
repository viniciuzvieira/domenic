<?php
/**
 * Customizer Control: accordion.
 *
 * Creates a new custom control.
 *
 * @created      21/11/2017
 * @package      Kirki
 * @subpackage   Controls
 * @since        1.7.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The "custom" control allows you to add any raw HTML.
 */
class Thim_Kirki_Control_Group extends WP_Customize_Control {

    /**
     * The control type.
     *
     * @access public
     * @var string
     */
    public $type = 'tc_group';

    /**
     * Enqueue control related scripts/styles.
     *
     * @access public
     *
     * @since 1.7.0
     */
    public function enqueue() {
        parent::enqueue();

        wp_enqueue_script( 'thim-kirki-accordion', THIM_CORE_INC_URI . '/kirki-extended/js/accordion.js', array( 'kirki-script' ), THIM_CORE_VERSION );
        wp_enqueue_style( 'thim-kirki-accordion', THIM_CORE_INC_URI . '/kirki-extended/css/accordion.css', array( 'kirki-styles' ), THIM_CORE_VERSION );
    }

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see Kirki_Customize_Control::to_json()}.
     *
     * @see    WP_Customize_Control::print_template()
     *
     * @access protected
     *
     * @since 1.7.0
     */
    protected function content_template() {
        ?>
        <div class="thim-group-heading">
            XXXXXXXXXXXXXXXXX
        </div>
        <?php
    }
}
