<?php
    $severis = "localhost";
    $lietotajs = "grobina1_janeks";
    $parole = 'v3jO43PHnHet!'; 
    $datubaze = "grobina1_janeks";

    $savienojums = mysqli_connect($severis, $lietotajs, $parole, $datubaze);

    if (!$savienojums) {
        echo "Nav izveidots savienojums ar DB";
        exit();
    }
?>