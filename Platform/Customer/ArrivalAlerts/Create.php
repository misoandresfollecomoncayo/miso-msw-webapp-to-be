<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Alerta de compras")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Nueva alerta");
    } else {
        $layout->setTitle("New alert");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "New alert" : "Nueva alerta", PUBLIC_PATH_PLATFORM . "Customer/ArrivalAlerts/"); ?>
            <div id="frmNew" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Tracking number -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Tracking number" : "Número de rastreo"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="false" data-name="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Tracking number" : "Número de rastreo"; ?>" id="txtTrackingNumber" /></div>
                    </div>
                    <!-- Detail -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Detail" : "Detalle"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Detail" : "Detalle"; ?>" id="txtDetail" /></div>
                    </div>
                    <!-- Items -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Quantity" : "Cantidad"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="number" data-required="true" data-name="<?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Quantity" : "Cantidad"; ?>" id="txtItems" /></div>
                    </div>
                    <!-- Store -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Store" : "Tienda"; ?></div>
                        <div class="float-left width-75">
                            <?php
                                $stores = StoreDAO::getStores();
                                $slStore = new CloudEngineHTMLSelect();
                                $slStore->addPropertie("class", "select-underline");
                                $slStore->addPropertie("id", "slStore");
                                $slStore->addPropertie("data-required", "true");
                                foreach ($stores as $s) {
                                    $slStore->addOption($s->getName(), $s->getIdStore());
                                }
                                $slStore->render();
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red"><?php echo CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? "SEND" : "ENVIAR"; ?></button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnSave").on("click", function(e) {
                var frmNew = new Form($("#frmNew"));
                if (frmNew.validate()) {
                    $.ajax({
                        url: URL_API + "ArrivalAlert/Create.php",
                        type: "POST",
                        data: {
                            TrackingNumber: $("#txtTrackingNumber").val(),
                            Purchase: $("#txtDetail").val(),
                            Items: $("#txtItems").val(),
                            IdStore: $("#slStore").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            new Notification("SUCCESS", r.body);
                            frmNew.reset();
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
