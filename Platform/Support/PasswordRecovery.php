<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php");
    }
    
    $layout = new Layout();
    $layout->setTitle(Internationalization::passwordRecoveryTitle());
    $layout->addJSFile("https://www.google.com/recaptcha/api.js?onload=recaptchaAdjust&render=explicit");
    $layout->printHead();
?>
    <body class="padding-5x background-color-light-gray mobile-padding-3x">
        <div class="width-1-3 float-left mobile-hide">&nbsp;</div>
        <div id="frmRecovery" class="width-1-3 float-left background-color-white padding-5x border-radius box-shadow">
            <div class="width-100 padding-5x margin-bottom-4x logotype"></div>
            <!-- User -->
            <div class="display-table width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::username(); ?></div>
                <input autocomplete="off" autofocus="true" data-required="true" data-name="<?php echo Internationalization::username(); ?>" id="txtUser" placeholder="<?php echo Internationalization::usernamePlaceholder(); ?>" class="input-text-underline" />
            </div>
            <!-- Captcha -->
            <div id="GRecaptchaParent" class="margin-top-4x width-100 text-align-center">
                <div class="g-recaptcha display-inline-block" id="GRecaptcha"></div>
            </div>
            <!-- Actions -->
            <div class="text-align-center margin-top-4x">
                <a href="../" class="button-white margin-right-2x text-decoration-none"><?php echo Internationalization::backButton(); ?></a>
                <button id="btnSend" class="button-red"><?php echo Internationalization::sendButton(); ?></button>
            </div>
            <!-- Credits !-->
            <a href="https://www.quantumsoft.co" target="_blank" class="display-inline-block width-100 text-align-center margin-top-4x font-size-s text-decoration-none">© <?php echo date("Y") ?> Quantumsoft</a>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            var recaptchaAdjust = function() {
                var scale = 0;
                
                if ($("#txtUser").innerWidth() < 304) {     // Mobile
                    scale = $("#txtUser").innerWidth() / 304;
                    $('#GRecaptcha').css("transform-origin", "0 0");
                } else {
                    scale = .9;
                }
                
                $('#GRecaptcha').css("transform", "scale(" + scale + ")");
                $("#GRecaptchaParent").width($("#txtUser").innerWidth());
                
                grecaptcha.render('GRecaptcha', {
                    'sitekey' : '6LcsrDsUAAAAALW1WXfoJLY2UXgPspenPWmo8rrO'
                });
            };
            
            $("#btnSend").on("click", function(e) {
                var frmRecovery = new Form($("#frmRecovery"),true);
                if (frmRecovery.validate()) {
                    $.ajax({
                        url: URL_API + "Support/PasswordRecovery.php",
                        type: "POST",
                        data: {
                            Email: $("#txtUser").val(),
                            Captcha: grecaptcha.getResponse()
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
                            frmRecovery.reset();
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>