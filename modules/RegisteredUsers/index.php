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


        $registeredUsers = Users::All();
        $i = 1;
        foreach ($registeredUsers as $item){
            $img = $item->avatar;
            $name = $item->personaName;
            $steamid = $item->steamid;
            $profileurl = $item->profileUrl;
            echo "<tr>";
            echo "<th>$i</th>";
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