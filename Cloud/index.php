<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Views/Transversal/Dashboard.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Iniciar sesión");
    $layout->printHead();
?>
    <body class="padding-6x background-color-studio mobile-padding-3x">
        <div class="width-1-3 float-left mobile-hide">&nbsp;</div>
        <div class="width-1-3 float-left background-color-white padding-5x border-radius box-shadow">
            <div class="width-100 login-logotype"></div>
            <!-- Form !-->
            <div id="frmLogin" class="width-100">
                <!-- User -->
                <div class="display-table width-100">
                    <div class="text-weight-bold margin-bottom">Usuario</div>
                    <input autocomplete="off" autofocus="true" data-required="true" data-name="Usuario" id="txtUser" placeholder="Digite su usuario" class="input-text-underline" />
                </div>
                <!-- Password -->
                <div class="margin-top-2x display-table width-100">
                    <div class="text-weight-bold margin-bottom">Clave</div>
                    <input data-required="true" data-name="Clave" id="txtPassword" placeholder="Digite su clave" type="password" class="input-text-underline" />
                </div>
                <!-- Actions !-->
                <div class="text-align-center margin-top-4x display-table width-100">
                    <button id="btnSend" class="button-blue">ENTRAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnSend").on("click", function(e) {
                var frmLogin = new Form($("#frmLogin"));
                if (frmLogin.validate()) {
                    $.ajax({
                        url: URL_API + "Session/Login.php",
                        type: "POST",
                        data: {
                            User: $("#txtUser").val(),
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
            });
        </script>
    </body>
</html>