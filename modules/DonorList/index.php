<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 16:44
 */

$sql = "SELECT * FROM `donors` ORDER BY `amount` DESC";
if (!$query = Database::Query($sql)) {
    die("Failed to query donor table");
}

?>

<div style="background: lightgrey;border-radius: 5px;padding: 5px 5px 0px 5px">
    <h3>Donations</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Persona name</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $i = 1;
        foreach ($query as $item) {
            $steamid64 = $item['steamid64'];
            $steamUser = new SteamUser($steamid64);
            $personaName = $steamUser->personaName;
            $imgUrl = $steamUser->avatar;
            $amount = $item['amount'];
            $profileUrl = $steamUser->profileUrl;
            echo "<tr>";
            echo "<td><a href='$profileUrl'><img style='border-radius: 50%' src='$imgUrl'></a> <a href='$profileUrl'>$personaName</a></td>";
            echo "<td>$$amount</td>";
            echo "</tr>";
            $i++;
        }
        ?>

        </tbody>
    </table>
</div>
