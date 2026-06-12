<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php");
    }
    
    $token = TokenDAO::getTokenById(CloudEngineHTTP::getGetVar("Token"));
    
    if ($token == null || $token->isUsed()) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }
    
    $layout = new Layout();
    $layout->setTitle(Internationalization::passwordRecoveryTitle());
    $layout->printHead();
?>
    <body class="padding-5x background-color-light-gray mobile-padding-3x">
        <div class="width-1-3 float-left mobile-hide">&nbsp;</div>
        <div id="frmUpdate" class="width-1-3 float-left background-color-white padding-5x border-radius box-shadow">
            <input id="hdToken" value="<?php echo $token->getIdToken(); ?>" type="hidden" />
            <div class="width-100 padding-5x margin-bottom-4x logotype"></div>
            <!-- New !-->
            <div class="display-inline-block width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::newPassword(); ?></div>
                <input autocomplete="off" autofocus="true" data-required="true" data-name="<?php echo Internationalization::newPassword(); ?>" id="txtNew" placeholder="<?php echo Internationalization::newPasswordPlaceholder(); ?>" type="password" class="input-text-underline" />
            </div>
            <!-- Confirm !-->
            <div class="margin-top-3x display-inline-block width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::confirmNewPassword(); ?></div>
                <input data-required="true" data-name="<?php echo Internationalization::confirmNewPassword(); ?>" id="txtConfirm" placeholder="<?php echo Internationalization::confirmNewPassword(); ?>" type="password" class="input-text-underline" />
            </div>
            <!-- Actions !-->
            <div class="text-align-center margin-top-4x">
                <button id="btnSave" class="button-red"><?php echo Internationalization::saveButton() ?></button>
            </div>
            <!-- Credits !-->
            <a href="https://www.quantumsoft.co" target="_blank" class="display-inline-block width-100 text-align-center margin-top-4x font-size-s text-decoration-none">© <?php echo date("Y") ?> Quantumsoft</a>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnSave").on("click", function(e) {
                var frmUpdate = new Form($("#frmUpdate"));
                if (frmUpdate.validate()) {
                    $.ajax({
                        url: URL_API + "Customer/PasswordRecovery.php",
                        type: "POST",
                        data: {
                            Token: $("#hdToken").val(),
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
                                frmLogin.reset();
                                closePreload();
                            } else {
                                document.location.href = r.body;
                            }
                        }
                    });
                }
            });
        </script>
    </body>
</html>