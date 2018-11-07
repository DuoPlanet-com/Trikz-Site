<?php
// Check if logged in
if ($user = Users::CurrentUser()) {
    // If we've been logged in, check if the user is registered
    if (!Users::Registered($user->steamid64)) {
        // If we are not registered, register the user
        Users::Register($user->steamid64);
    }
    // Display the logout link
    echo '
    <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="padding: 6px 0 0 0" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .
                    "<img src = '$user->avatar'> " . $user->personaName
                . '</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="steamauth/logout.php">Logout</a>
                </div>
            </li>
    ';
} else {
    // If we are not logged in, display login button
    echo loginbutton("rectangle");
}
