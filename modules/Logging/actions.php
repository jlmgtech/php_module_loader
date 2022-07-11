<?php

// TODO -- put a syntax error in here to see that it doesn't interfere with stuff.
add_action("Billing_other_links", function() {
    echo "Billing_other_links being processed in Logging actions.php\n";
    //Logging::info("logging adding link to Billing_other_links");
    return "<a href='/cp/plugins/logging/'>Logging Settings</a>";
});
