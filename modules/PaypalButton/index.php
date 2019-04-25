<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 13:07
 */

// Check if a user is logged in. And if so, store the user as variable.
if ($user = Users::CurrentUser()) {

    ?>
    <p>You are logged in! <a href="checkout.php?product=VIP">BUY NOW!</a></p>


    <?php


} else {
?>

    <p>You are not logged in, you cannot give me money :(</p>
    <?php
}

?>

