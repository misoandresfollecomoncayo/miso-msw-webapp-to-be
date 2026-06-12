<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Clientes")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $customer = CustomerDAO::getCustomerById(CloudEngineHTTP::getPostVar("IdCustomer"));
    
    if ($customer == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Customers");
    }
    
    $layout = new Layout();
    $layout->setTitle("Ver cliente");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Ver cliente", PUBLIC_PATH_PLATFORM . "Customers"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdIdCustomer" value="<?php echo $customer->getIdCustomer(); ?>" />
                <!-- Tabs -->
                <div class="display-table width-100 padding-bottom-3x text-align-right">
                    <div id="btnPurchases" class="cursor-pointer display-inline-block padding-2x">Compras</div>
                    <div id="btnShipments" class="cursor-pointer display-inline-block padding-2x">Envíos</div>
                    <div id="btnInvoices" class="cursor-pointer display-inline-block padding-2x">Facturas manuales</div>
                    <div id="btnAlerts" class="cursor-pointer display-inline-block padding-2x">Alertas de compras</div>
                    <div class="profile-tab display-inline-block padding-2x text-weight-bold">Perfil</div>
                </div>
                <!-- Personal info -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Locker number -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Casillero</div>
                        <div class="float-left width-75"><?php echo $customer->getLockerNumber() ?></div>
                    </div>
                    <!-- First names -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombres" id="txtNames" value="<?php echo $customer->getNames(); ?>" /></div>
                    </div>
                    <!-- Gender -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Género</div>
                        <div class="float-left width-75">
                            <?php
                                $slGender = new CloudEngineHTMLSelect();
                                $slGender->addPropertie("class", "select-underline");
                                $slGender->addPropertie("id", "slGender");
                                $slGender->addPropertie("data-required", "true");
                                $slGender->addPropertie("data-name", "Género");
                                $slGender->addOption("Selecciona una opción", "");
                                $slGender->addOption("Mujer", "FEMALE");
                                $slGender->addOption("Hombre", "MALE");
                                $slGender->setSelected($customer->getGender());
                                $slGender->render();
                            ?>
                        </div>
                    </div>
                    <!-- Birthdate -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Fecha de nacimiento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="date" data-required="true" data-name="Fecha de nacimiento" id="txtBirthdate" value="<?php echo $customer->getBirthdate(); ?>" /></div>
                    </div>
                    <!-- Language -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Idioma</div>
                        <div class="float-left width-75">
                            <?php
                                $slLanguage = new CloudEngineHTMLSelect();
                                $slLanguage->addPropertie("class", "select-underline");
                                $slLanguage->addPropertie("id", "lsLanguage");
                                $slLanguage->addPropertie("data-required", "true");
                                $slLanguage->addPropertie("data-name", "Idioma");
                                $slLanguage->addOption("Español", "SPANISH");
                                $slLanguage->addOption("Inglés", "ENGLISH");
                                $slLanguage->setSelected($customer->getLanguage());
                                $slLanguage->render();
                            ?>
                        </div>
                    </div>
                    <!-- Document type -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Tipo de documento</div>
                        <div class="float-left width-75">
                            <?php
                                $documentTypes = DocumentTypeDAO::getDocumentTypes();
                                $slDocumentType = new CloudEngineHTMLSelect();
                                $slDocumentType->addPropertie("class", "select-underline");
                                $slDocumentType->addPropertie("id", "slDocumentType");
                                $slDocumentType->addPropertie("data-required", "false");
                                $slDocumentType->addPropertie("data-name", "Tipo de documento");
                                $slDocumentType->addOption("Selecciona una opción", "");
                                foreach($documentTypes as $d) {
                                    $slDocumentType->addOption($d->getName(), $d->getIdDocumentType());
                                }
                                if ($customer->getDocumentType() != null) {
                                    $slDocumentType->setSelected($customer->getDocumentType()->getIdDocumentType());
                                }
                                $slDocumentType->render();
                            ?>
                        </div>
                    </div>
                    <!-- Document number -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Número de documento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="false" data-name="Número de documento" id="txtDocumentNumber" value="<?php echo $customer->getDocumentNumber(); ?>" /></div>
                    </div>
                </div>
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Country -->
                    <div class="display-table padding-bottom-3x width-100">
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
                                $slDocumentType->setSelected($customer->getCity()->getCountry()->getIdCountry());
                                $slDocumentType->render();
                            ?>
                        </div>
                    </div>
                    <!-- City -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Ciudad</div>
                        <div class="float-left width-75">
                            <?php
                                $cities = CityDAO::getCities();
                                $slCity = new CloudEngineHTMLSelect();
                                $slCity->addPropertie("class", "select-underline");
                                $slCity->addPropertie("id", "lsCity");
                                $slCity->addPropertie("data-required", "true");
                                $slCity->addPropertie("data-name", "Ciudad");
                                $slCity->addOption("Selecciona una ciudad", "");
                                foreach ($cities as $c) {
                                    $slCity->addOption($c->getName(), $c->getIdCity());
                                }
                                $slCity->setSelected($customer->getCity()->getIdCity());
                                $slCity->render();
                            ?>
                            <?php
                                if ($customer->getCity()->getIdCity() == "50e870e7-f1ec-48a7-937d-3c22191b2a90"
                                        || $customer->getCity()->getIdCity() == "4568a503-fee3-469e-88e5-0129ed218528"
                                        || $customer->getCity()->getIdCity() == "31303dfb-0689-49a3-a50c-f1161e00c0d2") {
                                    $showMessage = "inline-block";
                                } else {
                                    $showMessage = "none";
                                }
                            ?>
                            <div id="lblMessageCity" style="display: <?php echo $showMessage ?>" class="margin-top text-weight-bold text-color-red">Por favor registre su ciudad en el campo "Dirección".</div>
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Dirección</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección" id="txtAddress" placeholder="Dirección" value="<?php echo $customer->getAddress(); ?>" /></div>
                    </div>
                    <!-- Telephone -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono principal</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="true" data-name="Teléfono" id="txtTelephone" placeholder="Teléfono" value="<?php echo $customer->getTelephone(); ?>" /></div>
                    </div>
                    <!-- Telephone 2 -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono opcional</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="false" data-name="Teléfono opcional" id="txtTelephone2" placeholder="Teléfono opcional" value="<?php echo $customer->getTelephone2(); ?>" /></div>
                    </div>
                </div>
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Email -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="email" data-required="true" data-name="Correo electrónico" id="txtEmail" placeholder="Correo electrónico" value="<?php echo $customer->getEmail(); ?>" /></div>
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
            $("#btnPurchases").on("click", function(e) {
                $.redirect("Purchases.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnShipments").on("click", function(e) {
                $.redirect("Shipments.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnInvoices").on("click", function(e) {
                $.redirect("Invoices.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnAlerts").on("click", function(e) {
                $.redirect("Alerts.php", {IdCustomer : $("#hdIdCustomer").val()});
            });
            
            $("#btnSave").on("click", function(e) {
                var frmProfile = new Form($("#frmProfile"));
                if (frmProfile.validate()) {
                    $.ajax({
                        url: URL_API + "Customer/Edit.php",
                        type: "POST",
                        data: {
                            IdCustomer: $("#hdIdCustomer").val(),
                            Names: $("#txtNames").val(),
                            Gender: $("#slGender").val(),
                            Birthdate: $("#txtBirthdate").val(),
                            Language: $("#lsLanguage").val(),
                            IdDocumentType: $("#slDocumentType").val(),
                            DocumentNumber: $("#txtDocumentNumber").val(),
                            IdCity: $("#lsCity").val(),
                            Address: $("#txtAddress").val(),
                            Telephone: $("#txtTelephone").val(),
                            Telephone2: $("#txtTelephone2").val(),
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
            
            $("#lsCity").on("change", function(e) {
                var idCity = $(e.target).val();
                if (idCity === "50e870e7-f1ec-48a7-937d-3c22191b2a90" ||
                        idCity === "4568a503-fee3-469e-88e5-0129ed218528" ||
                        idCity === "31303dfb-0689-49a3-a50c-f1161e00c0d2") {
                    $("#lblMessageCity").show();
                } else {
                    $("#lblMessageCity").hide();
                }
            });
        </script>
    </body>
</html>
