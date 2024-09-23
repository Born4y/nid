<?php

if ($_SERVER["submit"] == "POST") {  
    
    $nid = $_POST["nid"];
    $dob = $_POST["dob"];

    $url = "https://api.versionx10.xyz/SV/LoL5.php?Nid=$nid&Dob=$dob";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // It's recommended to enable SSL verification for security.
    // Uncomment the following lines if your server supports SSL.
    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $resp = curl_exec($curl);

    if ($resp === false) {
        // Handle curl error
        echo 'Curl error: ' . curl_error($curl);
    } else {
        echo $resp;
    }

    curl_close($curl);
}

?>