<?php
/**
 * Template for displaying the author of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$user_data = $course->get_author();
?>

<div class="course-author" itemscope itemtype="http://schema.org/Person">
    <?php //echo get_avatar( get_post_field( 'post_author', $course->get_id() ), 40 ); ?>
    <div class="author-contain">
        <label itemprop="jobTitle"><?php esc_html_e( 'Teacher', 'eduma' ); ?></label>

        <div class="value" itemprop="name">
            <a href="<?php echo esc_url( learn_press_user_profile_link( get_post_field( 'post_author', $course->get_id() ) ) ); ?>">
                <?php
                if( !empty( $user_data->get_data( 'display_name' ) ) ) {
                    $author_name = $user_data->get_data( 'display_name' );
                }else{
                    $author_name = $user_data->get_data( 'user_login' );
                }
                echo $author_name;
                ?>
            </a>
        </div>
    </div>
</div>