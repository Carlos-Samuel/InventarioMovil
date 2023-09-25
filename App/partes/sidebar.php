<?php
  if (!isset($activado)) {
    $activado = "";
  }
?>

<aside id="sidebar" class="sidebar break-point-sm has-bg-image" style="display: none;">
  <a id="btn-collapse" class="sidebar-collapser"><i class="ri-arrow-left-s-line"></i></a>
  <div class="sidebar-layout">
    <div class="sidebar-header">
      <div class="pro-sidebar-logo">
        <div>A</div>
        <p id = "titulo_sidebar">Admin CS<p>
      </div>
    </div>
    <div class="sidebar-content">
      <nav class="menu open-current-submenu">
        <ul>
          <li class="menu-header"><span> INVENTARIO </span></li>
          <?php
            $permiso1 = "Admin";
            $permiso2 = "Alistamiento";

            if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
          ?>
          <li class="menu-item">
            <a href="lista_alistamiento.php">
              <span class="menu-icon">
                <?php
                  if ($activado == "Alistamiento"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Alistamiento</span>
            </a>
          </li>
          <?php
            }
          ?>
          <?php
            $permiso1 = "Admin";
            $permiso2 = "Verificacion";

            if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
          ?>
          <li class="menu-item">
            <a href="lista_verificacion.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Verificacion"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Verificación</span>
            </a>
          </li>
          <!-- <li class="menu-item">
            <a href="lista_verificacion.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "ImprimirEtiquetas"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Imprimir etiquetas</span>
            </a>
          </li> -->
          <?php
            }
          ?>
          <?php
            $permiso1 = "Admin";
            $permiso2 = "Entrega";

            if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
          ?>
          <li class="menu-item">
            <a href="lista_entrega.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Entrega"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Entrega</span>
            </a>
          </li>
          <?php
            }
          ?>
          <?php
            $permiso1 = "Admin";
            $permiso2 = "Reportes";

            if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
          ?>
          <li class="menu-header" style="padding-top: 20px"><span> REPORTES </span></li>
          <li class="menu-item">
            <a href="trazabilidad.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Trazabilidad"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Trazabilidad</span>
            </a>
          </li>
          <li class="menu-item">
            <a href="estadisticasCarga.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "EstadisticasCarga"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Estadisticas de carga</span>
            </a>
          </li>
          <li class="menu-item">
            <a href="documento.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Documentos"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Documentos</span>
            </a>
          </li>
          <li class="menu-item">
            <a href="cierresForzados.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "CForzado"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Cierres forzados</span>
            </a>
          </li>
          <li class="menu-item">
            <a href="estadisticas.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Estadisticas"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Estadísticas</span>
            </a>
          </li>
          <?php
            }
          ?>
          
          <li class="menu-header" style="padding-top: 20px"><span> ADMINISTRACION </span></li>
          <li class="menu-item">
          <?php
            $permiso1 = "Admin";
            $permiso2 = "Bitacora";

            if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
          ?>
            <a href="lista_usuarios.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Usuario"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Usuarios</span>
            </a>
            <a href="lista_bitacora.php">
            <span class="menu-icon">
                <?php
                  if ($activado == "Bitacora"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Bitacora</span>
            </a>
            <a href="parametros.php">
              <span class="menu-icon">
                <?php
                  if ($activado == "Parametros"){
                ?>
                  <i class="fa fa-bookmark"></i>
                <?php
                  }else{
                ?>
                  <i class="ri-book-2-fill"></i>
                <?php
                  }
                ?>
              </span>
              <span class="menu-title">Parametros</span>
            </a>
            <a href="controladores/importador.php">
              <span class="menu-icon">
                  <i class="ri-book-2-fill"></i>
              </span>
              <span class="menu-title">Importador</span>
            </a>
            <a href="controladores/borrador.php">
              <span class="menu-icon">
                  <i class="ri-book-2-fill"></i>
              </span>
              <span class="menu-title">Borrador</span>
            </a>
            <a href="controladores/imprimir.php">
              <span class="menu-icon">
                  <i class="ri-book-2-fill"></i>
              </span>
              <span class="menu-title">Imprimir</span>
            </a>
            <?php
              }
            ?>
            
            
            <a href="controladores/logout.php">
              <span class="menu-icon">
                <i class="ri-book-2-fill"></i>
              </span>
              <span class="menu-title">Salir</span>
            </a>
            <hr>
            <div class = "container" style = "text-align: center;">
              <small>Software CS ©, todos los derechos reservados.</small>
            </div>
        </ul>
      </nav>
    </div>
  </div>
</aside>