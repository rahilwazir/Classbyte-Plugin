<?php
/**
 * Class Schedule Login
 */
?>
<?php if (isset($message) && is_string($message)) { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<form class="reg-page hidden" id="cb_forms-only-ajax" method="post" name="cb_login_form">
    <div class="reg-header">
        <h2>Student Login</h2>
        <p>To create an account, <a href="#register" data-switch-form="cb_registration_form">click here</a>.</p>
    </div>

    <div class="form-group">
        <label for="cb_login_email">Email</label>
        <input type="email" id="cb_login_email" name="cb_login_email" value="" class="form-control">
    </div>
    <div class="form-group">
        <label for="cb_login_password">Password</label>
        <input type="password" id="cb_login_password" name="cb_login_password" value="" autocomplete="off" class="form-control">
    </div>
    <hr>
    <input type="hidden" name="_cb_nonce" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>">
    <input type="submit" class="pull-right btn" value="Login">
</form>