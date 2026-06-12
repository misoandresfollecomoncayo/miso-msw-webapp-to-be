<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Compras")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
        $layout->setTitle("Purchases");
    } else {
        $layout->setTitle("Compras");
    }
    $layout->printHead();
?>
    <body>
        <input type="hidden" id="hdLanguage" value="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage(); ?>"
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Purchases" : "Compras", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div style="display: flex; align-items: center; justify-content: space-between" class="width-100 margin-bottom-4x">
                    <div style="display: flex; align-items: center">
                        <div class="text-weight-bold margin-right-2x"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Peso en bodega:" : "Warehouse weight:"; ?></div>
                        <div><?php echo CloudEngineSession::getSessionObject()->getObject()->warehouseWeight() ?></div>
                    </div>
                    <button id="btnRequest" class="button-red"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "SOLICITAR ENVÍO" : "REQUEST SHIPMENT"; ?></button>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblPurchases" class="stripe width-100">
                        <thead>
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                                    echo '<tr><th>Request<br/><input type="checkbox" id="chkAll" /></th><th>Invoice</th><th>Created date</th><th>Tracking</th><th>Content</th><th>Weight</th><th>Store</th><th>Status</th><th>Pictures</th></tr>';
                                } else {
                                    echo '<tr><th>Solicitar<br/><input type="checkbox" id="chkAll" /></th><th>Factura</th><th>Fecha creado</th><th>Rastreo</th><th>Contenido</th><th>Peso</th><th>Tienda</th><th>Estado</th><th>Fotos</th></tr>';
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $purchases = CloudEngineSession::getSessionObject()->getObject()->getPurchases();
                                foreach ($purchases as $p) {
                                    $pictures = $p->getPictures();
                                    
                                    $code = "<tr>";
                                    $code .= $p->getStatus() == Purchase::STATUS_WAREHOUSE ? "<td class='text-align-center'><input type='checkbox' name='chkRequest' data-id='" . $p->getIdPurchase() . "' /></td>" : "<td></td>";
                                    $code .= $p->getStatus() == Purchase::STATUS_SHIPPED ? "<td class='text-align-center'>" . $p->getShipment()->getShippingNumber() . "</td>" : "<td></td>";
                                    $code .= "<td class='text-align-center'>" . $p->getCreatedTimestampFormatted() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getTrackingNumber() . "</td>";
                                    $code .= "<td>" . $p->getContent() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getNetWeight() . "</td>";
                                    $code .= "<td class='text-align-center'>" . ($p->getStore() != null ? $p->getStore()->getName() : "") . "</td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius " . $p->getStatusColor() . " padding text-color-white text-size-xs text-weight-bold'>" . $p->getStatusLanguage(CloudEngineSession::getSessionObject()->getObject()->getLanguage()) . "</div></td>";
                                    
                                    $code .= "<td class='text-align-center'>";
                                    for ($i=0; $i<count($pictures); $i++) {
                                        $code .= "<a target='_blank' href='" . PUBLIC_PATH_STATIC . "Uploads/" . $pictures[$i]->getIdPurchasePicture() . "' class='margin-right-2x text-decoration-underline text-color-blue'>" . ($i + 1) . "</a>";
                                    }
                                    $code .= "</td>";
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
            $("#chkAll").on("change", function() {
                if ($(this)[0].checked) {
                    $("[name=chkRequest]").prop("checked",true);
                } else {
                    $("[name=chkRequest]").prop("checked",false);
                }
                simulate();
            });
            
            $(document).ready( function () {
                if ($("#hdLanguage").val() === "SPANISH") {
                    $('#tblPurchases').DataTable({
                        ordering: false,
                        pageLength: 25,
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
                    $('#tblPurchases').DataTable({
                        ordering: false
                    });
                }
                
                $("#btnRequest").on("click", function() {
                    var purchases = new Array();
                    var checks = $("[name=chkRequest]");
                    
                    for (var i=0; i<checks.length; i++) {
                        if (checks[i].checked) {
                            purchases.push($(checks[i]).data("id"));
                        }
                    }
                    
                    if (purchases.length > 0) {
                        $.ajax({
                            url: URL_API + "Shipment/RequestShipmentNotify.php",
                            type: "POST",
                            data: {
                                Purchases: JSON.stringify(purchases)
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(response) {
                                document.location.reload();
                            }
                        });
                    } else {
                        if ($("#hdLanguage").val() === "SPANISH") {
                            alert("Por favor selecciona uno o más items.");
                        } else {
                            alert("please check one item at least.");
                        }
                    }
                });
            });
        </script>
    </body>
</html>