<?php
/**
 * Styling Helpers for Galleryberg Gallery Block
 *
 * @package Galleryberg
 */

namespace Galleryberg\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Styling_Helpers
 *
 * Provides helper functions for generating CSS and handling spacing/borders
 */
class Styling_Helpers {

	/**
	 * Check if spacing value is preset or custom
	 *
	 * @param string $value Spacing value.
	 * @return bool
	 */
	public static function is_spacing_preset( $value ) {
		if ( ! $value || ! is_string( $value ) ) {
			return false;
		}
		return '0' === $value || strpos( $value, 'var:preset|spacing|' ) === 0;
	}

	/**
	 * Return the spacing CSS variable
	 *
	 * @param string $value Spacing value.
	 * @return string|null
	 */
	public static function get_spacing_preset_css_var( $value ) {
		if ( ! $value ) {
			return null;
		}

		$matches = array();
		preg_match( '/var:preset\|spacing\|(.+)/', $value, $matches );

		if ( empty( $matches ) ) {
			return $value;
		}
		return "var(--wp--preset--spacing--{$matches[1]})";
	}

	/**
	 * Get spacing CSS from object
	 *
	 * @param array $object Spacing object.
	 * @return array
	 */
	public static function get_spacing_css( $object ) {
		$css = array();

		foreach ( $object as $key => $value ) {
			if ( self::is_spacing_preset( $value ) ) {
				$css[ $key ] = self::get_spacing_preset_css_var( $value );
			} else {
				$css[ $key ] = $value;
			}
		}

		return $css;
	}

	/**
	 * Check if value is undefined
	 *
	 * @param mixed $value Value to check.
	 * @return bool
	 */
	public static function is_undefined( $value ) {
		return ! isset( $value ) || null === $value || empty( $value );
	}

	/**
	 * Generate CSS string from styles array
	 *
	 * @param array $styles Styles array.
	 * @return string
	 */
	public static function generate_css_string( $styles ) {
		$css_string = '';

		foreach ( $styles as $key => $value ) {
			if (
				! self::is_undefined( $value ) &&
				false !== $value &&
				( ! is_string( $value ) || ( trim( $value ) !== '' && trim( $value ) !== 'undefined undefined undefined' ) ) &&
				! empty( $value )
			) {
				$css_string .= $key . ': ' . $value . '; ';
			}
		}

		return $css_string;
	}

	/**
	 * Get CSS value for a single side of the border
	 *
	 * @param array  $border Border array.
	 * @param string $side   Border side.
	 * @return string
	 */
	public static function get_single_side_border_value( $border, $side ) {
		$width = isset( $border[ $side ]['width'] ) ? $border[ $side ]['width'] : '';
		$style = isset( $border[ $side ]['style'] ) ? $border[ $side ]['style'] : '';
		$color = isset( $border[ $side ]['color'] ) ? $border[ $side ]['color'] : '';

		return "{$width} " . ( $width && empty( $border[ $side ]['style'] ) ? 'solid ' : $style ) . ( ! empty( $width ) && empty( $color ) ? '' : $color );
	}

	/**
	 * Check if border has split borders
	 *
	 * @param array $border Block border.
	 * @return bool
	 */
	public static function has_split_borders( $border = array() ) {
		$sides = array( 'top', 'right', 'bottom', 'left' );
		foreach ( $border as $side => $value ) {
			if ( in_array( $side, $sides, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get border CSS from attributes
	 *
	 * @param array $object Block border.
	 * @return array
	 */
	public static function get_border_css( $object ) {
		$css = array();

		if ( ! self::has_split_borders( $object ) ) {
			$css['top']    = $object;
			$css['right']  = $object;
			$css['bottom'] = $object;
			$css['left']   = $object;
			return $css;
		}

		return $object;
	}

	/**
	 * Get border variables for CSS
	 *
	 * @param array  $border Border array.
	 * @param string $slug   Slug to use in variable.
	 * @return array
	 */
	public static function get_border_variables_css( $border, $slug ) {
		$border_in_dimensions = self::get_border_css( $border );
		$border_sides         = array( 'top', 'right', 'bottom', 'left' );
		$borders              = array();

		foreach ( $border_sides as $side ) {
			$side_property             = "--galleryberg-blocks-{$slug}-border-{$side}";
			$side_value                = self::get_single_side_border_value( $border_in_dimensions, $side );
			$borders[ $side_property ] = $side_value;
		}

		return $borders;
	}

	/**
	 * Get border CSS properties and values without CSS variables
	 *
	 * @param array $border Border array.
	 * @return array
	 */
	public static function get_border_css_properties( $border ) {
		$border_in_dimensions = self::get_border_css( $border );
		$border_sides         = array( 'top', 'right', 'bottom', 'left' );
		$borders              = array();

		foreach ( $border_sides as $side ) {
			$property            = "border-{$side}";
			$value               = self::get_single_side_border_value( $border_in_dimensions, $side );
			$borders[ $property ] = $value;
		}

		return $borders;
	}

	/**
	 * Get background color variable
	 *
	 * @param array  $attributes          Block attributes.
	 * @param string $bg_color_attr_key   Background color attribute key.
	 * @param string $gradient_attr_key   Gradient attribute key.
	 * @return string
	 */
	public static function get_background_color_var( $attributes, $bg_color_attr_key, $gradient_attr_key ) {
		if ( ! empty( $attributes[ $bg_color_attr_key ] ) ) {
			return $attributes[ $bg_color_attr_key ];
		} elseif ( ! empty( $attributes[ $gradient_attr_key ] ) ) {
			return $attributes[ $gradient_attr_key ];
		} else {
			return '';
		}
	}
}
