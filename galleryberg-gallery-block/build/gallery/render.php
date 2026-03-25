<?php
/**
 * Render the Galleryberg Gallery block.
 *
 * @package Galleryberg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'galleryberg-gallery-container' );

$align = isset( $attributes['align'] ) ? $attributes['align'] : '';
$columns = isset( $attributes['columns'] ) ? $attributes['columns'] : null;
$lightbox = ! empty( $attributes['lightbox'] );
if ( $lightbox ) {
	$classes[] = 'galleryberg-has-lightbox';
}

if ( $align ) {
	$classes[] = 'align' . esc_attr( $align );
}
if ( null !== $columns ) {
	$classes[] = 'columns-' . intval( $columns );
} else {
	$classes[] = 'columns-default';
}

$layout    = $attributes['layout'] ?? 'tiles';
$classes[] = 'layout-' . esc_attr( $layout );

$bg_color    = \Galleryberg\Helpers\Styling_Helpers::get_background_color_var(
	$attributes,
	'backgroundColor',
	'backgroundGradient'
);
$padding_obj = \Galleryberg\Helpers\Styling_Helpers::get_spacing_css( $attributes['padding'] ?? array() );
$margin_obj  = \Galleryberg\Helpers\Styling_Helpers::get_spacing_css( $attributes['margin'] ?? array() );
$row_gap     = isset( $attributes['blockSpacing']['top'] ) ? \Galleryberg\Helpers\Styling_Helpers::get_spacing_preset_css_var( $attributes['blockSpacing']['top'] ) : '';
$column_gap  = isset( $attributes['blockSpacing']['left'] ) ? \Galleryberg\Helpers\Styling_Helpers::get_spacing_preset_css_var( $attributes['blockSpacing']['left'] ) : '';

$style = array(
	'row-gap'                    => $row_gap,
	'column-gap'                 => $column_gap,
	'background'                 => $bg_color,
	'border-top-left-radius'     => $attributes['borderRadius']['topLeft'] ?? '',
	'border-top-right-radius'    => $attributes['borderRadius']['topRight'] ?? '',
	'border-bottom-left-radius'  => $attributes['borderRadius']['bottomLeft'] ?? '',
	'border-bottom-right-radius' => $attributes['borderRadius']['bottomRight'] ?? '',
	'padding-top'                => $padding_obj['top'] ?? '',
	'padding-right'              => $padding_obj['right'] ?? '',
	'padding-bottom'             => $padding_obj['bottom'] ?? '',
	'padding-left'               => $padding_obj['left'] ?? '',
	'margin-top'                 => $margin_obj['top'] ?? '',
	'margin-right'               => $margin_obj['right'] ?? '',
	'margin-bottom'              => $margin_obj['bottom'] ?? '',
	'margin-left'                => $margin_obj['left'] ?? '',
	'border-top'                 => \Galleryberg\Helpers\Styling_Helpers::get_single_side_border_value( \Galleryberg\Helpers\Styling_Helpers::get_border_css( $attributes['border'] ?? array() ), 'top' ),
	'border-left'                => \Galleryberg\Helpers\Styling_Helpers::get_single_side_border_value( \Galleryberg\Helpers\Styling_Helpers::get_border_css( $attributes['border'] ?? array() ), 'left' ),
	'border-right'               => \Galleryberg\Helpers\Styling_Helpers::get_single_side_border_value( \Galleryberg\Helpers\Styling_Helpers::get_border_css( $attributes['border'] ?? array() ), 'right' ),
	'border-bottom'              => \Galleryberg\Helpers\Styling_Helpers::get_single_side_border_value( \Galleryberg\Helpers\Styling_Helpers::get_border_css( $attributes['border'] ?? array() ), 'bottom' ),
);
$layout  = $attributes['layout'] ?? 'tiles';
$columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;

$pro_layouts = apply_filters( 'galleryberg_pro_layouts', [] );

if ( 'tiles' === $layout || 'square' === $layout || in_array( $layout, $pro_layouts ) ) {
	$style['grid-template-columns'] = sprintf( 'repeat(%d,1fr)', $columns );
}
if ( 'masonry' === $layout ) {
	$style['column-count'] = $columns;
}

// Add responsive column CSS custom properties
$mobile_columns = isset( $attributes['mobileColumns'] ) ? intval( $attributes['mobileColumns'] ) : null;
$tablet_columns = isset( $attributes['tabletColumns'] ) ? intval( $attributes['tabletColumns'] ) : null;

if ( $mobile_columns ) {
	$style['--galleryberg-mobile-columns'] = $mobile_columns;
}
if ( $tablet_columns ) {
	$style['--galleryberg-tablet-columns'] = $tablet_columns;
}

$galleryberg_pro_gallery_classes = apply_filters( 'galleryberg_pro_gallery_classes', array(), $attributes );

$classes = array_merge( $classes, $galleryberg_pro_gallery_classes );

// Get block wrapper attributes
$wrapper_args = array(
	'class' => implode( ' ', $classes ),
	'style' => \Galleryberg\Helpers\Styling_Helpers::generate_css_string( $style ),
);
if ( $lightbox ) {
	$wrapper_args['data-open-effect']        = esc_attr( $attributes['openEffect'] ?? 'zoom' );
	$wrapper_args['data-close-effect']       = esc_attr( $attributes['closeEffect'] ?? 'zoom' );
	$wrapper_args['data-slide-effect']       = esc_attr( $attributes['slideEffect'] ?? 'slide' );
	$wrapper_args['data-keyboard-navigation'] = empty( $attributes['keyboardNavigation'] ) ? 'false' : 'true';
	$wrapper_args['data-loop']               = empty( $attributes['loop'] ) ? 'false' : 'true';
	$wrapper_args['data-zoomable']           = empty( $attributes['zoomable'] ) ? 'false' : 'true';
	$wrapper_args['data-draggable']          = empty( $attributes['draggable'] ) ? 'false' : 'true';
	$wrapper_args['data-show-lightbox-captions'] = empty( $attributes['showLightboxCaptions'] ) ? 'false' : 'true';
}

// Apply Pro data attributes filter
$wrapper_args = apply_filters( 'galleryberg_pro_gallery_data_attributes', $wrapper_args, $attributes );

$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );

?>


<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<?php echo wp_kses_post( $content ); ?>
</div>
