<?php
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);

    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Inicio")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Dashboard");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Dashboard"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <?php
                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Cliente") {
                        $code = "<div class='width-100 display-table'>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-4x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-red'>" . (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Notificaciones sin leer" : "Unread notifications") . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top-3x text-color-red'>" . count(CloudEngineSession::getSessionObject()->getObject()->getUnreadNotifications()) . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-4x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-green'>" . (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Peso en bodega" : "Warehouse weight") . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top-3x text-color-green'>" . CloudEngineSession::getSessionObject()->getObject()->warehouseWeight() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-4x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-orange'>" . (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Compras" : "Purchases") . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top-3x text-color-orange'>" . count(CloudEngineSession::getSessionObject()->getObject()->getPurchases()) . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-4x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-yellow'>" . (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Envíos" : "Shipments") . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top-3x text-color-yellow'>" . count(CloudEngineSession::getSessionObject()->getObject()->getShipments()) . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-4x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-green'>" . (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH ? "Facturas" : "Invoices") . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top-3x text-color-green'>" . count(CloudEngineSession::getSessionObject()->getObject()->getBills()) . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "</div>";
                        
                        echo $code;
                    } else if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                        $poundsColombia = DashboardDAO::getPoundsColombia();
                        $poundsEcuador = DashboardDAO::getPoundsEcuador();
                        $shipmentsColombia = DashboardDAO::getShipmentsColombia();
                        $shipmentsEcuador = DashboardDAO::getShipmentsEcuador();
                        $manualInvoices = DashboardDAO::getManualInvoices();
                        $totalCashCOP = DashboardDAO::getTotalCashCOP();
                        $totalCashUSD = DashboardDAO::getTotalCashUSD();
                        $pendingCashCOP = DashboardDAO::getPendingCashCOP();
                        $pendingCashUSD = DashboardDAO::getPendingCashUSD();
                        $activeLockers = DashboardDAO::getActiveLockers();
                        $inWarehousePurchases = DashboardDAO::getInWarehousePurchases();
                        
                        $code = "<div class='width-100 display-table'>";
                        
                        $code .= "<div class='width-1-3 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-yellow'>" . $inWarehousePurchases->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-yellow'>" . $inWarehousePurchases->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-1-3 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-green'>" . $manualInvoices->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-green'>" . $manualInvoices->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-1-3 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-red'>" . $activeLockers->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-red'>" . $activeLockers->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "</div>";
                        
                        $code .= "<div class='width-100 display-table'>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-green'>" . $totalCashCOP->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-green'>$ " . number_format($totalCashCOP->getValue(),2) . " COP</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-red'>" . $pendingCashCOP->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-red'>$ " . number_format($pendingCashCOP->getValue(),2) . " COP</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-orange'>" . $poundsColombia->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-orange'>" . $poundsColombia->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-yellow'>" . $shipmentsColombia->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-yellow'>" . $shipmentsColombia->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "</div>";
                        
                        $code .= "<div class='width-100 display-table'>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-green'>" . $totalCashUSD->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-green'>$ " . number_format($totalCashUSD->getValue(),2) . " USD</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-red'>" . $pendingCashUSD->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-red'>$ " . number_format($pendingCashUSD->getValue(),2) . " USD</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-orange'>" . $poundsEcuador->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-orange'>" . $poundsEcuador->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "<div class='width-25 float-left padding-2x'>";
                        $code .= "<div class='background-color-white padding-2x border-radius box-shadow'>";
                        $code .= "<div class='text-align-center text-color-yellow'>" . $shipmentsEcuador->getName() . "</div>";
                        $code .= "<div class='text-align-center text-size-l text-weight-bold margin-top text-color-yellow'>" . $shipmentsEcuador->getValue() . "</div>";
                        $code .= "</div>";
                        $code .= "</div>";
                        
                        $code .= "</div>";
                        
                        echo $code;
                    }
                ?>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            
        </script>
    </body>
</html>