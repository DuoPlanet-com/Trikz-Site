<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09-11-2018
 * Time: 11:17
 */

$sql = "SELECT * FROM vips";

if (!$query = Database::Query($sql)) {
    die("Unable to fetch vips");
}





?>

<div style="background: lightgrey;border-radius: 5px;padding: 5px 5px 0px 5px">
    <h3>VIPs</h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Persona name</th>
            <th>SteamID</th>
            <th>Start date</th>
            <th>End date</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $i = 1;
        foreach ($query as $item) {
            $steamid = $item['steamid'];
            $date_start = $item['timestamp_start'];
            $date_end = $item['timestamp_end'];
            $steamUser = new SteamUser($item['steamid64']);
            $personaName = $steamUser->personaName;
            $imgUrl = $steamUser->avatar;
            $profileUrl = $steamUser->profileUrl;
            echo "<tr>";
            echo "<th>$i</th>";
            echo "<td><a href='$profileUrl'><img style='border-radius: 50%' src='$imgUrl'> $personaName</a></td>";
            echo "<td>$steamid</td>";
            echo "<td>$date_start</td>";
            echo "<td>$date_end</td>";
            echo "</tr>";
            $i++;
        }
        ?>

        </tbody>
    </table>
</div>
