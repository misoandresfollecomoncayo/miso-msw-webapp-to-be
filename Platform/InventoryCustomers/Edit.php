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
    
    $customer = InventoryCustomerDAO::getById($_REQUEST["Id"]);
    
    if ($customer == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "InventoryCustomers");
    }
    
    $layout = new Layout();
    $layout->setTitle("Editar cliente ventas");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Editar cliente ventas", PUBLIC_PATH_PLATFORM . "InventoryCustomers"); ?>
            <div id="frmProfile" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdIdCustomer" value="<?php echo $customer->id; ?>" />
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x" style="display: flex; flex-direction: column; gap: 20px">
                    <!-- First names -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Nombres" id="txtNames" value='<?php echo $customer->name ?>' /></div>
                    </div>
                    <!-- Document number -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Número de documento</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Número de documento" id="txtDocumentNumber" value='<?php echo $customer->documentNumber ?>' /></div>
                    </div>
                    <!-- Country -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">País</div>
                        <div class="float-left width-75">
                            <?php
                                $countries = CountryDAO::getCountries();
                                
                                $slCountry = new CloudEngineHTMLSelect();
                                $slCountry->addPropertie("class", "select-underline");
                                $slCountry->addPropertie("id", "lsCountry");
                                $slCountry->addPropertie("data-required", "true");
                                $slCountry->addPropertie("data-name", "País");
                                $slCountry->addOption("Selecciona un país", "");
                                foreach ($countries as $c) {
                                    $slCountry->addOption($c->getName(), $c->getIdCountry());
                                }
                                $slCountry->setSelected($customer->getCity()->getCountry()->getIdCountry());
                                $slCountry->render();
                            ?>
                        </div>
                    </div>
                    <!-- City -->
                    <div class="display-table width-100">
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
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Dirección</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección" id="txtAddress" value='<?php echo $customer->address ?>' /></div>
                    </div>
                    <!-- Phone number -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Teléfono</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono" id="txtPhoneNumber" value='<?php echo $customer->phoneNumber ?>' /></div>
                    </div>
                    <!-- Email -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="text" data-required="true" data-name="Correo electrónico" id="txtEmail" value='<?php echo $customer->email ?>' /></div>
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
                        url: URL_API + "InventoryCustomer/Update.php",
                        type: "POST",
                        data: {
                            Id: $("#hdIdCustomer").val(),
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
