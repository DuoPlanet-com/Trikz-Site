<h1>Hello, world!</h1>

<?php

// Check if logged in
if ($user = Users::CurrentUser()) {
    // If we've been logged in, check if the user is registered
    if (!Users::Registered($user->steamid64)) {
        // If we are not registered, register the user
        Users::Register($user->steamid64);
    }
    // Display the logout link
    echo '<a href="steamauth/logout.php">LOGOUT</a>';
} else {
    // If we are not logged in, display login button
    echo loginbutton("rectangle");
}

?>