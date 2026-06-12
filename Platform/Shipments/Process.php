<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Procesar envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $customer = CustomerDAO::getCustomerById(CloudEngineHTTP::getPostVar("IdCustomer"));
    
    if ($customer == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Shipments");
    }
    
    $layout = new Layout();
    $layout->setTitle("Procesar envío");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Procesar envío", PUBLIC_PATH_PLATFORM . "Shipments"); ?>
            <div id="frmShipment" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdIdCustomer" value="<?php echo $customer->getIdCustomer(); ?>" />
                <input type="hidden" id="hdCountry" value="<?php echo $customer->getCity()->getCountry()->getName(); ?>" />
                <!-- Actions -->
                <div class="width-100 display-table text-align-right">
                    <button id="btnFinish" class="button-red display-inline-block text-decoration-none">LIQUIDAR</button>
                </div>
                <!-- Personal info -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <div class="display-table width-100">
                        <div class="float-left width-15 text-weight-bold padding">Casillero No.</div>
                        <div class="float-left width-10 label-underline"><?php echo $customer->getLockerNumber(); ?></div>
                        <div class="float-left width-10 text-weight-bold padding">Cliente</div>
                        <div class="float-left width-30 label-underline"><?php echo $customer->getNames(); ?></div>
                        <div class="float-left width-15 text-weight-bold padding">País / Ciudad</div>
                        <div class="float-left width-20 label-underline"><?php echo $customer->getCity()->getCountry()->getName() . " / " . $customer->getCity()->getName(); ?></div>
                    </div>
                </div>
                <!-- Purchases -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table id="tblPurchases" class="table width-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>¿Incluir?<br/><input type="checkbox" id="chkAll" /></th>
                                <th>Cantidad</th>
                                <th>Tracking No.</th>
                                <th>Contenido</th>
                                <th>Peso</th>
                                <th>Largo</th>
                                <th>Ancho</th>
                                <th>Alto</th>
                                <th>Libras vol.</th>
                                <th>Peso vol.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $purchases = $customer->getPendingPurchases();
                                foreach ($purchases as $p) {
                                    $code = "<tr>";
                                    
                                    if ($p->wasRequested()) {
                                        $code .= "<td class='text-align-center'><div style='width:10px; height:10px; background:#4CAF50; border-radius:100%'></div></td>";
                                    } else {
                                        $code .= "<td class='text-align-center'><div style='width:10px; height:10px; background:#eee; border-radius:100%'></div></td>";
                                    }
                                    
                                    $code .= "<td class='text-align-center'><input name='chkPurchase' data-id='" . $p->getIdPurchase() . "' data-weight='" . $p->getNetWeight() . "' data-vol-weight='" . $p->getVolumetricWeight() . "' type='checkbox' /></td>";
                                    $code .= "<td style='text-align:center'>" . $p->getQuantity() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getTrackingNumber() . "</td>";
                                    $code .= "<td>" . $p->getContent() . "</td>";
                                    $code .= "<td class='text-align-center'>";
                                    $code .= "<input name='txtNetWeight' id='txtNetWeight-" . $p->getIdPurchase() . "' data-id='" . $p->getIdPurchase() . "' type='number' class='input-text-underline text-align-center' style='width:100px' value='" . $p->getNetWeight() . "'/>";
                                    $code .= "<button name='btnUpdateNetWeight' data-id='" . $p->getIdPurchase() . "'><i class='fa fa-save'></i></button>";
                                    $code .= "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getLong() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getWidth() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getHigh() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getVolumetricPounds() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getVolumetricWeight() . "</td>";
                                    $code .= "<td class='text-align-center'><div name='btnEditPurchase' data-id='" . $p->getIdPurchase() . "' class='text-decoration-underline cursor-pointer'>Editar</div></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Data -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <div class="display-table width-100">
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Fecha</div>
                            <div>
                                <input id="txtDate" type="date" data-name="Fecha" data-required="true" class="input-text-underline " value="<?php echo date("Y-m-d") ?>" />
                            </div>
                        </div>
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Peso neto</div>
                            <div class="" id="lblNetWeight">0</div>
                        </div>
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Peso volumétrico</div>
                            <div class="" id="lblVolumetricWeight">0</div>
                        </div>
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">TRM</div>
                            <div>
                                <input id="txtTRM" type="number" data-name="TRM" data-required="true" class="input-text-underline " value="0" />
                            </div>
                        </div>
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Valor libra</div>
                            <div>
                                <input id="txtPoundValue" type="number" data-name="Vr. Libra" data-required="true" class="input-text-underline " value="0" />
                            </div>
                        </div>
                        <div style="width: calc(100% / 6)" class="float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Valor libra volumétrica</div>
                            <div>
                                <input id="txtVolumetricPoundValue" type="number" data-name="Vr. Libra volumétrica" data-required="true" class="input-text-underline " value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="display-table width-100 margin-top-3x">
                        <div class="width-25 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Valor declarado</div>
                            <div>
                                <input id="txtDeclaredValue" type="number" data-name="Valor declarado" data-required="true" class="input-text-underline " value="0" />
                            </div>
                        </div>
                        <div class="width-25 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Gestión aduanera</div>
                            <div>
                                <div class="" id="lblTax">0</div>
                            </div>
                        </div>
                        <div class="width-25 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Flete</div>
                            <div>
                                <div class="" id="lblFreight">0</div>
                            </div>
                        </div>
                        <div class="width-25 float-left padding">
                            <div id="lblSecure" class="text-weight-bold margin-bottom-3x">Seguro</div>
                            <div>
                                <input id="txtSecure" type="number" data-name="Seguro" data-required="true" class="input-text-underline " value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="display-table width-100 margin-top-3x">
                        <div class="width-10 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Valor adicional</div>
                            <div>
                                <input id="txtAdditionalValue" type="number" class="input-text-underline " value="0" />
                            </div>
                        </div>
                        <div class="width-20 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Desc. vr. adicional</div>
                            <div>
                                <input id="txtAdditionalDescription" type="text" class="input-text-underline " />
                            </div>
                        </div>
                        <div class="width-20 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Moneda</div>
                            <div>
                                <?php
                                    $slCurrency = new CloudEngineHTMLSelect();
                                    $slCurrency->addPropertie("class", "select-underline");
                                    $slCurrency->addPropertie("id", "slCurrency");
                                    $slCurrency->addPropertie("data-name", "Moneda");
                                    $slCurrency->addPropertie("data-required", "true");
                                    $slCurrency->addOption("Seleccione una opción", "");
                                    $slCurrency->addOption("COP", "COP");
                                    $slCurrency->addOption("USD", "USD");
                                    $slCurrency->render();
                                ?>
                            </div>
                        </div>
                        <div class="width-20 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Método de pago</div>
                            <div>
                                <?php
                                    $paymentMethods = PaymentMethodDAO::getPaymentMethods();
                                    $slMethod = new CloudEngineHTMLSelect();
                                    $slMethod->addPropertie("class", "select-underline");
                                    $slMethod->addPropertie("id", "slPaymentMethod");
                                    $slMethod->addPropertie("data-name", "Método de pago");
                                    $slMethod->addOption("Pendiente de pago", "");
                                    foreach ($paymentMethods as $m) {
                                        $slMethod->addOption($m->getName(), $m->getIdPaymentMethod());
                                    }
                                    $slMethod->render();
                                ?>
                            </div>
                        </div>
                        <div class="width-10 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">No. Secuencia</div>
                            <div>
                                <input id="txtSequenceNumber" data-name="No. Secuencia" data-required="true" type="text" class="input-text-underline " />
                            </div>
                        </div>
                        <div class="width-20 float-left padding">
                            <div class="text-weight-bold margin-bottom-3x">Empresa</div>
                            <div>
                                <?php
                                    $shipmentCompanies = ShipmentCompanyDAO::getShipmentCompanies();
                                    $slShipmentCompany = new CloudEngineHTMLSelect();
                                    $slShipmentCompany->addPropertie("class", "select-underline");
                                    $slShipmentCompany->addPropertie("id", "slShipmentCompany");
                                    $slShipmentCompany->addPropertie("data-required", "false");
                                    $slShipmentCompany->addPropertie("data-name", "Empresa de envío");
                                    $slShipmentCompany->addOption("Seleccione una opción", "");
                                    foreach ($shipmentCompanies as $s) {
                                        $slShipmentCompany->addOption($s->getName(), $s->getIdShipmentCompany());
                                    }
                                    $slShipmentCompany->render();
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="display-table width-100 margin-top-3x text-size-xl text-weight-bold text-align-center" id="lblTotal">Total: 0</div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            
            var netWeight = 0;
            var volumetricWeight = 0;
            var TRM = 0;
            var pound = 0;
            var volumetricPound = 0;
            var declaredValue = 0;
            var tax = 0;
            var freight = 0;
            var secure = 0;
            var additional = 0;
            var total = 0;
            
            $("#btnFinish").on("click", function(e) {
                var frm = new Form($("#frmShipment"));
                if (frm.validate() && confirm("¿Confirma liquidar el envío?")) {
                    var purchases = new Array();
                    var chkPurchases = $("[name=chkPurchase]");
                    
                    for (var i=0; i<chkPurchases.length; i++) {
                        if (chkPurchases[i].checked) {
                            purchases.push($(chkPurchases[i]).data("id"));
                        }
                    }
                    
                    if (purchases.length === 0) {
                        new Notification("ERROR", "Debe seleccionar mínimo 1 item.");
                        return;
                    } else {
                        $.ajax({
                            url: URL_API + "Shipment/Process.php",
                            type: "POST",
                            data: {
                                Purchases: JSON.stringify(purchases),
                                TRM: $("#txtTRM").val(),
                                PoundValue: $("#txtPoundValue").val(),
                                VolumetricPoundValue: $("#txtVolumetricPoundValue").val(),
                                DeclaredValue: $("#txtDeclaredValue").val(),
                                Secure: $("#txtSecure").val(),
                                AdditionalValue: $("#txtAdditionalValue").val(),
                                AdditionalDescription: $("#txtAdditionalDescription").val(),
                                Currency: $("#slCurrency").val(),
                                PaymentMethod: $("#slPaymentMethod").val(),
                                SequenceNumber: $("#txtSequenceNumber").val(),
                                IdShipmentCompany: $("#slShipmentCompany").val(),
                                Date: $("#txtDate").val()
                            },
                            beforeSend: function() {
                                showPreload();
                            },
                            success: function(r) {
                                var json = JSON.parse(r);
                                if (json.type === "Exception") {
                                    new Notification("ERROR", json.message);
                                } else {
                                    new Notification("SUCCESS", "Envío registrado correctamente.");
                                    if (confirm("¿Desea generar el formato en PDF?")) {
                                        $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: json.body}, "POST", "_blank");
                                        setTimeout(back, 500);
                                    } else {
                                        $.redirect("Pending.php");
                                    }
                                }
                                closePreload();
                            }
                        });
                    }
                }
            });
            
            function back() {
                document.location.href = "Pending.php";
            }
            
            $(":input").on("change keyup", function(e) {
                if ($(this).val() === "") {
                    $(this).val(0);
                }
                simulate();
            });
            
            $("[name=btnEditPurchase]").on("click", function(e) {
                var id = $(e.target).data("id");
                $.redirect(URL_PLATFORM + "/Purchases/Edit.php", {IdPurchase: id}, "POST", "_blank");
            });
            
            $("[name=btnUpdateNetWeight]").on("click", function() {
                var id = $(this).data("id");
                var netWeight = $("#txtNetWeight-" + id).val();
                
                if (confirm("¿Confirma cambiar el valor?")) {
                    $.ajax({
                        url: URL_API + "Purchase/UpdateNetWeight.php",
                        type: "POST",
                        data: {
                            IdPurchase: id,
                            NetWeight: netWeight
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(r) {
                            new Notification("SUCCESS", "Peso cambiado correctamente.");
                            closePreload();
                        }
                    });
                }
            });
            
            $("#chkAll").on("change", function() {
                if ($(this)[0].checked) {
                    $("[name=chkPurchase]").prop("checked",true);
                } else {
                    $("[name=chkPurchase]").prop("checked",false);
                }
                simulate();
            });
            
            function simulate() {
                netWeight = 0;
                volumetricWeight = 0;
                TRM = 0;
                pound = 0;
                volumetricPound = 0;
                declaredValue = 0;
                tax = 0;
                freight = 0;
                secure = 0;
                additional = 0;
                total = 0;
                currency = "";
                
                var purchases = $("[name=chkPurchase]");
                
                for (var i=0; i<purchases.length; i++) {
                    if ($(purchases[i])[0].checked === true) {
                        netWeight += parseFloat($("#txtNetWeight-" + $(purchases[i]).data("id")).val());
                        volumetricWeight += parseFloat($(purchases[i]).data("vol-weight"));
                    }
                }
                
                pound = parseFloat($("#txtPoundValue").val());
                volumetricPound = parseFloat($("#txtVolumetricPoundValue").val());
                TRM = parseFloat($("#txtTRM").val());
                declaredValue = parseFloat($("#txtDeclaredValue").val());
                secure = parseFloat($("#txtSecure").val());
                additional = parseFloat($("#txtAdditionalValue").val());
                currency = $("#slCurrency").val();
                
                // Tax
                if ($("#hdCountry").val() === "Colombia") {
                    if ((currency === "USD" && declaredValue < 201)
                            || (currency === "COP" && declaredValue < 201 * TRM)) {
                        tax = Number(parseFloat(declaredValue * 0.12).toFixed(2));
                    } else {
                        tax = Number(parseFloat(declaredValue * 0.31).toFixed(2));
                    }
                } else {
                    tax = 0;
                }
                
                // Freight
                if (volumetricWeight < netWeight) {
                    freight = Number(parseFloat(pound * netWeight).toFixed(2));
                } else {
                    freight = Number(parseFloat((pound * netWeight) + (volumetricPound * volumetricWeight)).toFixed(2));
                }
                
                // Secure
                if (declaredValue > 0 &&
                        ((declaredValue < 100 && currency === "USD") ||
                        (declaredValue < 100 * TRM && currency === "COP"))) {
                    suggestedSecure = 5;
                } else {
                    suggestedSecure = Number(parseFloat(declaredValue * 0.05).toFixed(2));
                }
                
                total = Number(parseFloat(tax + freight + secure + additional).toFixed(2));
                
                $("#lblNetWeight").text(netWeight.toFixed(2));
                $("#lblVolumetricWeight").text(volumetricWeight);
                $("#lblTax").text(tax);
                $("#lblFreight").text(freight);
                //$("#lblSecure").text("Seguro: " + suggestedSecure + " USD");
                
                if (!isNaN(total)) {
                    $("#lblTotal").text("Total: " + total);
                }
            }
            
            simulate();
        </script>
    </body>
</html>
