<?php
$user  = wp_get_current_user();
$email = $user->user_email;
?>

<h3 class="title">Send feedback<span class="close"></span></h3>

<div class="main">
    <form class="info">
        <div class="description">
            <p>Thim Core lets you send suggestions about our products. We welcome problem reports, feature ideas and general comments.</p>
        </div>

        <label class="content">
            <textarea class="widefat" name="content" rows="5" title="content" placeholder="Describe your issue or share your ideas"></textarea>
        </label>

        <label class="email">
            <span>We send response with this email</span>
            <input class="widefat" name="email" type="email" value="<?php echo esc_attr( $email ); ?>" title="email">
        </label>

        <label class="developer-access">
            <input type="checkbox" name="developer-access" title="developer-access" value="no">
            <span>Include developer access</span>
        </label>
    </form>
</div>

<div class="footer">
    <button class="btn-send button button-primary tc-button">Send</button>

    <div class="messages">
        <span class="content">Thank you for the feedback.</span>
    </div>
</div>
