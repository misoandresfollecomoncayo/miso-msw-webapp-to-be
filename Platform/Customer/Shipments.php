<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Envíos");
    } else {
        $layout->setTitle("Shipments");
    }
    $layout->printHead();
?>
    <body>
        <input type="hidden" id="hdLanguage" value="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage(); ?>"
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Shipments" : "Envíos", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblShipments" class="stripe width-100">
                        <thead>
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                                    echo '<tr><th>Created date</th><th>Number</th><th>Weight</th><th>Total</th><th>Status</th><th>Paid</th><th></th><th></th><th></th></tr>';
                                } else {
                                    echo '<tr><th>Fecha creado</th><th>Número</th><th>Peso</th><th>Total</th><th>Estado</th><th>Pagado</th><th></th><th></th><th></th></tr>';
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $shipments = CloudEngineSession::getSessionObject()->getObject()->getShipments();
                                foreach ($shipments as $s) {
                                    
                                    
                                    $code = "<tr>";
                                    $code .= "<td class='text-align-center'>" . $s->getCreatedTimestamp() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $s->getShippingNumber() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $s->getNetWeight() . "</td>";
                                    $code .= "<td class='text-align-center'>$ " . number_format($s->getTotal(), 2) . " " . $s->getCurrency() . "</td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius background-color-green padding text-color-white text-size-xs text-weight-bold'>" . $s->getStatus() . "</div></td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius " . $s->getPaymentColor() . " padding text-color-white text-size-xs text-weight-bold'>" . $s->getPaymentStatus() . "</div></td>";
                                    
                                    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                        $code .= "<td name='btnView' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</td>";
                                    } else {
                                        $code .= "<td name='btnView' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>View</td>";
                                    }
                                    
                                    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                        if ($s->getPaymentStatus() != "PAGADA") {
                                            $code .= "<td name='btnPay' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Pagar</td>";
                                        } else {
                                            $code .= "<td></td>";
                                        }
                                    } else {
                                        if ($s->getPaymentStatus() != "PAGADA") {
                                            $code .= "<td name='btnPay' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Pay</td>";
                                        } else {
                                            $code .= "<td></td>";
                                        }
                                    }
                                    
                                    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                        $code .= "<td name='btnTracking' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Rastrear</td>";
                                    } else {
                                        $code .= "<td name='btnTracking' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Tracking</td>";
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
                    $('#tblShipments').DataTable({
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
                    $('#tblShipments').DataTable({
                        ordering: false
                    });
                }
            });
            
            $("[name=btnView]").on("click", function() {
                $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $("[name=btnPay]").on("click", function() {
                $.redirect("/Platform/ElectronicPayment/", {EntityType:"SHIPMENT", IdEntity: $(this).data("id")});
            });
            
            $("[name=btnTracking]").on("click", function() {
                $.redirect("Tracking.php", {IdShipment: $(this).data("id")}, "POST");
            });
        </script>
    </body>
</html>