<?php
require_once("../conexion.php");
require_once("parametros_diarios/alerta_mantenimiento.php");
require_once("../general/ultimoId.php");
$idEquipo = @$_POST["idEquipo"];
$idContratoEquipo = @$_POST["idContratoEquipo"];
$codigo = "";
$horoKiloEquipo = "";
$descripcionEquipo = "";
$contador = 0;
$ultimaMedicion = 0;
$medicionSiguiente = 0;
$inicioParDiario = false;
$mantenimientoActual = 0;
$htmlEquipoUnion="";


$listaMantenimientosEquipos = mysqli_query($conexion, "SELECT * FROM equipo_mantenimiento em INNER JOIN tiempo_mantenimiento tm ON tm.TIMA_id=em.TIMA_id01 WHERE em.EQU_id01='$idEquipo' AND EQMA_estado=1 ORDER BY TIMA_tiempo;");

$ultimoParametro = mysqli_query($conexion, "SELECT PADI_medicion_digital,EQCO_tipo_medicion,PADI_medicion_analogico,PADI_medicion_kilometraje,PADI_medicion_cambio,PADI_man_siguiente,EQU_codigo,FAM_descripcion,FAM_id,EQU_principal,EQU_union FROM parametros_diarios pd right JOIN equipos_contrato ec ON pd.EQCO_id01=ec.EQCO_id INNER JOIN equipos e ON e.EQU_id=ec.EQU_id01 INNER JOIN familias f ON f.FAM_id=e.FAM_id01  WHERE EQU_id01='$idEquipo' ORDER BY PADI_id DESC LIMIT 1");
foreach ($ultimoParametro as $k) {
    $ultimaMedicionDigital = $k["PADI_medicion_digital"];
    $tipoMedicion = $k["EQCO_tipo_medicion"];
    $ultimaMedicionAnalogico = $k["PADI_medicion_analogico"];
    $ultimaMedicionKilometraje = $k["PADI_medicion_kilometraje"];
    $medicionSiguiente = $k["PADI_medicion_cambio"];
    $mantenimientoSiguiente = $k["PADI_man_siguiente"];
    $descripcionEquipo = $k["EQU_codigo"] . " - " . $k["FAM_descripcion"];
    $idFamilia = $k["FAM_id"];
    $equipoPrincipal = $k["EQU_principal"];
    $equipoUnion = $k["EQU_union"];
}
$idEquipoUnion =$idEquipo;
if ($equipoPrincipal==0) {
    $idEquipoUnion = $equipoUnion;
}
$obtenerEquipoUnion = mysqli_query($conexion,"SELECT FAM_descripcion,EQU_modelo_motor,EQU_principal,EQU_id,EQU_placa FROM equipos e INNER JOIN familias f ON e.FAM_id01=f.FAM_id WHERE EQU_union='$idEquipoUnion' || EQU_id='$idEquipoUnion'");
if (mysqli_num_rows($obtenerEquipoUnion) > 1) {
    foreach ($obtenerEquipoUnion as $x) {
        if ($x["EQU_principal"] != $equipoPrincipal) {
            $descripcionEquipoUnion = $x["FAM_descripcion"]."-".$x["EQU_placa"];
            $htmlEquipoUnion = "<span class='badge bg-primary'>$descripcionEquipoUnion</span>";
            $idEquipoButton = $x["EQU_id"];
        }
    }
}
$ultimaMedicionPrincipal = getMedicionPrincipal ($tipoMedicion,$ultimaMedicionDigital,$ultimaMedicionAnalogico,$ultimaMedicionKilometraje);

//variables para mostrar u ocultar los iconos de alerta 
$alertaPersonalizada = alertaMantenimiento($conexion,$idFamilia,$idEquipo);


$consultaEquipos = "SELECT EQCO_id,EQU_codigo,EQU_id,CONTR_descripcion FROM equipos e INNER JOIN equipos_contrato ec ON ec.EQU_id01=e.EQU_id INNER JOIN contratos c ON ec.CONTR_id01=c.CONTR_id";

?>
<div>
    <h5>CONTROL EQUIPOS</h5>
</div>
<div class="container-fluid bg-white my-2 py-3" id="indexEquipos">
    <div class="row g-5">
        <div class="col-lg-4 shadow">
            <img src="https://cargonautas.com.co/wp-content/uploads/2021/04/12.jpg" class="img-fluid px-3" alt="">
            <div class="row py-2 justify-content-center">
                <div class="col-md-8">
                    <form id="formEquipoContrEsta">
                        <!--   <input type="text" class="form-control"  placeholder="" id=""
         value="<?php echo $codigo ?>">  -->
                        <input type="text" list="listaEquiposContrato" id="codigoEquipoEstadistica" autocomplete="off" placeholder="Codigo Equipo" class="form-control form-control-sm mb-2 listaEquiposContrato" id="equipoDoEqAct">
                        <datalist id="listaEquiposContrato">
                            <?php
                            foreach (mysqli_query($conexion,$consultaEquipos) as $x) : ?>
                                <option data-value="<?php echo $x["EQU_id"] ?>"><?php echo $x["EQU_codigo"] . " - " . $x["CONTR_descripcion"] ?>
                                </option>
                            <?php endforeach; ?>
                        </datalist>
                </div>
                </form>
                <div class="col-md-3">
                    <button class="btn btn-blue-gyt btn-sm" type="button" onclick="vistaEstadisticaEquipo('<?php echo $idContratoEquipo  ?>',null)">Buscar</button>
                </div>
            </div>
            <div class="row mt-2 gy-2">
                <div class="col-sm-6 ">
                <div class="dropdown w-100">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Lista OTS
                        </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a 
                            class="dropdown-item" 
                            data-idequipo="<?php echo $idEquipo ?>" 
                            onclick="verListaOtGeneral(this)" 
                            href="#">
                            Lista general
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" 
                            data-id_eqco="<?php echo $idContratoEquipo ?>" 
                            data-idequipo="<?php echo $idEquipo ?>"
                            onclick="verListaOTS(this,true)">
                            Lista con filtros
                            </a>
                        </li>
                    </ul>
                </div>
                </div>
            <div class="col-sm-6 d-grid">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalAddOt" data-eqcoaddot="<?php echo $idContratoEquipo ?>" data-idequipoie="<?php echo $idEquipo ?>">Agregar OT</button>
            </div>
        </div>
        <div class="row py-2">
            <div class="dropdown w-100">
                <button class="btn btn-light dropdown-toggle w-100 border border-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                Parametros Diarios
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a 
                            class="dropdown-item" 
                            data-bs-target="#modalListadoPDPorEquipo" 
                            data-bs-toggle="modal" 
                            data-idequipo="<?php echo $idEquipo ?>" 
                            onclick="mostrarTablaListaPdGeneral(this)" 
                            href="#">
                            Lista general
                            </a>
                        </li>
                        <li>
                            <a  data-bs-toggle="modal" 
                                data-bs-target="#modalListadoPDPorEquipo" 
                                class="dropdown-item" 
                                data-idequipo="<?php echo $idEquipo ?>" 
                                data-id_eqco="<?php echo $idContratoEquipo ?>" 
                                onclick="verListaParameDiarios(this,true)"
                                href="#">
                                Lista con filtros
                            </a>
                        </li>
                    </ul>
                </div>
        </div>
            <hr>
            <b>Alertas</b><span>(aparecen a los 100 y 50 antes del siguiente mntto)</span>
            <div class="card shadow rounded-pill px-3 my-2 <?php echo $alertaPersonalizada[0]['icono']==null ? "d-none" :"d-block"; ?>">
                <div class="row align-items-center">
                    <div class="col-10">
                        <small class="fw-bold">Comprar de repuestos</small>
                        <p class="mb-0"><small><?php echo $alertaPersonalizada[0]["descripcion"] ?></small></p>
                    </div>
                    <div class="col-2 ">
                        <a href="#" class="float-end ListaPopover">
                            <?php echo $alertaPersonalizada[0]["icono"] ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card shadow rounded-pill px-3 my-2 <?php echo $alertaPersonalizada[1]['icono']==null ? "d-none" :"d-block"; ?>">
                <div class="row align-items-center">
                    <div class="col-10">
                        <small class="fw-bold">La orden del mantenimiento siguiente fue creada</small>
                        <p class="mb-0"><small><?php echo $alertaPersonalizada[1]["descripcion"] ?></small></p>
                    </div>
                    <div class="col-2 d-flex justify-content-end"">
                        <?php echo $alertaPersonalizada[1]["icono"] ?>
                    </div>
                </div>
            </div>

            <!-- <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAddOt">Parametro diario</button> -->
        </div>  
        <div class="col-lg-8 position-relative pb-5 pb-md-0">
            <div class="row justify-content-between">
                <div class="col-sm-8">
                    <h6 class="fw-bold"><?php echo $descripcionEquipo ?> 
                    <a href="#" onclick="vistaEstadisticaEquipo('<?php echo $idContratoEquipo  ?>','<?php echo $idEquipoButton ?>')"><?php echo $htmlEquipoUnion ?></a>
                </h6>
                </div>
                <div class="col-sm-4 float-end">
                    <h6 class="fw-bold float-end"> HR/KL : <?php echo $ultimaMedicionPrincipal; ?></h6>
                </div>
            </div>
            <div class="row gy-2">
                <?php if(mysqli_num_rows($listaMantenimientosEquipos)>0) { ?>
                <?php foreach ($listaMantenimientosEquipos as $y) :
                    $habilitarActualizar = "true";
                    $equiMantenimiento = $y["EQMA_id"] . "|" . $y["EQMA_pdf"] . "|" . $habilitarActualizar;
                    #$mantenimientoActual,$y["TIMA_tiempo"];
                    $classMantenimiento = ($mantenimientoSiguiente == $y["TIMA_tiempo"]  && $inicioParDiario == false) ? "border-2 border-primary bg-primary-opacity" : "";
                 /*    echo $y["TIMA_tiempo"]; */
                ?>
                    <div class="col-sm-3 col-md-4 col-xl-3 col-lg-3">
                        <div class="card border <?php echo $classMantenimiento ?>">
                            <div class="card-body py-2 text-center">

                                <h3 class="fw-bold text-center d-block"><?php echo $y["TIMA_tiempo"] ?></h3>
                                <a href="#" class="btn btn-light shadow btn-sm rounded-pill py-0 mb-2" data-bs-toggle="modal" data-bs-target="#modalListadoTipoSistema" onclick="verListaTISIMantenimiento('<?php echo $equiMantenimiento ?>')">
                                    Mantenimientos
                                </a><br>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalPdfMantenimientoEquipo" onclick="verPdfEquiposCambio('<?php echo $y['EQMA_id'] ?>')" class="btn btn-light shadow btn-sm rounded-pill py-0">
                                    Ver PDF
                                </a><br>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php } else { ?>
                    <div class="col-sm-6 mx-auto shadow bg-light mt-5 text-center">
                        <h4 class="p-3">No hay Frecuencia configurada para el equipo</h4>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="btn btn-blue-gyt text-light position-absolute bottom-0" id="EquipoContrato" style="right:0">Regresar a la consola </button>
        </div>
    </div>
</div>