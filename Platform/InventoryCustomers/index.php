<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Clientes ventas")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Clientes ventas");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Clientes ventas", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none">NUEVO</a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblCustomers" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Documento</th>
                                <th>País</th>
                                <th>Ciudad</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Correo electrónico</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $(document).ready( function () {
                var tblCustomers = $('#tblCustomers').DataTable({
                    ajax: URL_API + 'InventoryCustomer/DataTables.php',
                    processing: true,
                    serverSide: true,
                    ordering: false
                });
                
                $(document).on("click", "[name=btnDelete]", function(e) {
                    if (confirm("¿Confirma eliminar el registro?")) {
                        $.ajax({
                            url: URL_API + "Customer/Delete.php",
                            type: "POST",
                            data: {
                                IdCustomer: $(e.target).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(response) {
                                var r = JSON.parse(response);
                                if (r.type === "Exception") {
                                    new Notification("ERROR", r.message);
                                } else {
                                    new Notification("SUCCESS", r.body);
                                    tblCustomers.ajax.reload(null, false);
                                }
                                closePreload();
                            }
                        });
                    }
                });
                
                $(document).on("click", "[name=btnEdit]", function(e) {
                    $.redirect("Purchases.php", {IdCustomer: $(e.target).data("id")});
                });
                
                $(document).on("click", "[name=btnActive]", function(e) {
                    if (confirm("¿Confirma activar el cliente?")) {
                        $.ajax({
                            url: URL_API + "Customer/Active.php",
                            type: "POST",
                            data: {
                                IdCustomer: $(e.target).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(response) {
                                var r = JSON.parse(response);
                                if (r.type === "Exception") {
                                    new Notification("ERROR", r.message);
                                } else {
                                    new Notification("SUCCESS", r.body);
                                    tblCustomers.ajax.reload(null, false);
                                }
                                closePreload();
                            }
                        });
                    }
                });
                
                $(document).on("click", "[name=btnInactive]", function(e) {
                    if (confirm("¿Confirma desactivar el cliente?")) {
                        $.ajax({
                            url: URL_API + "Customer/Inactive.php",
                            type: "POST",
                            data: {
                                IdCustomer: $(e.target).data("id")
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(response) {
                                var r = JSON.parse(response);
                                if (r.type === "Exception") {
                                    new Notification("ERROR", r.message);
                                } else {
                                    new Notification("SUCCESS", r.body);
                                    tblCustomers.ajax.reload(null, false);
                                }
                                closePreload();
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>