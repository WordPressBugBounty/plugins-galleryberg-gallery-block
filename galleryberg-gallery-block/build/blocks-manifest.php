<?php
// This file is generated. Do not modify it manually.
return array(
	'gallery' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'galleryberg/gallery',
		'title' => 'Galleryberg Gallery',
		'category' => 'widgets',
		'description' => 'A customizable gallery block for displaying images in columns with optional cropping and spacing.',
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'mobileColumns' => array(
				'type' => 'number'
			),
			'tabletColumns' => array(
				'type' => 'number'
			),
			'cropImages' => array(
				'type' => 'boolean',
				'default' => true
			),
			'blockSpacing' => array(
				'type' => 'object',
				'default' => array(
					'top' => '16px',
					'left' => '16px',
					'right' => '16px',
					'bottom' => '16px'
				)
			),
			'isGapSeparated' => array(
				'type' => 'boolean',
				'default' => false
			),
			'images' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'lightbox' => array(
				'type' => 'boolean',
				'default' => false
			),
			'openEffect' => array(
				'type' => 'string',
				'default' => 'zoom'
			),
			'closeEffect' => array(
				'type' => 'string',
				'default' => 'zoom'
			),
			'slideEffect' => array(
				'type' => 'string',
				'default' => 'slide'
			),
			'keyboardNavigation' => array(
				'type' => 'boolean',
				'default' => true
			),
			'loop' => array(
				'type' => 'boolean',
				'default' => true
			),
			'zoomable' => array(
				'type' => 'boolean',
				'default' => true
			),
			'draggable' => array(
				'type' => 'boolean',
				'default' => true
			),
			'layout' => array(
				'type' => 'string',
				'default' => 'tiles'
			),
			'justifiedRowHeight' => array(
				'type' => 'number',
				'default' => 180
			),
			'backgroundColor' => array(
				'type' => 'string',
				'default' => null
			),
			'backgroundGradient' => array(
				'type' => 'string',
				'default' => null
			),
			'padding' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'margin' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'border' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'borderRadius' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'imagesBorderRadius' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'galleryCaptionType' => array(
				'type' => 'string',
				'default' => 'full-overlay'
			),
			'galleryCaptionVisibility' => array(
				'type' => 'string',
				'default' => 'always'
			),
			'galleryCaptionAlignment' => array(
				'type' => 'string',
				'default' => 'bottom center'
			),
			'galleryCaptionColor' => array(
				'type' => 'string',
				'default' => ''
			),
			'galleryCaptionBackgroundColor' => array(
				'type' => 'string',
				'default' => ''
			),
			'galleryCaptionBackgroundGradient' => array(
				'type' => 'string',
				'default' => 'linear-gradient(0deg,rgb(0,0,0) 0%,rgba(0,0,0,0) 100%)'
			),
			'enableHoverEffect' => array(
				'type' => 'boolean',
				'default' => false
			),
			'hoverEffect' => array(
				'type' => 'string',
				'default' => 'zoom-in'
			),
			'showCaptions' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showLightboxCaptions' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'supports' => array(
			'html' => false,
			'align' => true
		),
		'providesContext' => array(
			'galleryberg/enableLazyLoading' => 'enableLazyLoading',
			'layout' => 'layout',
			'justifiedRowHeight' => 'justifiedRowHeight',
			'blockSpacing' => 'blockSpacing',
			'imagesBorderRadius' => 'imagesBorderRadius',
			'galleryCaptionType' => 'galleryCaptionType',
			'galleryCaptionVisibility' => 'galleryCaptionVisibility',
			'galleryCaptionAlignment' => 'galleryCaptionAlignment',
			'galleryCaptionColor' => 'galleryCaptionColor',
			'galleryCaptionBackgroundColor' => 'galleryCaptionBackgroundColor',
			'galleryCaptionBackgroundGradient' => 'galleryCaptionBackgroundGradient',
			'enableHoverEffect' => 'enableHoverEffect',
			'hoverEffect' => 'hoverEffect',
			'showCaptions' => 'showCaptions'
		),
		'textdomain' => 'galleryberg-gallery-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewStyle' => array(
			'galleryberg-lightbox'
		),
		'render' => 'file:./render.php',
		'viewScript' => array(
			'file:./view.js',
			'galleryberg-lightbox'
		)
	),
	'image' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'version' => '0.0.3',
		'title' => 'Image',
		'name' => 'galleryberg/image',
		'category' => 'design',
		'parent' => array(
			'galleryberg/gallery'
		),
		'description' => 'A customizable image block for displaying images with optional cropping, spacing, and alignment.',
		'attributes' => array(
			'media' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'height' => array(
				'type' => 'string',
				'default' => ''
			),
			'width' => array(
				'type' => 'string',
				'default' => ''
			),
			'align' => array(
				'type' => 'string',
				'default' => ''
			),
			'alt' => array(
				'type' => 'string',
				'default' => ''
			),
			'aspectRatio' => array(
				'type' => 'string',
				'default' => ''
			),
			'scale' => array(
				'type' => 'string',
				'default' => ''
			),
			'sizeSlug' => array(
				'type' => 'string',
				'default' => 'large'
			),
			'caption' => array(
				'type' => 'string',
				'default' => ''
			),
			'showCaption' => array(
				'type' => 'boolean',
				'default' => false
			),
			'captionType' => array(
				'type' => 'string',
				'default' => ''
			),
			'captionVisibility' => array(
				'type' => 'string',
				'default' => ''
			),
			'captionAlignment' => array(
				'type' => 'string',
				'default' => ''
			),
			'captionColor' => array(
				'type' => 'string',
				'default' => ''
			),
			'captionBackgroundColor' => array(
				'type' => 'string',
				'default' => null
			),
			'captionBackgroundGradient' => array(
				'type' => 'string',
				'default' => null
			),
			'href' => array(
				'type' => 'string',
				'default' => ''
			),
			'linkClass' => array(
				'type' => 'string',
				'default' => ''
			),
			'linkDestination' => array(
				'type' => 'string',
				'default' => ''
			),
			'rel' => array(
				'type' => 'string',
				'default' => ''
			),
			'linkTarget' => array(
				'type' => 'string',
				'default' => ''
			),
			'border' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'borderRadius' => array(
				'type' => 'object',
				'default' => array(
					
				)
			),
			'isExample' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'supports' => array(
			'anchor' => true,
			'color' => array(
				'text' => false,
				'background' => false
			),
			'filter' => array(
				'duotone' => true
			),
			'selectors' => array(
				'filter' => array(
					'duotone' => '.wp-block-galleryberg-image img, .wp-block-galleryberg-image .components-placeholder'
				)
			)
		),
		'example' => array(
			'attributes' => array(
				'isExample' => true
			)
		),
		'usesContext' => array(
			'galleryberg/enableLazyLoading',
			'justifiedRowHeight',
			'layout',
			'blockSpacing',
			'imagesBorderRadius',
			'galleryCaptionType',
			'galleryCaptionVisibility',
			'galleryCaptionAlignment',
			'galleryCaptionColor',
			'galleryCaptionBackgroundColor',
			'galleryCaptionBackgroundGradient',
			'enableHoverEffect',
			'hoverEffect',
			'showCaptions'
		),
		'textdomain' => 'galleryberg-gallery-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	)
);
