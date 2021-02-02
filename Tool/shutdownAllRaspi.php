<?php

/*
 * One-liner to shutdown remote host 
 * https://linuxcommando.blogspot.com/2013/04/one-liner-to-shutdown-remote-host.html
 */

function shutdownRaspi($ip) {
    $adminUser = "pi";
    $shutdownCommand = exec("ssh -t $adminUser@" . $ip ." 'sudo shutdown -h now'", $output, $result);

    return $result; 
}

/*
 * Get all Raspberry Pi connected to the local network
 * https://serverfault.com/questions/786136/how-to-view-dnsmasq-client-mac-addresses-dynamically
 */

function getAllRaspi() {
    $getAllRaspiCommand = exec("cat /var/lib/misc/dnsmasq.leases", $allRaspiArr);

    return $allRaspiArr;
}


/*
 * Process shutdown of all Raspi
 */

function shutdownAllRaspi() {
    $allRaspiArr = getAllRaspi();

    foreach($allRaspiArr as $raspi) {
        $data = explode(" ", $raspi);
        $ip = $data[2];
        echo "Shutdown: " . $ip . " : " . shutdownRaspi($ip) . "\n";
    }
}


/*
 * Main
 */

shutdownAllRaspi();
?>
