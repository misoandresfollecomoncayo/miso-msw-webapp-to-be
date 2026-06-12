<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $sessionUser = CloudEngineSession::getSessionObject();
    
    if (null == $sessionUser ||
        !$sessionUser->hasPermission(Permission::SECURITY)) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("Seguridad");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Seguridad"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmUpdate" class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Current -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Clave actual</div>
                        <div class="float-left width-75">
                            <input autofocus="on" class="input-text-underline" type="password" data-required="true" data-name="Clave actual" id="txtCurrent" placeholder="Digite su clave actual" />
                        </div>
                    </div>
                    <!-- New -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nueva clave</div>
                        <div class="float-left width-75">
                            <input class="input-text-underline" type="password" data-required="true" data-name="Nueva clave" id="txtNew" placeholder="Digite su nueva clave" />
                        </div>
                    </div>
                    <!-- Confirm -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Confirmar nueva clave</div>
                        <div class="float-left width-75">
                            <input class="input-text-underline" type="password" data-required="true" data-name="Confirmar nueva clave" id="txtConfirm" placeholder="Digite nuevamente la nueva clave" />
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="display-table padding-top-4x width-100 text-align-right">
                        <button id="btnUpdate" class="button-blue">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnUpdate").on("click", function(e) {
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