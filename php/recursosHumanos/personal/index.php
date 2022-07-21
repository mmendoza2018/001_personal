<?php
require_once('modales.php');
require_once('../../conexion.php');

$resDepartamentos = mysqli_query($conexion, "SELECT * FROM country");
$resPuestos = mysqli_query($conexion, "SELECT * FROM gyt_puesto");

?>
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
<style>
  #formPersonalAdd label {
    font-weight: 600;
  }
</style>
<div>
  <h5>PERSONAL</h5>
</div>
<div class="container-fluid bg-white my-2 py-3">
  <div class="row g-5">
    <div class="col-sm-12">
      <!-- SmartWizard html -->
      <div id="smartwizard">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="#step-1">
              <div class="num">1</div>
              personales
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#step-2">
              <span class="num">2</span>
              Laborales
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#step-3">
              <span class="num">3</span>
              Emergencias
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="#step-4">
              <span class="num">4</span>
              Bancarios
            </a>
          </li>
        </ul>
        <form id="formPersonalAdd">
          <div class="tab-content mt-3">
            <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
              <div class="row" style="height: 330px;">
                <div class="col-md-4">
                  <label>Documento</label>
                  <select name="tipdoc" class="form-select form-select-sm select2">
                    <option value="">-- SELECCIONE --</option>
                    <option value="DNI">DNI</option>
                    <option value="CE">CE</option>
                    <option value="PASAPORTE">PASAPORTE</option>
                  </select>
                  <label>Num. Doc</label>
                  <input type="number" name="numdoc" data-validate class="form-control form-control-sm" required="">
                  <label>Nombres</label>
                  <input type="text" name="nombres" data-validate id="nombres" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();" required="">
                  <label>Apellidos Pat./Mat.</label>
                  <input type="text" name="apellidos" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();" required="">
                  <label>Sexo</label>
                  <select name="sexo" class="form-select form-select-sm select2" data-validate required="">
                    <option value="">-- SELECCIONE --</option>
                    <option value="MASCULINO">MASCULINO</option>
                    <option value="FEMENINO">FEMENINO</option>
                  </select>
                  <label>Fecha Nacimiento</label>
                  <input type="date" name="fecha_nac" data-validate class="form-control form-control-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="">
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                  <!-- /.form-group -->
                  <label>Lugar Nacimiento</label>
                  <input type="text" name="lugar_nac" class="form-control form-control-sm" data-validate onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="CIUDAD / PAIS">
                  <label>Estado Civil</label>
                  <select class="form-select form-select-sm select2" data-validate name="estado_civ" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>SOLTERO</option>
                    <option>CASADO</option>
                    <option>VIUDO</option>
                    <option>DIVORCIADO</option>
                    <option>CONVIVIENTE</option>
                  </select>
                  <label>Hijos</label>
                  <input type="number" name="hijo" class="form-control form-control-sm" data-validate onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="4">
                  <label>Mail</label>
                  <input type="email" name="email" class="form-control form-control-sm" data-validate onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <label>Nivel Estudios</label>
                  <select class="form-select form-select-sm select2" data-validate name="estudios" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>TEC. COMPLETO</option>
                    <option>UNI. BACHILLER</option>
                    <option>UNI. TITULADO</option>
                    <option>COLEGIADO</option>
                    <option>OTROS</option>
                  </select>
                  <label>Direccion</label>
                  <input type="text" name="direccion" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <!-- /.form-group -->
                </div>
                <div class="col-md-4">
                  <!-- /.form-group -->
                  <label>Departamento</label>
                  <select onchange="selectChange(this,'php/recursosHumanos/personal/optionsProvincia.php','provinciaId')" class="form-select form-select-sm" data-validate name="Departamento" required="">
                    <option value="">-- SELECCIONE --</option>
                    <?php foreach ($resDepartamentos as $x) : ?>
                      <option value="<?php echo $x["id"] ?>"><?php echo $x["country_name"] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label>Provincia</label>
                  <select id="provinciaId" onchange="selectChange(this,'php/recursosHumanos/personal/optionsDistrito.php','distritoId')" class="form-select form-select-sm" data-validate name="Provincia" required="">
                    <option value="">-- SELECCIONE --</option>
                  </select>
                  <label>Distrito</label>
                  <select id="distritoId" class="form-select form-select-sm" data-validate name="Distrito" required="">
                    <option value="">-- SELECCIONE --</option>
                  </select>
                  <label>Telefono</label>
                  <input type="text" name="telefono" class="form-control form-control-sm" data-validate onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <label>Tipo Sangre</label>
                  <select class="form-select form-select-sm select2" data-validate name="sangre" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>O NEGATIVO</option>
                    <option>O POSITIVO</option>
                    <option>A NEGATIVO</option>
                    <option>A POSITIVO</option>
                    <option>B NEGATIVO</option>
                    <option>B POSITIVO</option>
                    <option>AB NEGATIVO</option>
                    <option>AB POSITIVO</option>
                  </select>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

            </div>
            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
              <div class="row" style="height: 330px;">
                <div class="col-md-6">
                  <label>Puesto</label>
                  <select name="puesto" class="form-select form-select-sm select2" data-validate style="width: 100%;" required="">
                    <option value="">-- SELECCIONE --</option>
                    <?php foreach ($resPuestos as $x) : ?>
                      <option value="<?php echo $x["id_puesto"] ?>"><?php echo $x["pue_descripcion"] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label>Departamento</label>
                  <select name="depart" class="form-control form-control-sm select2" data-validate style="width: 100%;" required="">
                    <option value="">-- SELECCIONE --</option>
                    <option value="1">ADMINISTRACION</option>
                    <option value="2">OPERACIONES</option>
                    <option value="3">SEGURIDAD</option>
                    <option value="4">TALLER</option>
                  </select>
                  <label>Fecha Ingreso</label>
                  <input type="date" name="fecha" class="form-control form-control-sm" data-validate data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="">
                  <label>Sueldo</label>
                  <input type="number" data-validate name="sueldo" class="form-control form-control-sm">
                  <label>Bono</label>
                  <input type="number" name="bono" data-validate class="form-control form-control-sm">
                  <label>Regimen</label>
                  <select name="regimen" data-validate class="form-select form-select-sm select2" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>REGULAR</option>
                    <option>CONSTRUCCION CIVIL</option>
                  </select>
                  <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                  <!-- /.form-group -->
                  <label>Regimen de Trabajo</label>
                  <select class="form-select form-select-sm select2" data-validate name="regimen_tra" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>6X1</option>
                    <option>14X7</option>
                    <option>21X7</option>
                    <option>Jornada Maxima</option>
                  </select>
                  <label>Sistema Pension</label>
                  <select class="form-select form-select-sm select2" data-validate name="pension" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>AFP</option>
                    <option>ONP</option>
                  </select>
                  <label>CUSPP</label>
                  <input type="text" name="cuspp" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <label>Nombre AFP</label>
                  <input type="text" name="afp" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <label>Flujo</label>
                  <select class="form-select form-select-sm select2" data-validate name="flujo" style="width: 100%;">
                    <option value="">-- SELECCIONE --</option>
                    <option>FLUJO</option>
                    <option>MIXTA</option>
                  </select>
                  <!-- /.form-group -->
                </div>

                <!-- /.col -->
              </div>
            </div>
            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
              <div class="row" style="height: 330px;">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombre 1</label>
                    <input type="text" name="nombre1" data-validate class="form-control form-control-sm" placeholder="Obligatorio" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Parentesco</label>
                    <input type="text" name="parentesco1" data-validate placeholder="Obligatorio" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Celular</label>
                    <input type="text" name="celular1" data-validate placeholder="Obligatorio" class="form-control form-control-sm">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombre 2</label>
                    <input type="text" name="nombre2" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Parentesco</label>
                    <input type="text" name="parentesco2" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Celular</label>
                    <input type="text" name="celular2" class="form-control form-control-sm">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Nombre 3</label>
                    <input type="text" name="nombre3" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Parentesco</label>
                    <input type="text" name="parentesco3" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                    <label>Celular</label>
                    <input type="text" name="celular3" class="form-control form-control-sm">
                  </div>
                </div>
              </div>
            </div>
            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
              <div class="row" style="height: 330px;">
                <div class="col-md-4">
                  <label>Banco</label>
                  <input type="text" name="banco" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <!-- /.form-group -->
                </div>
                <div class="col-md-4">
                  <label>Numero de Cuenta</label>
                  <input type="text" name="cuenta" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <!-- /.form-group -->
                </div>
                <div class="col-md-4">
                  <label>CCI</label>
                  <input type="text" name="cci" data-validate class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();">
                  <!-- /.form-group -->
                </div>
              </div>
            </div>
          </div>
        </form>
        <!-- Include optional progressbar HTML -->
        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  setTimeout(() => {
    $('#smartwizard').smartWizard({
      lang: { // Language variables for button
        next: 'Siguiente',
        previous: 'Atras'
      },
      toolbar: {
        extraHtml: '<button class="btn btn-blue-gyt" type="submit" onclick="agregarPersonal()">Registrar</button>' // Extra html to show on toolbar
      },
    });
  }, 100);
</script>