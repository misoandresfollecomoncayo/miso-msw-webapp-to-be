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
    
    $bill = BillDAO::getBillById(CloudEngineHTTP::getPostVar("IdBill"));
    
    if ($bill == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Customer/Invoices.php");
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
                            <div class="float-left width-100"><?php echo $bill->getCreatedTimestampHuman() ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Código de rastreo</div>';
                                } else {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Tracking No.</div>';
                                }
                            ?>
                            <div class="float-left width-100"><?php echo $bill->getBillNumber() ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Cliente</div>';
                                } else {
                                    echo '<div class="float-left width-100 text-weight-bold margin-bottom-2x">Customer</div>';
                                }
                            ?>
                            <div class="float-left width-100"><?php echo $bill->getCustomer()->getNames() ?></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Total</div>
                            <div class="float-left width-100">$ <?php echo number_format($bill->getTotal(),2) . " " . $bill->getCurrency() ?></div>
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
                                    echo '<th style="width:20px;"></th><th>Caja</th><th>Movimiento</th>';
                                } else {
                                    echo '<th style="width:20px;"></th><th>Box</th><th>Movement</th>';
                                }
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $items = $bill->getItems();
                                foreach ($items as $i) {
                                    $tracking = $i->getTracking();
                                    
                                    $code = "<tr>";
                                    
                                    if ($i->wasDelivered()) {
                                        $code .= "<td class='text-align-center'><div style='width:10px; height:10px; border-radius:10px; background:#4caf50'></div></td>";
                                    } else {
                                        $code .= "<td></td>";
                                    }
                                    
                                    $code .= "<td class='text-align-center'><b>" . $i->getBoxNumber() . "</b></td>";
                                    $code .= "<td></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                    
                                    foreach ($tracking as $t) {
                                        $code = "<tr>";
                                        $code .= "<td></td>";
                                        $code .= "<td class='text-align-center'>" . substr($t->getCreatedTimestamp(),0,10) . "</td>";
                                        $code .= "<td>" . $t->getDescription() . "</td>";
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
