<?php
    $severis = "localhost";
    $lietotajs = "grobina1_janeks";
    $parole = 'v3jO43PHnHet!'; 
    $datubaze = "grobina1_janeks";

    $savienojums = mysqli_connect($severis, $lietotajs, $parole, $datubaze);

    if (!$savienojums) {
        echo "<h1>FATAL ERROR: NEVAR SAVIENOTIES AR DATU BĀZI!</h1>"; 
        exit();
    } else {
        // SUCCESS CHECK - IF YOU SEE THIS, CONNECTION IS OK
        // Remove this line after testing!
        // echo "<h1>DEBUG: SAVIENOJUMS VEIKSMĪGS!</h1>"; 
    }
?>