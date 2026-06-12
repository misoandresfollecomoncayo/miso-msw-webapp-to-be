<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    $layout = new Layout();
    $layout->setTitle(Internationalization::trackingTitle());
    $layout->addJSFile("https://www.google.com/recaptcha/api.js?onload=recaptchaAdjust&render=explicit");
    $layout->printHead();
?>
    <body class="padding-5x background-color-light-gray mobile-padding-3x">
        <div class="width-1-3 float-left mobile-hide">&nbsp;</div>
        <div id="frmQuery" class="width-1-3 float-left background-color-white padding-5x border-radius box-shadow">
            <div class="width-100 padding-5x margin-bottom-4x logotype"></div>
            <!-- User -->
            <div class="display-table width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::trackingNumber(); ?></div>
                <div id="trackingContainer" class="display-table width-100 margin-top-3x">
                    <div class="width-15-fixed float-left label-underline">UNI</div>
                    <input autocomplete="off" autofocus="true" data-required="true" data-name="<?php echo Internationalization::trackingNumber(); ?>" id="txtNumber" type="number" class="input-text-underline" style="width: 85% !important" />
                </div>
            </div>
            <!-- Locker -->
            <div class="display-table width-100 margin-top-3x">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::lockerNumber(); ?></div>
                <div class="display-table width-100 margin-top-3x">
                    <input autocomplete="off" autofocus="true" data-required="false" data-name="<?php echo Internationalization::lockerNumber(); ?>" id="txtLocker" type="number" class="input-text-underline" />
                </div>
            </div>
            <!-- Captcha -->
            <div id="GRecaptchaParent" class="margin-top-4x width-100 text-align-center">
                <div class="g-recaptcha display-inline-block" id="GRecaptcha"></div>
            </div>
            <!-- Actions -->
            <div class="text-align-center margin-top-4x">
                <a href="https://www.uniexpresssolutions.com" class="button-white margin-right-2x text-decoration-none"><?php echo Internationalization::backButton(); ?></a>
                <button id="btnSend" class="button-red"><?php echo Internationalization::sendButton(); ?></button>
            </div>
            <!-- Credits !-->
            <a href="https://www.quantumsoft.co" target="_blank" class="display-inline-block width-100 text-align-center margin-top-4x font-size-s text-decoration-none">© <?php echo date("Y") ?> Quantumsoft</a>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            var recaptchaAdjust = function() {
                var scale = 0;
                
                if ($("#trackingContainer").innerWidth() < 304) {     // Mobile
                    scale = $("#trackingContainer").innerWidth() / 304;
                    $('#GRecaptcha').css("transform-origin", "0 0");
                } else {
                    scale = .9;
                }
                
                $('#GRecaptcha').css("transform", "scale(" + scale + ")");
                $("#GRecaptchaParent").width($("#trackingContainer").innerWidth());
                
                grecaptcha.render('GRecaptcha', {
                    'sitekey' : '6LcsrDsUAAAAALW1WXfoJLY2UXgPspenPWmo8rrO'
                });
            };
            
            $("#btnSend").on("click", function(e) {
                var frmQuery = new Form($("#frmQuery"),true);
                if (frmQuery.validate()) {
                    $.redirect("TrackingResult.php", {Number: $("#txtNumber").val(), Locker: $("#txtLocker").val(), Captcha: grecaptcha.getResponse()}, "POST", "_self");
                }
            });
        </script>
    </body>
</html>