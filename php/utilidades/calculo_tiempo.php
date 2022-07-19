<?php 

//$hoy = date("Y-m-d");
date_default_timezone_set("America/Lima");
$hoy = new DateTime("now");
function calculoTiempo($df)
{
    $str = '';
    if ($df->m > 0) {
        // meses
        $str .= ($df->m > 1) ? $df->m . ' Meses ' : $df->m . ' Mes ';
    }
    if (($df->d)> 0) {
        // dias
        $str .= ($df->d > 1) ? $df->d . ' Dias ' :  $df->d . ' Dia ';
    }
    if($str=="" && $df->invert == 0){
        return "Mañana";
    }
    if($str=="" && $df->invert == 1){
        return "Hoy";
    }
    echo $str .= ($df->invert == 1) ? ' de retraso' : ' restantes';
    
}

require_once("conexion.php");


function calculoFechaPersonalizado($fechaVencimiento,$regular,$malo){
    $hoy = new DateTime("now");
    $diff = $hoy->diff($fechaVencimiento);
    /* echo $diff->days . ' days '; */
    if($diff->invert== 1){
        return "text-danger";
    }
    if ($diff->days>= $regular) {
        return "text-success";
    }
    if($diff->days>= $malo){
        return "text-warning";
    }
    if($diff->days< $malo){
        return "text-danger";
    }
    
}

// alertas con dias mess y año

/* function get_format_c($df)
{
    $str = '';
    // años
    if ($df->y > 0) {
      
        $str .= ($df->y > 1) ? $df->y . ' años ' : $df->y . ' año ';
    }
    if ($df->m > 0) {
        // meses
        $str .= ($df->m > 1) ? $df->m . ' Meses ' : $df->m . ' Mes ';
    }
    if (($df->d)> 0) {
        // dias
        $str .= ($df->d > 1) ? $df->d . ' Dias ' :  $df->d . ' Dia ';
    }
    if($str=="" && $df->invert == 0){
        return "Mañana";
    }
    if($str=="" && $df->invert == 1){
        return "Hoy";
    }
    echo $str .= ($df->invert == 1) ? ' de retraso' : ' restantes';
    
}
 */
?>