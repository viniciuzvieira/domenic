<?php

/**
 * Core functions
 *
 * @package   Thim_Core
 * @since     1.0.0
 */

/**
 * Get instance Thim_Core_Customizer.
 *
 * @return Thim_Core_Customizer
 * @since 0.1.0
 */
if ( ! function_exists( 'thim_customizer' ) ) {
	function thim_customizer() {
		return Thim_Core_Customizer::instance();
	}
}

/**
 * Trigger compile custom css theme.
 *
 * @require add_filter `tc_variables_compile_scss_theme` before call this function.
 *
 * @since 1.0.1
 */
if ( ! function_exists( 'thim_compile_custom_css_theme' ) ) {
	function thim_compile_custom_css_theme() {
		$customizer = Thim_Core_Customizer::instance();
		$customizer->after_save_customize();
	}
}

/**
 * Show entry format images, video, gallery, audio, etc.
 *
 * @return void
 */
if ( ! function_exists( 'thim_post_formats' ) ):
	function thim_post_formats( $size ) {
		$html = '';
		switch ( get_post_format() ) {
			case 'image':
				$image = thim_get_image( array(
					'size'     => $size,
					'format'   => 'src',
					'meta_key' => 'thim_image',
					'echo'     => false,
				) );
				if ( ! $image ) {
					break;
				}

				$html = sprintf( '<a class="post-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>', esc_url( get_permalink() ), esc_attr( the_title_attribute( 'echo=0' ) ), $image );
				break;
			case 'gallery':
				$images = thim_meta( 'thim_gallery', "type=image&single=false&size=$size" );
				$thumbs = thim_meta( 'thim_gallery', "type=image&single=false&size=thumbnail" );
				if ( empty( $images ) ) {
					break;
				}
				$html .= '<div class="flexslider">';
				$html .= '<ul class="slides">';
				foreach ( $images as $key => $image ) {
					if ( ! empty( $image['url'] ) ) {
						$html .= sprintf( '<li data-thumb="%s"><a href="%s" class="hover-gradient"><img src="%s" alt="gallery"></a></li>', $thumbs[ $key ]['url'], esc_url( get_permalink() ), esc_url( $image['url'] ) );
					}
				}
				$html .= '</ul>';
				$html .= '</div>';
				break;
			case 'audio':
				$audio = thim_meta( 'thim_audio' );
				if ( ! $audio ) {
					break;
				}
				// If URL: show oEmbed HTML or jPlayer
				if ( filter_var( $audio, FILTER_VALIDATE_URL ) ) {
					//jsplayer
					wp_enqueue_style( 'thim-pixel-industry', THIM_CORE_ADMIN_URI . '/assets/js/jplayer/skin/pixel-industry/pixel-industry.css' );
					wp_enqueue_script( 'thim-jplayer', THIM_CORE_ADMIN_URI . '/assets/js/jplayer/jquery.jplayer.min.js', array( 'jquery' ), '', true );

					// Try oEmbed first
					if ( $oembed = @wp_oembed_get( $audio ) ) {
						$html .= $oembed;
					} // Use jPlayer
					else {
						$id   = uniqid();
						$html .= "<div data-player='$id' class='jp-jplayer' data-audio='$audio'></div>";
						$html .= thim_jplayer( $id );
					}
				} // If embed code: just display
				else {
					$html .= $audio;
				}
				break;
			case 'video':

				$video = thim_meta( 'thim_video' );
				if ( ! $video ) {
					break;
				}
				// If URL: show oEmbed HTML
				if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
					if ( $oembed = @wp_oembed_get( $video ) ) {
						$html .= $oembed;
					}
				} // If embed code: just display
				else {
					$html .= $video;
				}
				break;
			default:
				$thumb = get_the_post_thumbnail( get_the_ID(), $size );
				if ( empty( $thumb ) ) {
					return;
				}
				$html .= '<a class="post-image" href="' . esc_url( get_permalink() ) . '">';
				$html .= $thumb;
				$html .= '</a>';
		}
		if ( $html ) {
			echo "<div class='post-formats-wrapper'>$html</div>";
		}
	}
endif;
add_action( 'thim_entry_top', 'thim_post_formats' );

/**
 * Get jplayer interface for post format audio
 *
 * @return string HTML for thim_jplayer functions
 */
if ( ! function_exists( 'thim_get_jplayer_interface' ) ) {
	function thim_get_jplayer_interface() { ?>
        <div class="jp-gui jp-interface">
            <ul class="jp-controls">
                <li>
                    <a href="javascript:;" class="jp-previous" tabindex="1"><?php esc_attr_e( 'previous', 'thim' ); ?></a>
                </li>
                <li><a href="javascript:;" class="jp-play" tabindex="1"><?php esc_attr_e( 'play', 'thim' ); ?></a>
                </li>
                <li><a href="javascript:;" class="jp-pause" tabindex="1"><?php esc_attr_e( 'pause', 'thim' ); ?></a>
                </li>
                <li><a href="javascript:;" class="jp-next" tabindex="1"><?php esc_attr_e( 'next', 'thim' ); ?></a>
                </li>
                <li><a href="javascript:;" class="jp-stop" tabindex="1"><?php esc_attr_e( 'stop', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-mute" tabindex="1" title="<?php esc_attr_e( 'mute', 'thim' ); ?>"><?php esc_attr_e( 'mute', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php esc_attr_e( 'unmute', 'thim' ); ?>"><?php esc_attr_e( 'unmute', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-volume-max" tabindex="1" title="<?php esc_attr_e( 'max volume', 'thim' ); ?>"><?php esc_attr_e( 'max volume', 'thim' ); ?></a>
                </li>
            </ul>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-bar">
                <div class="jp-volume-bar-value"></div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time"></div>
                <div class="jp-duration"></div>
            </div>
            <ul class="jp-toggles">
                <li>
                    <a href="javascript:;" class="jp-shuffle" tabindex="1" title="<?php esc_attr_e( 'shuffle', 'thim' ); ?>"><?php esc_attr_e( 'shuffle', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="<?php esc_attr_e( 'shuffle off', 'thim' ); ?>"><?php esc_attr_e( 'shuffle off', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-repeat" tabindex="1" title="<?php esc_attr_e( 'repeat', 'thim' ); ?>"><?php esc_attr_e( 'repeat', 'thim' ); ?></a>
                </li>
                <li>
                    <a href="javascript:;" class="jp-repeat-off" tabindex="1" title="<?php esc_attr_e( 'repeat off', 'thim' ); ?>"><?php esc_attr_e( 'repeat off', 'thim' ); ?></a>
                </li>
            </ul>
        </div>
		<?php
	}
}

/**
 * Get jplayer for post format audio
 *
 * @param $id
 *
 * @return string HTML for jplayer
 */
if ( ! function_exists( 'thim_jplayer' ) ) {
	function thim_jplayer( $id = 'jp_container_1' ) {
		ob_start();
		?>
        <div id="<?php echo esc_attr( $id ); ?>" class="jp-audio">
            <div class="jp-type-playlist">
				<?php thim_get_jplayer_interface(); ?>
                <div class="jp-no-solution">
					<?php printf( __( '<span>Update Required</span> To play the media you will need to either update your browser to a recent version or update your <a href="%s" target="_blank">Flash plugin</a>.', 'thim' ), 'http://get.adobe.com/flashplayer/' ); ?>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}

/**
 * Get image features
 *
 * @param $args
 *
 * @return array|void
 */
if ( ! function_exists( 'thim_get_image' ) ) {
	function thim_get_image( $args = array() ) {
		$default = apply_filters( 'thim_get_image_default_args', array(
			'post_id'  => get_the_ID(),
			'size'     => 'thumbnail',
			'format'   => 'html', // html or src
			'attr'     => '',
			'meta_key' => '',
			'scan'     => true,
			'default'  => '',
			'echo'     => true,
		) );

		$args = wp_parse_args( $args, $default );

		if ( ! $args['post_id'] ) {
			$args['post_id'] = get_the_ID();
		}

		// Get image from cache
		$key         = md5( serialize( $args ) );
		$image_cache = wp_cache_get( $args['post_id'], 'thim_get_image' );

		if ( ! is_array( $image_cache ) ) {
			$image_cache = array();
		}

		if ( empty( $image_cache[ $key ] ) ) {
			// Get post thumbnail
			if ( has_post_thumbnail( $args['post_id'] ) ) {
				$id   = get_post_thumbnail_id();
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}

			// Get the first image in the custom field
			if ( ! isset( $html, $src ) && $args['meta_key'] ) {
				$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

				// Check if this post has attached images
				if ( $id ) {
					$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
					list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
				}
			}

			// Get the first attached image
			if ( ! isset( $html, $src ) ) {
				$image_ids = array_keys( get_children( array(
					'post_parent'    => $args['post_id'],
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				) ) );

				// Check if this post has attached images
				if ( ! empty( $image_ids ) ) {
					$id   = $image_ids[0];
					$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
					list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
				}
			}

			// Get the first image in the post content
			if ( ! isset( $html, $src ) && ( $args['scan'] ) ) {
				preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

				if ( ! empty( $matches ) ) {
					$html = $matches[0];
					$src  = $matches[1];
				}
			}

			// Use default when nothing found
			if ( ! isset( $html, $src ) && ! empty( $args['default'] ) ) {
				if ( is_array( $args['default'] ) ) {
					$html = @$args['html'];
					$src  = @$args['src'];
				} else {
					$html = $src = $args['default'];
				}
			}

			// Still no images found?
			if ( ! isset( $html, $src ) ) {
				return false;
			}

			$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

			$image_cache[ $key ] = $output;
			wp_cache_set( $args['post_id'], $image_cache, 'thim_get_image' );
		} // If image already cached
		else {
			$output = $image_cache[ $key ];
		}

		$output = apply_filters( 'thim_get_image', $output, $args );

		if ( ! $args['echo'] ) {
			return $output;
		}

		echo ent2ncr( $output );
	}
}

/**
 * Get post meta
 *
 * @param $key
 * @param $args
 * @param $post_id
 *
 * @return string
 * @return bool
 */
if ( ! function_exists( 'thim_meta' ) ) {
	function thim_meta( $key, $args = array(), $post_id = null ) {
		$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

		$args = wp_parse_args( $args, array(
			'type' => 'text',
		) );

		// Image
		if ( in_array( $args['type'], array( 'image' ) ) ) {
			if ( isset( $args['single'] ) && $args['single'] == "false" ) {
				// Gallery
				$temp          = array();
				$data          = array();
				$attachment_id = get_post_meta( $post_id, $key, false );
				if ( ! $attachment_id ) {
					return $data;
				}

				if ( empty( $attachment_id ) ) {
					return $data;
				}
				foreach ( $attachment_id as $k => $v ) {
					$image_attributes = wp_get_attachment_image_src( $v, $args['size'] );
					$temp['url']      = $image_attributes[0];
					$data[]           = $temp;
				}

				return $data;
			} else {
				// Single Image
				$attachment_id    = get_post_meta( $post_id, $key, true );
				$image_attributes = wp_get_attachment_image_src( $attachment_id, $args['size'] );

				return $image_attributes;
			}
		}

		return get_post_meta( $post_id, $key, $args );
	}
}

/**
 * Get page id by path. If not found return false.
 *
 * @since 0.5.0
 *
 * @param $page_slug
 *
 * @return bool|int
 */
if ( ! function_exists( 'thim_get_page_id_by_path' ) ) {
	function thim_get_page_id_by_path( $page_slug ) {
		$page = get_page_by_path( $page_slug );

		if ( $page ) {
			return $page->ID;
		}

		return false;
	}
}

/**
 * Add log.
 *
 * @since 0.8.3
 *
 * @param $message
 * @param string $handle
 * @param bool $clear
 */
if ( ! function_exists( 'thim_add_log' ) ) {
	function thim_add_log( $message, $handle = 'log', $clear = false ) {
		if ( ! TP::is_debug() ) {
			return;
		}

		if ( version_compare( phpversion(), '5.6', '<' ) ) {
			return;
		}

		$thim_log = Thim_Logger::instance();
		@$thim_log->add( $message, $handle, $clear );
	}
}

/**
 * let_to_num function.
 *
 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
 *
 * @since 1.1.1
 *
 * @param $size
 *
 * @return int
 */
function thim_core_let_to_num( $size ) {
	$l   = substr( $size, - 1 );
	$ret = substr( $size, 0, - 1 );
	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}

	return $ret;
}
