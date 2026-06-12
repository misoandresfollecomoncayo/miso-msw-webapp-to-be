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
                    <div id="btnShipments" class="cursor-pointer display-inline-block padding-2x">Envíos</div>
                    <div id="btnInvoices" class="cursor-pointer display-inline-block padding-2x">Facturas manuales</div>
                    <div class="profile-tab display-inline-block padding-2x text-weight-bold">Alertas de compras</div>
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
                    <table id="tblAlerts" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tracking No.</th>
                                <th>Detalle</th>
                                <th>Cantidad</th>
                                <th>Tienda</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $alerts = $customer->getArrivalAlerts();
                                foreach ($alerts as $a) {
                                    $code = "<tr>";
                                    $code .= "<td class='text-align-center'>" . $a->getCreatedTimestamp() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getTrackingNumber() . "</td>";
                                    $code .= "<td>" . $a->getPurchase() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getItems() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $a->getStore()->getName() . "</td>";
                                    $code .= "<td class='text-align-center'><div class='border-radius text-size-xs padding text-weight-bold text-color-white " . $a->getStatusColor() . "'>" . $a->getStatus() . "</div></td>";
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
            
            $("#btnShipments").on("click", function(e) {
                $.redirect("Shipments.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnInvoices").on("click", function(e) {
                $.redirect("Invoices.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnProfile").on("click", function(e) {
                $.redirect("Edit.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $(document).ready( function () {
                $('#tblAlerts').DataTable();
            });
        </script>
    </body>
</html>
