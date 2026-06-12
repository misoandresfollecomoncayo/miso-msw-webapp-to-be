<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Clientes ventas")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Crear cliente ventas");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear cliente ventas", PUBLIC_PATH_PLATFORM . "InventoryCustomers"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x" style="display: flex; flex-direction: column; gap: 20px">
                    <!-- First names -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombres" id="txtNames" /></div>
                    </div>
                    <!-- Document number -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Número de documento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Número de documento" id="txtDocumentNumber" /></div>
                    </div>
                    <!-- Country -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">País</div>
                        <div class="float-left width-75">
                            <?php
                                $countries = CountryDAO::getCountries();
                                
                                $slDocumentType = new CloudEngineHTMLSelect();
                                $slDocumentType->addPropertie("class", "select-underline");
                                $slDocumentType->addPropertie("id", "lsCountry");
                                $slDocumentType->addPropertie("data-required", "true");
                                $slDocumentType->addPropertie("data-name", "País");
                                $slDocumentType->addOption("Selecciona un país", "");
                                foreach ($countries as $c) {
                                    $slDocumentType->addOption($c->getName(), $c->getIdCountry());
                                }
                                $slDocumentType->render();
                            ?>
                        </div>
                    </div>
                    <!-- City -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Ciudad</div>
                        <div class="float-left width-75">
                            <?php
                                $slCity = new CloudEngineHTMLSelect();
                                $slCity->addPropertie("class", "select-underline");
                                $slCity->addPropertie("id", "lsCity");
                                $slCity->addPropertie("data-required", "true");
                                $slCity->addPropertie("data-name", "Ciudad");
                                $slCity->addOption("Selecciona una ciudad", "");
                                $slCity->render();
                            ?>
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Dirección</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección" id="txtAddress" /></div>
                    </div>
                    <!-- Phone number -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono" id="txtPhoneNumber" /></div>
                    </div>
                    <!-- Email -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Correo electrónico" id="txtEmail" /></div>
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
                var frmProfile = new Form($("#frmProfile"));
                if (frmProfile.validate()) {
                    $.ajax({
                        url: URL_API + "InventoryCustomer/Create.php",
                        type: "POST",
                        data: {
                            Name: $("#txtNames").val(),
                            DocumentNumber: $("#txtDocumentNumber").val(),
                            IdCity: $("#lsCity").val(),
                            Address: $("#txtAddress").val(),
                            PhoneNumber: $("#txtPhoneNumber").val(),
                            Email: $("#txtEmail").val()
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
                                frmProfile.reset();
                            }
                            closePreload();
                        }
                    });
                }
            });
            
            $("#lsCountry").on("change", function(e) {
                var idCountry = $(e.target).val();
                if (idCountry !== "") {
                    $.ajax({
                        url: URL_API + "City/GetByCountry.php",
                        type: "POST",
                        data: {
                            IdCountry: idCountry
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            $("#lsCity").empty();
                            $("#lsCity").append($("<option>", {value: "", text: "Seleccione su ciudad"}));
                            for (var i in r) {
                                $("#lsCity").append($("<option>", {value: r[i].id, text: r[i].name}));
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
