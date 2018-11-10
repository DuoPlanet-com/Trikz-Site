<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 16:10
 */


if ($user = Users::CurrentUser()) {

    ?>
    <p>You are logged in!</p>
    <form action="checkout.php?product=donation" method="post">
        <input type="number" name="donationAmount">
        <input type="submit">
    </form>


    <?php


} else {
    ?>

    <p>You are not logged in, you cannot give me money :(</p>
    <?php
}

?>

