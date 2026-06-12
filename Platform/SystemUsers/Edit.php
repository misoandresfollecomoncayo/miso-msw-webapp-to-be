<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Usuarios sistema")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $systemUser = SystemUserDAO::getSystemUserById(CloudEngineHTTP::getPostVar("IdSystemUser"));
    
    if ($systemUser == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "SystemUsers");
    }
    
    $layout = new Layout();
    $layout->setTitle("Editar usuario sistema");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Editar usuario sistema", PUBLIC_PATH_PLATFORM . "SystemUsers/"); ?>
            <div id="frmUser" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <input type="hidden" id="hdIdSystemUser" value="<?php echo $systemUser->getIdSystemUser(); ?>" />
                    <!-- Names -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombres" id="txtNames" value="<?php echo $systemUser->getNames(); ?>" /></div>
                    </div>
                    <!-- Email -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Correo electrónico" id="txtEmail" value="<?php echo $systemUser->getEmail(); ?>" /></div>
                    </div>
                    <!-- Role -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Rol</div>
                        <div class="float-left width-75">
                            <?php
                                $roles = RoleDAO::getRoles();
                            
                                $slRole = new CloudEngineHTMLSelect();
                                $slRole->addPropertie("class", "select-underline");
                                $slRole->addPropertie("id", "slRole");
                                $slRole->addPropertie("data-required", "true");
                                $slRole->addPropertie("data-name", "Rol");
                                $slRole->addOption("Selecciona una opción", "");
                                foreach ($roles as $r) {
                                    $slRole->addOption($r->getName(), $r->getIdRole());
                                }
                                $slRole->setSelected($systemUser->getRole()->getIdRole());
                                $slRole->render();
                            ?>
                        </div>
                    </div>
                    <!-- Send request shipment notification -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">¿Notificar solicitud envío?</div>
                        <div class="float-left width-75">
                            <?php
                                $slRequestShipmentNotification = new CloudEngineHTMLSelect();
                                $slRequestShipmentNotification->addPropertie("class", "select-underline");
                                $slRequestShipmentNotification->addPropertie("id", "slRequestShipmentNotification");
                                $slRequestShipmentNotification->addPropertie("data-required", "true");
                                $slRequestShipmentNotification->addOption("SI", 1);
                                $slRequestShipmentNotification->addOption("NO", 0);
                                $slRequestShipmentNotification->setSelected($systemUser->sendRequestShipmentNotification());
                                $slRequestShipmentNotification->render();
                            ?>
                        </div>
                    </div>
                    <!-- Send alert arrival notification -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">¿Notificar alerta de llegada?</div>
                        <div class="float-left width-75">
                            <?php
                                $slAlertArrivalNotification = new CloudEngineHTMLSelect();
                                $slAlertArrivalNotification->addPropertie("class", "select-underline");
                                $slAlertArrivalNotification->addPropertie("id", "slAlertArrivalNotification");
                                $slAlertArrivalNotification->addPropertie("data-required", "true");
                                $slAlertArrivalNotification->addOption("SI", 1);
                                $slAlertArrivalNotification->addOption("NO", 0);
                                $slAlertArrivalNotification->setSelected($systemUser->sendAlertArrivalNotification());
                                $slAlertArrivalNotification->render();
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnSave").on("click", function(e) {
                var frmUser = new Form($("#frmUser"));
                if (frmUser.validate()) {
                    $.ajax({
                        url: URL_API + "SystemUser/Edit.php",
                        type: "POST",
                        data: {
                            IdSystemUser: $("#hdIdSystemUser").val(),
                            Names: $("#txtNames").val(),
                            Email: $("#txtEmail").val(),
                            IdRole: $("#slRole").val(),
                            SendRequestShipmentNotification: $("#slRequestShipmentNotification").val(),
                            SendAlertArrivalNotification: $("#slAlertArrivalNotification").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                            } else {
                                new Notification("SUCCESS", r.body);
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
