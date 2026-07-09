<?php
/**
 * Render the Galleryberg Image block.
 *
 * The actual markup generation lives in \Galleryberg\Helpers\Image_Renderer so
 * that dynamic sources (e.g. the Pro ACF integration) can emit identical image
 * markup without duplicating this logic.
 *
 * @package Galleryberg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo \Galleryberg\Helpers\Image_Renderer::render(
	$attributes,
	isset( $block->context ) ? (array) $block->context : array()
); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped within the renderer.
