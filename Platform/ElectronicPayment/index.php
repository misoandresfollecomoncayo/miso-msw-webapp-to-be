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
        header("location:" . PUBLIC_PATH_PLATFORM);
    }
    
    $bills = array();
    $shipments = array();
    
    foreach ($entities as $e) {
        if (isset($e->type) && isset($e->id)) {
            if ($e->type == "BILL") {
                array_push($bills, BillDAO::getBillById($e->id));
            } else {
                array_push($shipments, ShippingDAO::getShippingById($e->id));
            }
        }
    }
    
    $COP = 0;
    $USD = 0;
    $TRMAdd = 30;
    $total = 0;
    $COPConverted = 0;
    
    foreach ($bills as $b) {
        if ($b->getCurrency() == "COP") {
            $COP += $b->getPendingPayment();
        } else {
            $USD += $b->getPendingPayment();
        }
    }
    
    foreach ($shipments as $s) {
        if ($s->getCurrency() == "COP") {
            $COP += $s->getPendingPayment();
        } else {
            $USD += $s->getPendingPayment();
        }
    }    
    
    // USD to COP equivalent
    if ($COP > 0) {
        $ch = curl_init('http://apilayer.net/api/live?access_key=50133cfe09474beef1536f2d01b38cca&currencies=COP');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $TRM = json_decode($response)->quotes->USDCOP;
        $TRM += $TRMAdd;
        
        $COPConverted = $COP / $TRM;
    }
    
    $total = $USD + $COPConverted;
    
    $layout = new Layout();
    $layout->setTitle("Pago electrónico");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Pago electrónico", null); ?>
            <div id="frmPayment" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto" style="display: flex; flex-direction: column; align-items: center" >
                <div style="width: 70%" class="background-color-white border-radius box-shadow mobile-width-100">
                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; width: 100%; background: #f44336; border-radius: 3px 3px 0 0" class="padding-4x">
                        <div class="text-size-m text-weight-bold text-color-white">PAGO ELECTRÓNICO</div>
                        <div class="text-size-m text-weight-bold text-color-white">$ <?php echo number_format($total,2) ?> USD</div>
                    </div>
                    <!-- Body -->
                    <input type="hidden" id="hdTotal" value="<?php echo number_format($total,2) ?>" />
                    <div class="padding-4x">
                        <?php
                            // Bills
                            if (count($bills) > 0) {
                                $code = '<!-- Bills -->';
                                $code .= '<div class="padding-2x border-radius margin-bottom-2x" style="border: 1px solid rgba(0,0,0,.2)">';
                                $code .= '<div class="text-weight-bold">FACTURA(S) A PAGAR</div>';

                                foreach ($bills as $b) {
                                    $code .= '<div class="margin-top-3x" style="display: flex; align-items: center; justify-content: space-between">';
                                    $code .= '<div>' . $b->getBillNumber() . '</div>';
                                    if ($b->getCurrency() == "COP") {
                                        $code .= '<div class="width-50 text-align-right" style="border-bottom:1px dashed rgba(0,0,0,.2)">$ ' . number_format($b->getPendingPayment(),2) . ' ' . $b->getCurrency() . ' = $ ' . number_format($b->getPendingPayment() / $TRM, 2) . ' USD</div>';
                                    } else {
                                        $code .= '<div class="width-50 text-align-right" style="border-bottom:1px dashed rgba(0,0,0,.2)">$ ' . number_format($b->getPendingPayment(),2) . ' ' . $b->getCurrency() . '</div>';
                                    }
                                    $code .= '</div>';
                                }

                                $code .= '</div>';
                                echo $code;
                            }
                            
                            // Shipments
                            if (count($shipments) > 0) {
                                $code = '<!-- Shipments -->';
                                $code .= '<div class="padding-2x border-radius margin-bottom-2x" style="border: 1px solid rgba(0,0,0,.2)">';
                                $code .= '<div class="text-weight-bold">ENVÍO(S) A PAGAR</div>';

                                foreach ($shipments as $s) {
                                    $code .= '<div class="margin-top-3x" style="display: flex; align-items: center; justify-content: space-between">';
                                    $code .= '<div>' . $s->getShippingNumber() . '</div>';
                                    if ($s->getCurrency() == "COP") {
                                        $code .= '<div class="width-50 text-align-right" style="border-bottom:1px dashed rgba(0,0,0,.2)">$ ' . number_format($s->getPendingPayment(),2) . ' ' . $s->getCurrency() . ' = $ ' . number_format($s->getPendingPayment() / $TRM, 2) . ' USD</div>';
                                    } else {
                                        $code .= '<div class="width-50 text-align-right" style="border-bottom:1px dashed rgba(0,0,0,.2)">$ ' . number_format($s->getPendingPayment(),2) . ' ' . $s->getCurrency() . '</div>';
                                    }
                                    $code .= '</div>';
                                }

                                $code .= '</div>';
                                echo $code;
                            }
                        ?>
                        
                        <!-- Total -->
                        <div class="padding-2x border-radius" style="border: 1px solid rgba(0,0,0,.2)">
                            <div class="text-weight-bold">RESUMEN</div>
                            <?php
                                if ($COP > 0) {
                                    echo '<div class="margin-top-3x" style="display: flex; align-items: center; justify-content: space-between">';
                                    echo '<div>CONVERSIÓN DE MONEDA</div>';
                                    echo '<div class="width-50 text-align-right" style="border-bottom:1px dashed rgba(0,0,0,.2)">$ 1 USD = $ ' . number_format($TRM, 2) . ' COP</div>';
                                    echo '</div>';
                                }
                            ?>
                            <div class="margin-top-3x" style="display: flex; align-items: center; justify-content: space-between">
                                <div>PAGO TOTAL</div>
                                <div class="width-50 text-align-right" style="font-size: 20px; font-weight: bold; border-bottom:1px dashed rgba(0,0,0,.2)">$ <?php echo number_format($total, 2) ?> USD</div>
                            </div>
                        </div>
                        
                        <!-- PayPal button -->
                        <div class="margin-top-4x" style="display: flex; justify-content: center">
                            <div style="width: 200px" id="paypal-button-container"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script src="https://www.paypal.com/sdk/js?client-id=AYxjhlQjpBJiz_YnG2qPeFUt6C9-FiJ8ArwMPGZWttwMRm0G0c3y8iVk7j0_OL3oR8J5M4PaSOGJ5Oau"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo str_replace(",","",number_format($total,2)) ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        if (details.status === "COMPLETED") {
                            showPreload();
                            $.redirect("/Platform/ElectronicPayment/Result.php" , {Entities : JSON.stringify(<?php echo CloudEngineHTTP::getPostVar("Entities") ?>), Total: $("#hdTotal").val() });
                        }
                    });
                }
            }).render('#paypal-button-container');
        </script>
    </body>
</html>
