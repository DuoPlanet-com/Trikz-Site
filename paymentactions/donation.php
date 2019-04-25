<?php
if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
    $steamid64 = $user->steamid64;
    if ($transferral->Execute()) {
        $sql= "SELECT * FROM `donors` WHERE `steamid64` = $steamid64";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 1) {
                $row = $query->fetch_assoc();
                $newAmount = $transferral->Price() + $row['amount'];
                $sql = "UPDATE `donors` SET `amount`='$newAmount' WHERE `steamid64` = $steamid64";
                if (!$query = Database::Query($sql)) {
                    die("Could not query database");
                }
            } else {
                $price = $transferral->Price();
                $price = Database::Escaped($price);
                $sql = "INSERT INTO `donors` (`steamid64`,`amount`) VALUES ('$steamid64','$price')";
                if (!$query = Database::Query($sql)) {
                    die("Could not query database");
                }
            }
        } else {
            die("Could not query database");
        }
        header("Location: index.php?ps=true");
    }
}