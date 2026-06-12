<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
        
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        (!CloudEngineSession::getSessionObject()->hasPermission("Facturas manuales")
            && !CloudEngineSession::getSessionObject()->hasPermission("Facturas / Envíos"))) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $entities = json_decode(CloudEngineHTTP::getPostVar("Entities"));
    
    if (count($entities) == 0) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Bills");
    }
    
    $bills = array();
    $shipments = array();
    
    foreach ($entities as $e) {
        if ($e->type == "BILL") {
            array_push($bills, BillDAO::getBillById($e->id));
        } else {
            array_push($shipments, ShippingDAO::getShippingById($e->id));
        }
    }
    
    foreach ($bills as $b) {
        if (CloudEngineSession::getSessionObject() == Access::TYPE_SYSTEM_USER) {
            BillPartialPaymentDAO::create(date("Y-m-d H:i:s"), $b->getPendingPayment(), 'b29659c5-6e97-4d46-aaf4-7141efb7530e', $b->getIdBill(), CloudEngineSession::getSessionObject()->getIdRegister());
        } else {
            BillPartialPaymentDAO::create(date("Y-m-d H:i:s"), $b->getPendingPayment(), 'b29659c5-6e97-4d46-aaf4-7141efb7530e', $b->getIdBill(), "d7cb08fe-14b0-45bf-9456-62d8d26d0000");
        }
        #EmailEngine::electronicPayment("BILL", $b->getIdBill(), $b->getPendingPayment());
    }
    
    foreach ($shipments as $s) {
        if (CloudEngineSession::getSessionObject() == Access::TYPE_SYSTEM_USER) {
            ShippingPartialPaymentDAO::create(date("Y-m-d H:i:s"), $s->getPendingPayment(), 'b29659c5-6e97-4d46-aaf4-7141efb7530e', $s->getIdShipping(), CloudEngineSession::getSessionObject()->getIdRegister());
        } else {
            ShippingPartialPaymentDAO::create(date("Y-m-d H:i:s"), $s->getPendingPayment(), 'b29659c5-6e97-4d46-aaf4-7141efb7530e', $s->getIdShipping(), "d7cb08fe-14b0-45bf-9456-62d8d26d0000");
        }
        #EmailEngine::electronicPayment("SHIPMENT", $s->getIdShipping(), $s->getPendingPayment());
    }
    
    $layout = new Layout();
    $layout->setTitle("Pago electrónico");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Pago electrónico", null); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto" style="display: flex; flex-direction: column; align-items: center">
                <div style="width: 70%" class="background-color-white border-radius box-shadow mobile-width-100">
                    <input type="hidden" id="hdUserType" value="<?php echo CloudEngineSession::getSessionObject()->getType() ?>" />
                    <!-- Header -->
                    <div class="padding-4x" style="display: flex; flex-direction: column; align-items: center">
                        <div style="width: 65px; height: 65px; border-radius: 100%; background-color: #4caf50; display: flex; justify-content: center; align-items: center; font-size: 30px"><i class="fa fa-check text-color-white"></i></div>
                        <div class="margin-top-3x">Ha realizado un pago por</div>
                        <div class="margin-top-3x text-size-l">$ <?php echo CloudEngineHTTP::getPostVar("Total"); ?> USD</div>
                    </div>
                    <!-- Info -->
                    <div class="padding-4x">
                        <div style="display: flex; justify-content: space-between" class="padding-top-2x padding-bottom-2x border-bottom-dark">
                            <div class="text-weight-bold">Pago desde</div>
                            <div>PayPal</div>
                        </div>
                        <div style="display: flex; justify-content: space-between" class="padding-top-2x padding-bottom-2x border-bottom-dark">
                            <div class="text-weight-bold">Fecha de pago</div>
                            <div><?php echo date("M d, Y"); ?></div>
                        </div>
                    </div>
                    <!-- Contact -->
                    <div class="padding-4x">En caso de preguntas o inquietudes, por favor contáctenos al correo electrónico info@uniexpresssolutions.com o llámenos al 754-229-6774.</div>
                    <!-- Actions -->
                    <div class="padding-4x">
                        <div style="text-align: right">
                            <button id="btnFinish" class="button-blue display-inline-block text-decoration-none">FINALIZAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnFinish").on("click", function() {
                var userType = $("#hdUserType").val();
                if (userType === "SystemUser") {
                    $.redirect("/Platform/Bills/");
                } else {
                    $.redirect("/Platform/Customer/Invoices.php");
                }
            });
        </script>
    </body>
</html>
