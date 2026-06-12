<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Facturas manuales")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Crear factura");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear factura", PUBLIC_PATH_PLATFORM . "Bills"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmBill" class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <datalist id="dlCustomers"></datalist>
                    <!-- From -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-15 padding">
                            <div class="float-left width-100 text-weight-bold">Casillero No.</div>
                            <div class="float-left width-100"><input list="dlCustomers" class="input-text-underline" type="text" data-required="false" data-name="Casillero No." id="txtLockerFrom" /></div>
                        </div>
                        <div class="float-left width-35 padding">
                            <div class="float-left width-100 text-weight-bold">Remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Remitente" id="txtFrom" /></div>
                        </div>
                        <div class="float-left width-30 padding">
                            <div class="float-left width-100 text-weight-bold">Dirección remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección remitente" id="txtFromAddress" /></div>
                        </div>
                        <div class="float-left width-20 padding">
                            <div class="float-left width-100 text-weight-bold">Teléfono remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono remitente" id="txtFromPhone" /></div>
                        </div>
                    </div>
                    <!-- To -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-15 padding">
                            <div class="float-left width-100 text-weight-bold">Casillero No.</div>
                            <div class="float-left width-100"><input list="dlCustomers" class="input-text-underline" type="text" data-required="false" data-name="Casillero No." id="txtLockerTo" /></div>
                        </div>
                        <div class="float-left width-35 padding">
                            <div class="float-left width-100 text-weight-bold">Destinatario</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Destinatario" id="txtTo" /></div>
                        </div>
                        <div class="float-left width-30 padding">
                            <div class="float-left width-100 text-weight-bold">Dirección destinatario</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección destinatario" id="txtToAddress" /></div>
                        </div>
                        <div class="float-left width-20 padding">
                            <div class="float-left width-100 text-weight-bold">Teléfono destinatario</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono destinatario" id="txtToPhone" /></div>
                        </div>
                    </div>
                    <!-- Date, country, currency -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">Fecha</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="date" data-required="true" data-name="Fecha" id="txtDate" value='<?php echo date("Y-m-d") ?>' /></div>
                        </div>
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">País destino</div>
                            <div class="float-left width-100">
                                <?php
                                    $slCountry = new CloudEngineHTMLSelect();
                                    $slCountry->addPropertie("class", "select-underline");
                                    $slCountry->addPropertie("id", "slCountry");
                                    $slCountry->addPropertie("data-required", "true");
                                    $slCountry->addPropertie("data-name", "País destino");
                                    $slCountry->addOption("Selecciona una opción", "");
                                    
                                    $countries = CountryDAO::getCountries();
                                    foreach ($countries as $c) {
                                        $slCountry->addOption($c->getName(), $c->getIdCountry());
                                    }
                                    
                                    $slCountry->render();
                                ?>
                            </div>
                        </div>
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">Moneda</div>
                            <div class="float-left width-100">
                                <?php
                                    $slCurrency = new CloudEngineHTMLSelect();
                                    $slCurrency->addPropertie("class", "select-underline");
                                    $slCurrency->addPropertie("id", "slCurrency");
                                    $slCurrency->addPropertie("data-required", "true");
                                    $slCurrency->addPropertie("data-name", "Moneda");
                                    $slCurrency->addOption("Selecciona una opción", "");
                                    $slCurrency->addOption("COP", "COP");
                                    $slCurrency->addOption("USD", "USD");
                                    $slCurrency->render();
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Payment, method and shipment company -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">Pago</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="number" data-required="false" data-name="Pago parcial" id="txtTemporaryPayment" /></div>
                        </div>
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">Método de pago</div>
                            <div class="float-left width-100">
                                <?php
                                    $paymentMethods = PaymentMethodDAO::getPaymentMethods();
                                    $slPaymentMethod = new CloudEngineHTMLSelect();
                                    $slPaymentMethod->addPropertie("class", "select-underline");
                                    $slPaymentMethod->addPropertie("id", "slPaymentMethod");
                                    $slPaymentMethod->addPropertie("data-required", "false");
                                    $slPaymentMethod->addPropertie("data-name", "Método de pago");
                                    $slPaymentMethod->addOption("Selecciona una opción", "");
                                    foreach ($paymentMethods as $m) {
                                        $slPaymentMethod->addOption($m->getName(), $m->getIdPaymentMethod());
                                    }
                                    $slPaymentMethod->render();
                                ?>
                            </div>
                        </div>
                        <div class="float-left width-1-3 padding">
                            <div class="float-left width-100 text-weight-bold">Empresa de envío</div>
                            <div class="float-left width-100">
                                <?php
                                    $shipmentCompanies = ShipmentCompanyDAO::getShipmentCompanies();
                                    $slShipmentCompany = new CloudEngineHTMLSelect();
                                    $slShipmentCompany->addPropertie("class", "select-underline");
                                    $slShipmentCompany->addPropertie("id", "slShipmentCompany");
                                    $slShipmentCompany->addPropertie("data-required", "false");
                                    $slShipmentCompany->addPropertie("data-name", "Empresa de envío");
                                    $slShipmentCompany->addOption("Selecciona una opción", "");
                                    foreach ($shipmentCompanies as $s) {
                                        $slShipmentCompany->addOption($s->getName(), $s->getIdShipmentCompany());
                                    }
                                    $slShipmentCompany->render();
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Items -->
                    <table id="tblItems" class="table stripe">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Descripción</th>
                                <th>No. Caja</th>
                                <th>Peso</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            <?php
                                $code = '<tr>';
                                $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="No. Caja" name="boxNumber" class="input-text-underline" /></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Peso" name="weight" class="input-text-underline" /></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Total" name="total" class="input-text-underline" /></td>';
                                $code .= '</tr>';
                                echo $code;
                            ?>
                        </tbody>
                    </table>
                    <div class="display-table width-100 margin-top-3x text-size-l text-weight-bold text-align-right" id="lblTotal">Total: $ 0</div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            function Item(description,box,weight,total) {
                this.description = description;
                this.box = box;
                this.weight = weight;
                this.total = total;
            }
            
            function back() {
                document.location.href = "index.php";
            }
            
            $("#btnSave").on("click", function(e) {
                var tblItems = new Form($("#frmBill"));
                if (tblItems.validate()) {
                    var items = new Array();
                    
                    var rows = $("#tblItems").find("tbody").find("tr");
                    
                    for (var i=0; i<rows.length; i++) {
                        items.push(new Item($(rows[i]).find("[name=description]").val(),$(rows[i]).find("[name=boxNumber]").val(),$(rows[i]).find("[name=weight]").val(),$(rows[i]).find("[name=total]").val()));
                    }
                    
                    $.ajax({
                        url: URL_API + "Bill/Create.php",
                        type: "POST",
                        data: {
                            Date: $("#txtDate").val(),
                            FromLockerNumber: $("#txtLockerFrom").val(),
                            From: $("#txtFrom").val(),
                            FromAddress: $("#txtFromAddress").val(),
                            FromPhone: $("#txtFromPhone").val(),
                            To: $("#txtTo").val(),
                            ToAddress: $("#txtToAddress").val(),
                            ToPhone: $("#txtToPhone").val(),
                            ToCountry: $("#slCountry").val(),
                            TemporaryPayment: $("#txtTemporaryPayment").val(),
                            Currency: $("#slCurrency").val(),
                            IdPaymentMethod: $("#slPaymentMethod").val(),
                            IdShipmentCompany: $("#slShipmentCompany").val(),
                            Items: JSON.stringify(items)
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            if (confirm("¿Desea generar el formato en PDF?")) {
                                var json = JSON.parse(response);
                                $.redirect(URL_API + "PDF/Bill.php", {IdBill: json.body}, "POST", "_blank");
                                setTimeout(back, 500);
                            } else {
                                $.redirect("index.php");
                            }
                            closePreload();
                        }
                    });
                }
            });
            
            $(document).on("click", "[name=btnCloneRow]", function() {
                var tr = $(document.createElement("tr"));
                $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="No. Caja" name="boxNumber" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Peso" name="weight" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Total" name="total" class="input-text-underline" /></td>');
                $("#tblBody").append($(tr));
            });
            
            $(document).on("click", "[name=btnDeleteRow]", function(e) {
                $(e.target).parent().parent().remove();
                calculateTotal();
            });
            
            $(document).on("keyup", "[name=total]", function() {
                calculateTotal();
            });
            
            function calculateTotal() {
                var total = 0;
                var inputs = $("[name=total]");
                
                for (var i=0; i<inputs.length; i++) {
                    if ($(inputs[i]).val() !== "") {
                        total += parseFloat($(inputs[i]).val());
                    }
                }
                
                $("#lblTotal").text("Total: $ " + total.toFixed(2));
            }
            
            $("#txtLockerFrom").on("blur", function(e) {
                var lockerNumber = $(e.target).val();
                if (lockerNumber !== "") {
                    $.ajax({
                        url: URL_API + "Customer/GetByLocker.php",
                        type: "POST",
                        data: {
                            LockerNumber: lockerNumber
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                            } else {
                                var customer = JSON.parse(r.body)[0];
                                $("#txtFrom").val(customer.names);
                                $("#txtFromAddress").val(customer.address);
                                $("#txtFromPhone").val(customer.phone);
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $("#txtLockerFrom").on("keyup", function(e) {
                var search = $(e.target).val();
                if (search !== null && search !== "") {
                    $("#dlCustomers").empty();
                    
                    $.ajax({
                        url: URL_API + "Customer/GetByLockerOrNames.php",
                        type: "POST",
                        data: {
                            Search: search
                        },
                        beforeSend: function() {
                            //showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type !== "Exception") {
                                var customers = JSON.parse(r.body);
                                for (var i=0; i<customers.length; i++) {
                                    var option = $(document.createElement("option"));
                                    option.text(customers[i].names);
                                    option.val(customers[i].lockerNumber);
                                    $("#dlCustomers").append(option);
                                }
                            }
        
                            //closePreload();
                        }
                    });
                }
            });
            
            $("#txtLockerTo").on("blur", function(e) {
                var lockerNumber = $(e.target).val();
                if (lockerNumber !== "") {
                    $.ajax({
                        url: URL_API + "Customer/GetByLocker.php",
                        type: "POST",
                        data: {
                            LockerNumber: lockerNumber
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                            } else {
                                var customer = JSON.parse(r.body)[0];
                                $("#txtTo").val(customer.names);
                                $("#txtToAddress").val(customer.address);
                                $("#txtToPhone").val(customer.phone);
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $("#txtLockerTo").on("keyup", function(e) {
                var search = $(e.target).val();
                if (search !== null && search !== "") {
                    $("#dlCustomers").empty();
                    
                    $.ajax({
                        url: URL_API + "Customer/GetByLockerOrNames.php",
                        type: "POST",
                        data: {
                            Search: search
                        },
                        beforeSend: function() {
                            //showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type !== "Exception") {
                                var customers = JSON.parse(r.body);
                                for (var i=0; i<customers.length; i++) {
                                    var option = $(document.createElement("option"));
                                    option.text(customers[i].names);
                                    option.val(customers[i].lockerNumber);
                                    $("#dlCustomers").append(option);
                                }
                            }
        
                            //closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
