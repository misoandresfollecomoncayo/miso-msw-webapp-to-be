<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Seguridad")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject()->getObject();
    
    $layout = new Layout();
    if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER &&
        $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH) {
        $layout->setTitle("Security");
    } else {
        $layout->setTitle("Seguridad");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Security" : "Seguridad", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmUpdate" class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Current -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Current password" : "Clave actual" ?></div>
                        <div class="float-left width-75"><input autofocus="on" class="input-text-underline" type="password" data-required="true" data-name="<?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Current password" : "Clave actual" ?>" id="txtCurrent" /></div>
                    </div>
                    <!-- New -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "New password" : "Nueva clave" ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="password" data-required="true" data-name="<?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "New password" : "Nueva clave" ?>" id="txtNew" /></div>
                    </div>
                    <!-- Confirm -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Confirm new password" : "Confirmar nueva clave" ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="password" data-required="true" data-name="<?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Confirm new password" : "Confirmar nueva clave" ?>" id="txtConfirm" /></div>
                    </div>
                    <!-- Actions -->
                    <div class="display-table padding-top-4x width-100 text-align-right">
                        <button id="btnSave" class="button-red"><?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "SAVE" : "GUARDAR" ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            $("#btnSave").on("click", function(e) {
                var frmUpdate = new Form($("#frmUpdate"));
                if (frmUpdate.validate()) {
                    $.ajax({
                        url: URL_API + "Session/ChangePassword.php",
                        type: "POST",
                        data: {
                            Current: $("#txtCurrent").val(),
                            New: $("#txtNew").val(),
                            Confirm: $("#txtConfirm").val()
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
                            frmUpdate.reset();
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>