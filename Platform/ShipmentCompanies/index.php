<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Empresas de envío")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Empresas de envío");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Empresas de envío", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none">NUEVA</a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblCompanies" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $companies = ShipmentCompanyDAO::getShipmentCompanies();
                                foreach ($companies as $c) {
                                    $code = "<tr>";
                                    $code .= "<td>" . $c->getName() . "</td>";
                                    $code .= "<td name='btnEdit' data-id='" . $c->getIdShipmentCompany() . "' class='text-align-center text-decoration-underline cursor-pointer'>Editar</td>";
                                    $code .= "<td name='btnDelete' data-id='" . $c->getIdShipmentCompany() . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</td>";
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
                $('#tblCompanies').DataTable({ordering: false});
                
                $(document).on("click", "[name=btnEdit]", function(e) {
                    $.redirect("Edit.php", {Id: $(this).data("id")});
                });
                
                $(document).on("click", "[name=btnDelete]", function(e) {
                    if (confirm("¿Confirma eliminar la empresa?")) {
                        $.ajax({
                            url: URL_API + "ShipmentCompany/Delete.php",
                            data: {
                                Id: $(this).data("id")
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