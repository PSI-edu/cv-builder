<?php

/**
 * The form to be loaded on the plugin's admin page
 */

if( current_user_can( 'edit_users' ) ) {

    // CREATE STUFF THAT WILL GO IN THE FORM ---------------------------------------------------------------------------

    // What should I be doing?
    GLOBAL $step;

    if (!isset($step)) { //---------------------- STEP 0: GET USER -----------------------------------------------------
        require_once(__DIR__ . '/add_user_step0.php');
    }
    elseif ($step === "1a") {
        require_once (__DIR__."/add_user_step1a.php");
    }
    elseif ($step === "1b") {
        require_once (__DIR__."/add_user_step1b.php");
    }
    else {
        echo "next step";
    }
}
else {
    ?>
    <p> <?php __("You are not authorized to perform this operation.", $this->plugin_name) ?> </p>
    <?php
}