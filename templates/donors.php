<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 16:48
 */
?>

<?php Modules::Load("Navbar"); ?>

<main style="margin-top: 55px">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <?php Modules::Load("DonorList") ?>
        </div>
        <div class="col-md-2">

        </div>
    </div>
</main>

<?php Modules::Load("Footer") ?>
