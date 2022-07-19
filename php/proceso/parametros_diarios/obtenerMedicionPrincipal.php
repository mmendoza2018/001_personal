<?php
function getMedicionPrincipal ($tipoMedicion,$medicionDigital,$medicionAnalogico,$medicionKilometraje)
{
    $ultimaMedicionPrincipal = 0;
    //asignamos la medicion con la que trabajara
    if ($tipoMedicion=="Horometro digital") {
        $ultimaMedicionPrincipal = $medicionDigital;
    }else if ($tipoMedicion=="Horometro analogico") {
        $ultimaMedicionPrincipal = $medicionAnalogico;
    }else{
        $ultimaMedicionPrincipal = $medicionKilometraje;
    }
    return $ultimaMedicionPrincipal;
}

