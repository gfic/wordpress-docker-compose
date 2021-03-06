<div id="quickContact" class="quick-contact-window">
    <div class="quick-contact">
        <div class="qw-title">
           <?php 
            $qctitle=get_theme_mod('olam_footer_support_title');
            echo esc_html_e($qctitle); 
            ?>
            <span><i class="icon-sample icon-minus"></i></span>
        </div>
        <div class="quick-window">
            <div class="quickcontact-success">
            </div>
            <form method="POST" id="olam-quick-contact">
                <div class="input-wrap name-field"><div class="olam_name form-alert"></div><input name="qc-name" id="qc-name" type="text" placeholder="<?php esc_html_e('Name','olam'); ?>"></div>
                <div class="input-wrap email-field"><div class="olam_email form-alert"></div><input name="qc-email" id="qc-email" type="email" placeholder="<?php esc_html_e('Email','olam'); ?>"></div>
                <div class="input-wrap message-field"> <div class="olam_message form-alert"></div><textarea name="message" id="qc-message" rows="6" placeholder="<?php esc_html_e('Message','olam'); ?>"></textarea> </div>
                <input type="submit" value="<?php esc_html_e('Support','olam'); ?>">
            </form>
        </div>
    </div>
</div>