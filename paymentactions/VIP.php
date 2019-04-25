<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 15:50
 */

if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
    $id = $user->steamid;

    if (Users::IsVIP($user->steamid64)) {
        $days = (int)Settings::GetSettings()['products']['VIP']['days'];
        $expirationDate = Users::VIPExpirationDate($user->steamid64);
        $nextDate = new DateTime($expirationDate);
        $nextDate->add(new DateInterval('P' . $days . 'D'));
        $nextDateStamp = $nextDate->format("Y-m-d H:m:s") . ".000000";
        $sql = "UPDATE `vips` SET `timestamp_end` = '$nextDateStamp' WHERE `steamid`='$id'";
        if ($transferral->Execute()) {
            if (Database::NonEscapedQuery($sql)) {
                header("Location:index.php?ps=".$_GET['success']);
            } else {
                die("Failed to update user VIP date");
            }
        }
    } else {
        $id = Database::Escaped($id);
        $sql = "SELECT * FROM vips WHERE steamid = '$id'";
        if (($query = Database::NonEscapedQuery($sql))->num_rows == 0) {
            if ($transferral->Execute()) {
                if ($_GET['success'] == "true") {
                    $dateTime = new DateTime();
                    $days = (int) Settings::GetSettings()['products']['VIP']['days'];
                    $nextTime = new DateTime();
                    $nextTime->add(new DateInterval('P' . $days . 'D'));
                    $dateTimeStamp = $dateTime->format("Y-m-d H:m:s") . ".000000";
                    $nextTimeStamp = $nextTime->format("Y-m-d H:m:s") . ".000000";
                    $sid64 = $user->steamid64;

                    $sql = "INSERT INTO vips (steamid64, steamid,timestamp_start,timestamp_end) VALUES ('$sid64','$id','$dateTimeStamp' ,'$nextTimeStamp')";
                    if (!DataBase::Query($sql)) {
                        $_GET['success'] = "false";
                    }
                    header("Location:index.php?ps=".$_GET['success']);
                }
            }
        } else {
            var_dump($query);
            header("Location:index.php?ps=false");
        }
    }
}

