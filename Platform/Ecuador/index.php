<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Ecuador")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Ecuador");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Ecuador", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <?php
                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                        $code = '';
                        $code .= '<!-- Actions --><div class="width-100 display-table margin-bottom-4x text-align-right"><a href="Create.php" class="button-red display-inline-block text-decoration-none">AGREGAR</a></div>';
                        echo $code;
                    }
                ?>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblList" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $list = EcuadorDAO::getEcuadorsList();
                                foreach ($list as $l) {
                                    $code = "<tr>";
                                    $code .= "<td class='text-align-center'>" . $l->getBillNumber() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $l->getCustomerNames() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $l->getCompletedQuantity() . " / " . $l->getQuantity() . "</td>";
                                    
                                    if ($l->getStatus() == "PENDIENTE") {
                                        $code .= "<td class='text-align-center'><div style='display:inline-block' class='padding border-radius background-color-red text-color-white text-size-xs text-weight-bold'>PENDIENTE</div></td>";
                                    } else {
                                        $code .= "<td class='text-align-center'><div style='display:inline-block' class='padding border-radius background-color-green text-color-white text-size-xs text-weight-bold'>COMPLETADO</div></td>";
                                    }
                                    
                                    $code .= "<td name='btnView' data-id='" . $l->getIdEcuador() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</td>";
                                    
                                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                        $code .= "<td name='btnDelete' data-id='" . $l->getIdEcuador() . "' class='text-align-center text-decoration-underline cursor-pointer'>Eliminar</td>";
                                    } else {
                                        $code .= "<td></td>";
                                    }
                                    
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
                $('#tblList').DataTable({
                    ordering: false,
                    pageLength: 25
                });
            });
            
            $(document).on("click","[name=btnView]",function() {
                $.redirect("../Ecuador/View.php", {Id: $(this).data("id")});
            });
            
            $(document).on("click","[name=btnDelete]",function() {
                if (confirm("¿Confirma eliminar el registro?")) {
                    $.ajax({
                        url: URL_API + "Ecuador/Delete.php",
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
        </script>
    </body>
</html>