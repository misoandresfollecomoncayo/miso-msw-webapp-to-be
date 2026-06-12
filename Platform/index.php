<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php");
    }
    
    $layout = new Layout();
    $layout->setTitle(Internationalization::loginTitle());
    $layout->printHead();
?>
    <body class="padding-5x background-color-light-gray mobile-padding-3x">
        <div class="width-1-3 float-left mobile-hide">&nbsp;</div>
        <div id="frmLogin" class="width-1-3 float-left background-color-white padding-5x border-radius box-shadow">
            <div class="width-100 padding-5x margin-bottom-4x logotype"></div>
            <!-- User !-->
            <div class="display-inline-block width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::username(); ?></div>
                <input autocomplete="off" autofocus="true" data-required="true" data-name="<?php echo Internationalization::username(); ?>" id="txtUser" placeholder="<?php echo Internationalization::usernamePlaceholder(); ?>" class="input-text-underline" />
            </div>
            <!-- Password !-->
            <div class="margin-top-2x display-inline-block width-100">
                <div class="text-weight-bold margin-bottom"><?php echo Internationalization::password(); ?></div>
                <input data-required="true" data-name="<?php echo Internationalization::password(); ?>" id="txtPassword" placeholder="<?php echo Internationalization::passwordPlaceholder(); ?>" type="password" class="input-text-underline" />
            </div>
            <!-- Password recovery -->
            <div class="margin-top-4x">
                <a href="Support/PasswordRecovery.php" class="margin-bottom"><?php echo Internationalization::passwordRecovery(); ?></a>
            </div>
            <!-- Register -->
            <div class="margin-top-2x">
                <a href="Customer/Register.php" class="margin-bottom"><?php echo Internationalization::newCustomer(); ?></a>
            </div>
            <!-- Actions !-->
            <div class="text-align-center margin-top-4x">
                <button id="btnLogin" class="button-red"><?php echo Internationalization::loginButton(); ?></button>
            </div>
            <!-- Credits !-->
            <a href="https://www.quantumsoft.co" target="_blank" class="display-inline-block width-100 text-align-center margin-top-4x font-size-s text-decoration-none">© <?php echo date("Y") ?> Quantumsoft</a>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            function login() {
                var frmLogin = new Form($("#frmLogin"));
                if (frmLogin.validate()) {
                    $.ajax({
                        url: URL_API + "Session/Login.php",
                        type: "POST",
                        data: {
                            Email: $("#txtUser").val(),
                            Password: $("#txtPassword").val()
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
            }
            
            $("#txtUser").on("keypress", function(e) {
                if ($(e)[0].originalEvent.charCode === 13) {
                    login();
                }
            });
            
            $("#txtPassword").on("keypress", function(e) {
                if ($(e)[0].originalEvent.charCode === 13) {
                    login();
                }
            });
            
            $("#btnLogin").on("click", function(e) {
                login();
            });
        </script>
    </body>
</html>
