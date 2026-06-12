<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Contáctanos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject()->getObject();
    
    $layout = new Layout();
    if ($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Contáctanos");
    } else {
        $layout->setTitle("Contact Us");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH ? "Contáctanos" : "Contact Us", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 display-table">
                    <div class="width-25 float-left padding">
                        <div class="background-color-white border-radius box-shadow padding-3x text-align-center" style="height: 167px">
                            <i class="fa fa-envelope" style="font-size: 35px !important"></i>
                            <?php
                                if ($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">CORREO ELECTRÓNICO</div>';
                                } else {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">EMAIL</div>';
                                }
                            ?>
                            <div class="text-align-center margin-top-2x" style="word-break: break-all">info@uniexpresssolutions.com</div>
                        </div>
                    </div>
                    <div class="width-25 float-left padding">
                        <div class="background-color-white border-radius box-shadow padding-3x text-align-center" style="height: 167px">
                            <i class="fa fa-map-marker" style="font-size: 35px !important"></i>
                            <?php
                                if ($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">DIRECCIÓN</div>';
                                } else {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">ADDRESS</div>';
                                }
                            ?>
                            <div class="text-align-center margin-top-2x">13794 NW 4th Street, Suite 201<br/>Sunrise, FL 33325</div>
                        </div>
                    </div>
                    <div class="width-25 float-left padding">
                        <div class="background-color-white border-radius box-shadow padding-3x text-align-center" style="height: 167px">
                            <i class="fa fa-phone" style="font-size: 35px !important"></i>
                            <?php
                                if ($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">TELÉFONOS</div>';
                                } else {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">PHONES</div>';
                                }
                            ?>
                            <div class="text-align-center margin-top-2x">(954) 835-5933<br/>(954) 812-8778<br/>(954) 801-4845</div>
                        </div>
                    </div>
                    <div class="width-25 float-left padding">
                        <div class="background-color-white border-radius box-shadow padding-3x text-align-center" style="height: 167px">
                            <i class="fa fa-clock-o" style="font-size: 35px !important"></i>
                            <?php
                                if ($sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH) {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">HORARIO DE OFICINA</div>';
                                    echo '<div class="text-align-center margin-top-2x">Lunes - Viernes</div>';
                                } else {
                                    echo '<div class="text-weight-bold text-size-xs text-align-center margin-top-4x">OFFICE HOURS</div>';
                                    echo '<div class="text-align-center margin-top-2x">Monday - Friday</div>';
                                }
                            ?>
                            <div>09:00 AM a 05:00 PM</div>
                        </div>
                    </div>
                </div>
                <div class="padding">
                    <div id="frmMessage" class="width-100 padding-4x background-color-white border-radius box-shadow margin-top">
                        <!-- Message -->
                        <div class="display-table padding-top-3x padding-bottom-3x width-100">
                            <div class="float-left width-100 text-weight-bold"><?php echo $sessionUser->getLanguage() == Customer::LANGUAGE_SPANISH ? "Envíanos tu mensaje" : "Send us your message" ?></div>
                            <div class="float-left width-100 margin-top-2x">
                                <textarea style="resize: none; height: 100px" autofocus="on" class="input-text-underline" data-required="true" data-name="<?php echo $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Message" : "Mensaje" ?>" placeholder="<?php echo $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Type your message here" : "Escribe tu mensaje aquí" ?>" id="txtMessage"></textarea>
                            </div>
                        </div>
                        <!-- Actions -->
                        <div class="display-table padding-top-4x width-100 text-align-right">
                            <button id="btnSend" class="button-red"><?php echo CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "SEND" : "ENVIAR" ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            $("#btnSend").on("click", function(e) {
                var frm = new Form($("#frmMessage"));
                if (frm.validate()) {
                    $.ajax({
                        url: URL_API + "Support/Contact.php",
                        type: "POST",
                        data: {
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
                                $("#txtMessage").val("");
                            }
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
