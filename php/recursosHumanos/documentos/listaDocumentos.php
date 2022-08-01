<?php
include_once("../../conexion.php");
//include_once("../../calculo_tiempo.php");
$whereConsulta = "";
$idPersona = @$_POST["idPersona"];

$consulta = "SELECT * FROM gyt_documentos do
INNER JOIN gyt_tipodocumento td ON do.id_tipodocumento = td.id_tipodocumento
INNER JOIN gyt_personas pe ON do.id_persona = pe.id_persona 
INNER JOIN gyt_puesto pu ON pe.id_puesto = pu.id_puesto 
INNER JOIN gyt_departamentos de ON pe.id_departamento = de.id_departamento 
WHERE do.id_persona=$idPersona";

$resListadoDoc = mysqli_query($conexion, $consulta);
$arrayDatos = mysqli_fetch_assoc($resListadoDoc);

?>
<div>
  <div class="col-sm-12 col-md-10 col-lg-9  mx-auto">
  <div class="row">
    <div class="col-sm-6">
      <table class="table table-sm">
        <tbody>
          <tr>
            <td class="fw-bold">DNI</td>
            <td><?php echo $arrayDatos["id_persona"] ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Nombres y apellidos</td>
            <td><?php echo $arrayDatos["per_nombres"] .' '.$arrayDatos["per_apellidos"]; ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Fecha de nacimiento</td>
            <td><?php echo $arrayDatos["per_fechanac"] ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Teléfono</td>
            <td><?php echo $arrayDatos["per_telefono"] ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-sm-6">
    <table class="table table-sm">
        <tbody>
          <tr>
            <td class="fw-bold">Fecha de ingreso</td>
            <td><?php echo $arrayDatos["per_fechaingreso"] ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Régimen</td>
            <td><?php echo $arrayDatos["per_regimen"] ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Puesto</td>
            <td><?php echo $arrayDatos["pue_descripcion"] ?></td>
          </tr>
          <tr>
            <td class="fw-bold">Departamento</td>
            <td><?php echo $arrayDatos["dep_descripcion"] ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </div>
  <div class="container-fluid bg-white my-2 py-3">
    <div class="table-responsive">
      <table id="tablaListaDoc" class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>DNI</th>
            <th>Nombres</th>
            <th>Documento</th>
            <th>FechaInicio</th>
            <th>FechaFin</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <?php
        foreach ($resListadoDoc as $x) {
          $idDocumento = $x["id_documento"];
          $data = $x["id_documento"] ."|".$x["id_persona"] ."|".$x["id_tipodocumento"] ."|".$x["doc_fecha1"] ."|".$x["doc_fecha2"] ."|".$x["doc_numdoc"]."|".$x["doc_descripcion"]."|".$x["doc_empresa"]."|".$x["doc_observa"]."|".$idPersona;
          ?>
          <tr>
            <td><?php echo $idDocumento; ?></td>
            <td><?php echo $x["id_persona"]; ?></td>
            <td><?php echo $x["per_nombres"] . ' ' . $x["per_apellidos"] ?></td>
            <td><?php echo $x["tdoc_descripcion"]; ?></td>
            <td><?php echo $x["doc_fecha1"]; ?></td>
            <td><?php echo $x["doc_fecha2"]; ?></td>
            <td class="text-center">
              <a class="link_edit text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalActDocPersonal"
              onclick="llenarDocumentosAct('<?php echo $data ?>')" href="#">
                <i class="fa fa-edit"></i>
              </a>
              |
              <a class="link_edit text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalVerDocPersonal"  onclick="verDocumentoPersonal('<?php echo $idDocumento ?>')" href="#">
                <i class="fa fa-print"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<script>
  var table = $('#tablaListaDoc').DataTable({
    "info": false,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    }
  });
 
</script>