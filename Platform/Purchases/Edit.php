<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Mercancía")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $purchase = PurchaseDAO::getPurchaseById(CloudEngineHTTP::getPostVar("IdPurchase"));
    
    if ($purchase == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Purchases");
    }
    
    $layout = new Layout();
    $layout->setTitle("Editar mercancía");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Editar mercancía", PUBLIC_PATH_PLATFORM . "Purchases"); ?>
            <div id="frmPurchase" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdIdPurchase" value="<?php echo $purchase->getIdPurchase(); ?>" />
                <datalist id="dlCustomers"></datalist>
                <!-- Actions -->
                <div class="width-100 display-table text-align-right">
                    <button id="btnSave" class="button-red display-inline-block text-decoration-none">GUARDAR</button>
                </div>
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-4x">
                    <!-- Locker, customer and tracking -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="date" data-required="true" data-name="Fecha" id="txtDate" value="<?php echo substr($purchase->getCreatedTimestamp(),0,10) ?>" /></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Casillero No.</div>
                            <div class="float-left width-100"><input list="dlCustomers" class="input-text-underline" type="text" data-required="true" data-name="Casillero No." id="txtLockerNumber" value="<?php echo $purchase->getCustomer()->getLockerNumber(); ?>" /></div>
                        </div>
                        <div class="float-left width-40 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Cliente</div>
                            <div class="float-left width-100" id="lblCustomerNames"><?php echo $purchase->getCustomer()->getNames(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Tracking No.</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Tracking No." id="txtTrackingNumber" value="<?php echo $purchase->getTrackingNumber(); ?>" /></div>
                        </div>
                    </div>
                    <!-- Weight, content, quantity and store -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-40 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Contenido</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Contenido" id="txtContent" value="<?php echo $purchase->getContent(); ?>" /></div>
                        </div>
                        <div class="float-left width-15 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Peso neto</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="number" data-required="true" data-name="Peso neto" id="txtNetWeight" value="<?php echo $purchase->getNetWeight(); ?>" /></div>
                        </div>
                        <div class="float-left width-15 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Cantidad</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="number" data-required="true" data-name="Cantidad" id="txtQuantity" value="<?php echo $purchase->getQuantity(); ?>" /></div>
                        </div>
                        <div class="float-left width-30 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Tienda</div>
                            <?php
                                $stores = StoreDAO::getStores();
                                $slStore = new CloudEngineHTMLSelect();
                                $slStore->addPropertie("class", "select-underline");
                                $slStore->addPropertie("id", "slStore");
                                $slStore->addPropertie("data-required", "false");
                                $slStore->addPropertie("data-name", "Tienda");
                                $slStore->addOption("Selecciona una tienda", "");
                                foreach ($stores as $s) {
                                    $slStore->addOption($s->getName(), $s->getIdStore());
                                }
                                if ($purchase->getStore() != null) {
                                    $slStore->setSelected($purchase->getStore()->getIdStore());
                                }
                                $slStore->render();
                            ?>
                        </div>
                    </div>
                    <!-- Dimensions and value -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Largo</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Largo" id="txtLong" value="<?php echo $purchase->getLong(); ?>" /></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Ancho</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Ancho" id="txtWidth" value="<?php echo $purchase->getWidth(); ?>" /></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Alto</div>
                            <div class="float-left width-100"><input class="input-text-underline" type="text" data-required="true" data-name="Alto" id="txtHigh" value="<?php echo $purchase->getHigh(); ?>" /></div>
                        </div>
                        <div class="float-left width-25 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Peso vol.</div>
                            <div class="float-left width-100"><?php echo $purchase->getVolumetricPounds(); ?></div>
                        </div>
                    </div>
                </div>
                <!-- Pictures -->
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table class="table width-100">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $pictures = $purchase->getPictures();
                                $available = 3 - count($pictures);
                                
                                foreach ($pictures as $p) {
                                    $code = "<tr>";
                                    $code .= "<td>Foto</td>";
                                    $code .= "<td class='text-align-center'><a class='display-inline-block margin-right-3x' target='_blank' href='" . PUBLIC_PATH_STATIC. "Uploads/" . $p->getIdPurchasePicture() . "'>Ver</a><div class='display-inline-block text-decoration-underline cursor-pointer' name='btnDelete' data-id='" . $p->getIdPurchasePicture() . "'>Eliminar</div></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                                
                                for ($i=0; $i<$available; $i++) {
                                    $code = "<tr>";
                                    $code .= "<td colspan='2'><input name='flPicture' type='file' /></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Tracking -->
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table class="table width-100">
                        <thead>
                            <tr>
                                <th>Movimiento</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tracking = $purchase->getTracking();
                                foreach ($tracking as $t) {
                                    $code = "<tr>";
                                    $code .= "<td>" . $t->getDescription() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $t->getCreatedTimestamp() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $t->getSystemUser()->getNames() . "</td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnSave").on("click", function(e) {
                var frmPurchase = new Form($("#frmPurchase"));
                if (frmPurchase.validate()) {
                    $.ajax({
                        url: URL_API + "Purchase/Edit.php",
                        type: "POST",
                        data: {
                            IdPurchase: $("#hdIdPurchase").val(),
                            Date: $("#txtDate").val(),
                            LockerNumber: $("#txtLockerNumber").val(),
                            TrackingNumber: $("#txtTrackingNumber").val(),
                            Weight: $("#txtNetWeight").val(),
                            Content: $("#txtContent").val(),
                            Store: $("#slStore").val(),
                            Long: $("#txtLong").val(),
                            Width: $("#txtWidth").val(),
                            High: $("#txtHigh").val(),
                            Quantity: $("#txtQuantity").val()
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
            
            $("#txtLockerNumber").on("keyup", function(e) {
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
            
            $("#txtLockerNumber").on("blur", function(e) {
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
                                $("#lblCustomerNames").text(customer.names);
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $("[name=btnDelete]").on("click", function(e) {
                if (confirm("¿Confirma eliminar la foto?")) {
                    var id = $(e.target).data("id");
                    $.ajax({
                        url: URL_API + "Purchase/DeletePicture.php",
                        type: "POST",
                        data: {
                            IdPurchasePicture: id
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
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $("[name=flPicture]").on("change", function(e) {
                var form = new FormData();
                form.append("IdPurchase", $("#hdIdPurchase").val());
                form.append("File", e.target.files[0]);
                
                $.ajax({
                    url: URL_API + "Purchase/LoadPicture.php",
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
