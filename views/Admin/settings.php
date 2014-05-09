<div class="wrap">
    <h2>Settings</h2>

    <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>
    <?php } ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'cb-settings' ); do_settings_sections( 'cb-settings' ); ?>
        <div id="tabs">
            <ul>
                <li><a href="#main">API Settings</a></li>
                <li><a href="#pages">Pages</a></li>
                <li><a href="#styles">Styles</a></li>
            </ul>

            <table class="form-table" id="main">
                <tr valign="top">
                    <th scope="row"><label for="cb_cb_username">Email</label></th>
                    <td><input type="text" name="cb_cb_username" id="cb_cb_username" class="regular-text" value="<?php echo get_option('cb_cb_username'); ?>" />
                        <p class="description">Your email which you used for login in ClassByte</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="cb_cb_api">API Key</label></th>
                    <td><input type="text" name="cb_cb_api" id="cb_cb_api" class="regular-text" value="<?php echo get_option('cb_cb_api'); ?>" />
                    <p class="description">Your API key provided in ClassByte Admin</p>
                    </td>
                </tr>
            </table>

            <table class="form-table" id="pages">
                <tr><td>Coming soon</td></tr>
            </table>

            <table class="form-table" id="styles">
                <tr><td>Coming soon</td></tr>
            </table>
        </div>
        <?php submit_button(); ?>
    </form>
</div>