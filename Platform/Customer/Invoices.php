<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Facturas / Envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Facturas / Envíos");
    } else {
        $layout->setTitle("Invoices / Shipments");
    }
    $layout->printHead();
?>
    <body>
        <input type="hidden" id="hdLanguage" value="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage(); ?>"
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Invoices / Shipments" : "Facturas / Envíos", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="margin-bottom-4x" style="display:flex;justify-content: flex-end"><button  id="btnPayNow" class="button-blue"><i class="fa fa-paypal margin-right-2x text-color-white"></i><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "PAY NOW" : "PAGAR AHORA" ?></button></div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblInvoices" class="stripe width-100">
                        <thead>
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
                                    echo '<tr><th>Check<br/><input type="checkbox" id="chkAll" /></th><th>Created date</th><th>Number</th><th>Receiver</th><th>Total</th><th>Status</th><th></th><th></th><th></th></tr>';
                                } else {
                                    echo '<tr><th>Seleccionar<br/><input type="checkbox" id="chkAll" /></th><th>Fecha creado</th><th>Número</th><th>Destinatario</th><th>Total</th><th>Estado</th><th></th><th></th></th><th></tr>';
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $invoices = CloudEngineSession::getSessionObject()->getObject()->getBills();
                                $shipments = CloudEngineSession::getSessionObject()->getObject()->getShipments();
                                $items = array();
                                
                                $items = array_merge($invoices, $shipments);
                                
                                for ($i = 0; $i < count($items) - 1; $i ++) {
                                    for ($j = $i + 1; $j < count($items); $j ++) {
                                        if ($items[$j]->getCreatedTimestamp() > $items[$i]->getCreatedTimestamp()) {
                                            $temp = $items[$i];
                                            $items[$i] = $items[$j];
                                            $items[$j] = $temp;
                                        }
                                    }
                                }
                                
                                foreach ($items as $i) {
                                    if (method_exists($i, "getBillNumber")) {
                                        $code = "<tr>";
                                    
                                        if ($i->getPendingPayment() > 0) {
                                            $code .= "<td class='text-align-center'><input name='chkItem' type='checkbox' data-type='BILL' data-id='" . $i->getIdBill() . "' /></td>";
                                        } else {
                                            $code .= "<td class='text-align-center'></td>";
                                        }

                                        $code .= "<td class='text-align-center'>" . $i->getCreatedTimestamp() . "</td>";
                                        $code .= "<td class='text-align-center'>" . $i->getBillNumber() . "</td>";
                                        $code .= "<td class='text-align-center'>" . $i->getTo() . "</td>";
                                        $code .= "<td class='text-align-center'>$ " . number_format($i->getTotal(), 2) . " " . $i->getCurrency() . "</td>";
                                        $code .= "<td class='text-align-center'><div class='border-radius " . $i->getPaymentColor() . " padding text-color-white text-size-xs text-weight-bold'>" . $i->getPaymentStatusLanguage(CloudEngineSession::getSessionObject()->getObject()->getLanguage()) . "</div></td>";

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            if ($i->hasPicture()) {
                                                $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC . "Uploads/Invoices/" . $i->getIdBill() . "'>Foto</a></td>";
                                            } else {
                                                $code .= "<td></td>";
                                            }
                                        } else {
                                            if ($i->hasPicture()) {
                                                $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC . "Uploads/Invoices/" . $i->getIdBill() . "'>Picture</a></td>";
                                            } else {
                                                $code .= "<td></td>";
                                            }
                                        }

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            $code .= "<td name='btnViewBill' data-id='" . $i->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</td>";
                                        } else {
                                            $code .= "<td name='btnViewBill' data-id='" . $i->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>View</td>";
                                        }

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            $code .= "<td name='btnTrackingInvoice' data-id='" . $i->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Rastrear</td>";
                                        } else {
                                            $code .= "<td name='btnTrackingInvoice' data-id='" . $i->getIdBill() . "' class='text-align-center text-decoration-underline cursor-pointer'>Tracking</td>";
                                        }

                                        $code .= "</tr>";
                                        echo $code;
                                    } else {
                                        $s = $i;
                                        $code = "<tr>";
                                    
                                        if ($s->getPendingPayment() > 0) {
                                            $code .= "<td class='text-align-center'><input name='chkItem' type='checkbox' data-type='SHIPMENT' data-id='" . $s->getIdShipping() . "' /></td>";
                                        } else {
                                            $code .= "<td class='text-align-center'></td>";
                                        }

                                        $code .= "<td class='text-align-center'>" . $s->getCreatedTimestamp() . "</td>";
                                        $code .= "<td class='text-align-center'>" . $s->getShippingNumber() . "</td>";
                                        $code .= "<td class='text-align-center'>N/A</td>";
                                        $code .= "<td class='text-align-center'>$ " . number_format($s->getTotal(), 2) . " " . $s->getCurrency() . "</td>";
                                        $code .= "<td class='text-align-center'><div class='border-radius " . $s->getPaymentColor() . " padding text-color-white text-size-xs text-weight-bold'>" . $s->getPaymentStatusLanguage(CloudEngineSession::getSessionObject()->getObject()->getLanguage()) . "</div></td>";

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            if ($s->hasPicture()) {
                                                $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC . "Uploads/Invoices/" . $s->getIdShipping() . "'>Foto</a></td>";
                                            } else {
                                                $code .= "<td></td>";
                                            }
                                        } else {
                                            if ($s->hasPicture()) {
                                                $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC . "Uploads/Invoices/" . $s->getIdShipping() . "'>Picture</a></td>";
                                            } else {
                                                $code .= "<td></td>";
                                            }
                                        }

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            $code .= "<td name='btnViewShipment' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Ver</td>";
                                        } else {
                                            $code .= "<td name='btnViewShipment' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>View</td>";
                                        }

                                        if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                            $code .= "<td name='btnTracking' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Rastrear</td>";
                                        } else {
                                            $code .= "<td name='btnTracking' data-id='" . $s->getIdShipping() . "' class='text-align-center text-decoration-underline cursor-pointer'>Tracking</td>";
                                        }

                                        $code .= "</tr>";
                                        echo $code;
                                    }   
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
                    $("[name=chkItem]").prop("checked",true);
                } else {
                    $("[name=chkItem]").prop("checked",false);
                }
                simulate();
            });
            
            $(document).ready( function () {
                if ($("#hdLanguage").val() === "SPANISH") {
                    $('#tblInvoices').DataTable({
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
                    $('#tblInvoices').DataTable({
                        ordering: false
                    });
                }
                
                $("#btnPayNow").on("click", function() {
                    var entities = new Array();
                    var checks = $("[name=chkItem]");
                    
                    for (var i=0; i<checks.length; i++) {
                        if (checks[i].checked) {
                            entities.push(new Entity($(checks[i]).data("id"), $(checks[i]).data("type")));
                        }
                    }
                    
                    if (entities.length > 0) {
                        $.redirect("/Platform/ElectronicPayment/", {Entities : JSON.stringify(entities)});
                    } else {
                        if ($("#hdLanguage").val() === "SPANISH") {
                            alert("Por favor selecciona uno o más items.");
                        } else {
                            alert("Please check one item at least.");
                        }
                    }
                });
            });
            
            $("[name=btnViewBill]").on("click", function() {
                $.redirect(URL_API + "PDF/Bill.php", {IdBill: $(this).data("id")}, "POST", "_blank");
            });
            
            $("[name=btnViewShipment]").on("click", function() {
                $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $("[name=btnTracking]").on("click", function() {
                $.redirect("Tracking.php", {IdShipment: $(this).data("id")}, "POST");
            });
            
            $("[name=btnTrackingInvoice]").on("click", function() {
                $.redirect("InvoiceTracking.php", {IdBill: $(this).data("id")}, "POST");
            });
        </script>
    </body>
</html>