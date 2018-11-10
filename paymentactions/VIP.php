<?php
if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
    $id = $user->steamid;

    if (Users::IsVIP($user->steamid64)) {
        $days = (int)Settings::GetSettings()['products']['VIP']['days'];
        $expirationDate = Users::VIPExpirationDate($user->steamid64);
        $nextDate = new DateTime($expirationDate);
        $nextDate->add(new DateInterval('P' . $days . 'D'));
        $nextDateStamp = $nextDate->format("Y-m-d H:m:s");
        $sql = "UPDATE `vips` SET `timestamp_end` = '$nextDateStamp.000000' WHERE `steamid`='$id'";
        if ($transferral->Execute()) {
            if (Database::Query($sql)) {
                header("Location:index.php?ps=".$_GET['success']);
            } else {
                die("Failed to update user VIP date");
            }
        }
    } else {
        $sql = "SELECT * FROM vips WHERE steamid = '$id'";
        if (($query = Database::Query($sql))->num_rows == 0) {
            if ($transferral->Execute()) {
                if ($_GET['success'] == "true") {
                    $dateTime = new DateTime();
                    $days = (int) Settings::GetSettings()['products']['VIP']['days'];
                    $nextTime = new DateTime();
                    $nextTime->add(new DateInterval('P' . $days . 'D'));
                    $dateTimeStamp = $dateTime->format("Y-m-d H:m:s");
                    $nextTimeStamp = $nextTime->format("Y-m-d H:m:s");
                    $sid64 = $user->steamid64;
                    $sql = "INSERT INTO vips (steamid64, steamid,timestamp_start,timestamp_end) VALUES ('$sid64','$id','$dateTimeStamp.000000' ,'$nextTimeStamp.000000')";
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