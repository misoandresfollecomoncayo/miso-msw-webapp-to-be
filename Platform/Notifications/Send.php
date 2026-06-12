<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Enviar notificación")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject()->getObject();
    
    $layout = new Layout();
    $layout->setTitle("Enviar notificación");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Enviar notificación", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmSend" class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- User -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Destinatario</div>
                        <div class="float-left width-75">
                            <?php
                                $systemUsers = SystemUserDAO::getSystemUsers();
                                $slUser = new CloudEngineHTMLSelect();
                                $slUser->addPropertie("class", "select-underline");
                                $slUser->addPropertie("id", "slUser");
                                $slUser->addPropertie("data-name", "Destinatario");
                                $slUser->addPropertie("data-required", "true");
                                $slUser->addOption("Seleccione un destinatario", "");
                                $slUser->addOption("Todos los clientes", "*Customers");
                                $slUser->addOption("Todos los administradores", "*Administrators");
                                foreach ($systemUsers as $s) {
                                    $slUser->addOption($s->getNames(), $s->getIdSystemUser());
                                }
                                $slUser->render();
                            ?>
                        </div>
                    </div>
                    <!-- Message -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Mensaje</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Mensaje" id="txtMessage" /></div>
                    </div>
                    <!-- Actions -->
                    <div class="display-table padding-top-4x width-100 text-align-right">
                        <button id="btnSend" class="button-red">ENVIAR</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            $("#btnSend").on("click", function(e) {
                var frmSend = new Form($("#frmSend"));
                if (frmSend.validate()) {
                    $.ajax({
                        url: URL_API + "Notification/Send.php",
                        type: "POST",
                        data: {
                            User: $("#slUser").val(),
                            Message: $("#txtMessage").val()
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
                                frmSend.reset();
                            }
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>