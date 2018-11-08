<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 13:07
 */


if ($user = Users::CurrentUser()) {

    ?>
    <p>You are logged in! <a href="checkout.php">BUY NOW!</a></p>


    <?php


} else {
?>

    <p>You are not logged in, you cannot give me money :(</p>
    <?php
}

?>

