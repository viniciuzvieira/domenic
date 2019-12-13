<?php
if ( ! class_exists( 'THIM_Meta_Box' ) ) {
	/**
	 * Thim Theme
	 *
	 * Manage meta box in the THIM Framework
	 *
	 * @class THIM_Meta_Box
	 * @package thimpress
	 * @since 1.0
	 * @author kien16
	 */
	class THIM_Meta_Box {
		/**
		 * @var array Meta box information
		 */
		public $meta_box;

		// Safe to start up
		public function __construct( $args ) {

			// Assign meta box values to local variables and add it's missed values
			$this->meta_box = $args;

			// Add meta boxes on the 'add_meta_boxes' hook.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			// Save post meta
			add_action( 'save_post', array( $this, 'save_data' ) );

			// Enqueue common styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			/**********************/
			// Attach images via Ajax
			add_action( 'wp_ajax_thim_attach_media', array( $this, 'wp_ajax_attach_media' ) );

			// Edit images via Ajax
			add_action( 'wp_ajax_thim_edit_media', array( $this, 'wp_ajax_edit_media' ) );

			// Reorder images via Ajax
			add_action( 'wp_ajax_thim_reorder_images', array( $this, 'wp_ajax_reorder_images' ) );

			// Delete file via Ajax
			add_action( 'wp_ajax_thim_delete_file', array( $this, 'wp_ajax_delete_file' ) );

			/********************/
			// Attach images Video via Ajax
			add_action( 'wp_ajax_thim_attach_image_video', array( $this, 'wp_ajax_attach_image_video' ) );

			// Edit images via Ajax
			add_action( 'wp_ajax_thim_edit_image_video', array( $this, 'wp_ajax_edit_image_video' ) );

			// Reorder images via Ajax
			add_action( 'wp_ajax_thim_reorder_image_video', array( $this, 'wp_ajax_reorder_image_video' ) );

			// Delete file via Ajax
			add_action( 'wp_ajax_thim_delete_image_video', array( $this, 'wp_ajax_delete_image_video' ) );


		}

		/**
		 * Enqueue common styles
		 *
		 * @return void
		 */
		function admin_enqueue_scripts() {
			wp_enqueue_style( 'thim-meta-box', CORE_PLUGIN_URL . '/libraries/meta-boxes/css/meta-box.css', array(), "" );
		}

		/**
		 * Add meta box for multiple post types
		 *
		 * @return void
		 */
		public function add_meta_boxes() {
			// Use nonce for verification
			// create a custom nonce for submit verification later
			foreach ( $this->meta_box['pages'] as $page ) {
				add_meta_box(
					$this->meta_box['id'],
					$this->meta_box['title'],
					array( $this, 'meta_boxes_callback' ),
					$page,
					isset( $this->meta_box['context'] ) ? $this->meta_box['context'] : 'normal',
					isset( $this->meta_box['priority'] ) ? $this->meta_box['priority'] : 'default',
					$this->meta_box['fields']
				);
			}
		}

		// Callback function, uses helper function to print each meta box
		public function meta_boxes_callback( $post, $fields ) {
			// create a custom nonce for submit verification later
			echo '<input type="hidden" name="thim_meta_box_nonce" value="', wp_create_nonce( basename( __FILE__ ) ), '" />';

			foreach ( $fields['args'] as $field ) {
				switch ( $field['type'] ) {
					case 'textfield':
						$this->textfield( $field, $post->ID );
						break;
					case 'textarea':
						$this->textarea( $field, $post->ID );
						break;
					case 'checkbox':
						$this->checkbox( $field, $post->ID );
						break;
					case 'select':
						$this->select( $field, $post->ID );
						break;
					case 'image':
						$this->image( $field, $post->ID );
						break;
					case 'image_video':
						$this->image_video( $field, $post->ID );
						break;

				}
			}
		}

		private function image_video( $field, $post_id ) {
			// Make sure scripts for new media uploader in WordPress 3.5 is enqueued
			wp_enqueue_media();
//			wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array('jquery'));
			wp_enqueue_script( 'jquery-ui-dialog' );

			// A style available in WP
			wp_enqueue_style( 'wp-jquery-ui-dialog', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css', array() );

			wp_enqueue_style( 'thim-image-video', CORE_PLUGIN_URL . '/libraries/meta-boxes/css/image-video.css', array(), "" );
			wp_enqueue_script( 'thim-image-video-js', CORE_PLUGIN_URL . '/libraries/meta-boxes/js/image-video.js', array( 'jquery-ui-sortable' ), "", true );


			$images = get_post_meta( $post_id, $field['id'], false );

			$reorder_nonce = wp_create_nonce( "thim-reorder-images_{$field['id']}" );
			$delete_nonce  = wp_create_nonce( "thim-delete-file_{$field['id']}" );

			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';
			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';

			echo '<div class="thim-input">';
			echo '<ul class="thim-images-video thim-image-video-uploaded ui-sortable" data-field_id="' . $field['id'] . '" data-reorder_nonce="' . $reorder_nonce . '" data-delete_nonce="' . $delete_nonce . '">';
			foreach ( $images as $image ) {
				$img_url = wp_get_attachment_image_src( $image, 'thumbnail' );
				$link    = get_edit_post_link( $image );

				if ( substr( $image, 0, 2 ) == "v." ) {
					echo '
                            <li id="item_' . $image . '">
                                <iframe src="http://player.vimeo.com/video/%s?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="150" height="150" frameborder="0"></iframe>
                                <div class="thim-image-bar">
                                    <a title="Edit" class="thim-edit-file-k" href="#" target="_blank">Edit</a> |
                                    <a title="Delete" class="thim-delete-file-k" href="#" data-attachment_id="' . $image . '">x</a>
                                </div>
                            </li>
                        ';

				} else if ( substr( $image, 0, 2 ) == "y." ) {
					echo '
                        <li id="item_' . $image . '">
                            <iframe title="YouTube video player" class="youtube-player" type="text/html" width="150" height="150" src="http://www.youtube.com/embed/%s" frameborder="0"></iframe>
                            <div class="thim-image-bar">
                                <a title="Edit" class="thim-edit-file-k" href="#" target="_blank">Edit</a> |
                                <a title="Delete" class="thim-delete-file-k" href="#" data-attachment_id="' . $image . '">x</a>
                            </div>
                        </li>
                    ';
				} else {
					echo '
                    <li id="item_' . $image . '">
                        <img src="' . $img_url[0] . '" />
                        <div class="thim-image-bar">
                            <a title="Edit" class="thim-edit-file" href="' . $link . '" target="_blank">Edit</a> |
                            <a title="Delete" class="thim-delete-file" href="#" data-attachment_id="' . $image . '">×</a>
                        </div>
                    </li>
                    ';

				}
			}
			echo '</ul>';
			$attach_nonce = wp_create_nonce( "thim-attach-media_" . $field['id'] );
			echo '<a href="#" class="button thim-image-video-advanced-upload hide-if-no-js new-files" data-attach_media_nonce="' . $attach_nonce . '">Select or Upload Images</a><a href="javascript:void(null);" class="button-primary thim-video-advanced-upload-k" data-attach_media_nonce=' . $attach_nonce . '>Add Video</a>';

			echo '
                <div id="dialog-k" title="Insert Video Code">

                <div class="thim-label">
                    <label for="project_video_type">Video</label>
                </div>
                <div class="thim-input">
                    <select class="thim-select" name="thim-video-type-k" id="thim-video-type-k" size="0"><option value="youtube" selected="selected">Youtube</option><option value="vimeo">Vimeo</option></select>
                </div>

                <div class="thim-label">
                    <label for="project_video_embed">Video URL or own Embedd Code<br>(Audio Embedd Code is possible, too)</label>
                </div>
                <div class="thim-input">
                    <textarea class="thim-textarea large-text" name="thim-video-data-k" id="thim-video-data-k" cols="40" rows="8" placeholder=""></textarea>
                    <p id="nnn" class="description">Just paste the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>GUEZCxBcM78</strong>) you want to show, or insert own Embed Code. <br>This will show the Video <strong>INSTEAD</strong> of the Image Slider.<br><strong>Of course you can also insert your Audio Embedd Code!</strong><br><br><strong>Notice:</strong> The Preview Image will be the Image set as Featured Image..</p>
                </div>

                </div>';

			echo '<div class="desc">' . $field['desc'] . '</div>';
			echo '</div>';

			echo '</div>';
		}

		private function textarea( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . ': </label></div>';

			echo '<div class="thim-input">';
			echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '">' . $post_meta . '</textarea>';
			echo '<div class="desc">' . $field['desc'] . '</div>';
			echo '</div>';

			echo '</div>';
		}

		private function textfield( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}

			printf(
				'<div class="thim-field%s"><div class="thim-label"><label>%s: </label></div> <div class="thim-input"><input type="text" name="%s" value="%s" /> <div class="desc">%s</div></div></div>',
				$extra_class,
				$field['name'],
				$field['id'],
				$post_meta,
				$field['desc']
			);
		}

		private function checkbox( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';
			?>

            <div class="thim-input"><input type="checkbox"
                                           name="<?php echo $field['id']; ?>" <?php checked( $post_meta, 'on' ); ?> />
                <div class="desc"><?php echo $field['desc']; ?></div>
            </div>
            </div>
			<?php
		}

		private function select( $field, $post_id ) {
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			$post_meta = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';

			echo '<div class="thim-input"><select name="' . $field['id'] . '" id="' . $field['id'] . '">';

			foreach ( $field['options'] as $key => $value ) {
				echo '<option ' . ( ( $key == $post_meta ) ? ' selected ' : '' ) . ' value="' . $key . '">' . $value . '</option>';
			}
			echo '</select><div class="desc">' . ( isset( $field['desc'] ) ? $field['desc'] : "" ) . '</div></div></div>';
		}

		private function image( $field, $post_id ) {
			// Make sure scripts for new media uploader in WordPress 3.5 is enqueued
			wp_enqueue_media();

			wp_enqueue_style( 'thim-image', CORE_PLUGIN_URL . '/libraries/meta-boxes/css/image.css', array(), "" );
			wp_enqueue_script( 'thim-image-js', CORE_PLUGIN_URL . '/libraries/meta-boxes/js/image.js', array( 'jquery-ui-sortable' ), "", true );


			$images = get_post_meta( $post_id, $field['id'], false );

			$reorder_nonce = wp_create_nonce( "thim-reorder-images_{$field['id']}" );
			$delete_nonce  = wp_create_nonce( "thim-delete-file_{$field['id']}" );
			$post_meta     = get_post_meta( $post_id, $field['id'], true );
			if ( isset( $field['class'] ) ) {
				$extra_class = " " . $field['class'];
			} else {
				$extra_class = "";
			}
			echo '<div class="thim-field' . $extra_class . '">';

			echo '<div class="thim-label"><label>' . $field['name'] . '</label></div>';

			echo '<div class="thim-input">';
			echo '<ul class="thim-images thim-uploaded ui-sortable" data-field_id="' . $field['id'] . '" data-reorder_nonce="' . $reorder_nonce . '" data-delete_nonce="' . $delete_nonce . '">';
			foreach ( $images as $image ) {
				$img_url = wp_get_attachment_image_src( $image, 'thumbnail' );
				$link    = get_edit_post_link( $image );
				echo '
                    <li id="item_' . $image . '">
                        <img src="' . $img_url[0] . '" />
                        <div class="thim-image-bar">
                            <a title="Edit" class="thim-edit-file" href="' . $link . '" target="_blank">Edit</a> |
                            <a title="Delete" class="thim-delete-file" href="#" data-attachment_id="' . $image . '">×</a>
                        </div>
                    </li>
                ';
			}
			echo '</ul>';
			$attach_nonce = wp_create_nonce( "thim-attach-media_" . $field['id'] );
			echo '<a href="#" class="button thim-image-advanced-upload hide-if-no-js new-files" data-attach_media_nonce="' . $attach_nonce . '">Select or Upload Images</a>';
			echo '<div class="desc">' . $field['desc'] . '</div>';
			echo '</div>';

			echo '</div>';
		}

		/**
		 * Ajax callback for attaching media to field
		 *
		 * @return void
		 */
		static function wp_ajax_attach_media() {
			$post_id        = is_numeric( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
			$field_id       = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_ids = isset( $_POST['attachment_ids'] ) ? $_POST['attachment_ids'] : array();

			check_ajax_referer( "thim-attach-media_{$field_id}" );
			$html = "";
			foreach ( $attachment_ids as $attachment_id ) {
				$img_url = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
				$link    = get_edit_post_link( $attachment_id );
				$html    .= '<li id="item_' . $attachment_id . '">
                            <img src="' . $img_url[0] . '">
                                <div class="thim-image-bar">
                                    <a title="Edit" class="thim-edit-file" href="' . $link . '" target="_blank">Edit</a> |
                                    <a title="Delete" class="thim-delete-file" href="#" data-attachment_id="' . $attachment_id . '">×</a>
                            </div>
                        </li>';

				add_post_meta( $post_id, $field_id, $attachment_id, false );

			}
			wp_send_json_success( $html );
		}

		/**
		 * Ajax callback for attaching media to field
		 *
		 * @return void
		 */
		static function wp_ajax_edit_media() {
			$post_id        = is_numeric( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
			$field_id       = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_ids = isset( $_POST['attachment_ids'] ) ? $_POST['attachment_ids'] : array();
			$attachment_old = isset( $_POST['attachment_old'] ) ? $_POST['attachment_old'] : 0;

			check_ajax_referer( "thim-attach-media_{$field_id}" );
			foreach ( $attachment_ids as $attachment_id ) {
				update_post_meta( $post_id, $field_id, $attachment_id, $attachment_old );
			}
			wp_send_json_success();
		}

		/**
		 * Ajax callback for reordering images
		 *
		 * @return void
		 */
		static function wp_ajax_reorder_images() {
			$field_id = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$order    = isset( $_POST['order'] ) ? $_POST['order'] : 0;
			$post_id  = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;

			check_ajax_referer( "thim-reorder-images_{$field_id}" );

			parse_str( $order, $items );

			delete_post_meta( $post_id, $field_id );
			foreach ( $items['item'] as $item ) {
				add_post_meta( $post_id, $field_id, $item, false );
			}
			wp_send_json_success();
		}

		/**
		 * Ajax callback for deleting files.
		 * Modified from a function
		 *
		 * @return void
		 */
		static function wp_ajax_delete_file() {
			$post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
			$field_id      = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_id = isset( $_POST['attachment_id'] ) ? $_POST['attachment_id'] : 0; //change
			$force_delete  = isset( $_POST['force_delete'] ) ? intval( $_POST['force_delete'] ) : 0;

			check_ajax_referer( "thim-delete-file_{$field_id}" );

			delete_post_meta( $post_id, $field_id, $attachment_id );
			$ok = $force_delete ? wp_delete_attachment( $attachment_id ) : true;

			if ( $ok ) {
				wp_send_json_success();
			} else {
				wp_send_json_error( __( 'Error: Cannot delete file', 'tp-portfolio' ) );
			}
		}

		/******************************/
		/**
		 * Ajax callback for attaching media to field
		 *
		 * @return void
		 */
		static function wp_ajax_attach_image_video() {
			$post_id        = is_numeric( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
			$field_id       = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_ids = isset( $_POST['attachment_ids'] ) ? $_POST['attachment_ids'] : array();

			check_ajax_referer( "thim-attach-media_{$field_id}" );
			$html = "";
			foreach ( $attachment_ids as $attachment_id ) {
				$img_url = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
				$link    = get_edit_post_link( $attachment_id );
				$html    .= '<li id="item_' . $attachment_id . '">
                            <img src="' . $img_url[0] . '">
                                <div class="thim-image-bar">
                                    <a title="Edit" class="thim-edit-file" href="' . $link . '" target="_blank">Edit</a> |
                                    <a title="Delete" class="thim-delete-file" href="#" data-attachment_id="' . $attachment_id . '">×</a>
                            </div>
                        </li>';

				add_post_meta( $post_id, $field_id, $attachment_id, false );

			}
			wp_send_json_success( $html );
		}

		/**
		 * Ajax callback for attaching media to field
		 *
		 * @return void
		 */
		static function wp_ajax_edit_image_video() {
			$post_id        = is_numeric( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
			$field_id       = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_ids = isset( $_POST['attachment_ids'] ) ? $_POST['attachment_ids'] : array();
			$attachment_old = isset( $_POST['attachment_old'] ) ? $_POST['attachment_old'] : 0;

			check_ajax_referer( "thim-attach-media_{$field_id}" );
			foreach ( $attachment_ids as $attachment_id ) {
				update_post_meta( $post_id, $field_id, $attachment_id, $attachment_old );
				//add_post_meta( $post_id, $field_id, $attachment_id, false );
			}
			wp_send_json_success();
		}

		/**
		 * Ajax callback for reordering images
		 *
		 * @return void
		 */
		static function wp_ajax_reorder_image_video() {
			$field_id = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$order    = isset( $_POST['order'] ) ? $_POST['order'] : 0;
			$post_id  = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;

			check_ajax_referer( "thim-reorder-images_{$field_id}" );

			parse_str( $order, $items );

			delete_post_meta( $post_id, $field_id );
			foreach ( $items['item'] as $item ) {
				add_post_meta( $post_id, $field_id, $item, false );
			}
			wp_send_json_success();
		}

		/**
		 * Ajax callback for deleting files.
		 * Modified from a function
		 *
		 * @return void
		 */
		static function wp_ajax_delete_image_video() {
			$post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
			$field_id      = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_id = isset( $_POST['attachment_id'] ) ? $_POST['attachment_id'] : 0; //change
			$force_delete  = isset( $_POST['force_delete'] ) ? intval( $_POST['force_delete'] ) : 0;

			check_ajax_referer( "thim-delete-file_{$field_id}" );

			delete_post_meta( $post_id, $field_id, $attachment_id );
			$ok = $force_delete ? wp_delete_attachment( $attachment_id ) : true;

			if ( $ok ) {
				wp_send_json_success();
			} else {
				wp_send_json_error( __( 'Error: Cannot delete file', 'tp-portfolio' ) );
			}
		}

		// Save data from meta box
		public function save_data( $post_id ) {
			// verify nonce
			if ( ! isset( $_POST['thim_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['thim_meta_box_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// check permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			foreach ( $this->meta_box['fields'] as $field ) {
				$old = get_post_meta( $post_id, $field['id'], true );
				$new = $_POST[ $field['id'] ];
				if ( $new && $new != $old ) {
					update_post_meta( $post_id, $field['id'], $new );
				}
			}
		}
	}
}