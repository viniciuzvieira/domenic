<?php

/**
 * Class Kirki_Field_Accordion.
 *
 * @since 1.7.0
 */

/**
 * Field overrides.
 */
class Kirki_Field_Accordion extends Kirki_Field {

    /**
     * @var string
     *
     * @since 1.7.0
     */
    protected $type = 'thimgroup';

    /**
     * Sets the $sanitize_callback
     *
     * @access protected
     */
    protected function set_sanitize_callback() {

        // If a custom sanitize_callback has been defined,
        // then we don't need to proceed any further.
        if ( ! empty( $this->sanitize_callback ) ) {
            return;
        }
        // Custom fields don't actually save any value.
        // just use __return_true.
        $this->sanitize_callback = '__return_true';

    }
}
