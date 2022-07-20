<nav id="sidebar" class="sidebar-wrapper">
  <div class="sidebar-content">
    <!-- sidebar-brand  -->
    <div class="text-center">
      <a href="menu.php" id="index" class="my-2 text-center"><img src="assets/img/gyt.png" class="mx-auto img-fluid mt-1" alt="gyt"></a>
    </div>
    <!-- sidebar-header  -->
    <div class="sidebar-item sidebar-header d-flex flex-nowrap">
      <div class="user-pic">
        <img class="img-responsive img-rounded mx-2 mt-0" src="assets/plugins/sidebar/src/img/user.jpg" with="80" alt="User picture">
      </div>
      <div class="user-info">
        <span class="user-name">
          <strong><?php echo $_SESSION["nombre_trabajador"] ?></strong>
        </span>
        <!-- <span class="user-role">Administrator</span> -->
        <span class="user-status">
          <i class="fa fa-circle"></i>
          <span>Online</span>
        </span>
      </div>
    </div>
    <!-- sidebar-menu  -->
    <div class="sidebar-item sidebar-menu">
      <ul>
        <li class="header-menu"><span>General</span></li>
        <li class="sidebar-dropdown">
          <a href="#">
            <i class="fas fa-users"></i>
            <span class="menu-text">Recursos humanos</span>
            <span class="badge badge-pill badge-warning">New</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li class="sidebar-dropdown-child">
                <a href="#"><span class="menu-text2">personal</span> </a>
                <div class="sidebar-submenu-child" style="display: none;">
                  <ul>
                    <li><a href="#" id="sidebarNuevoPersonal">Nuevo personal</a></li>
                    <li><a href="#" id="sidebarGestionPersonal">Gestión personal</a></li>
                  </ul>
                </div>
              </li>
              <li class="sidebar-dropdown-child">
                <a href="#"><span class="menu-text2">Documentos</span> </a>
                <div class="sidebar-submenu-child" style="display: none;">
                  <ul>
                    <li><a href="#" id="docEquipo">Nuevo documento</a></li>
                    <li><a href="#" id="gestionDocEquipos">Gestión documentos</a></li>
                  </ul>
                </div>
              </li>
              <li class="sidebar-dropdown-child">
                <a href="#"><span class="menu-text2">Fichas</span> </a>
                <div class="sidebar-submenu-child" style="display: none;">
                  <ul>
                    <li><a href="#" id="docEquipo">Consultas</a></li>
                  </ul>
                </div>
              </li>
              <li class="sidebar-dropdown-child">
                <a href="#"><span class="menu-text2">SCTR</span> </a>
                <div class="sidebar-submenu-child" style="display: none;">
                  <ul>
                    <li><a href="#" id="gestionDocEquipos">Gestión SCTR</a></li>
                    <li><a href="#" id="gestionDocEquipos">Gestión ampliaciones</a></li>
                    <li><a href="#" id="gestionDocEquipos">Asignaciones SCTR</a></li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
        <li class="sidebar-dropdown">
          <a href="#">
          <i class="fas fa-bell"></i>
            <span class="menu-text">Monitor de alertas</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li><a href="#" id="listaProyectos">Contratos</a></li>
            </ul>
          </div>
        </li>
        <li class="sidebar-dropdown">
          <a href="#">
          <i class="fas fa-address-book"></i>
            <span class="menu-text">Permisos</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li><a href="#" id="listaProyectos">Nuevo permiso</a></li>
              <li><a href="#" id="listaProyectos">Consultar permiso</a></li>
            </ul>
          </div>
        </li>
        <li class="header-menu">
          <span>Mantenimientos</span>
        </li>
        <li class="sidebar-dropdown">
          <a href="#">
            <i class="fas fa-truck-monster"></i>
            <span class="menu-text">Equipos</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li><a href="#" id="AlertasDocEquipo">Alertas doc. equipos</a></li>
              <li class="sidebar-dropdown-child">
                <a href="#"><span class="menu-text2">Informacion general</span> </a>
                <div class="sidebar-submenu-child" style="display: none;">
                  <ul>
                    <li><a href="#" id="sidebarTipoDocumento">Marcas</a></li>
                    <li><a href="#" id="modelo">Modelos</a></li>
                    <li><a href="#" id="familia">Familias</a></li>
                    <li><a href="#" id="propietario">Propietarios</a></li>
                    <li><a href="#" id="tipoDocEquipo">Tipos documentos</a></li>
                  </ul>
                </div>
              </li>
              <li class="sidebar-dropdown-child d-none">
                <a href="#" tyle="pointer-events: none; display: inline-block;"><span class="menu-text2">Información mecanica</span> </a>
                <div class="sidebar-submenu-child">
                  <ul>
                    <li><a href="#" id="tipoSisEquipo">Tipo sistema</a></li>
                    <li><a href="#" id="tipoOt">Tipo de OT</a></li>
                    <li><a href="#" id="sistemaEquipo">sistema equipo</a></li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
        <li class="sidebar-dropdown">
          <a href="#">
            <i class="fas fa-sitemap"></i>
            <span class="menu-text">Procesos</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li><a href="#" id="proyecto">Proyectos</a></li>
              <li><a href="#" id="cliente">Clientes</a></li>
              <li><a href="#" id="Sistemafamilia">Planes de Mantenimiento</a></li>
              <li><a href="#" id="tipoSistema">Tipo sistemas</a></li>
              <!-- <li><a href="#" id="tipoOt">Tipo de OT</a></li> -->
              <li><a href="#" id="cargoTrabajador">Cargos trabajador</a></li>
              <li><a href="#" id="trabajadores">Trabajadores</a></li>
              <?php if ($_SESSION["rol_trabajador"] == "Administrador") { ?>
                <li><a href="#" id="roles">Roles</a></li>
              <?php } ?>
            </ul>
          </div>
        </li>
      </ul>
    </div>
    <!-- sidebar-menu  -->
  </div>
  <!-- sidebar-footer  -->
  <div class="sidebar-footer">
    <div class="dropdown ">
      <!-- show -->

      <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <a href="php/cerrar_sesion.php" class="text-danger"><i class="fas fa-reply-all"></i></a>
      </a>
      <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <a class="text-warning" data-bs-toggle="modal" data-bs-target="#modaAlertaDocumentos"><i class="fas fa-bell"></i></a>
        <span class="badge badge-pill badge-warning notification">3</span>
      </a>
      <!-- <div class="dropdown-menu notifications" aria-labelledby="dropdownMenuMessage">
                <div class="notifications-header">
                    <a href="php/cerrar_sesion.php" class="text-danger"><i class=""></i></a>
                </div>
                <div class="dropdown-divider"></div>
                 <a class="dropdown-item" href="#">
                    <div class="notification-content">
                        <div class="icon">
                            <i class="fas fa-check text-success border border-success"></i>
                        </div>
                        <div class="content">
                            <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                            <div class="notification-time">
                                6 minutes ago
                            </div>
                        </div>
                    </div>
                </a>
                <a class="dropdown-item" href="#">
                    <div class="notification-content">
                        <div class="icon">
                            <i class="fas fa-exclamation text-info border border-info">a</i>
                        </div>
                        <div class="content">
                            <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                            <div class="notification-time">
                                Today
                            </div>
                        </div>
                    </div>
                </a>
                <a class="dropdown-item" href="#">
                    <div class="notification-content">
                        <div class="icon">
                            <i class='bx bxs-like bx-tada'></i>fsdf
                        </div>
                        <div class="content">
                            <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                            <div class="notification-time">
                                Yesterday
                            </div>
                        </div>
                    </div>
                </a> 
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="#">View all notifications</a>
            </div> -->
    </div>
    <div class="dropdown">
      <!-- <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-envelope"></i>
                <span class="badge badge-pill badge-success notification">7</span>
            </a> -->
      <div class="dropdown-menu messages" aria-labelledby="dropdownMenuMessage">
        <!-- <div class="messages-header">
                    <i class="fa fa-envelope"></i>
                    Messages
                </div> -->
        <!-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <div class="message-content">
                        <div class="pic">
                        </div>
                        <div class="content">
                            <div class="message-title">
                                <strong> Jhon doe</strong>
                            </div>
                            <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                        </div>
                    </div>

                </a> -->
        <!-- <a class="dropdown-item" href="#">
                    <div class="message-content">
                        <div class="pic">
                        </div>
                        <div class="content">
                            <div class="message-title">
                                <strong> Jhon doe</strong>
                            </div>
                            <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                        </div>
                    </div>

                </a> -->
        <!-- <a class="dropdown-item" href="#">
                    <div class="message-content">
                        <div class="pic">
                        </div>
                        <div class="content">
                            <div class="message-title">
                                <strong> Jhon doe</strong>
                            </div>
                            <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                elit. In totam explicabo</div>
                        </div>
                    </div>
                </a> -->
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center" href="#">View all messages</a>

      </div>
    </div>
    <div class="dropdown">
      <!--  <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog"></i>
                <span class="badge-sonar"></span>
            </a> -->
      <div class="dropdown-menu" aria-labelledby="dropdownMenuMessage">
        <a class="dropdown-item" href="#">My profile</a>
        <a class="dropdown-item" href="#">Help</a>
        <a class="dropdown-item" href="#">Setting</a>
      </div>
    </div>
    <div>
      <!--  <a id="pin-sidebar-custom" class="pt-1" href="#"> <i class="fas fa-caret-left fa-2x"></i></a> -->
    </div>
    <div class="pinned-footer">
      <a href="#">
        <i class="fas fa-caret-right fa-2x"></i>
        <!-- <i class="far fa-stop-circle"></i> -->
      </a>
    </div>
  </div>
</nav>