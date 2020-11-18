<?php
if ( post_password_required() )
  return;
?>

<div id="comments" class="comments-area">


  <?php if ( !have_comments() && comments_open()) : ?>
    <h4>
      <?php
      _e( '<span class="badge bg-red">0</span> reaction', 'wpappninja');
      ?>
    </h4>
  <?php endif; // have_comments() ?>

  <?php if ( have_comments() ) : ?>
    <h4>
      <?php
      printf( _nx( '<span class="badge bg-red">1</span> reaction', '<span class="badge bg-wpappninja">%1$s</span> reactions', 'comments title', 'wpappninja' ), number_format_i18n( get_comments_number() ));
      ?>
    </h4>

    <?php
    $commentsArray = get_approved_comments(get_the_ID());
    $comments = null;

    foreach ($commentsArray as $e) {
      $comments .= '<article>';
      $comments .= '<div class="content-block-title" style="margin:0 0 5px;font-size:11px;"><date datetime="'.$e->comment_date.'">'.date_i18n(get_option('date_format'), strtotime($e->comment_date)).'</date></div>';
      $comments .= '<div class="chip bg-wpappninja"><div class="chip-media"><img src="'.wpappninja_get_gravatar($e->comment_author_email).'"></div><div class="chip-label" style="color:white">'.$e->comment_author.'</div></div>';
      $comments .= '<p>'.$e->comment_content.'</p>';
      $comments .= '</article>';
    }

    echo $comments; 

    ?>


    <?php if ( ! comments_open() && get_comments_number() ) : ?>
      <p class="no-comments"><?php _e( 'Comments are closed.' , 'wpappninja' ); ?></p>
    <?php endif; ?>

  <?php endif; // have_comments() ?>

  <?php
  $fields =  array(

  'author' =>
  '<div class="item-inner">
  <div class="item-title label">' . __( 'Name', 'wpappninja' ) . '</div>
  <div class="item-input">
    <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' />
  </div>

</div>',

'email' =>
'<div class="item-inner">
<div class="item-title label">' . __( 'Email', 'wpappninja' ) . '</div>
<div class="item-input">
  <input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
  '" size="30"' . $aria_req . ' />
</div>

</div>',

'url' =>
'<div class="item-inner">
<div class="item-title label">' . __( 'Website', 'wpappninja' ) . '</div>
<div class="item-input">
  <input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
  '" size="30" />
</div>

</div>',
);


  $args = $args = array(
    'class_submit' => 'button',

    'format'            => 'xhtml',

    'title_reply'=>'',

    'comment_field'=>'<div class="item-inner">
    <div class="item-title label">' . __( 'Comment', 'wpappninja' ) . '</div>
    <div class="item-input">
      <textarea id="comment" name="comment" aria-required="true"></textarea>
    </div>

  </div>',

    'must_log_in' => '<p class="must-log-in">' .
    sprintf(
      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
      ) . '</p>',

    'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
      __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
      admin_url( 'profile.php' ),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
      ) . '</p>',

    'comment_notes_before' => '',

    'comment_notes_after' => '',

    'fields' => apply_filters( 'comment_form_default_fields', $fields ),
    );
    ?>
    <div class="list-block">
      <?php

      comment_form($args); ?>
    </div>
</div><!-- #comments -->