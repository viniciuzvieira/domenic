<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class Thim_Elementor_Extend_Icons {
	private static $instance = null;

	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'font_setup' ) );
	}

	public function font_setup() {
		wp_enqueue_style( 'thim-font-icon', THIM_URI . 'assets/css/thim-icons.css' );
		wp_enqueue_style( 'flaticon', THIM_URI . 'assets/css/flaticon.css' );
		wp_enqueue_style( 'font-pe-icon-7', THIM_URI . 'assets/css/font-pe-icon-7.css' );
	}

	public static function get_font_flaticon() {
		$flaticons = apply_filters( 'thim_list_flaticons', array(
			'flaticon-school-material'   => 'school-material',
			'flaticon-book'              => 'book',
			'flaticon-blackboard'        => 'blackboard',
			'flaticon-mortarboard'       => 'mortarboard',
			'flaticon-apple'             => 'apple',
			'flaticon-science-1'         => 'science-1',
			'flaticon-idea'              => 'idea',
			'flaticon-books-1'           => 'books-1',
			'flaticon-pencil-case'       => 'pencil-case',
			'flaticon-medal'             => 'medal',
			'flaticon-library'           => 'library',
			'flaticon-open-book'         => 'open-book',
			'flaticon-microscope-1'      => 'microscope-1',
			'flaticon-microscope'        => 'microscope',
			'flaticon-notebook'          => 'notebook',
			'flaticon-drawing'           => 'drawing',
			'flaticon-diploma'           => 'diploma',
			'flaticon-online'            => 'online',
			'flaticon-technology-2'      => 'technology-2',
			'flaticon-internet'          => 'internet',
			'flaticon-technology-1'      => 'technology-1',
			'flaticon-school'            => 'school',
			'flaticon-book-1'            => 'book-1',
			'flaticon-technology'        => 'technology',
			'flaticon-education'         => 'education',
			'flaticon-homework'          => 'homework',
			'flaticon-code'              => 'code',
			'flaticon-login'             => 'login',
			'flaticon-notes'             => 'notes',
			'flaticon-learning-2'        => 'learning-2',
			'flaticon-search'            => 'search',
			'flaticon-learning-1'        => 'learning-1',
			'flaticon-statistics'        => 'statistics',
			'flaticon-test'              => 'test',
			'flaticon-learning'          => 'learning',
			'flaticon-study'             => 'study',
			'flaticon-basketball-player' => 'basketball-player',
			'flaticon-biology'           => 'biology',
			'flaticon-students'          => 'students',
			'flaticon-diploma-1'         => 'diploma-1',
			'flaticon-books'             => 'books',
			'flaticon-networking'        => 'networking',
			'flaticon-teacher'           => 'teacher',
			'flaticon-graduate'          => 'graduate',
			'flaticon-reading'           => 'reading',
			'flaticon-online-learning'   => 'online-learning',
			'flaticon-innovation'        => 'innovation',
			'flaticon-research'          => 'research',
			'flaticon-geography'         => 'geography',
			'flaticon-science'           => 'science'
		) );

		return $flaticons;
	}

	public static function get_font_7_stroke() {
		$strokeicons = apply_filters( 'thim_list_stroke_icon', array(
			'pe-7s-album' => 'album',
			'pe-7s-arc'   => 'arc',
		) );

		return $strokeicons;
	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

Thim_Elementor_Extend_Icons::get_instance();