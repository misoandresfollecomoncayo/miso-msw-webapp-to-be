<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Perfil")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $customer = CloudEngineSession::getSessionObject()->getObject();
    
    $layout = new Layout();
    if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
        $layout->setTitle("Perfil");
    } else {
        $layout->setTitle("Profile");
    }
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar($customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Perfil" : "Profile", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 margin-bottom-4x text-align-right">
                    <button id="btnSave" class="button-red"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "GUARDAR" : "SAVE"; ?></button>
                </div>
                <div class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Locker -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "No. Casillero" : "Locker number"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getLockerNumber(); ?></div>
                    </div>
                </div>
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Names -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Nombres" : "Names"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getNames(); ?></div>
                    </div>
                    <!-- Gender -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Género" : "Gender"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getGenderSpanish(); ?></div>
                    </div>
                    <!-- Birthdate -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Fecha de nacimiento" : "Birthday"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getBirthdateFormatted(); ?></div>
                    </div>
                    <!-- Language -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Idioma" : "Language"; ?></div>
                        <div class="float-left width-75">
                            <?php
                                $slLanguage = new CloudEngineHTMLSelect();
                                $slLanguage->addPropertie("class", "select-underline");
                                $slLanguage->addPropertie("id", "lsLanguage");
                                $slLanguage->addPropertie("data-required", "true");
                                $slLanguage->addPropertie("data-name", "Idioma");
                                $slLanguage->addOption("English", "ENGLISH");
                                $slLanguage->addOption("Español", "SPANISH");
                                $slLanguage->setSelected($customer->getLanguage());
                                $slLanguage->render();
                            ?>
                        </div>
                    </div>
                    <!-- Document type -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Tipo de documento" : "Document type"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getDocumentTypeName(); ?></div>
                    </div>
                    <!-- Document number -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Número de documento" : "Document number"; ?></div>
                        <div class="float-left width-75"><?php echo $customer->getDocumentNumber(); ?></div>
                    </div>
                </div>
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Country -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "País" : "Country"; ?></div>
                        <div class="float-left width-75">
                            <?php
                                $countries = CountryDAO::getCountries();
                                
                                $slDocumentType = new CloudEngineHTMLSelect();
                                $slDocumentType->addPropertie("class", "select-underline");
                                $slDocumentType->addPropertie("id", "lsCountry");
                                $slDocumentType->addPropertie("data-required", "true");
                                $slDocumentType->addPropertie("data-name", "País");
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
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Ciudad" : "City"; ?></div>
                        <div class="float-left width-75">
                            <?php
                                $cities = $customer->getCity()->getCountry()->getCities();
                                
                                $slCity = new CloudEngineHTMLSelect();
                                $slCity->addPropertie("class", "select-underline");
                                $slCity->addPropertie("id", "lsCity");
                                $slCity->addPropertie("data-required", "true");
                                $slCity->addPropertie("data-name", "Ciudad");
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
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Dirección" : "Address"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="<?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Dirección" : "Address"; ?>" id="txtAddress" value="<?php echo $customer->getAddress(); ?>" /></div>
                    </div>
                    <!-- Telephone -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Teléfono principal" : "Principal phone number"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="true" data-name="<?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Teléfono principal" : "Principal phone number"; ?>" id="txtTelephone" value="<?php echo $customer->getTelephone(); ?>" /></div>
                    </div>
                    <!-- Telephone 2 -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Teléfono opcional" : "Optional phone number"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="false" data-name="<?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Teléfono opcional" : "Optional phone number"; ?>" id="txtTelephone2" value="<?php echo $customer->getTelephone2(); ?>" /></div>
                    </div>
                </div>
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Email -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold"><?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Correo electrónico" : "Email"; ?></div>
                        <div class="float-left width-75"><input class="input-text-underline" type="email" data-required="true" data-name="<?php echo $customer->getLanguage() == Customer::LANGUAGE_SPANISH ? "Correo electrónico" : "Email"; ?>" id="txtEmail" value="<?php echo $customer->getEmail(); ?>" /></div>
                    </div>
                </div>
                
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnSave").on("click", function(e) {
                var frmProfile = new Form($("#frmProfile"));
                if (frmProfile.validate()) {
                    $.ajax({
                        url: URL_API + "Customer/Update.php",
                        type: "POST",
                        data: {
                            Language: $("#lsLanguage").val(),
                            IdCity: $("#lsCity").val(),
                            Address: $("#txtAddress").val(),
                            Telephone: $("#txtTelephone").val(),
                            Telephone2: $("#txtTelephone2").val(),
                            Email: $("#txtEmail").val(),
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                                closePreload();
                            } else {
                                document.location.reload();
                            }
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
