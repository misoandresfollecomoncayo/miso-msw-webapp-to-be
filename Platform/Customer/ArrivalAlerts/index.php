<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Alerta de compras")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Alerta de compras");
    } else {
        $layout->setTitle("Purchase alerts");
    }
    $layout->printHead();
?>
    <body>
        <input type="hidden" id="hdLanguage" value="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage(); ?>"
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Purchase alerts" : "Alerta de compras", PUBLIC_PATH_PLATFORM . "Tansversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "NUEVA" : "NEW"; ?></a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblAlerts" class="stripe width-100">
                        <thead>
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                                    echo '<tr><th>Date</th><th>Tracking</th><th>Detail</th><th>Items</th><th>Store</th><th>Status</th><th></th></tr>';
                                } else {
                                    echo '<tr><th>Fecha</th><th>Rastreo</th><th>Detalle</th><th>Items</th><th>Tienda</th><th>Estado</th><th></th></tr>';
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $arrivals = CloudEngineSession::getSessionObject()->getObject()->getArrivalAlerts();
                                foreach ($arrivals as $a) {
                                    $code = "<tr>";
                                    $code .= "<td class='text-align-center'>" . $a->getCreatedTimestamp() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getTrackingNumber() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getPurchase() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getItems() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getStore()->getName() . "</td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius " . $a->getStatusColor() . " padding text-color-white text-size-xs text-weight-bold'>" . $a->getStatusLanguage(CloudEngineSession::getSessionObject()->getObject()->getLanguage()) . "</div></td>";
                                    
                                    if ($a->getStatus() == ArrivalAlert::STATUS_PENDING) {
                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            $code .= "<td name='btnCancel' data-id='" . $a->getIdArrivalAlert() . "' class='text-align-center text-decoration-underline cursor-pointer'>Cancelar</td>";
                                        } else {
                                            $code .= "<td name='btnCancel' data-id='" . $a->getIdArrivalAlert() . "' class='text-align-center text-decoration-underline cursor-pointer'>Cancel</td>";
                                        }
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
                if ($("#hdLanguage").val() === "SPANISH") {
                    $('#tblAlerts').DataTable({
                        ordering: false,
                        language: {
                            lengthMenu: "Mostrar _MENU_ registros por página",
                            zeroRecords: "No hay registros para mostrar",
                            info: "Mostrando _PAGE_ de _PAGES_ páginas",
                            infoEmpty: "No hay registros para mostrar",
                            infoFiltered: "(filtrado de _MAX_ registros)",
                            search: "Buscar",
                            loadingRecords: "Cargando...",
                            processing:     "Procesando...",
                            paginate: {
                                first:      "Primero",
                                last:       "Último",
                                next:       "Siguiente",
                                previous:   "Anterior"
                            }
                        }
                    });
                } else {
                    $('#tblAlerts').DataTable({
                        ordering: false
                    });
                }
            });
            
            $("[name=btnCancel]").on("click", function() {
                var confirmMessage = $("#hdLanguage").val() === "SPANISH" ? "¿Confirma cancelar la alerta?" : "¿Do you confirm cancel the alert?";
                if (confirm(confirmMessage)) {
                    $.ajax({
                        url: URL_API + "ArrivalAlert/Cancel.php",
                        type: "POST",
                        data: {
                            Id: $(this).data("id")
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            if ($("#hdLanguage").val() === "SPANISH") {
                                alert("Alerta cancelada correctamente.");
                            } else {
                                alert("Alert canceled successfully.");
                            }
                            document.location.reload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>