<?php

// Populate the dropdown list with existing users ordered by their Display Name
$dropdown_html = '<select required id="cv_user_select" name="cv[user_select]">
                            <option value="">' . __('Select a User', $this->plugin_text_domain) . '</option>';
$wp_users = get_users(array('order' => 'ASC', 'orderby' => 'display_name', 'fields' => array('id', 'display_name')));

foreach ($wp_users as $user) {
    $user_id = esc_html($user->id);
    $user_display_name = esc_html($user->display_name);

    $dropdown_html .= '<option value="' . $user_id . '">' . $user_display_name . '</option>' . "\n";
}
$dropdown_html .= '</select>';

// Generate a custom nonce value.
$cv_add_meta_nonce = wp_create_nonce('cv_add_user_meta_form_nonce');

// BUILD THE FORM
?>
    <div class="cv_add_user_meta_form">

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="cv_add_user_meta_form">
            <input type="hidden" name="action" value="cv_form_response">
            <input type="hidden" name="cv_add_user_meta_nonce" value="<?php echo $cv_add_meta_nonce ?>">
            <input type="hidden" name="step" value="get_user">

            <p> Select the user to add or edit <?php echo $dropdown_html; ?></p>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="Submit Form"></p>
        </form>
        <br/><br/>
        <div id="cv_form_feedback"></div>
        <br/><br/>
    </div>
<?php
