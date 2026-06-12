<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Facturas / Envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $shipping = ShippingDAO::getShippingById(CloudEngineHTTP::getPostVar("IdShipment"));
    
    if ($shipping == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Customer/Shipments.php");
    }
    
    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Rastreo");
    } else {
        $layout->setTitle("Tracking");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Trazabilidad", PUBLIC_PATH_PLATFORM . "Customer/Invoices.php"); ?>
            <div id="frmTracking" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Date, number, customer and sequence -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 padding-2x" >
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>';
                                } else {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Date</div>';
                                }
                            ?>
                            <div class="float-left width-100"><?php echo $shipping->getCreatedTimestamp(); ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Código de rastreo</div>';
                                } else {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Tracking No.</div>';
                                }
                            ?>
                            <div class="float-left width-100"><?php echo $shipping->getShippingNumber(); ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Cliente</div>';
                                } else {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Customer</div>';
                                }
                            ?>
                            <div class="float-left width-100"><?php echo $shipping->getPurchases()[0]->getCustomer()->getNames() ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Total</div>
                            <div class="float-left width-100"><?php echo number_format($shipping->getTotal(),2) . " " . $shipping->getCurrency() ?></div>
                        </div>
                    </div>
                </div>
                <!-- Tracking -->
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table class="table width-100">
                        <thead>
                            <tr>
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<th>Movimiento</th><th>Fecha</th>';
                                } else {
                                    echo '<th>Movement</th><th>Date</th>';
                                }
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tracking = $shipping->getTracking();
                                foreach ($tracking as $t) {
                                    $code = "<tr>";
                                    $code .= "<td>" . $t->getDescription() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $t->getCreatedTimestamp() . "</td>";
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
            $("#btnSave").on("click", function(e) {
                var frmTracking = new Form($("#frmTracking"));
                if (frmTracking.validate()) {
                    $.ajax({
                        url: URL_API + "Shipment/Tracking.php",
                        type: "POST",
                        data: {
                            IdShipping: $("#hdIdShipping").val(),
                            Text: $("#txtText").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            document.location.reload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
