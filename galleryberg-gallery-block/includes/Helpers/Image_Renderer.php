<?php
/**
 * Shared image renderer for Galleryberg.
 *
 * Produces the frontend `<figure><img>…<figcaption></figure>` markup for a
 * single gallery image. Used by the `galleryberg/image` block's render.php and
 * (in the Pro plugin) by dynamic sources such as ACF, so that both paths emit
 * byte-for-byte identical markup and pick up the same CSS/lightbox behaviour.
 *
 * @package Galleryberg
 */

namespace Galleryberg\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Image_Renderer
 */
class Image_Renderer {

	/**
	 * Render a single gallery image to HTML.
	 *
	 * `$attributes` mirrors the `galleryberg/image` block attributes (media,
	 * caption, sizeSlug, border, borderRadius, shadow, aspectRatio, scale,
	 * focalPoint, width, height, href, etc.). `$context` mirrors the gallery
	 * block context (layout, blockSpacing, imagesBorderRadius, imagesShadow,
	 * galleryCaption*, enableHoverEffect, hoverEffect, showCaptions, …).
	 *
	 * @param array $attributes Image attributes.
	 * @param array $context    Gallery context values.
	 * @return string Rendered HTML.
	 */
	public static function render( array $attributes, array $context = array() ) {
		$media         = $attributes['media'] ?? array();
		$size_slug     = $attributes['sizeSlug'] ?? '';
		$url           = $media['sizes'][ $size_slug ]['url'] ?? $media['url'] ?? '';
		$img_alt       = esc_attr( $attributes['alt'] ?? '' );
		$caption       = $attributes['caption'] ?? '';
		$show_caption  = $attributes['showCaption'] ?? false;
		$align         = $attributes['align'] ?? '';
		$href          = $attributes['href'] ?? '';
		$link_class    = $attributes['linkClass'] ?? '';
		$link_target   = $attributes['linkTarget'] ?? '';
		$image_classes = $attributes['imageClasses'] ?? '';
		$rel           = $attributes['rel'] ?? '';

		// Check if captions should be shown (either individually or from gallery).
		$gallery_show_captions = isset( $context['showCaptions'] ) ? $context['showCaptions'] : false;
		$should_show_caption   = $show_caption || $gallery_show_captions;

		// Use individual image settings or fallback to gallery context.
		$caption_type        = ! empty( $attributes['captionType'] ) ? $attributes['captionType'] : ( $context['galleryCaptionType'] ?? 'below' );
		$caption_visibility  = ! empty( $attributes['captionVisibility'] ) ? $attributes['captionVisibility'] : ( $context['galleryCaptionVisibility'] ?? 'always' );
		$caption_alignment   = ! empty( $attributes['captionAlignment'] ) ? $attributes['captionAlignment'] : ( $context['galleryCaptionAlignment'] ?? 'left' );
		$caption_color       = ! empty( $attributes['captionColor'] ) ? $attributes['captionColor'] : ( $context['galleryCaptionColor'] ?? '' );
		$caption_bg_color    = ! empty( $attributes['captionBackgroundColor'] ) ? $attributes['captionBackgroundColor'] : ( $context['galleryCaptionBackgroundColor'] ?? '' );
		$caption_bg_gradient = ! empty( $attributes['captionBackgroundGradient'] ) ? $attributes['captionBackgroundGradient'] : ( $context['galleryCaptionBackgroundGradient'] ?? '' );

		// Typography.
		$caption_font_size       = ! empty( $attributes['captionFontSize'] ) ? $attributes['captionFontSize'] : ( $context['galleryCaptionFontSize'] ?? '' );
		$caption_font_appearance = ! empty( $attributes['captionFontAppearance'] ) ? $attributes['captionFontAppearance'] : ( $context['galleryCaptionFontAppearance'] ?? array() );
		$caption_font_style      = $caption_font_appearance['fontStyle'] ?? '';
		$caption_font_weight     = $caption_font_appearance['fontWeight'] ?? '';
		$caption_line_height     = ! empty( $attributes['captionLineHeight'] ) ? $attributes['captionLineHeight'] : ( $context['galleryCaptionLineHeight'] ?? '' );
		$caption_letter_spacing  = ! empty( $attributes['captionLetterSpacing'] ) ? $attributes['captionLetterSpacing'] : ( $context['galleryCaptionLetterSpacing'] ?? '' );
		$caption_text_decoration = ! empty( $attributes['captionTextDecoration'] ) ? $attributes['captionTextDecoration'] : ( $context['galleryCaptionTextDecoration'] ?? '' );
		$caption_text_transform  = ! empty( $attributes['captionTextTransform'] ) ? $attributes['captionTextTransform'] : ( $context['galleryCaptionTextTransform'] ?? '' );

		$has_href         = ! empty( $href );
		$img_src          = $url ? esc_url( $url ) : '';
		$link_class_attr  = $link_class ? 'class="' . esc_attr( $link_class ) . '"' : '';
		$link_target_attr = $link_target ? 'target="' . esc_attr( $link_target ) . '"' : '';
		$new_rel_attr     = $rel ? ' rel="' . esc_attr( $rel ) . '"' : '';

		// Build caption styles - combine background color and gradient.
		$caption_bg_style = Styling_Helpers::get_background_color_var(
			array(
				'captionBackgroundColor'    => $caption_bg_color,
				'captionBackgroundGradient' => $caption_bg_gradient,
			),
			'captionBackgroundColor',
			'captionBackgroundGradient'
		);

		$caption_styles = array();
		if ( $caption_color ) {
			$caption_styles['color'] = esc_attr( $caption_color );
		}
		if ( $caption_bg_style ) {
			$caption_styles['background'] = $caption_bg_style;
		}
		if ( $caption_font_size ) {
			$caption_styles['font-size'] = esc_attr( $caption_font_size );
		}
		if ( $caption_font_style ) {
			$caption_styles['font-style'] = esc_attr( $caption_font_style );
		}
		if ( $caption_font_weight ) {
			$caption_styles['font-weight'] = esc_attr( $caption_font_weight );
		}
		if ( $caption_line_height ) {
			$caption_styles['line-height'] = esc_attr( $caption_line_height );
		}
		if ( $caption_letter_spacing ) {
			$caption_styles['letter-spacing'] = esc_attr( $caption_letter_spacing );
		}
		if ( $caption_text_decoration ) {
			$caption_styles['text-decoration'] = esc_attr( $caption_text_decoration );
		}
		if ( $caption_text_transform ) {
			$caption_styles['text-transform'] = esc_attr( $caption_text_transform );
		}

		$caption_style_attr = ! empty( $caption_styles ) ? 'style="' . Styling_Helpers::generate_css_string( $caption_styles ) . '"' : '';
		$caption_classes    = array( 'wp-element-caption' );
		if ( $caption_type ) {
			$caption_classes[] = 'caption-type-' . esc_attr( $caption_type );
		}
		if ( $caption_visibility ) {
			$caption_classes[] = 'caption-visibility-' . esc_attr( $caption_visibility );
		}

		// Format caption alignment class for both standard (left, center, right) and matrix (top-left, etc.) formats.
		$caption_alignment_class = '';
		if ( $caption_alignment ) {
			if ( strpos( $caption_alignment, ' ' ) !== false ) {
				$caption_alignment_class = 'caption-align-' . str_replace( ' ', '-', $caption_alignment );
			} else {
				$caption_alignment_class = 'caption-align-' . $caption_alignment;
			}
			$caption_classes[] = $caption_alignment_class;
		}
		$caption_class_attr = 'class="' . implode( ' ', $caption_classes ) . '"';
		$caption_html       = ( $should_show_caption && $caption ) ? '<figcaption ' . $caption_class_attr . ' ' . $caption_style_attr . '>' . wp_kses_post( $caption ) . '</figcaption>' : '';
		$id                 = isset( $media['id'] ) ? $media['id'] : '';

		$aspect_ratio  = ! empty( $attributes['aspectRatio'] ) ? esc_attr( $attributes['aspectRatio'] ) : '';
		$scale         = ! empty( $attributes['scale'] ) ? esc_attr( $attributes['scale'] ) : '';
		$focal_point   = isset( $attributes['focalPoint'] ) ? $attributes['focalPoint'] : array(
			'x' => 0.5,
			'y' => 0.5,
		);
		$focal_x       = isset( $focal_point['x'] ) ? round( floatval( $focal_point['x'] ) * 100, 2 ) : 50;
		$focal_y       = isset( $focal_point['y'] ) ? round( floatval( $focal_point['y'] ) * 100, 2 ) : 50;
		$width         = isset( $attributes['width'] ) ? intval( $attributes['width'] ) : '';
		$height        = isset( $attributes['height'] ) ? intval( $attributes['height'] ) : '';
		$border        = $attributes['border'] ?? array();
		$border_radius = ! empty( $attributes['borderRadius'] ) ? $attributes['borderRadius'] : array();
		$shadow        = ! empty( $attributes['shadow'] ) ? $attributes['shadow'] : '';

		// Use gallery-level border radius from context if the image has no border radius set.
		$effective_border_radius = empty( $border_radius ) && isset( $context['imagesBorderRadius'] ) ? $context['imagesBorderRadius'] : $border_radius;

		// Use gallery-level shadow from context if the image has no shadow set.
		$effective_shadow = empty( $shadow ) && isset( $context['imagesShadow'] ) ? $context['imagesShadow'] : $shadow;

		$style = array(
			'border-top-left-radius'     => isset( $effective_border_radius['topLeft'] ) ? $effective_border_radius['topLeft'] : '',
			'border-top-right-radius'    => isset( $effective_border_radius['topRight'] ) ? $effective_border_radius['topRight'] : '',
			'border-bottom-left-radius'  => isset( $effective_border_radius['bottomLeft'] ) ? $effective_border_radius['bottomLeft'] : '',
			'border-bottom-right-radius' => isset( $effective_border_radius['bottomRight'] ) ? $effective_border_radius['bottomRight'] : '',
			'aspect-ratio'               => $aspect_ratio ? $aspect_ratio : '',
			'object-fit'                 => $scale ? $scale : '',
			'object-position'            => "{$focal_x}% {$focal_y}%",
			'width'                      => $width ? "{$width}px" : '',
			'height'                     => $height ? "{$height}px" : '',
		);

		// Applied on the figure wrapper (not the img) so it isn't clipped by the
		// `overflow: hidden` the hover-effect styles set on this same element.
		// The matching border radius is mirrored onto the wrapper so the shadow
		// follows the rounded corners instead of the figure's square box.
		$wrapper_styles = array(
			'box-shadow' => $effective_shadow,
		);
		if ( $effective_shadow ) {
			$wrapper_styles['border-top-left-radius']     = isset( $effective_border_radius['topLeft'] ) ? $effective_border_radius['topLeft'] : '';
			$wrapper_styles['border-top-right-radius']    = isset( $effective_border_radius['topRight'] ) ? $effective_border_radius['topRight'] : '';
			$wrapper_styles['border-bottom-left-radius']  = isset( $effective_border_radius['bottomLeft'] ) ? $effective_border_radius['bottomLeft'] : '';
			$wrapper_styles['border-bottom-right-radius'] = isset( $effective_border_radius['bottomRight'] ) ? $effective_border_radius['bottomRight'] : '';
		}

		if ( isset( $context['layout'] ) && 'justified' === $context['layout'] ) {
			if ( isset( $context['justifiedRowHeight'] ) && $context['justifiedRowHeight'] ) {
				$row_height               = intval( $context['justifiedRowHeight'] );
				$wrapper_styles['height'] = $row_height . 'px';
				$natural_width            = isset( $media['width'] ) ? intval( $media['width'] ) : 0;
				$natural_height           = isset( $media['height'] ) ? intval( $media['height'] ) : 0;
				if ( $natural_width && $natural_height ) {
					$wrapper_styles['flex-basis'] = round( $row_height * ( $natural_width / $natural_height ) ) . 'px';
				}
			}
		} elseif ( isset( $context['layout'] ) && 'masonry' === $context['layout'] && isset( $context['blockSpacing'] ) && $context['blockSpacing'] ) {
			$block_gap                       = Styling_Helpers::get_spacing_preset_css_var( $context['blockSpacing']['top'] ) ?? '16px';
			$wrapper_styles['margin-bottom'] = $block_gap;
		}
		$wrapper_styles = apply_filters( 'galleryberg_image_wrapper_styles', $wrapper_styles, $attributes, $context );
		$border_css     = Styling_Helpers::get_border_css_properties( $border );
		$style          = array_merge( $style, $border_css );

		$classes = array(
			'wp-block-galleryberg-image',
			'galleryberg-image',
		);

		// In the carousel layout each image is a Swiper slide.
		if ( isset( $context['layout'] ) && 'carousel' === $context['layout'] ) {
			$classes[] = 'swiper-slide';
		}

		if ( ! empty( $align ) ) {
			$classes[] = 'galleryberg-image-' . $align;
		}

		if ( ! empty( $size_slug ) ) {
			$classes[] = 'size-' . $size_slug;
		}

		// Add hover effect class if enabled in gallery context.
		if ( isset( $context['enableHoverEffect'] ) && $context['enableHoverEffect'] ) {
			$classes[] = 'has-hover-effect';

			$hover_effect = isset( $context['hoverEffect'] ) ? $context['hoverEffect'] : 'zoom-in';
			$classes[]    = 'hover-' . esc_attr( $hover_effect );
		}

		if ( ! empty( $width ) || ! empty( $height ) ) {
			$classes[] = 'is-resized';
		}

		$image_id = $id ? 'wp-image-' . esc_attr( $id ) : '';

		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => implode( ' ', $classes ),
				'id'    => $image_id,
				'style' => Styling_Helpers::generate_css_string( $wrapper_styles ),
			)
		);

		$image_html = '';
		// Validate that the ID actually belongs to the URL to prevent showing wrong images.
		$use_attachment = false;
		if ( $id && $img_src ) {
			$attachment_url = wp_get_attachment_url( $id );
			if ( $attachment_url && basename( $attachment_url ) === basename( $img_src ) ) {
				$use_attachment = true;
			}
		}

		if ( $use_attachment ) {
			$image_html = wp_get_attachment_image(
				$id,
				$size_slug ? $size_slug : 'full',
				false,
				array(
					'alt'   => $img_alt,
					'style' => Styling_Helpers::generate_css_string( $style ),
					'class' => trim( $image_classes ),
				)
			);
		}

		// Fallback to URL if attachment doesn't match or doesn't exist.
		if ( empty( $image_html ) && ! empty( $img_src ) ) {
			$image_html = sprintf(
				'<img src="%s" alt="%s" style="%s" class="%s" />',
				$img_src,
				$img_alt,
				Styling_Helpers::generate_css_string( $style ),
				trim( $image_classes )
			);
		}

		ob_start();
		?>
		<figure <?php echo wp_kses_post( $wrapper_attributes ); ?>>
			<?php if ( $has_href ) : ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php echo esc_attr( $link_class_attr ); ?> <?php echo esc_attr( $link_target_attr ); ?> <?php echo esc_attr( $new_rel_attr ); ?>>
					<?php echo wp_kses_post( $image_html ); ?>
				</a>
			<?php else : ?>
				<?php echo wp_kses_post( $image_html ); ?>
			<?php endif; ?>
			<?php echo wp_kses_post( $caption_html ); ?>
		</figure>
		<?php
		return ob_get_clean();
	}
}
