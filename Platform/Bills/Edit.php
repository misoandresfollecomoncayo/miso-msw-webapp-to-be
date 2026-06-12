<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Facturas manuales")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $bill = BillDAO::getBillById(CloudEngineHTTP::getPostVar("IdBill"));
    
    if ($bill == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Bills");
    }
    
    $layout = new Layout();
    $layout->setTitle("Editar factura");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Editar factura No. " . $bill->getBillNumber(), PUBLIC_PATH_PLATFORM . "Bills/index.php?Search=" . CloudEngineHTTP::getPostVar("Search") . "&Page=" . CloudEngineHTTP::getPostVar("Page")); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <!-- tabs -->
                <div class="display-table width-100 padding-bottom-3x text-align-right">
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabPDF" class="cursor-pointer display-inline-block padding-2x">Ver PDF</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" class="profile-tab text-weight-bold display-inline-block padding-2x">Editar</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabPayment" class="cursor-pointer display-inline-block padding-2x">Pagos</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabTracking" class="cursor-pointer display-inline-block padding-2x">Trazabilidad</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabAnnul" class="cursor-pointer display-inline-block padding-2x">Anular</div>
                </div>
                <!-- Pictures -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table class="table width-100">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($bill->hasPicture()) {
                                    $code = "<tr>";
                                    $code .= "<td>Foto documento</td>";
                                    $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC. "Uploads/Invoices/" . $bill->getIdBill() . "'>Ver</a><div class='display-inline-block text-decoration-underline cursor-pointer' name='btnDelete' data-id='" . $bill->getIdBill() . "'>Eliminar</div></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                } else {
                                    $code = "<tr>";
                                    $code .= "<td colspan='2'><input name='flPicture' type='file' /></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- form -->
                <div id="frmBill" class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <input type="hidden" id="hdIdBill" value="<?php echo $bill->getIdBill(); ?>" />
                    <datalist id="dlCustomers"></datalist>
                    <!-- From -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-15 padding">
                            <div class="float-left width-100 text-weight-bold">Casillero No.</div>
                            <div class="float-left width-100"><input list="dlCustomers" class="input-text-underline" type="text" data-required="false" data-name="Casillero No." id="txtLockerFrom" value="<?php echo ($bill->getCustomer() != null ? $bill->getCustomer()->getLockerNumber() : "" ) ?>" /></div>
                        </div>
                        <div class="float-left width-35 padding">
                            <div class="float-left width-100 text-weight-bold">Remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Remitente" id="txtFrom" value="<?php echo $bill->getFrom(); ?>" /></div>
                        </div>
                        <div class="float-left width-30 padding">
                            <div class="float-left width-100 text-weight-bold">Dirección remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección remitente" id="txtFromAddress" value="<?php echo $bill->getFromAddress(); ?>" /></div>
                        </div>
                        <div class="float-left width-20 padding">
                            <div class="float-left width-100 text-weight-bold">Teléfono remitente</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono remitente" id="txtFromPhone" value="<?php echo $bill->getFromPhone(); ?>" /></div>
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
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Destinatario" id="txtTo" value="<?php echo $bill->getTo(); ?>" /></div>
                        </div>
                        <div class="float-left width-30 padding">
                            <div class="float-left width-100 text-weight-bold">Dirección destinatario</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Dirección destinatario" id="txtToAddress" value="<?php echo $bill->getToAddress(); ?>" /></div>
                        </div>
                        <div class="float-left width-20 padding">
                            <div class="float-left width-100 text-weight-bold">Teléfono destinatario</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Teléfono destinatario" id="txtToPhone" value="<?php echo $bill->getToPhone(); ?>" /></div>
                        </div>
                    </div>
                    <!-- Temporary payment, currency, payment Method and shipment company -->
                    <div class="display-table width-100 margin-bottom-2x">
                        <div class="float-left width-25 padding">
                            <div class="float-left width-100 text-weight-bold">Fecha</div>
                            <div class="float-left width-100"><input value="<?php echo substr($bill->getCreatedTimestamp(),0,10); ?>" class="input-text-underline" type="date" data-required="true" data-name="Fecha" id="txtDate" /></div>
                        </div>
                        <div class="float-left width-25 padding">
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
                                    
                                    if ($bill->getToCountry() != null) {
                                        $slCountry->setSelected($bill->getToCountry()->getIdCountry());
                                    }
                                    $slCountry->render();
                                ?>
                            </div>
                        </div>
                        <div class="float-left width-25 padding">
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
                                    $slCurrency->setSelected($bill->getCurrency());
                                    $slCurrency->render();
                                ?>
                            </div>
                        </div>
                        <div class="float-left width-25 padding">
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
                                    $slShipmentCompany->setSelected($bill->getShipmentCompany() != null ? $bill->getShipmentCompany()->getIdShipmentCompany() : "");
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
                                <th># Fila</th>
                                <th></th> <!-- Id hidden -->
                                <th>Descripción</th>
                                <th>No. Caja</th>
                                <th>Peso</th>
                                <th>Total</th>
                                <th>Trazabilidad</th>
                                <th></th>   <!-- Tracking date -->
                                <th></th>   <!-- Save -->
                                <th>Entregado</th>   <!-- Delivered -->
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            <?php
                                $items = $bill->getItems();
                                
                                for ($i=0; $i<count($items); $i++) {
                                    $item = $items[$i];
                                    $trackings = $item->getTracking();
                                    $tracking = count($trackings) > 0 ? $trackings[0] : "";
                                    
                                    $code = '<tr>';
                                    
                                    if ($i > 0) {
                                        $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>';
                                    } else {
                                        $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i></td>';
                                    }
                                    $code .= '<td name="lblRowNumber" style="white-space: nowrap; text-align:center">' . ($i + 1) . '</td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="hidden" name="id" value="' . $item->getIdBillItem() . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" value="' . $item->getDescription() . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="No. Caja" name="boxNumber" class="input-text-underline" value="' . $item->getBoxNumber() . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Peso" name="weight" class="input-text-underline" value="' . $item->getWeight() . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Total" name="total" class="input-text-underline" value="' . $item->getAmount() . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="false" data-name="Trazabilidad" name="txtTrackingDescription_' . $item->getIdBillItem() . '" class="input-text-underline" value="' . ($tracking != null ? $tracking->getDescription() : "") . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="date" data-required="false" data-name="Trazabilidad" name="txtTrackingDate_' . $item->getIdBillItem() . '" class="input-text-underline" value="' . ($tracking != null ? substr($tracking->getCreatedTimestamp(),0,10) : "") . '" /></td>';
                                    $code .= "<td><button name='btnSaveTracking' data-id='" . $item->getIdBillItem() . "'><i class='fa fa-save'></i></button></td>";
                                    $code .= "<td class='text-align-center'><input name='chkDelivered' data-id='" . $item->getIdBillItem() . "' type='checkbox' " . ($item->wasDelivered() ? "checked disabled" : "") . " /></td>";
                                    $code .= '</tr>';
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="display-table width-100 margin-top-3x text-size-m text-weight-bold text-align-right" id="lblTotalWeight">Peso: <?php echo number_format($bill->getWeight(),2) ?></div>
                    <div class="display-table width-100 text-size-l text-weight-bold text-align-right" id="lblTotal">Total: $ <?php echo number_format($bill->getTotal(),2) ?></div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            function Item(id,description,box,weight,total) {
                this.id = id;
                this.description = description;
                this.box = box;
                this.weight = weight;
                this.total = total;
            }
            
            $(document).ready(function() {
                let rows = $("#tblItems").find("tbody").find("tr");
                if (rows.length === 0) {
                    var tr = $(document.createElement("tr"));
                    $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                    $(tr).append('<td name="lblRowNumber" style="white-space: nowrap; text-align:center"></td>');
                    $(tr).append('<td><input id="' + Math.random() * 9999 + '" type="hidden" data-required="true" name="id" value="-1" /></td>');
                    $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>');
                    $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="No. Caja" name="boxNumber" class="input-text-underline" /></td>');
                    $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Peso" name="weight" class="input-text-underline" /></td>');
                    $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Total" name="total" class="input-text-underline" /></td>');
                    $(tr).append('<td></td><td></td><td></td><td></td>');
                    $("#tblBody").append($(tr));
                    updateRowsNumber();
                }
            });
            
            $(document).on("click", "[name=btnSaveTracking]", function(e) {
                var id = $(this).data("id");
                if (confirm("¿Confirma guardar la trazabilidad?")) {
                    $.ajax({
                        url: URL_API + "Bill/Tracking.php",
                        type: "POST",
                        data: {
                            IdBillItem: id,
                            Text: $("[name=txtTrackingDescription_" + id + "]").val(),
                            Date: $("[name=txtTrackingDate_" + id + "]").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            closePreload();
                        }
                    });
                }
            });
            
            $(document).on("click", "[name=chkDelivered]", function(e) {
                if (confirm("¿Confirma enviado?")) {
                    $.ajax({
                        url: URL_API + "Bill/Deliver.php",
                        data: {
                            Id: $(e.target).data("id")
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            $(e.target).attr("disabled", "true");
                            closePreload();
                        }
                    });
                } else {
                    return false;
                }
            });
            
            $("#btnSave").on("click", function(e) {
                var tblItems = new Form($("#tblItems"));
                if (tblItems.validate()) {
                    var items = new Array();
                    
                    var rows = $("#tblItems").find("tbody").find("tr");
                    
                    for (var i=0; i<rows.length; i++) {
                        items.push(new Item($(rows[i]).find("[name=id]").val(),$(rows[i]).find("[name=description]").val(),$(rows[i]).find("[name=boxNumber]").val(),$(rows[i]).find("[name=weight]").val(),$(rows[i]).find("[name=total]").val()));
                    }
                    
                    $.ajax({
                        url: URL_API + "Bill/Edit.php",
                        type: "POST",
                        data: {
                            Date: $("#txtDate").val(),
                            IdBill: $("#hdIdBill").val(),
                            FromLockerNumber: $("#txtLockerFrom").val(),
                            From: $("#txtFrom").val(),
                            FromAddress: $("#txtFromAddress").val(),
                            FromPhone: $("#txtFromPhone").val(),
                            To: $("#txtTo").val(),
                            ToAddress: $("#txtToAddress").val(),
                            ToPhone: $("#txtToPhone").val(),
                            ToCountry: $("#slCountry").val(),
                            Currency: $("#slCurrency").val(),
                            IdShipmentCompany: $("#slShipmentCompany").val(),
                            Items: JSON.stringify(items)
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            document.location.reload();
                        }
                    });
                }
            });
            
            $(document).on("click", "[name=btnCloneRow]", function() {
                var tr = $(document.createElement("tr"));
                $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                $(tr).append('<td name="lblRowNumber" style="white-space: nowrap; text-align:center"></td>');
                $(tr).append('<td><input id="' + Math.random() * 9999 + '" type="hidden" data-required="true" name="id" value="-1" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="No. Caja" name="boxNumber" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Peso" name="weight" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Total" name="total" class="input-text-underline" /></td>');
                $(tr).append('<td></td><td></td><td></td><td></td>');
                $("#tblBody").append($(tr));
                updateRowsNumber();
            });
            
            $(document).on("click", "[name=btnDeleteRow]", function(e) {
                if (confirm("¿Confirma eliminar el registro?")) {
                    var id = $(this).parent().parent().find("[name=id]").val();
                    
                    if (id !== "-1") {
                        // Send ajax request
                        $.ajax({
                            url: URL_API + "Bill/DeleteBillItem.php",
                            type: "POST",
                            data: {
                                Id: id
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(response) {
                                $(e.target).parent().parent().remove();
                                calculateTotal();
                                updateRowsNumber();
                                closePreload();
                            }
                        });
                    } else {
                        $(e.target).parent().parent().remove();
                        calculateTotal();
                        updateRowsNumber();
                    }
                }
            });
            
            $(document).on("keyup", "[name=total]", function() {
                calculateTotal();
            });
            
            $(document).on("keyup", "[name=weight]", function() {
                calculateTotal();
            });
            
            function updateRowsNumber() {
                var rows = $("#tblItems").find("tbody").find("tr");
                for (var i=0; i<rows.length; i++) {
                    var label = $(rows[i]).find("[name=lblRowNumber]");
                    label.text(i + 1);
                }
            }
            
            function calculateTotal() {
                var total = 0;
                var totalWeight = 0;
                var inputs = $("[name=total]");
                var inputsWeight = $("[name=weight]");
                
                for (var i=0; i<inputs.length; i++) {
                    if ($(inputs[i]).val() !== "") {
                        total += parseFloat($(inputs[i]).val());
                    }
                }
                
                for (var i=0; i<inputsWeight.length; i++) {
                    if ($(inputsWeight[i]).val() !== "") {
                        totalWeight += parseFloat($(inputsWeight[i]).val());
                    }
                }
                
                $("#lblTotalWeight").text("Peso: " + totalWeight.toFixed(2));
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
            
            // Tabs
            $("#btnTabPDF").on("click", function() {
                $.redirect(URL_API + "PDF/Bill.php", {IdBill: $(this).data("id")}, "POST", "_blank");
            });
            
            $("#btnTabEdit").on("click", function() {
                $.redirect("../Bills/Edit.php", {IdBill: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabPayment").on("click", function() {
                $.redirect("../Bills/Pay.php", {IdBill: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabTracking").on("click", function() {
                $.redirect("../Bills/Tracking.php", {IdBill: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabAnnul").on("click", function() {
                if (confirm("¿Confirma anular la factura?")) {
                    $.ajax({
                        url: URL_API + "Bill/Annull.php",
                        data: {
                            Id: $(this).data("id")
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            document.location.href = "../Bills/";
                        }
                    });
                }
            });
            
            $("[name=btnDelete]").on("click", function(e) {
                if (confirm("¿Confirma eliminar la foto?")) {
                    $.ajax({
                        url: URL_API + "Bill/DeletePicture.php",
                        type: "POST",
                        data: {
                            Id: $("#hdIdBill").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                            } else {
                                document.location.reload();
                            }
                            
                            //closePreload();
                        }
                    });
                }
            });
            
            $("[name=flPicture]").on("change", function(e) {
                var form = new FormData();
                form.append("Id", $("#hdIdBill").val());
                form.append("File", e.target.files[0]);
                
                $.ajax({
                    url: URL_API + "Bill/LoadPicture.php",
                    type: "POST",
                    data: form,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function(response) {
                        var r = JSON.parse(response);
                        if (r.type === "Exception") {
                            new Notification("ERROR", r.message);
                        } else {
                            document.location.reload();
                        }
                    }
                });
            });
        </script>
    </body>
</html>
