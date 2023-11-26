<?php
    function compararUbicaciones($a, $b) {
        
        if ($a['ubicacion'] == 'DATO NO DISPONIBLE' && $b['ubicacion'] == 'DATO NO DISPONIBLE') {
            return 0;
        } elseif ($a['ubicacion'] == 'DATO NO DISPONIBLE') {
            return 1; 
        } elseif ($b['ubicacion'] == 'DATO NO DISPONIBLE') {
            return -1;
        }
        
        preg_match('/P(\d+)/', $a['ubicacion'], $matchesA);
        preg_match('/P(\d+)/', $b['ubicacion'], $matchesB);

        $numP_A = isset($matchesA[1]) ? intval($matchesA[1]) : 0;
        $numP_B = isset($matchesB[1]) ? intval($matchesB[1]) : 0;

        if ($numP_A < $numP_B) {
            return -1;
        } elseif ($numP_A > $numP_B) {
            return 1;
        } else {
            preg_match('/B(\d+)/', $a['ubicacion'], $matchesA);
            preg_match('/B(\d+)/', $b['ubicacion'], $matchesB);

            $numB_A = isset($matchesA[1]) ? intval($matchesA[1]) : 0;
            $numB_B = isset($matchesB[1]) ? intval($matchesB[1]) : 0;

            if ($numB_A < $numB_B) {
                return -1;
            } elseif ($numB_A > $numB_B) {
                return 1;
            } else {

                preg_match('/A(\d+)/', $a['ubicacion'], $matchesA);
                preg_match('/A(\d+)/', $b['ubicacion'], $matchesB);

                $numA_A = isset($matchesA[1]) ? intval($matchesA[1]) : 0;
                $numA_B = isset($matchesB[1]) ? intval($matchesB[1]) : 0;

                if ($numA_A < $numA_B) {
                    return -1;
                } elseif ($numA_A > $numA_B) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }


?>