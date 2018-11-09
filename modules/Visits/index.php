<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09-11-2018
 * Time: 01:04
 */

$sql = "SELECT * FROM `visits` ORDER BY id DESC LIMIT 10";
$sql2 = "SELECT address FROM `visits`";
if (!$query = Database::Query($sql)) {
    die("Couldnt query visitors.");
}




?>
<div class="row">
    <div class="col-md-9">
        <div style="background: lightgrey;border-radius: 5px;padding: 5px 5px 0px 5px">
            <h3>Recent Visits</h3>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Name</th>
                    <th>Page</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($query as $item) {
                    $visitId = $item['id'];
                    if ($item['steamid'] != "N/A") {
                        $steamUser = new SteamUser($item['steamid']);
                        $imgUrl = $visitName->avatar;
                        $visitName =  "<img src='$imgUrl' style='border-radius: 50%' > ". $steamUser->personaName;
                        $profileUrl = $steamUser->profileUrl;
                    } else {
                        $profileUrl = null;
                        $visitName = "N/A";
                    }
                    $pageVisited = $item['page'];
                    $address = $item['address'];
                    $visitTime = $item['timestamp'];
                    $visitType = $item['type'];
                    echo "<tr>";
                    echo "<th>$visitId</th>";
                    if (isset($profileUrl)) {
                        echo "<td><a href='$profileUrl'> $visitName</a></td>";
                    } else {
                        echo "<td>$visitName</td>";
                    }
                    echo "<td>$pageVisited</td>";
                    echo "<td>$visitType</td>";
                    echo "<td>$address</td>";
                    echo "<td>$visitTime</td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background: lightgrey;border-radius: 5px;padding: 5px 5px 0px 5px">
            <h3>Unique visits</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Address</th>
                    <th>Visits</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!$query2 = Database::Query($sql2)) {
                    die("Couldnt query visitors.");
                }
                $array = [];
                foreach ($query2 as $item) {
                    if (isset($array[$item['address']])) {
                        $array[$item['address']]++;
                    } else {
                        $array[$item['address']] = 1;
                    }
                }
                foreach ($array as $key => $value) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    echo "<td>$value</td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>

        </div>
    </div>
</div>