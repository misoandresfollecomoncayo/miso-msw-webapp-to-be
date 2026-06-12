<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Clientes")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Crear cliente");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear cliente", PUBLIC_PATH_PLATFORM . "Customers"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- First names -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombres" id="txtNames" /></div>
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
                                $slGender->render();
                            ?>
                        </div>
                    </div>
                    <!-- Birthdate -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Fecha de nacimiento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="date" data-required="true" data-name="Fecha de nacimiento" id="txtBirthdate" /></div>
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
                                $slDocumentType->render();
                            ?>
                        </div>
                    </div>
                    <!-- Document number -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Número de documento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="false" data-name="Número de documento" id="txtDocumentNumber" /></div>
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
                                $slDocumentType->render();
                            ?>
                        </div>
                    </div>
                    <!-- City -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
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
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Dirección</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección" id="txtAddress" placeholder="Dirección" value="" /></div>
                    </div>
                    <!-- Telephone -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono principal</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="true" data-name="Teléfono" id="txtTelephone" placeholder="Teléfono" value="" /></div>
                    </div>
                    <!-- Telephone 2 -->
                    <div class="display-table padding-top-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono opcional</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="tel" data-required="false" data-name="Teléfono opcional" id="txtTelephone2" placeholder="Teléfono opcional" value="" /></div>
                    </div>
                </div>
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <!-- Email -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="email" data-required="true" data-name="Correo electrónico" id="txtEmail" placeholder="Correo electrónico" value="" /></div>
                    </div>
                    <!-- Password -->
                    <div class="display-table width-100 padding-top-3x">
                        <div class="float-left width-25 text-weight-bold">Clave</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Clave" id="txtPassword" placeholder="Clave" value="<?php echo CloudEngineStrings::randomString(8, CloudEngineStrings::RANDOM_STRING_TYPE_NUMERIC); ?>" /></div>
                    </div>
                    <!-- Notify -->
                    <div class="display-table width-100 padding-top-3x">
                        <div class="float-left width-25 text-weight-bold">¿Notificar creación de casillero?</div>
                        <div class="float-left width-75"><input type="checkbox" id="chkNotify" /></div>
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
                        url: URL_API + "Customer/Create.php",
                        type: "POST",
                        data: {
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
                            Email: $("#txtEmail").val(),
                            Password: $("#txtPassword").val(),
                            Notify: $("#chkNotify")[0].checked
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
