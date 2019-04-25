<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 23:54
 */




?>
<div style="background: lightgrey;border-radius: 5px;padding: 5px 5px 0px 5px">
    <h3>Registered Users</h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">SteamId</th>
            <th scope="col">Steam Profile</th>
        </tr>
        </thead>
        <tbody>
        <?php


        $registeredUsers = Users::All(); // Grab all registered users as array of SteamUser objects.

        $i = 1; // For table.

        // loop through all users in db.
        foreach ($registeredUsers as $item){
            $img = $item->avatar; // The image of the steam profile.
            $name = $item->personaName; // The name of the steam profile.
            $steamid = $item->steamid; // The steamid of the steam profile
            $profileurl = $item->profileUrl; // A link to their profile.

            // Insert row into table.
            echo "<tr>";
                echo "<th>$i</th>"; // Table header.

                echo "<td><img style='border-radius: 50%' src='$img'> $name</td>";
                echo "<td>$steamid</td>";
                echo "<td><a href='$profileurl'>Visit steam profile</a></td>";
            echo "</tr>";
            $i++;
        }
        ?>

        </tbody>
    </table>
</div>