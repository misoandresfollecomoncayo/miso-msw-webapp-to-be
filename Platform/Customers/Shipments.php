<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Clientes")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $customer = CustomerDAO::getCustomerById(CloudEngineHTTP::getPostVar("IdCustomer"));
    
    if ($customer == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Customers");
    }
    
    $layout = new Layout();
    $layout->setTitle("Ver cliente");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Ver cliente", PUBLIC_PATH_PLATFORM . "Customers"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdIdCustomer" value="<?php echo $customer->getIdCustomer(); ?>" />
                <!-- Tabs -->
                <div class="display-table width-100 padding-bottom-3x text-align-right">
                    <div id="btnPurchases" class="cursor-pointer display-inline-block padding-2x">Compras</div>
                    <div class="profile-tab display-inline-block padding-2x text-weight-bold">Envíos</div>
                    <div id="btnInvoices" class="cursor-pointer display-inline-block padding-2x">Facturas manuales</div>
                    <div id="btnAlerts" class="cursor-pointer display-inline-block padding-2x">Alertas de compras</div>
                    <div id="btnProfile" class="cursor-pointer display-inline-block padding-2x">Perfil</div>
                </div>
                <!-- Personal info -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Cliente</div>
                        <div class="float-left width-75"><?php echo $customer->getNames(); ?></div>
                    </div>
                    <div class="display-table width-100 margin-top-2x">
                        <div class="float-left width-25 text-weight-bold">Casillero</div>
                        <div class="float-left width-75"><?php echo $customer->getLockerNumber(); ?></div>
                    </div>
                    <div class="display-table width-100 margin-top-2x">
                        <div class="float-left width-25 text-weight-bold">Pagos realizados</div>
                        <div class="float-left width-75">$ <?php echo $customer->getPaid(); ?></div>
                    </div>
                    <div class="display-table width-100 margin-top-2x">
                        <div class="float-left width-25 text-weight-bold">Pagos pendientes</div>
                        <div class="float-left width-75">$ <?php echo $customer->getPendingPayment(); ?></div>
                    </div>
                </div>
                <!-- Purchases -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table id="tblPurchases" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Fecha registrado</th>
                                <th>Número</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $shipments = $customer->getShipments();
                                foreach ($shipments as $s) {
                                    $code = "<tr>";
                                    $code .= "<td class='text-align-center'>" . $s->getCreatedTimestampHuman() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $s->getShippingNumber() . "</td>";
                                    $code .= "<td class='text-align-center'>$ " . number_format($s->getTotal(), 2) . " " . $s->getCurrency() . "</td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius padding " . $s->getPaymentColor() . " text-size-xs text-weight-bold text-color-white'>" . $s->getPaymentStatus() . "</div></td>";
                                    $code .= "<td><div name='btnDetail' data-id='" . $s->getIdShipping() . "' class='text-decoration-underline cursor-pointer'>Ver</div></td>";
                                    
                                    if (!$s->wasAnnulled()) {
                                        $code .= "<td style='white-space:nowrap' name='btnPayment' data-id='" . $s->getIdShipping() . "' class='text-decoration-underline cursor-pointer'>Registrar pago</td>";
                                        $code .= "<td name='btnTracking' data-id='" . $s->getIdShipping() . "' class='text-decoration-underline cursor-pointer'>Trazabilidad</td>";
                                        $code .= "<td name='btnAnnull' data-id='" . $s->getIdShipping() . "' class='text-decoration-underline cursor-pointer'>Anular</td>";
                                    } else {
                                        $code .= "<td></td>";
                                        $code .= "<td></td>";
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
            $("#btnPurchases").on("click", function(e) {
                $.redirect("Purchases.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnInvoices").on("click", function(e) {
                $.redirect("Invoices.php", {IdCustomer : $("#hdIdCustomer").val()});
            });

            $("#btnAlerts").on("click", function(e) {
                $.redirect("Alerts.php", {IdCustomer : $("#hdIdCustomer").val()});
            });

            $("#btnProfile").on("click", function(e) {
                $.redirect("Edit.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $(document).ready( function () {
                $('#tblPurchases').DataTable({
                    ordering: false
                });
            });
            
            $(document).on("click", "[name=btnDetail]", function(e) {
                $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $(document).on("click", "[name=btnPayment]", function(e) {
                $.redirect("../Shipments/Payment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $(document).on("click", "[name=btnTracking]", function(e) {
                $.redirect("../Shipments/Tracking.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $("[name=btnAnnull]").on("click", function() {
                if (confirm("¿Confirma anular el registro?")) {
                    $.ajax({
                        url: URL_API + "Shipment/Annull.php",
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
