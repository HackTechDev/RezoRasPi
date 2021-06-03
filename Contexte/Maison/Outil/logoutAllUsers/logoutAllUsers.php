<?php

function getAllConnectedUsers($ipRaspi) {
    $output = shell_exec('ssh pi@' . $ipRaspi . ' who');
    $result = explode(PHP_EOL,$output);
    return $result;
}

function logoutUser($user, $ipRaspi) {
    $output = shell_exec('ssh ' . $user . '@' . $ipRaspi .' "pkill -9 -f lxsession"');
    return $output;
}


function getAllConnectedRaspi() {
    $output = shell_exec('cat /var/lib/misc/dnsmasq.leases');
    $result = explode(PHP_EOL,$output);
    return $result;
}

function connect2IpRaspi4Logout($ipRaspi) {
    $getAllConnectedUsers = getAllConnectedUsers($ipRaspi);

    foreach($getAllConnectedUsers as $userInfo) {
        $userArr = explode(" ", $userInfo);
        if ($userArr[0] == "pi" || $userArr[0] == "") {
            continue;
        } else {
            $user = $userArr[0];
            echo "User: " . $user;
            logoutUser($user, $ipRaspi);
            echo "\n";
        }
    }
}

$getAllConnectedRaspi = getAllConnectedRaspi();

foreach ($getAllConnectedRaspi as $connectedRaspiInfo) {
    $connectedRaspiInfoArr = explode(" ", $connectedRaspiInfo);
    if ($connectedRaspiInfoArr[0] == "") {
        continue;
    } else {
        $ipRaspi = $connectedRaspiInfoArr[2];
        echo "@ip: " . $ipRaspi . "\n";
        connect2IpRaspi4Logout($ipRaspi);
    }
}

?>
