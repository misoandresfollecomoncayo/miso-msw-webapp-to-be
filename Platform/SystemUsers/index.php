<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Usuarios sistema")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Usuarios sistema");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Usuarios sistema", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none">NUEVO</a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblUsers" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Notificar solicitud envío</th>
                                <th>Notificar alerta de llegada</th>
                                <th>Estado</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $systemUsers = SystemUserDAO::getSystemUsers();
                                foreach ($systemUsers as $u) {
                                    $code = "<tr>";
                                    $code .= "<td>" . $u->getNames() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $u->getEmail() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $u->getRole()->getName() . "</td>";
                                    
                                    if ($u->sendRequestShipmentNotification()) {
                                        $code .= "<td class='text-align-center'><div class='background-color-green border-radius text-size-xs text-color-white text-weight-bold padding'>SI</div></td>";
                                    } else {
                                        $code .= "<td class='text-align-center'><div class='background-color-red border-radius text-size-xs text-color-white text-weight-bold padding'>NO</div></td>";
                                    }
                                    
                                    if ($u->sendAlertArrivalNotification()) {
                                        $code .= "<td class='text-align-center'><div class='background-color-green border-radius text-size-xs text-color-white text-weight-bold padding'>SI</div></td>";
                                    } else {
                                        $code .= "<td class='text-align-center'><div class='background-color-red border-radius text-size-xs text-color-white text-weight-bold padding'>NO</div></td>";
                                    }
                                    
                                    if ($u->isActive()) {
                                        $code .= "<td class='text-align-center'><div class='background-color-green border-radius text-size-xs text-color-white text-weight-bold padding'>ACTIVO</div></td>";
                                    } else {
                                        $code .= "<td class='text-align-center'><div class='background-color-red border-radius text-size-xs text-color-white text-weight-bold padding'>INACTIVO</div></td>";
                                    }
                                    
                                    $code .= "<td name='btnEdit' data-id='" . $u->getIdSystemUser() . "' class='text-align-center text-decoration-underline cursor-pointer'>Editar</td>";
                                    
                                    if ($u->isActive()) {
                                        $code .= "<td name='btnDeactivate' data-id='" . $u->getIdSystemUser() . "' class='text-align-center text-decoration-underline cursor-pointer'>Desactivar</td>";
                                    } else {
                                        $code .= "<td name='btnActive' data-id='" . $u->getIdSystemUser() . "' class='text-align-center text-decoration-underline cursor-pointer'>Activar</td>";
                                    }
                                    
                                    $code .= "<td name='btnDelete' data-id='" . $u->getIdSystemUser() . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</td>";
                                    
                                    $code .= "</tr>";
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $(document).ready( function () {
                var tblUsers = $('#tblUsers').DataTable({ordering: false});
                
                $(document).on("click", "[name=btnEdit]", function(e) {
                    $.redirect("Edit.php", {IdSystemUser: $(this).data("id")});
                });
                
                $(document).on("click", "[name=btnActive]", function(e) {
                    if (confirm("¿Confirma activar el usuario?")) {
                        $.ajax({
                            url: URL_API + "SystemUser/Active.php",
                            data: {
                                IdUser: $(this).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function() {
                                document.location.reload();
                            }
                        });
                    }
                });
                
                $(document).on("click", "[name=btnDeactivate]", function(e) {
                    if (confirm("¿Confirma desactivar el usuario?")) {
                        $.ajax({
                            url: URL_API + "SystemUser/Deactivate.php",
                            data: {
                                IdUser: $(this).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function() {
                                document.location.reload();
                            }
                        });
                    }
                });
                
                $(document).on("click", "[name=btnDelete]", function(e) {
                    if (confirm("¿Confirma eliminar el usuario?")) {
                        $.ajax({
                            url: URL_API + "SystemUser/Delete.php",
                            data: {
                                IdUser: $(this).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function() {
                                document.location.reload();
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>