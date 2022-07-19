<?php
require_once("../php/conexion.php");
//require_once("../php/calculo_tiempo.php");
$equiposAct = "SELECT EQU_codigo,FAM_descripcion,FAM_id,EQU_placa,EQU_modelo_motor FROM equipos e INNER JOIN familias fa ON  fa.FAM_id = e.FAM_id01 WHERE EQU_estado=1";
$ResEquiposAct = mysqli_query($conexion, $equiposAct);
$clientes = mysqli_query($conexion, "SELECT * FROM clientes WHERE CLIE_estado=1");
$proyectos = mysqli_query($conexion, "SELECT PROY_id,PROY_descripcion FROM proyectos WHERE PROY_estado!='CIERRE Y FINALIZACION'");
$famEquipos = mysqli_query($conexion, "SELECT * FROM familias WHERE FAM_estado=1");
$famEquipoSecundario = mysqli_query($conexion, "SELECT * FROM familias WHERE FAM_estado=1 AND FAM_super_estructura=1");
$marcaEquipos = mysqli_query($conexion, "SELECT * FROM marcas WHERE MAR_estado=1");
$propietarioEquipos = mysqli_query($conexion, "SELECT * FROM propietarios WHERE PROP_estado=1");
$modeloEquipos = mysqli_query($conexion, "SELECT * FROM modelos WHERE MOD_estado=1");
$consultaDocEquipos =  "SELECT * FROM documento_equipos de INNER JOIN tipo_doc_equipos te ON de.TIDO_id01=te.TIDO_id 
                                                     INNER JOIN equipos e ON de.EQU_id01=e.EQU_id  WHERE DOEQ_estado = 1";
$docEquipos = mysqli_query($conexion, $consultaDocEquipos);
$sistemaFamilias = mysqli_query($conexion, "SELECT TISI_descripcion,TISI_id FROM tipo_sistemas WHERE TISI_estado=1");
$tipoMedicion = mysqli_query($conexion, "SELECT * FROM tipo_medicion WHERE TIME_estado = 1");
$trabajadores = mysqli_query($conexion, "SELECT TRAB_id,TRAB_nombres FROM trabajadores WHERE CAR_id01=3");
$supervisores = mysqli_query($conexion, "SELECT TRAB_id,TRAB_nombres FROM trabajadores WHERE CAR_id01=1");
$operadores = mysqli_query($conexion, "SELECT TRAB_id,TRAB_nombres,TRAB_dni FROM trabajadores WHERE CAR_id01=5");
$cargos = mysqli_query($conexion, "SELECT CAR_descripcion,CAR_id FROM cargos WHERE CAR_estado=1");

?>
<!-- inicio lista de datos por tabla (actualiza en cada insercion)-->
<div id="llegaListaTrabajadoresGeneral"></div>
<div id="llegaListaOperadoresGeneral"></div>
<div id="llegaListaPlannersGeneral"></div>

<!-- fin lista de datos por tabla -->
<!-- Modal actualiza marcas -->
<div class="modal fade" id="modalMarcaAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar marca</h5>
      </div>
      <div class="modal-body">
        <form id="formMarcaAct">
          <input type="text" name="idAct" hidden id="idMarAct">
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionMarAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoMarAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-blue-gyt" onclick="actualizaMarca()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agrega equipos -->
<div class="modal fade" id="modalAgregaEqu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header py-1">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Agregar equipo</h5>
      </div>
      <div class="modal-body">
        <form id="formEquipo">
          <div class="row">
            <div class="col-lg-2 col-sm-6 col-md-6">
              <label class="mb-1">Codigo</label>
              <input type="text" class="form-control form-control-sm mb-2" readonly data-validate name="codigo" id="codigoEquipo1">
              <input type="text" hidden id="correlativoequipo1">
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label class="mb-1">Equipo</label>
              <select class="form-select form-select-sm" data-validate disabled id="familiaEquipo1" data-id>
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($famEquipos as $x) : ?>
                  <option data-inicial="<?php echo $x["FAM_codigo"] ?>" value="<?php echo $x["FAM_id"] ?>"><?php echo $x["FAM_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
              <input type="hidden" name="descripcion" id="inputEquipo1" data-id>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Placa/serie</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="placa">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Marca</label>
              <select class="form-select form-select-sm" data-validate name="marca">
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($marcaEquipos as $x) : ?>
                  <option value="<?php echo $x["MAR_id"] ?>"><?php echo $x["MAR_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Modelo</label>
              <select class="form-select form-select-sm" data-validate name="modelo">
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($modeloEquipos as $x) : ?>
                  <option value="<?php echo $x["MOD_id"] ?>"><?php echo $x["MOD_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Marca de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="marcaMotor">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Modelo de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="modeloMotor">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Número de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="numeroMotor">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Año de fabricación</label>
              <input type="number" class="form-control form-control-sm mb-2" step="false" data-validate name="fabricacion">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Capacidad</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="capacidad">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Número de serie del chasis</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="serieChasis">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Propietario</label>
              <select class="form-select form-select-sm" data-validate name="propietario" id="propietarioEquipo1">
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($propietarioEquipos as $x) : ?>
                  <option value="<?php echo $x["PROP_id"] ?>"><?php echo $x["PROP_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">T. medición</label>
              <select class="form-select form-select-sm mb-2" name="tMedicion" data-validate>
                <option value="" selected disabled>Seleccione una opción</option>
                <option value="HOROMETRO">HOROMETRO</option>
                <option value="KILOMETRAJE">KILOMETRAJE</option>
              </select>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Centro de costo</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="centroCosto">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Fecha ingreso</label>
              <input type="date" class="form-control form-control-sm mb-2" data-validate name="fechaIngreso">
            </div>
          </div>
        </form>
        <div>

        </div>
        <div class="d-flex align-items-center">
          <a class="text-secondary" type="button" data-bs-toggle="collapse" id="linkNuevoEquipo" data-bs-target="" aria-expanded="false" aria-controls="collapseExample">
            agregar grua secundaria
          </a>
          <input class="form-check-input mx-2" type="checkbox" value="" id="checkEquipo" onclick="activarLinkEquipo(this)">

          <a class="text-warning tootipFormEquipo" data-bs-toggle="tooltip" data-bs-placement="right" title="El formulario 2 solo se enviara si esta marcada esta casilla, asegurese de hacerlo si enviara dos formularios!">
            <i class="fas fa-exclamation-circle"></i>
          </a>
        </div>
        <div class="collapse" id="collapseExample">
          <div class="card card-body py-1 mt-1">
            <form id="formEquipo2">
              <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Codigo</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="codigo" readonly id="codigoEquipo2">
                  <input type="text" hidden id="correlativoequipo2">
                  <input type="text" hidden name="segundoEquipo" value="true">
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Equipo</label>
                  <select class="form-select form-select-sm" name="descripcion" data-validate id="familiaEquipo2">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($famEquipoSecundario as $x) : ?>
                      <option data-inicial="<?php echo $x["FAM_codigo"] ?>" value="<?php echo $x["FAM_id"] ?>"><?php echo $x["FAM_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                  <!-- <input type="hidden" name="descripcion" id="inputEquipo2" data-id/> -->
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Placa/serie</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="placa">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Marca de grua </label>
                  <select class="form-select form-select-sm" data-validate name="marca">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($marcaEquipos as $x) : ?>
                      <option value="<?php echo $x["MAR_id"] ?>"><?php echo $x["MAR_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Marca de motor</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="marcaMotor">
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Modelo de motor</label>
                  <input type="text" name="modeloMotor" data-validate class="form-control form-control-sm mb-2">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Número de motor</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="numeroMotor">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Año de fabricación</label>
                  <input type="number" class="form-control form-control-sm mb-2" step="false" data-validate name="fabricacion">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Capacidad</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="capacidad">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">T. medición</label>
                  <select class="form-select form-select-sm mb-2" name="tMedicion" data-validate>
                    <option value="" selected disabled>Seleccione una opción</option>
                    <option value="HOROMETRO">HOROMETRO</option>
                    <option value="KILOMETRAJE">KILOMETRAJE</option>
                  </select>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <!-- <input type="hidden" id="propietarioEquipo2" data-validate  name="propietario"> -->
                  <select class="form-select form-select-sm d-none" data-validate name="propietario" id="propietarioEquipo2">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($propietarioEquipos as $x) : ?>
                      <option value="<?php echo $x["PROP_id"] ?>"><?php echo $x["PROP_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer py-1">
        <button type="button" class="btn btn-sm btn-secondary" onclick="limpiarFormulario('formEquipo')" data-bs-dismiss="modal">Cerrar</button>
        <button class="btn btn-blue-gyt btn-sm float-end" onclick="agregarEquipo(['formEquipo','formEquipo2'],'modalAgregaEqu')" type="button">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza modelos -->
<div class="modal fade" id="modalModeloAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar modelo</h5>
      </div>
      <div class="modal-body">
        <form id="formModeloAct">
          <input type="text" name="idAct" hidden id="idModAct">
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionModAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoModAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaModelo()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza familias -->
<div class="modal fade" id="modalFamiliaAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header py-1">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Familia</h5>
      </div>
      <div class="modal-body">
        <form id="formFamiliaAct">
          <input type="text" name="idAct" hidden id="idFamAct">
          <label> Codigo</label>
          <input type="text" name="codigoAct" class="form-control form-control-sm mb-2" id="codigoFamAct" data-validate>
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-select-sm mb-2" id="descripcionFamAct" data-validate>
          <label class="mb-1">A. compra de respuestos KM</label>
          <input type="number" class="form-control form-control-sm mb-2" id="alerta01FamActKM" data-validate name="alertaCompraRepuestoKM">
          <label class="mb-1">A. creación OT KM</label>
          <input type="number" class="form-control form-control-sm mb-2" id="alerta02FamActKM" data-validate name="alertaCreacionOtKM">
          <label class="mb-1">A. compra de respuestos HR</label>
          <input type="number" class="form-control form-control-sm mb-2" id="alerta01FamActHR" data-validate name="alertaCompraRepuestoHR">
          <label class="mb-1">A. creación OT HR</label>
          <input type="number" class="form-control form-control-sm mb-2" id="alerta02FamActHR" data-validate name="alertaCreacionOtHR">
          <div class="form-check my-2">
            <input class="form-check-input" type="checkbox" value="true" name="tipo" id="chekTipoFam">
            <label class="form-check-label" for="chekTipoFam">
              Super estructura o equipo secundario
            </label>
          </div>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoFamAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaFamilia()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza propietarios -->
<div class="modal fade" id="modalPropietarioAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar propietario</h5>
      </div>
      <div class="modal-body">
        <form id="formPropietarioAct">
          <input type="text" name="idAct" hidden id="idPropAct">
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionPropAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoPropAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaPropietario()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza proyectos -->
<div class="modal fade" id="modalProyectoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Proyecto</h5>
      </div>
      <div class="modal-body">
        <form id="formProyectoAct">
          <input type="text" name="idAct" hidden id="idProyAct">
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionProyAct" data-validate>
          <label>F. inicio</label>
          <input type="date" name="fInicioAct" class="form-control form-control-sm mb-2" id="fInicioActProyAct" data-validate>
          <label>F. cierre</label>
          <input type="date" name="fCierreAct" class="form-control form-control-sm mb-2" id="fCierreActProyAct">
          <label> Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoProyAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaProyecto()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza tipo sistemas -->
<div class="modal fade" id="modaltipoSistemaAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Tipo Sistemas</h5>
      </div>
      <div class="modal-body">
        <form id="formTipoSistemaAct">
          <input type="text" name="idAct" hidden id="idTisi">
          <label>Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionTisiAct" data-validate>
          <label>Abreviación</label>
          <input type="text" name="abreviacionAct" class="form-control form-control-sm mb-2" id="abreviacionTisiAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoTisiAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaTipoSistema()">Actualizar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal actualiza tipo ot -->
<div class="modal fade" id="modaltipoOtAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Tipo OT</h5>
      </div>
      <div class="modal-body">
        <form id="formTipoOtAct">
          <input type="text" name="idAct" hidden id="idOt">
          <label>Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionOtAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoOtAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaTipoOt()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza clientes -->
<div class="modal fade" id="modalClienteAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Cliente</h5>
      </div>
      <div class="modal-body">
        <form id="formClienteAct">
          <input type="text" name="idAct" hidden id="idCliAct">
          <label> RUC</label>
          <input type="text" name="rucAct" class="form-control form-control-sm mb-2" id="rucCliAct" data-validate>
          <label> Razon social</label>
          <input type="text" name="razonSocialAct" class="form-control form-control-sm mb-2" id="razonSocialCliAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoCliAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaCliente()">Actualizar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal actualiza trabajadores -->
<div class="modal fade" id="modalTrabajadoresAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Trabajador</h5>
      </div>
      <div class="modal-body">
        <form id="formTrabajadorAct">
          <input type="text" name="idAct" hidden id="idTrabAct">
          <label> DNI</label>
          <input type="text" name="dniAct" class="form-control form-control-sm mb-2" id="dniTrabAct" data-validate>
          <label>Nombres</label>
          <input type="text" name="nombresAct" class="form-control form-control-sm mb-2" id="nombresTrabAct" data-validate>
          <label>Cargos</label>
          <select class="form-select form-select-sm mb-2" data-validate name="cargoAct" id="cargoTrabAct">
            <option value="" selected disabled>Seleccione una opción</option>
            <?php foreach ($cargos as $x) : ?>
              <option value="<?php echo $x["CAR_id"] ?>"><?php echo $x["CAR_descripcion"] ?></option>
            <?php endforeach ?>
          </select>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoTrabAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaTrabajador()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza cargos -->
<div class="modal fade" id="modalCargoTrabajadorAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar cargo</h5>
      </div>
      <div class="modal-body">
        <form id="formCargoTrabajadorAct">
          <input type="text" name="idAct" hidden id="idCargoTrabAct">
          <label>Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionCargoTrabAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoCargoTrabAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaCargoTrabajador()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal ver detalle equipos -->
<div class="modal fade" id="modalDetalleEquipo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="equipoTiEqDet" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" id="modalDescSegundoEq">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row text-center justify-content-center accordion-itemalign-items-center">
          <div class="col mb-2 ">
            <div class="card shadow">
              <h6 class="modal-title mx-auto my-2" id="codigoEqDet"></h6>
              <hr class="mt-0">
              <div class="table-resposive">
                <table class="table table-borderless table-sm">
                  <tr>
                    <td> <span class="fw-bold">Placa / Serie</span></td>
                    <td id="placaEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Modelo</span></td>
                    <td id="modeloEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Marca</span></td>
                    <td id="marcaEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">N° de serie chasis</span></td>
                    <td id="chasisEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">A. de fabricacion</span></td>
                    <td id="fabricacionEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Capacidad</span></td>
                    <td id="capacidadEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Modelo del motor</span></td>
                    <td id="modeloMotorEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Marca de motor</span></td>
                    <td id="marcaMotorEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">N° de motor</span></td>
                    <td id="motorEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Centro de costo</span></td>
                    <td id="centroCostoEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">Tipo de medición</span></td>
                    <td id="medicionEqDet"></td>
                  </tr>
                  
                  <tr>
                    <td> <span class="fw-bold">F. de ingreso</span></td>
                    <td id="ingresoEqDet"></td>
                  </tr>
                  <tr>
                    <td> <span class="fw-bold">F. de salida</span></td>
                    <td id="salidaEqDet"></td>
                  </tr>
                  <tr>
                    <td colspan="2"> <a href="#" data-idequipo id="idEquipoVerDetayu" onclick="eventoVerPdf(this)"> Descargar ficha del equipo</a></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col">
            <div id="llegaDatosSegundoEquipo"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="quitarZindexModales('modalDetalleEquipo')" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza equipos -->
<div class="modal fade" id="modalEquipoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header py-1">
        <h5 class="modal-title mx-auto my-1" id="staticBackdropLabel">Editar Equipos</h5>
      </div>
      <div class="modal-body mx-3">
        <form id="formEquipoAct">
          <div class="row">
            <div class="col-lg-2 col-sm-6 col-md-6">
              <input type="text" name="idAct" hidden id="idEqAct">
              <label class="mb-1">Codigo</label>
              <input type="text" class="form-control form-control-sm mb-2" readonly data-validate name="codigoAct" id="codigoEqAct">

            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label class="mb-1">Equipo</label>
              <input class="form-control form-control-sm" readonly data-validate id="descFamilia">
              <input type="hidden" id="equipoTiEqAct" data-validate name="equipoAct" id="equipoTiEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Placa / serie</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="placaAct" id="placaEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Marca</label>
              <select class="form-select form-select-sm" data-validate name="marcaAct" id="marcaEqAct">
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($marcaEquipos as $x) : ?>
                  <option value="<?php echo $x["MAR_id"] ?>"><?php echo $x["MAR_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Modelo</label>
              <select class="form-select form-select-sm" name="modeloAct" id="modeloEqAct" data-validate>
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($modeloEquipos as $x) : ?>
                  <option value="<?php echo $x["MOD_id"] ?>"><?php echo $x["MOD_descripcion"] ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Marca de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="marcaMotorEqAct" id="marcaMotorEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Modelo de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="modeloMotorEqAct" id="modeloMotorEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Número de motor</label>
              <input type="text" class="form-control form-control-sm mb-2" name="numeroMotorEqAct" id="numeroMotorEqAct">
            </div>

          </div>
          <div class="row">
            <div class="col-lg-2 col-sm-6 col-md-6">
              <label class="mb-1">Año de fabricación</label>
              <input type="number" class="form-control form-control-sm mb-2" step="false" data-validate name="fabricacionAct" id="fabricacionEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Capacidad</label>
              <input type="text" class="form-control form-control-sm mb-2" data-validate name="capacidadAct" id="capacidadEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Número de serie del chasis</label>
              <input type="text" class="form-control form-control-sm mb-2" name="chasisAct" id="chasisEqAct">
            </div>
            <div class="col-lg-1 col-sm-6 col-md-6">
              <label class="mb-1">Propietario</label>
              <input class="form-control form-control-sm" type="text" readonly data-validate id="descPropietarioAct">
              <input type="hidden" data-validate name="propietarioAct" id="propietarioEqACt">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">T. medición</label>
              <select class="form-select form-select-sm mb-2" name="tMedicionAct" id="tMedicionEqAct" data-validate>
                <option value="" selected disabled>Seleccione una opción</option>
                <option value="HOROMETRO">HOROMETRO</option>
                <option value="KILOMETRAJE">KILOMETRAJE</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Fecha ingreso</label>
              <input type="date" class="form-control form-control-sm mb-2" name="ingresoAct" id="ingresoEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Centro de costo</label>
              <input type="text" class="form-control form-control-sm mb-2" id="centroCostoAct" data-validate name="centroCostoAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1"> Fecha de salida</label>
              <input type="date" name="salidaAct" class="form-control form-control-sm mb-2" id="salidaEqAct">
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
              <label class="mb-1">Estado</label>
              <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoEqAct" data-validate>
                <option value="1">Habilitado</option>
                <option value="0">inhabilitar</option>
              </select>
            </div>
          </div>
          <div id="llegadaDatosActEquipoSec">
            <hr>
            <div class="row">
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Codigo</label>
                <input type="text" class="form-control form-control-sm mb-2" id="codigoEqAct2" data-validate readonly>
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Equipo</label>
                <input type="text" class="form-control form-control-sm mb-2" readonly data-validate id="descFamilia2">
                <!-- <input type="hidden" name="descripcion" id="inputEquipo2" data-id/> -->
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Placa/serie</label>
                <input type="text" class="form-control form-control-sm mb-2" id="placaEqAct2" data-validate name="placaAct2">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Marca de grua</label>
                <select class="form-select form-select-sm" data-validate name="marcaAct2" id="marcaEqAct2">
                  <option value="" selected disabled>Seleccione una opción</option>
                  <?php foreach ($marcaEquipos as $x) : ?>
                    <option value="<?php echo $x["MAR_id"] ?>"><?php echo $x["MAR_descripcion"] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Marca de motor</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="marcaMotorEqAct2" id="marcaMotorEqAct2">
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                <label class="mb-1">Modelo de motor</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="modeloMotorAct2" id="modeloMotorAct2">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-sm-6 col-md-6">
                <label class="mb-1">Número de motor</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate name="numeroMotorEqAct2" id="numeroMotorEqAct2">
              </div>
              <div class="col-lg-3 col-sm-6 col-md-6">
                <label class="mb-1">Año de fabricación</label>
                <input type="number" class="form-control form-control-sm mb-2" id="fabricacionEqAct2" step="false" data-validate name="fabricacionAct2">
              </div>
              <div class="col-lg-3 col-sm-6 col-md-6">
                <label class="mb-1">Capacidad</label>
                <input type="text" class="form-control form-control-sm mb-2" data-validate id="capacidadEqAct2" name="capacidadAct2">
              </div>
              <div class="col-lg-3 col-sm-6 col-md-6">
                <label class="mb-1">T. medición</label>
                <select class="form-select form-select-sm mb-2" name="tMedicionAct2" id="tMedicionEqAct2" data-validate>
                  <option value="" selected disabled>Seleccione una opción</option>
                  <option value="HOROMETRO">HOROMETRO</option>
                  <option value="KILOMETRAJE">KILOMETRAJE</option>
                </select>
              </div>
            </div>
          </div>
        </form>
        <div class="d-flex align-items-center">
          <a class="text-blue-gyt" type="button" data-bs-toggle="collapse" id="linkNuevoEquipo2" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
            Agregar grua secundaria
          </a>
        </div>
        <div class="collapse" id="collapseExample2">
          <div class="card card-body py-1 mt-1">
            <form id="formEquipoAdd2">
              <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Codigo</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="codigo" readonly id="codigoEquipo3">
                  <input type="text" hidden name="segundoEquipo" value="true">
                  <input type="hidden" name="equipoPrincipal" id="equipoPrincipal" value="">
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Equipo</label>
                  <select class="form-select form-select-sm" name="descripcion" data-validate id="familiaEquipo3">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($famEquipoSecundario as $x) : ?>
                      <option data-inicial="<?php echo $x["FAM_codigo"] ?>" value="<?php echo $x["FAM_id"] ?>"><?php echo $x["FAM_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                  <!-- <input type="hidden" name="descripcion" id="inputEquipo2" data-id/> -->
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Placa/serie</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="placa">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Marca de grua</label>
                  <select class="form-select form-select-sm" data-validate name="marca">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($marcaEquipos as $x) : ?>
                      <option value="<?php echo $x["MAR_id"] ?>"><?php echo $x["MAR_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Marca de motor</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="marcaMotor">
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Modelo de motor</label>
                  <input type="text" class="form-control form-control-sm mb-2" name="modeloMotor">                  
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Número de motor</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="numeroMotor">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Año de fabricación</label>
                  <input type="number" class="form-control form-control-sm mb-2" step="false" data-validate name="fabricacion">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Capacidad</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate name="capacidad">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">T. medición</label>
                  <select class="form-select form-select-sm mb-2" name="tMedicion" data-validate>
                    <option value="" selected disabled>Seleccione una opción</option>
                    <option value="HOROMETRO">HOROMETRO</option>
                    <option value="KILOMETRAJE">KILOMETRAJE</option>
                  </select>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <!-- <input type="hidden" id="propietarioEquipo2" data-validate  name="propietario"> -->
                  <select class="d-none" data-validate name="propietario" id="propietarioEquipo3">
                    <?php foreach ($propietarioEquipos as $x) : ?>
                      <option value="<?php echo $x["PROP_id"] ?>"><?php echo $x["PROP_descripcion"] ?></option>
                    <?php endforeach ?>
                  </select>
                  <button class="btn btn-sm bg-blue-gyt text-light mt-2 mx-auto" type="button" onclick="agregarEquipo(['formEquipoAdd2'],'modalEquipoAct')">Agregar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaEquipos()">Actualizar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal alerta documentos equipos -->
<div class="modal fade" id="modaAlertaDocumentos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-red-gyt">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Alerta de documentos</h5>
      </div>
      <div class="modal-body">
        <form id="formEnvioAdjuntos">
          <div class="table-responsive">
            <table id="tabla_alerta_documentos_equipo" class="table table-striped">
              <thead>
                <tr>
                  <th>Codigo Equipo</th>
                  <th>Tipo documento</th>
                  <th>Descripción</th>
                  <th>F. ingreso</th>
                  <th>F. vencimiento</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $tipoAlertas = mysqli_query($conexion, "SELECT * FROM anticipacion_alertas WHERE ALE_seccion='documento_equipos'");
                foreach ($tipoAlertas as $x) {
                  $regular = $x["ALE_regular"];
                  $malo = $x["ALE_malo"];
                }
                $classCircle = "";
                foreach ($docEquipos as $x) :
                  $datosDocEquipo = $x["DOEQ_id"] . "|" . $x["EQU_codigo"] . "|" . $x["DOEQ_descripcion"] . "|" . $x["DOEQ_vencimiento"] . "|" . $x["TIDO_estado"];

                  $classCircle = calculoFechaPersonalizado(new DateTime($x["DOEQ_vencimiento"]), $regular, $malo);
                  if ($classCircle == "text-success") continue;  ?>
                  <tr>
                    <td><?php echo $x["EQU_codigo"] ?> </td>
                    <td><?php echo $x["TIDO_descripcion"] ?></td>
                    <td><?php echo $x["DOEQ_descripcion"] ?></td>
                    <td><?php echo $x["DOEQ_ingreso"] ?></td>
                    <td>
                      <?php if ($x["DOEQ_vencimiento"] == "0000-00-00") {
                        echo "";
                      } else { ?>
                        <i class="fas fa-circle <?php echo $classCircle ?>"></i>
                      <?php echo $x["DOEQ_vencimiento"];
                      } ?>
                    </td>
                    <td class="text-center"><a href="#" data-bs-toggle="modal" data-bs-target="#modalDocEquipo" onclick="verDocEquipo('<?php echo $x['DOEQ_id'] ?>')"><i class="fas fa-file-pdf text-dark"></i></a>
                      <a href="#" data-bs-toggle="modal" data-bs-target="#modalDocEquipoAct" onclick="llenarDatosDocEquipo('<?php echo $datosDocEquipo ?>')"><i class="fas fa-edit text-dark"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar alertas documentos equipos -->
<div class="modal fade" id="modalAlertaDocEquipo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Alertas</h5>
      </div>
      <div class="modal-body">
        <form id="formAlertaEquiposAct">
          <label>Cantidad de días alerta ambar</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-circle text-warning"></i></span>
            <input type="text" name="regular" class="form-control form-control-sm" id="alertaAmbar" aria-describedby="basic-addon1">
          </div>
          <label>Cantidad de días alerta roja</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-circle text-danger"></i></span>
            <input type="text" name="mala" class="form-control form-control-sm" id="alertaRoja" aria-describedby="basic-addon1">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaAlertaEquipos()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal lista imagenes equipos -->
<div class="modal fade" id="modalVerListaImagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body" id="llegaListaImagenes">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal lista documentos equipos -->
<div class="modal fade" id="modalVerListaDocumentos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body" id="llegaListaDocumentos">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="quitarZindexModales('modalVerListaDocumentos')" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal ver documentos -->
<div class="modal fade" id="modalDocEquipo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="float-end me-2 mt-2">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" id="llegaDocumentoEquipo">
      </div>
    </div>
  </div>
</div>
<!-- Modal envio de adjuntos -->
<div class="modal fade" id="modalEnviarAdjunto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header bg-red-gyt">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Envio de adjuntos por correo</h5>
      </div>
      <div class="modal-body">
        <form id="formEnvioAdjunto">
          <label>Equipos seleccionados</label>
          <div id="detalleEnvio" class="bg-gray-gyt p-2"></div>
          <label>Correo del remitente</label>
          <div class="input-group mb-1">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-at text-dark"></i></span>
            <input type="text" class="form-control form-control-sm" id="correoAsunto" aria-describedby="basic-addon1" placeholder="Administrador@gyt.com">
          </div>
          <label>Asunto o descripción</label>
          <textarea name="textarea" class="form-control" placeholder="..." id="asuntoEnvio" rows="3"></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" id="rutaEnvio" onclick="enviarDocEquipo()">Enviar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal envio de adjuntos multiples equipos-->
<div class="modal fade" id="modalEnviarAdjuntoMulti" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-red-gyt">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Envio de documentos multiples</h5>
      </div>
      <div class="modal-body">
        <form id="formEnvioAdjuntoMultiple">
          <label>Equipos seleccionados</label>
          <div id="llegadaListaDocumentosMulti" class="row"></div>
          <label>Correo del remitente</label>
          <div class="input-group mb-1">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-at text-dark"></i></span>
            <input type="text" class="form-control form-control-sm" id="correodocsMulti" aria-describedby="basic-addon1" placeholder="Administrador@gyt.com">
          </div>
          <label>Asunto o descripción</label>
          <textarea name="textarea" class="form-control" placeholder="..." id="asuntodocsMulti" rows="3"></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" id="rutaEnvio" onclick="enviarDocEquipoMulti()">Enviar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal ver imagenes -->
<div class="modal fade" id="modalImgEquipo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="float-end me-2 mt-2">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center" id="llegaImgEquipo">
      </div>
    </div>
  </div>
</div>

<!-- Modal ver detalle QR -->
<div class="modal fade" id="modalDetalleQr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="float-end me-2 mt-2">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center" id="llegaDetalleQr">
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar documentos -->
<div class="modal fade" id="modalDocEquipoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar documento Equipo</h5>
      </div>
      <div class="modal-body">
        <form id="formDocEquipoAct">
          <input type="text" name="idAct" hidden id="idDoEqAct">
          <label>Equipo</label>
          <input type="text" name="equipoAct" list="docEquipoAct" placeholder="Equipo" class="form-control form-control-sm mb-2" id="equipoDoEqAct" data-validate autocomplete="off">
          <datalist id="docEquipoAct">
            <?php
            foreach ($ResEquiposAct as $x) : ?>
              <option value="<?php echo $x["EQU_codigo"] ?>"><?php echo $x["FAM_descripcion"] ?></option>
            <?php endforeach; ?>
          </datalist>
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionDoEqAct" data-validate>
          <input type="file" class="form-control form-control-sm mb-2" name="archivoAct" id="archivoDoEquAct" accept="application/pdf">
          <label class="ps-0">Fecha de vencimiento</label>
          <input type="date" id="vencimientoDocEqAct" name="vencimientoAct" class="form-control form-control-sm mb-2" value="">
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoDoEqAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaDocEquipos()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar imagenes -->
<div class="modal fade" id="modalImgEquipoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar imagen</h5>
      </div>
      <div class="modal-body">
        <form id="formImgEquipoAct">
          <input type="text" name="idAct" hidden id="idImgEqAct">
          <label>Equipo</label>
          <input type="text" name="equipoAct" list="docEquipoAct" placeholder="Equipo" class="form-control form-control-sm mb-2" id="equipoImgEqAct" data-validate autocomplete="off">
          <datalist id="docEquipoAct">
            <?php
            foreach ($ResEquiposAct as $x) : ?>
              <option value="<?php echo $x["EQU_codigo"] ?>"><?php echo $x["FAM_descripcion"] ?></option>
            <?php endforeach; ?>
          </datalist>
          <label> Descripción</label>
          <input type="text" name="descripcionAct" class="form-control form-control-sm mb-2" id="descripcionImgEqAct" data-validate>
          <input type="file" class="form-control form-control-sm mb-2" name="archivoAct" id="archivoImgEquAct" accept="image/*">
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estadoAct" id="estadoImgEqAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaImgEquipos()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal lista de contratos -->
<div class="modal fade" id="modalListadoContrato" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body" id="llegaListadoContrato">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar contratos -->
<div class="modal fade" id="modalAgregaContrato" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Agregar contrato</h5>
      </div>
      <div class="modal-body">
        <form id="formAgregaContrato">
          <label>Proyecto</label>
          <input type="hidden" name="idProyecto" id="idProyectoAdd">
          <input type="text" readonly class="form-control form-control-sm mb-2" id="descProyectoAdd" data-validate>
          <label>Descripción</label>
          <input type="text" name="descripcion" class="form-control form-control-sm mb-2" data-validate>
          <label>Número contrato</label>
          <input type="text" name="contrato" class="form-control form-control-sm mb-2" data-validate>
          <label>Cliente</label>
          <input type="text" list="clienteAddContrato" class="form-control clienteAddContrato form-control-sm mb-2" data-validate autocomplete="off">
          <datalist class=" mb-2" id="clienteAddContrato" name="cliente" data-validate>
            <option value="" selected disabled>Seleccione una opción</option>
            <?php foreach ($clientes as $x) : ?>
              <option data-value="<?php echo $x["CLIE_id"] ?>"><?php echo $x["CLIE_razon_social"] . "-" . $x["CLIE_ruc"] ?></option>
            <?php endforeach; ?>
          </datalist>
          <label>Fecha de inicio</label>
          <input type="date" name="fechaInicio" class="form-control form-control-sm mb-2" data-validate>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="agregaContrato()">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar contratos -->
<div class="modal fade" id="modalActContrato" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Actualiza contrato</h5>
      </div>
      <div class="modal-body">
        <form id="formActContrato">
          <div class="row">
            <div class="col-sm-6">
              <label>Descripción</label>
              <input type="hidden" id="idConAct" name="idContrato">
              <input type="text" name="descripcion" class="form-control form-control-sm mb-2" id="descConAct" data-validate>
            </div>
            <div class="col-sm-6">
              <label>Número contrato</label>
              <input type="text" name="numero" class="form-control form-control-sm mb-2" id="numConAct" data-validate>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <input type="hidden" name="proyecto" id="proyConAct" data-validate>
              <!-- <label>Proyecto</label>
            <select class="form-select form-select-sm mb-2" name="proyecto" id="proyConAct">
            <option value="" selected disabled>Seleccione una opción</option>
              <?php foreach ($proyectos as $x) : ?>
                  <option value="<?php echo $x["PROY_id"] ?>"><?php echo $x["PROY_descripcion"] ?></option>
              <?php endforeach; ?>
            </select> -->
              <label>Cliente</label>
              <select class="form-select form-select-sm mb-2" name="cliente" id="cliConAct" data-validate>
                <option value="" selected disabled>Seleccione una opción</option>
                <?php foreach ($clientes as $x) : ?>
                  <option value="<?php echo $x["CLIE_id"] ?>"><?php echo $x["CLIE_razon_social"] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Fecha de inicio</label>
              <input type="date" name="fechaInicio" class="form-control form-control-sm mb-2" id="fechIConAct" data-validate>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label>Fecha de culminación</label>
              <input type="date" name="fechaFin" class="form-control form-control-sm mb-2" id="fechFConAct">
            </div>
            <div class="col-sm-6">
              <label>Estado</label>
              <select name="estado" class="form-select form-select-sm" id="estConAct">
                <option value="INICIO DE CONTRATO">INICIO DE CONTRATO</option>
                <option value="EN EJECUCIÓN">EN EJECUCIÓN</option>
                <option value="CIERRE Y FINALIZACIÓN">CIERRE Y FINALIZACIÓN</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizaContratos()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal lista de equipos -->
<div class="modal fade" id="modalListadoEquiposCon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" id="modalListaEEstilos">
    <div class="modal-content">
      <div class="modal-body" id="llegaListadoEquipos">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Agregar equipos -->
<div class="modal fade" id="modalAgregaEquipoCon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header py-1">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Agregar Equipos</h5>
      </div>
      <div class="modal-body">
        <form id="formAddEquipoCon">
          <label>Contrato</label>
          <input type="hidden" name="idContrato" id="idContratoAddEquipo">
          <input type="text" readonly class="form-control form-control-sm mb-2" id="contratoAddEquipo">
          <div class="row">
            <div class="col-4">
              <label class="mb-1">Equipos</label>
            </div>
            <div class="col-3">
              <label class="mb-1">F. ingreso</label>
            </div>
            <div class="col-4">
              <label class="mb-1">Tipo medición</label>
            </div>
          </div>
          <div class="row" data-clone id="inputCloneEQCO">
            <div class="col-4">
              <input type="text" list="listaEquipos" data-validate class="form-control form-control-sm listaEquipos" name="equipoCon[]" autocomplete="off">
              <!-- inner html  -->
              <div id="llegaListaEquiposHabiles"></div>
            </div>
            <div class="col-3">
              <input type="date" class="form-control form-control-sm" name="fechaIngreso[]" data-validate>
            </div>
            <div class="col-4">
              <select class="form-select form-select-sm" data-validate name="tipoMedicion[]">
                <option value="" selected disabled>Seleccione una opción</option>
                <option value="Kilometraje">Kilometraje</option>
                <option value="Horometro analogico">Horometro analogico</option>
                <option value="Horometro digital">Horometro digital</option>
              </select>
            </div>
            <div class="col-1">
              <a href="#" class="text-danger">
                <i class="fas fa-minus-circle fa-2x mb-3" onclick="QuitarElemento(this)"></i>
              </a>
            </div>
          </div>
        </form>
        <button class="btn btn-sm btn-warning mt-2" onclick="clonarElemento('inputCloneEQCO')" type="button">Nuevo</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="agregaEquipoCont()">Agregar</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal lista de sistemas de una familia -->
<div class="modal fade" id="modalListadoTiempoMantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header py-0 my-0">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Lista de sistemas</h5>
          <small class="mx-0" id="descSisFamiliaEquipo"></small>
        </div>
      </div>
      <div class="modal-body">
        <input type="hidden" id="llegaIdEquipoListaMamtenimientos">
        <div id="llegaListadoSisFamilia">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar nuevo configuracion de mantenimiento -->
<div class="modal fade" id="modalIngresoConfigMantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
    <div class="modal-content">
      <div class="modal-header py-0 my-0">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Lista de sistemas</h5>
          <small class="mx-0" id="descSisFamiliaEquipo"></small>
        </div>
      </div>
      <div class="modal-body">
        <label>Tiempo Mantenimiento</label>
        <input type="hidden" id="idEquipoManteConfig">
        <input type="text" list="listaTiempoMantenimiento" id="idTimaConfigEQMA" class="form-control listaTiempoMantenimiento form-control-sm mb-2" data-validate autocomplete="off">
        <div id="llegaDataListTiempoMan"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-blue-gyt text-light" onclick="AgregarConfiguracionTIMA()">Guardar</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal lista tipos sistema cambio equipos -->
<div class="modal fade" id="modalListadoTipoSistema" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
    <div class="modal-content">
      <div class="modal-header py-0 my-0">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Lista de sistemas</h5>
          <small class="mx-0" id="descSisFamiliaEquipo"></small>
        </div>
      </div>
      <div class="modal-body" id="llegaListadoTISI">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal ver pdf mantenimiento equipo -->
<div class="modal fade" id="modalPdfMantenimientoEquipo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="float-end me-2 mt-2">
        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" id="llegaPdfMantenimientoEquipo">
      </div>
    </div>
  </div>
</div>
<!-- Modal agregar tipos de sistema a un cambio de equipo -->
<div class="modal fade" id="modalAgregaTISI" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
    <div class="modal-content">
      <div class="modal-header py-1 row justify-content-center">
        <h5 class="modal-title text-center" id="staticBackdropLabel">Agregar Sistemas </h5><br>
        <small class="text-center" id="descFamiliaSisFamilia"></small>
      </div>
      <div class="modal-body">
        <form id="formAddTISIEquipoCAmbio">
          <input type="hidden" name="idCambioEquipo" id="idCambioEquipo">
          <div class="row justify-content-center" data-clone id="inputCloneSistema">
            <div class="col-10">
              <label>Familia</label>
              <input type="text" list="listaSisFamilias" data-validate class="form-control form-control-sm listaSisFamilias" name="idSistemas[]" autocomplete="off">
              <datalist id="listaSisFamilias">
                <?php foreach ($sistemaFamilias as $x) : ?>
                  <option data-value="<?php echo $x["TISI_id"] ?>"><?php echo $x["TISI_descripcion"] ?></option>
                <?php endforeach; ?>
              </datalist>
            </div>
            <div class="col-2 d-flex justify-content-end">
              <a href="#" class="text-danger mt-3"><i class="fas fa-minus-circle fa-2x mb-3" onclick="QuitarElemento(this)"></i></a>
            </div>
          </div>
        </form>
        <button class="btn btn-sm btn-warning mt-2" onclick="clonarElemento('inputCloneSistema')" type="button">Nuevo</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="agregaTISIEquipoCambio()">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar equipos mantenimiento -->
<div class="modal fade" id="modalActEquMantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
    <div class="modal-content">
      <div class="modal-header py-0 my-0">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Ingreso de PDF</h5>
          <small class="mx-0" id="descSisFamiliaEquipo"></small>
        </div>
      </div>
      <div class="modal-body">
        <form id="formActCambiosEquipos">
          <input type="file" name="archivoPdf" class="form-control form-control-sm" accept="application/pdf">
          <input type="hidden" name="idCambioEquipo" id="idCambioEquipoAct">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizarMantenimientosEquipo()">Guardar cambios</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agregar cambios equipo -->
<div class="modal fade" id="modalAddMantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
    <div class="modal-content">
      <div class="modal-header py-0 my-0">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Mantenimientos Equipos</h5>
          <small class="mx-0" id="descequipoAddSistema"></small>
        </div>
      </div>
      <div class="modal-body" id="llegaAddListadoCambiosEquipo">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="agregaEquipoMantenimientos()">Guardar cambios</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- lista de OTS por equipo-->
<div class="modal fade" id="modalLsiatOTSPorEquipo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <div class="col-sm-10 mx-auto my-auto"  id="cajaFiltrosOt">
          <form id="formObtenerListaOt">
          <div class="row pb-1">
            <div class="col-md-3">
              <label for="">Proyectos</label>
              <select 
              class="form-select form-select-sm" 
              id="selectProyectoConfOt"
              onchange="selectAnidadoConfE(this,'selectContratoConfOt','inputContratoConfOt')" id="selectProyectoConfOt">
                </select>
              </div>
              <div class="col-md-3">
              <label for="">Contratos</label>
              <input class="form-control form-control-sm selectContratoConfOt" autocomplete="off" list="selectContratoConfOt" id="inputContratoConfOt">
              <datalist id="selectContratoConfOt">
              </datalist>
            </div>
            <div class="col-md-2">
              <label for="">Fecha de inicio</label>
              <input type="date" class="form-control form-control-sm" id="PrimerDiaAnioListOt">
            </div>
            <div class="col-md-2">
              <label for="">Fecha final</label>
              <input type="date" class="form-control form-control-sm" id="ultimoDiaAnioListOt">
            </div>
            <div class="col-md-1 pt-3">
              <button class="btn btn-sm btn-blue-gyt text-light" type="button" id="buscarListaOt" data-id_eqco data-idequipo onclick="verListaOTS(this,false)">Buscar</button>
            </div>
          </div>
          </form>
        </div>
        <div id="llegaListaOtPorEquipo"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="quitarZindexModales('modalLsiatOTSPorEquipo')" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- lista de parametros diarios por equipo-->
<div class="modal fade" id="modalListadoPDPorEquipo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <div class="col-sm-10 mx-auto my-auto" id="cajaFiltrosPDGeneral">
        <form id="formObtenerListaPd">
          <div class="row pb-1">
          <div class="col-md-3">
              <label for="">Proyectos</label>
              <select class="form-select form-select-sm" 
              onchange="selectAnidadoConfE(this,'selectContratoConfPd','inputContratoConfPd')" id="selectProyectoConfPd">
                </select>
              </div>
              <div class="col-md-3">
              <label for="">Contratos</label>
              <input class="form-control form-control-sm selectContratoConfPd" list="selectContratoConfPd" autocomplete="off" id="inputContratoConfPd">
              <datalist id="selectContratoConfPd">
              </datalist>
            </div>
            <div class="col-md-2">
              <label for="">Fecha de inicio</label>
              <input type="date" class="form-control form-control-sm" id="PrimerDiaMesPD">
            </div>
            <div class="col-md-2">
              <label for="">Fecha final</label>
              <input type="date" class="form-control form-control-sm" id="ultimoDiaMesPD">
            </div>
            <div class="col-md-1 pt-3">
              <button class="btn btn-sm btn-blue-gyt text-light" type="button" id="buscarListaPD" data-id_eqco data-idequipo onclick="verListaParameDiarios(this,false)">Buscar</button>
            </div>
          </div>
        </form>
        </div>
        <div id="llegaListaPDPorEquipo"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar ots-->
<div class="modal fade" id="modalActOt" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" id="modactOtConfigSize">
    <div class="modal-content" style="height: 100%;">
      <div class="modal-body">
        <ul class="nav nav-pills nav-fill mb-3 border border-primary" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">INFORMACIÓN GENERAL</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-imagenes" type="button" role="tab" aria-controls="pills-imagenes" aria-selected="false">IMAGENES</button>
          </li>
        </ul>
        <form id="formActordenesTrabajo">
          <div class="tab-content" id="pills-tabContent">
            <!--  pill general -->
            <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-home-tab">
              <div class="row gx-0 ">
                <div class="col-lg-1 col-sm-6 col-md-6">
                  <label class="mb-1"># de OT</label>
                  <input type="text" class="form-control form-control-sm mb-2" readonly name="idOtAct" id="idOtAct">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Codigo/Placa Equipo</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate readonly id="codigoEQOTAct">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Familia</label>
                  <input type="text" class="form-control form-control-sm mb-2" data-validate readonly id="famEQOTAct">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Tipo Evento</label>
                  <select name="evento" class="form-select form-select-sm" data-validate id="eventoOTAct">
                    <option value="PREVENTIVO">PREVENTIVO</option>
                    <option value="CORRECTIVO">CORRECTIVO</option>
                    <option value="OTRO">OTRO</option>
                  </select>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">Kilometraje</label>
                  <input type="number" class="form-control form-control-sm mb-2" name="kilometraje" id="kilometrajeOTACt">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">Horometro B. Hidraulico</label>
                  <input type="number" class="form-control form-control-sm mb-2" id="HBrazoHidraulicoOTAct" name="hBHidraulico">
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Horometro chasis</label>
                  <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text">ANA</span>
                    <input type="number" class="form-control" id="HChasisAnaOTACt" name="hChasisAna" aria-label="Username">
                    <span class="input-group-text">DIGI</span>
                    <input type="number" class="form-control" id="HChasisDigiOTACt" name="hChasisDigi" aria-label="Server">
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Horometro grua</label>
                  <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text">ANA</span>
                    <input type="number" class="form-control" id="HGruaAnaOTACt" name="HgruaAna" aria-label="Username">
                    <span class="input-group-text">DIGI</span>
                    <input type="number" class="form-control" id="HGruaDigiOTACt" name="HgruaDigi" aria-label="Server">
                  </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                  <label class="mb-1">Supervisor de Área</label>
                  <input type="text" class="form-control form-control-sm mb-2 dataListSupervisoresOt" list="dataListSupervisoresOt" id="supervisoresOTAct" autocomplete="off">
                  <datalist id="dataListSupervisoresOt" class="LlegaOpcionesPlanners">
                  </datalist>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Tecnico de campo</label>
                  <input type="text" class="form-control form-control-sm mb-2 dataListTrabajadoresOt" id="tecnicoCampoOTAct" list="dataListTrabajadoresOt" autocomplete="off">
                  <datalist id="dataListTrabajadoresOt" class="LlegaOpcionesTrabajadores">
                  </datalist>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Operador</label>
                  <input type="text" class="form-control form-control-sm mb-2 dataListOperadoresOt" id="OperadorOTAct" list="dataListOperadoresOt" autocomplete="off">
                  <datalist id="dataListOperadoresOt" class="llegaOpcionesOperadores">
                  </datalist>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Jefe de equipos</label>
                  <input type="text" class="form-control form-control-sm mb-2 dataLisJefeEquiposOt" id="jefeEquiposOTAct" list="dataLisJefeEquiposOt" autocomplete="off">
                  <datalist id="dataLisJefeEquiposOt" class="LlegaOpcionesPlanners">
                  </datalist>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                  <label class="mb-1">Tipo de grua</label>
                  <div class="mt-1" id="radioTGACTOT">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" value="CARRIER" name="carrierOt" id="checkCarrierOt">
                      <label class="form-check-label" for="checkCarrierOt"> CARRIER </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" value="UPPER" name="upperOt" id="checkUpperOt">
                      <label class="form-check-label" for="checkUpperOt"> UPPER </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" value="GRUA" name="gruaOt" id="checkGruaOt">
                      <label class="form-check-label" for="checkGruaOt"> GRUA </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">Centro de costo</label>
                  <input type="text" class="form-control form-control-sm mb-2" name="centroCosto" id="centroCostoOTAct">
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1" id="descripcionTIMEOT"></label>
                  <input type="text" class="form-control form-control-sm mb-2" readonly name="" id="medicionCreacionOtTIMEOT">
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">F. de inicio</label>
                  <input type="date" class="form-control form-control-sm mb-2" name="fInicio" id="FIinicioOTAct">
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">F. cierre</label>
                  <input type="date" class="form-control form-control-sm mb-2" name="fCierre" id="fCierreOTAct">
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">Estado</label>
                  <select name="estado" class="form-select form-select-sm mb-2" id="estadoOTAct">
                    <option value="" selected disabled>Seleccione una opción</option>
                    <option value="PENDIENTE">PENDIENTE</option>
                    <option value="EN PROCESO">EN PROCESO</option>
                    <option value="FINALIZADO">FINALIZADO</option>
                  </select>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                  <label class="mb-1">Placa II:</label>
                  <input type="text" class="form-control form-control-sm mb-2" name="placa2" id="placa2OTAct">
                </div>
              </div>
              <hr class="modal-act">
              <!-- trabajaos realizados -->
              <div class="row">
                <!-- trabajos a realizar -->
                <div class="col-sm-4 text-center">
                  <h5 class="text-center">Trabajos a realizar</h5>
                  <div id="llegaTareasARelizar">
                  </div>
                </div>
                <!-- fin trabajos a realizar -->
                <div class="col-sm-8">
                  <h5 class="text-center">Trabajos realizados</h5>
                  <div>
                    <div class="row" data-clone id="CloneCambiosRealizadosOt">
                      <div class="col-12 col-lg-5">
                        <label>Descripción</label>
                        <input type="text" name="descripcionTRealizado[]" data-val2 class="form-control form-control-sm descripcionTR">
                      </div>
                      <div class="col-12 col-lg-4">
                        <label>Trabajador</label>
                        <input type="text" class="form-control form-control-sm mb-2 listaTrabTRREOt" data-val2 autocomplete="off" list="listaTrabTRREOt" id="trabajadorTRREOTAct">
                        <datalist id="listaTrabTRREOt" class="LlegaOpcionesTrabajadores">
                        </datalist>
                      </div>
                      <div class="col-10 col-lg-2">
                        <label>Duración(horas)</label>
                        <input type="number" name="duracionTRealizado[]" data-val2 class="form-control form-control-sm duracionTR">
                      </div>
                      <div class="col-2 col-lg-1 d-flex justify-content-end align-items-center">
                        <a href="#" class="text-danger mt-3"><i class="fas fa-minus-circle fa-2x mb-3" onclick="QuitarElemento(this)"></i></a>
                      </div>
                    </div>
                  </div>
                  <button class="btn btn-sm btn-warning my-2" onclick="clonarElemento('CloneCambiosRealizadosOt')" type="button">Nuevo</button>
                  <div id="llegaCambiosRealizados"></div>
                </div>
                <!-- fin trabajos realizados -->
                
              </div>
            <!--  insumos usados -->
            <hr class="modal-act">
            <div class="row justify-content-center">
            <h5 class="text-center">insumos usados</h5>
                <div class="col-sm-12">
                  <div>
                    <div class="row" data-clone id="CloneCambiosRepuestosOt">
                      <div class="col-12 col-lg-2">
                        <label>Descripción</label>
                        <input type="text" name="descripcionAutoparte[]" data-val2 class="form-control form-control-sm">
                      </div>
                      <div class="col-12 col-lg-2">
                        <label># parte</label>
                        <input type="text" name="serieAutoparte[]" data-val2 class="form-control form-control-sm ">
                      </div>
                      <div class="col-12 col-lg-1">
                        <label>Marca</label>
                        <input type="text" name="marcaAutoparte[]" data-val2 class="form-control form-control-sm ">
                      </div>
                      <div class="col-12 col-lg-1">
                        <label>Moneda</label>
                        <select name="monedaAutoparte[]" class="form-select form-select-sm">
                          <option value="">Seleccione</option>
                          <option value="SOLES">SOLES</option>
                          <option value="DOLARES">DOLARES</option>
                          <option value="EUROS">EUROS</option>
                        </select>
                      </div>
                      <div class="col-12 col-lg-1">
                        <label>Cantidad</label>
                        <input type="number" name="cantidadAutoparte[]" data-val2 id="cantAutoSuma" class="form-control form-control-sm cantidad">
                        <!-- oninput="sumarDosCampos(this)" -->
                      </div>
                      <div class="col-12 col-lg-1">
                        <label>U. Medida</label>
                        <select name="uMedidaAutoparte[]" data-val2 class="form-select form-select-sm">
                          <option value="">Seleccione</option>
                          <option value="Kilos">Kilos</option>
                          <option value="Litros">Litros</option>
                          <option value="Unidades">Unidades</option>
                          <option value="Galones">Galones</option>
                        </select>
                      </div>
                      <div class="col-12 col-lg-1">
                        <label>Precio</label>
                        <input type="number" name="precioAutoparte[]" id="precioAutoSuma" class="form-control form-control-sm precio">
                      </div>
                      <div class="col-10 col-lg-2">
                        <label>Observación</label>
                        <input type="text" name="observacionAutoparte[]" value="" class="form-control form-control-sm ">
                      </div>
                      <div class="col-2 col-lg-1 align-middle">
                        <a href="#" class="text-danger mt-4  float-end"><i class="fas fa-minus-circle fa-2x mb-3" onclick="QuitarElemento(this)"></i></a>
                      </div>
                    </div>
                  </div>
                  <button class="btn btn-sm btn-warning mb-3" onclick="clonarElemento('CloneCambiosRepuestosOt')" type="button">Nuevo</button>
                  <div id="llegaInsumosOt"></div>
                </div>
              </div>
            <!-- fin  insumos usados -->

              
            </div>
            <!-- fin pill general -->
            <!--  pill imagenes -->
            <div class="tab-pane fade" id="pills-imagenes" role="tabpanel" aria-labelledby="pills-contact-tab">
              <button class="btn btn-sm btn-success text-light mb-3" data-idotimg="" data-bs-toggle="modal" data-bs-target="#modalAddImgOt" onclick="llenarIdOtAddImg(this)" type="button">agregar imagenes</button>
              <div class="row justify-content-center">
                <div class="col-sm-12 text-center">
                    <div id="llegaImagenesOtAct"></div>
                </div>
              </div>
            </div>
            <!-- fin pill imagenes -->
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-blue-gyt" onclick="actualizarOrdenTrabajo()">Guardar</button>
        <button class="btn btn-sm bg-danger text-light" data-idotpdf onclick="verPdfOrdenTrabajo(this)"> Generar PDF</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualiza trabajos realizados -->
<div class="modal fade" id="modalTrabajoRealizadoAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar Trabajo Realizado</h5>
      </div>
      <div class="modal-body">
        <form id="formTrabajoRealizadoAct">
          <input type="text" name="idAct" hidden id="idTRealizadosAct">
          <label> Descripción</label>
          <input type="text" name="descripcion" class="form-control form-control-sm mb-2" id="descTRealizadosAct" data-validate>
          <label>Trabajador</label>
          <input type="text" class="form-control form-control-sm mb-2 listaTrabTRREOt" autocomplete="off" list="listaTrabTRREOt" id="trabajadorTRealizadosAct">
          <datalist id="listaTrabTRREOt" class="LlegaOpcionesTrabajadores">
          </datalist>
          <label>Duración</label>
          <input type="number" name="duracion" class="form-control form-control-sm mb-2" id="duraTRealizadosAct" data-validate>
          <label>Estado</label>
          <select class="form-select form-select-sm mb-2" name="estado" id="estadoTRealizadosAct" data-validate>
            <option value="1">Habilitado</option>
            <option value="0">inhabilitar</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizarCRealizadosOt()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal insumos Ot -->
<div class="modal fade" id="modalInsumosOtAct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Editar insumos</h5>
      </div>
      <div class="modal-body">
        <form id="formInsumosOtAct">
          <div class="row">
            <div class="col-lg-8 col-sm-6 col-md-6">
              <input type="text" name="idAct" hidden id="idInsumosOtAct">
              <label> Descripción</label>
              <input type="text" name="descripcion" class="form-control form-control-sm mb-2" id="descInsumosOtAct" data-validate>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label># parte</label>
              <input type="text" name="codigo" class="form-control form-control-sm mb-2" id="codigoInsumosOtAct" data-validate>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6">
              <label>Marca</label>
              <input type="text" name="marca" class="form-control form-control-sm mb-2" id="marcaInsumosOtAct" data-validate>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6">
              <label>Moneda</label>
              <select name="moneda" data-validate class="form-select form-select-sm" id="monedaiInsumosOtAct">
                <option value="">Seleccione</option>
                <option value="SOLES">SOLES</option>
                <option value="DOLARES">DOLARES</option>
                <option value="EUROS">EUROS</option>
              </select>
            </div>
            
          </div>
          <div class="row">
          <div class="col-lg-4 col-sm-6 col-md-6">
              <label>U. Medida</label>
              <select name="uMedida" data-validate class="form-select form-select-sm mb-2" id="uMedidaInsumosOtAct">
                <option value="Kilos">Kilos</option>
                <option value="Litros">Litros</option>
                <option value="Unidades">Unidades</option>
                <option value="Galones">Galones</option>
              </select>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label>Cantidad</label>
              <input type="number" name="cantidad" class="form-control form-control-sm mb-2" id="cantiInsumosOtAct" data-validate>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label>Precio</label>
              <input type="number" name="precio" class="form-control form-control-sm mb-2" id="precioInsumosOtAct" data-validate>
            </div>
            <!--<div class="col-lg-4 col-sm-6 col-md-6">
              <label> Total</label>
              <input type="text" name="total" readonly class="form-control form-control-sm mb-2" id="totalInsumosOtAct" data-validate>
            </div> -->
          </div>
          <div class="row">
            <div class="col-lg-8 col-sm-6 col-md-6">
              <label> Observación</label>
              <input type="text" name="observacion" class="form-control form-control-sm mb-2" id="obserInsumosOtAct" data-validate>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
              <label>Estado</label>
              <select class="form-select form-select-sm mb-2" name="estado" id="estadoInsumosOtAct" data-validate>
                <option value="1">Habilitado</option>
                <option value="0">inhabilitar</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="actualizarInsumosOt()">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar ot -->
<div class="modal fade" id="modalAddOt" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mx-auto" id="staticBackdropLabel">Generar Orden de trabajo</h5>
      </div>
      <div class="modal-body">
        <form id="formAddOrdenesTrabajo">
          <div class="row">
            <div class="">
              <label class="mb-1">Tipo Evento</label>
              <select name="evento" class="form-select form-select-sm" data-validate>
                <option value="" disabled>Seleccione una opción</option>
                <option selected value="CORRECTIVO">CORRECTIVO</option>
                <option  value="PREVENTIVO">PREVENTIVO</option>
                <option value="OTRO">OTRO</option>
              </select>
            </div>
            <div>
              <b class="text-center">Trabajos a realizar</b>
              <div class="mt-2">
                <div class="row justify-content-between" data-clone id="inputCloneSistemaAddOt">
                  <div class="col-10">
                    <input type="text" list="listaTipoSistemas" data-validate class="form-control form-control-sm listaTipoSistemas" autocomplete="off">
                    <datalist id="listaTipoSistemas">
                      <?php foreach ($sistemaFamilias as $x) : ?>
                        <option data-value="<?php echo $x["TISI_id"] ?>"><?php echo $x["TISI_descripcion"] ?></option>
                      <?php endforeach; ?>
                    </datalist>
                  </div>
                  <div class="col-1 d-flex justify-content-end">
                    <a href="#" class="text-danger mt-1"><i class="fas fa-minus-circle fa-2x mb-3" onclick="QuitarElemento(this)"></i></a>
                  </div>
                </div>
              </div>
              <button class="btn btn-sm btn-warning mt-2" onclick="clonarElemento('inputCloneSistemaAddOt')" type="button">Nuevo</button>
            </div>
            <div class="col-12 d-flex justify-content-end">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm bg-blue-gyt text-light" onclick="agregaOrdenesTrabajo()">Agregar</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agrega parametro diario-->
<div class="modal fade" id="modalAddPaDiario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " id="CajaModalmodalPD">
    <div class="modal-content">
      <div class="d-flex justify-content-between py-1 border-bottom">
        <div class="d-flex gap-2 align-align-items-center ps-2">
          <button type="button" data-idequipri data-idequisec  onclick="llenarDatosParametrosDiarios(this,true)" class="btn btn-sm btn-sec btn-blue-gyt">Registrar</button>
        </div>
        <div class="d-flex gap-2">
          <input type="date" name="" data-idequipri data-idequisec onchange="llenarDatosParametrosDiarios(this,false,'date')" class="form-control form-control-sm btn-sec" id="fechaBusquedaPD">
        </div>
        <button type="button w-100" class="btn-close p-2 pe-4" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-1 d-flex justify-content-center">
        <div class="contenedor-pd-flex">
        <i class="fas fa-arrow-left fa-2x btn-sec" id="icon-left" data-idequipri data-idequisec onclick="llenarDatosParametrosDiarios(this,false,'noDate','anterior')" style="cursor: pointer;"></i>
          <div class="item-pd-flex shadow" id="llegaInputsPDEPrimario">

          </div>
          <div class="item-pd-flex shadow" id="llegaInputsPDESecundario">
            <!-- parametro diario equipo secundario -->
          </div>
          <i class="fas fa-arrow-right fa-2x btn-sec" id="icon-right" data-idequipri data-idequisec onclick="llenarDatosParametrosDiarios(this,false,'noDate','siguiente')" style="cursor: pointer;"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal agrega parametro diario-->
<div class="modal fade" id="modalHistorialMedidoresPD" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" >
    <div class="modal-content">
      <div class="modal-body" id="llegadaTablaHistorialPD">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agrega imagenes ot-->
<div class="modal fade" id="modalAddImgOt" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header py-1">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Imagenes OT</h5>
          <small class="mx-0" id="descEquipoPaDiario"></small>
        </div>
      </div>
      <div class="modal-body">
        <form id="formAgregaimgOt">
          <input type="hidden" id="idOtAddImg" value="idOt">
          <!--           <input type="file" id="imgsOt" onchange="cargarVisualizarImagenesOt()" class="form-control d-none" accept=".jpg,.jpeg" name="idEquipoContrato" multiple> -->
          <input id="imgsOt" class="" style="opacity:0">
          <label for="imgsOt" class="contenedorDragDrop">
            <img src="assets/img/dragAndDrop.gif" class="img-fluid" alt="">
            <img id="llegaImagenesOt" class="img-fluid">
          </label>
        </form>
        <div>

        </div>
        <template id="templateImgOt">
          <div class="col-md-6 mb-2">
            <div class="ratio ratio-16x9">
              <img src="" width="100%" height="100%" class="" alt="">
            </div>
          </div>
        </template>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-blue-gyt" onclick="agregarImagenesOt()">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agrega imagenes ot-->
<div class="modal fade" id="modalHistorialEquipo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header py-1">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Historial de equipos</h5>
        </div>
      </div>
      <div class="modal-body">
        <form>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-8">
                <label>Equipo</label>
                <input type="text" list="listaEquipoGeneral" id="codigoEquipoHE" class="form-control form-control-sm" name="codigoEquipoHistorial">
                <div id="llegadaListHistorialEC">
                </div>
              </div>
              <div class="col-sm-4 d-flex align-items-end">
                <button class="btn btn-sm bg-blue-gyt text-light" type="button" onclick="buscarHistorialEquipo()">Buscar</button>
              </div>
            </div>
          </div>
          <div id="llegaTablaHEquipos">

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal confirmacion Finalizacion contrato equipo-->
<div class="modal fade" id="modalconfirmacionCierreContrato" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <div id="llegaContenidoConfirmEQCO">
        </div>
        <div class="d-flex justify-content-center my-3">
          <button class="btn text-light me-2" data-acteqco data-acteqtipo id="dataConfigActEQCO" style="background-color: rgb(48, 133, 214);
" onclick="actualizaEquipoCont()">Actualizar</button>
          <button class="btn text-light" style="background-color: rgb(221, 51, 51)" onclick="$('#modalconfirmacionCierreContrato').modal('hide')">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- modal actualizar los roles -->

<div class="modal fade" id="modalActRoles" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
    <div class="modal-header py-1">
        <div class="text-center mx-auto">
          <h5 class="modal-title mb-0" id="staticBackdropLabel">Roles activos</h5>
        </div>
      </div>
      <div class="modal-body" id="llegaListaRolesTrabajador">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal ayuda (secciones)-->
<div class="modal fade" id="modalAyudaSecciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
    <div class="modal-header d-flex justify-content-center align-items-center p-2">
      <h6 id="descripcionEquipoAyuda" class="text-uppercase px-1 my-0"></h6>
      </div>
      <div class="modal-body">
      <h4 class="fw-bold text-center">¿Qué deseas hacer?</h3>
        <div class="row gap-y-3">
          <div class="col-6 col-sm-6">
            <div class="d-flex shadow cursor-pointer py-2 flex-column justify-content-center align-items-center m-2" 
             onclick="verDocumentosAyuda(this);"
             data-idequipoayuda>
              <i class="fas fa-file fa-5x"></i>
              <strong class="mt-2">Documentos</strong>
            </div>
          </div>
          <div class="col-6 col-sm-6 ">
            <div id="ayudaButtonMantenimiento" 
            class="d-flex shadow cursor-pointer py-2  flex-column justify-content-center align-items-center m-2"
            data-id_eqco 
            data-idequipo
            onclick="agregarZindexModales('modalLsiatOTSPorEquipo');verListaOTS(this,true)">
            <i class="fas fa-cog fa-5x"></i>
              <strong class="mt-2">Mantenimientos</strong>
            </div>
          </div>
          <div class="col-6 col-sm-6 ">
            <div class="d-flex shadow cursor-pointer py-2  flex-column justify-content-center align-items-center m-2"
            onclick="verInfoAyuda(this);"
             data-idequipoayuda>
            <i class="fas fa-truck fa-5x"></i>
              <strong class="mt-2">Información</strong>
            </div>
          </div>
          <div class="col-6 col-sm-6 ">
            <div id="btn-scan-qr" onclick="encenderCamara();$('#modalAyudaSecciones').modal('hide')" data-bs-toggle="modal" data-bs-target="#modalCamaraQR" class="d-flex shadow cursor-pointer py-2  flex-column justify-content-center align-items-center m-2">
            <i class="fas fa-qrcode fa-5x"></i>
              <strong class="mt-2">Lector QR</strong>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal ayuda (secciones)-->
<div class="modal fade" id="modalCamaraQR" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body">
      <div class="container-fluid bg-white my-2 px-0 d-flex justify-content-center">
    <div class="col-sm-12 col-md-12 col-lg-12 shadow">
      <div class="row text-center">
      <canvas hidden="" id="qr-canvas" class="img-fluid"></canvas>
      </div>
      <div class="row mx-5 my-3">
      <button class="btn btn-red-gyt btn-sm rounded-3" onclick="cerrarCamara()">Detener camara</button>
      </div>
    </div>
</div>
      </div>
    </div>
  </div>
</div>
<script src="assets/js/codigo_qr.js"></script>
<script>

    $(document).ready(function() {
        $('#tabla_alerta_documentos_equipo').DataTable({
            "info":false,
            "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
        });
    });
</script>
