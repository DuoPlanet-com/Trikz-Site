<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09-11-2018
 * Time: 11:17
 */

// Find VIPs from db.
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

        // For table
        $i = 1;

        // loop through each VIP
        foreach ($query as $item) {
            $steamid = $item['steamid']; // Their steamid
            $date_start = $item['timestamp_start']; // When the vip was purchased.
            $date_end = $item['timestamp_end']; // Then the VIP expires. Note that if the user buys VIP again. This will be extended.
            $steamUser = new SteamUser($item['steamid64']); // A SteamUser object, based on the VIP's steamid.
            $personaName = $steamUser->personaName; // Current steam name of VIP.
            $imgUrl = $steamUser->avatar; // The profile image of VIP.
            $profileUrl = $steamUser->profileUrl; // A link to their profile.

            // Insert row into table.
            echo "<tr>";
                echo "<th>$i</th>"; // Header

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
