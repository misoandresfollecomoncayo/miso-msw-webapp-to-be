<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Empresas de envío")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $company = ShipmentCompanyDAO::getShipmentCompanyById(CloudEngineHTTP::getPostVar("Id"));
    
    if ($company == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "ShipmentCompanies");
    }
    
    $layout = new Layout();
    $layout->setTitle("Editar empresa de envío");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Editar empresa de envío", PUBLIC_PATH_PLATFORM . "ShipmentCompanies/"); ?>
            <div id="frmUser" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <input type="hidden" id="hdIdShipmentCompany" value="<?php echo $company->getIdShipmentCompany() ?>" />
                    <!-- Name -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nombre</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombre" id="txtName" value="<?php echo $company->getName(); ?>" /></div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnSave").on("click", function(e) {
                var frmUser = new Form($("#frmUser"));
                if (frmUser.validate()) {
                    $.ajax({
                        url: URL_API + "ShipmentCompany/Edit.php",
                        type: "POST",
                        data: {
                            Id: $("#hdIdShipmentCompany").val(),
                            Name: $("#txtName").val()
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
                            
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
