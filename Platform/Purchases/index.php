<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Mercancía")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Mercancía");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Mercancía", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none">REGISTRAR NUEVO</a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblPurchases" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>No. Casillero</th>
                                <th>Cliente</th>
                                <th>Tracking No.</th>
                                <th>Contenido</th>
                                <th>Cantidad</th>
                                <th>Peso</th>
                                <th>Estado</th>
                                <th>Fecha creado</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $(document).ready( function () {
                $('#tblPurchases').DataTable({
                    ajax: URL_API + 'Purchase/DataTables.php',
                    processing: true,
                    serverSide: true,
                    ordering: false
                });
            });
            
            $(document).on("click", "[name=btnEdit]", function(e) {
                var id = $(e.target).data("id");
                $.redirect("Edit.php", {IdPurchase: id});
            });
            
            $(document).on("click", "[name=btnDelete]", function(e) {
                if (confirm("¿Confirma eliminar el registro?")) {
                    var id = $(e.target).data("id");
                    $.ajax({
                        url: URL_API + "Purchase/Delete.php",
                        data: {
                            Id: id
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
            
            $(document).on("click", "[name=btnShowBoxes]", function() {
                var parent = $(this).parent();
                var m = new MessageBox(parent.text());
                m.show();
            });
        </script>
    </body>
</html>