<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Notificaciones")) {
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
        $layout->setTitle("Notifications");
    } else {
        $layout->setTitle("Notificaciones");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar(CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && $sessionUser->getLanguage() == Customer::LANGUAGE_ENGLISH ? "Notifications" : "Notificaciones", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <button id="btnDelete" class="button-red display-inline-block text-decoration-none">ELIMINAR SELECCIONADOS</button>
                </div>
                <?php
                    $notifications = $sessionUser->getNotifications();
                    
                    foreach ($notifications as $n) {
                        $code = '<div class="background-color-white border-radius width-100 margin-bottom-2x padding display-table box-shadow">';
                        
                        $code .= '<div class="width-15 float-left">';
                        $code .= "<button name='btnDelete' data-id='" . $n->getIdNotification() . "' class='mobile-margin-top-4x button-gray float-left' style='padding: 4px 8px !important'><i class='fa fa-trash'></i></button>";
                        $code .= '<input name="chkNotification" data-id="' . $n->getIdNotification() . '" class="float-left margin-right margin-left" type="checkbox" />';
                        if (!$n->wasViewed()) {
                            $code .= "<button name='btnProcess' data-id='" . $n->getIdNotification() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding: 4px 8px !important'>PENDIENTE</button>";
                        } else {
                            $code .= "<div class='mobile-margin-top-4x background-color-green float-left border-radius padding text-weight-bold text-color-white margin-right' style='font-size:11px !important'>REALIZADO</div>";
                        }
                        $code .= '</div>';
                        
                        $code .= '<div class="width-85 float-left">';
                        $code .= '<div style="opacity: .5" class="float-left width-100 text-size-xs text-weight-bold margin-bottom">' . $n->getCreatedTimestampFormatted() . '</div>';
                        $code .= '<div class="float-left width-100">' . $n->getContent() . '</div>';
                        $code .= '</div>';
                        
                        $code .= '</div>';
                        echo $code;
                    }
                ?>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("[name=btnProcess]").on("click", function() {
                $.ajax({
                    url: URL_API + "Notification/Process.php",
                    type: "POST",
                    data: {
                        IdNotification: $(this).data("id")
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function() {
                        document.location.reload();
                    }
                });
            });
            
            $("[name=btnDelete]").on("click", function() {
                if (confirm("¿Confirma eliminar la notificación?")) {
                    $.ajax({
                        url: URL_API + "Notification/Delete.php",
                        type: "POST",
                        data: {
                            IdNotification: $(this).data("id")
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
            
            $("#btnDelete").on("click", function() {
                var checks = $("[name=chkNotification]");
                
                if (checks.length > 0 && confirm("¿Confirma eliminar las notificaciones seleccionadas?")) {
                    for (var i=0; i<checks.length; i++) {
                        if (checks[i].checked) {
                            $.ajax({
                                url: URL_API + "Notification/Delete.php",
                                type: "POST",
                                data: {
                                    IdNotification: $(checks[i]).data("id")
                                },
                                beforeSend: function() {
                                    showPreload();
                                },
                                success: function() {
                                    document.location.reload();
                                }
                            });
                        }
                    }
                }
            });
        </script>
    </body>
</html>
