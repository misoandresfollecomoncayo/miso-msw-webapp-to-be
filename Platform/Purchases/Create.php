<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Mercancía")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Recepción de mercancía");
    $layout->printHead();
    
    $rows = 1;
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Recepción de mercancía", PUBLIC_PATH_PLATFORM . "Purchases/"); ?>
            <div class="padding-5x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <div class="float-left">
                        <div class="text-weight-bold float-left margin-top-2x">¿Cuántos registros desea cargar?</div>
                        <?php
                            $slRows = new CloudEngineHTMLSelect();
                            $slRows->addPropertie("class", "margin-left-3x select-underline float-left");
                            $slRows->addPropertie("style", "width: 50px");
                            $slRows->addPropertie("id", "slRows");
                            for ($i=1;$i<=100;$i++) {
                                $slRows->addOption($i, $i);
                            }
                            $slRows->render();
                        ?>
                    </div>
                    <button id="btnSave" class="button-red">REGISTRAR</button>
                </div>
                <!-- Table -->
                <div id="frmReception" class="padding-4x background-color-white border-radius box-shadow display-table">
                    <?php
                        $stores = StoreDAO::getStores();
                        foreach ($stores as $s) {
                            echo '<input type="hidden" name="hdStore" data-name="' . $s->getName() . '" data-id="' . $s->getIdStore() . '" />';
                        }
                        $currentDate = date("Y-m-d");
                        echo '<input type="hidden" id="hdCurrentDate" value="' . $currentDate . '" />';
                    ?>
                    <datalist id="dlCustomers"></datalist>
                    <table id="tblData" class="table stripe">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Fecha</th>
                                <th>Casillero / Nombres</th>
                                <th>Tracking number</th>
                                <th>Cliente</th>
                                <th>Peso neto</th>
                                <th>Cantidad</th>
                                <th>Contenido</th>
                                <th>Tienda</th>
                                <th>Largo</th>
                                <th>Ancho</th>
                                <th>Alto</th>
                                <th>Fotos</th>
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            <?php
                                for ($i=0; $i<$rows; $i++) {
                                    $code = '<tr>';
                                    $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="date" data-required="true" data-name="Fecha" name="Date" class="input-text-underline" value="' . $currentDate . '" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" list="dlCustomers" type="text" data-required="true" data-name="Casillero" name="lockerNumber" style="width: 150px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Tracking number" name="trackingNumber" style="width: 200px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><div name="names">Nombres</div></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" data-required="true" data-name="Peso neto" name="weight" type="number" style="width: 100px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" data-required="true" data-name="Cantidad" name="quantity" type="number" style="width: 100px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><textarea id="' . rand(0, 9999) . '" data-required="true" data-name="Contenido" name="content" style="width: 300px; height:50px; resize:none" class="input-text-underline"></textarea></td>';
                                    $code .= '<td style="white-space: nowrap">';
                                    $slStore = new CloudEngineHTMLSelect();
                                    $slStore->addPropertie("class", "select-underline");
                                    $slStore->addPropertie("style", "width: 200px");
                                    $slStore->addPropertie("name", "store");
                                    $slStore->addPropertie("id", rand(0,9999));
                                    $slStore->addPropertie("data-required", "false");
                                    $slStore->addPropertie("data-name", "Tienda");
                                    $slStore->addOption("Tienda", "");
                                    foreach ($stores as $s) {
                                        $slStore->addOption($s->getName(), $s->getIdStore());
                                    }
                                    $code .= $slStore->getCode();
                                    $code .= '</td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" value="0" data-required="true" data-name="Largo" name="long" type="number" style="width: 100px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" value="0" data-required="true" data-name="Ancho" name="width" type="number" style="width: 100px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" value="0" data-required="true" data-name="Alto" name="high" type="number" style="width: 100px" class="input-text-underline" /></td>';
                                    $code .= '<td style="white-space: nowrap"><input type="file" name="flPicture1" style="width:220px" /><input type="file" name="flPicture2" style="width:220px" /><input type="file" name="flPicture3" style="width:220px" /></td>';
                                    $code .= '</tr>';
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
            var rows = 1;
            var bulkLog = new Array();
            var bulkCurrent = 0;
            
            $("#btnSave").on("click", function(e) {
                var frmReception = new Form($("#frmReception"));
                if (frmReception.validate()) {
                    showPreload();
                    bulkInsert();
                }
            });
            
            function bulkInsert() {
                if (parseInt(bulkCurrent) === parseInt(rows)) {
                    $.redirect("Result.php", {Result: JSON.stringify(bulkLog)});
                    return;
                }
                
                var currentRow = $("#tblData").find("tbody").find("tr")[bulkCurrent];
                
                var form = new FormData();
                form.append("Date", $(currentRow).find("[name=Date]").val());
                form.append("LockerNumber", $(currentRow).find("[name=lockerNumber]").val());
                form.append("TrackingNumber", $(currentRow).find("[name=trackingNumber]").val());
                form.append("Content", $(currentRow).find("[name=content]").val());
                form.append("IdStore", $(currentRow).find("[name=store]").val());
                form.append("NetWeight", $(currentRow).find("[name=weight]").val());
                form.append("Long", $(currentRow).find("[name=long]").val());
                form.append("Width", $(currentRow).find("[name=width]").val());
                form.append("High", $(currentRow).find("[name=high]").val());
                form.append("Quantity", $(currentRow).find("[name=quantity]").val());
                form.append("Picture1", $(currentRow).find("[name=flPicture1]").prop('files')[0]);
                form.append("Picture2", $(currentRow).find("[name=flPicture2]").prop('files')[0]);
                form.append("Picture3", $(currentRow).find("[name=flPicture3]").prop('files')[0]);
                
                $.ajax({
                    url: URL_API + "Purchase/Create.php",
                    type: "POST",
                    data: form,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        //showPreload();
                    },
                    success: function(response) {
                        var r = JSON.parse(response);
                        if (r.type === "Exception") {
                            bulkLog.push("Error: " + r.message);
                        } else {
                            bulkLog.push("Correcto: " + r.body);
                        }
                        bulkCurrent ++;
                        bulkInsert();
                    }
                });
            }
            
            $(document).on("keyup", "[name=lockerNumber]", function(e) {
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
            
            $(document).on("blur", "[name=trackingNumber]", function(e) {
                var trackingNumber = $(e.target).val();
                if (trackingNumber !== "") {
                    $.ajax({
                        url: URL_API + "ArrivalAlert/GetByTrackingNumber.php",
                        type: "POST",
                        data: {
                            TrackingNumber: trackingNumber
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            
                            if (r.type === "Exception") {
                                //new Notification("ERROR", r.message);
                            } else {
                                var alert = JSON.parse(r.body)[0];
                                $(e.target).parent().parent().find("[name=quantity]").val(alert.quantity);
                                $(e.target).parent().parent().find("[name=content]").val(alert.detail);
                                //$(e.target).parent().parent().find("[name=content]").attr("disabled", true);
                                //$(e.target).parent().parent().find("[name=content]").css("background-color", "#eee");
                                $(e.target).parent().parent().find("[name=store]").val(alert.idStore);
                                //$(e.target).parent().parent().find("[name=store]").attr("disabled", true);
                                //$(e.target).parent().parent().find("[name=store]").css("background-color", "#eee");
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $(document).on("blur", "[name=lockerNumber]", function(e) {
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
                                $(e.target).parent().parent().find("[name=names]").text(customer.names);
                            }
                            
                            closePreload();
                        }
                    });
                }
            });
            
            $(document).on("click", "[name=btnDeleteRow]", function(e) {
                $(e.target).parent().parent().remove();
                rows --;
                $("#slRows").val(rows);
            });
            
            $(document).on("click", "[name=btnCloneRow]", function(e) {
                var lockerNumber = $(e.target).parent().parent().find("[name=lockerNumber]").val();
                var trackingNumber = $(e.target).parent().parent().find("[name=trackingNumber]").val();
                var customer = $(e.target).parent().parent().find("[name=names]").text();
                
                var stores = $("[name=hdStore]");
                var tr = $(document.createElement("tr"));
                $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                $(tr).append('<td style="white-space:nowrap"><input id="' + Math.random() * 9999 + '" type="date" data-required="true" data-name="Fecha" name="Date" class="input-text-underline" value="<?php echo $currentDate ?>" /></td>');
                $(tr).append('<td style="white-space:nowrap"><input id="' + Math.random() * 9999 + '" type="text" list="dlCustomers" data-required="true" data-name="Casillero" name="lockerNumber" style="width: 150px" class="input-text-underline" value="' + lockerNumber + '" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Tracking number" name="trackingNumber" style="width: 200px" class="input-text-underline" value="' + trackingNumber + '" /></td>');
                $(tr).append('<td style="white-space: nowrap"><div name="names">' + customer + '</div></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" data-required="true" data-name="Peso neto" name="weight" type="number" style="width: 100px" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" data-required="true" data-name="Cantidad" name="quantity" type="number" style="width: 100px" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><textarea id="' + Math.random() * 9999 + '" data-required="true" data-name="Contenido" name="content" style="width: 300px; height:50px; resize:none" class="input-text-underline"></textarea></td>');

                var tdStores = $(document.createElement("td"));
                var storesSelect = $(document.createElement("select"));
                storesSelect.attr("class", "select-underline");
                storesSelect.attr("style", "width: 200px");
                storesSelect.attr("name", "store");
                storesSelect.attr("id", Math.random() * 9999);
                storesSelect.attr("data-required", "0");
                storesSelect.attr("data-name", "Tienda");
                storesSelect.append("<option value='' selected>Tienda</option>");
                for (var j=0; j<stores.length; j++) {
                    var s = $(stores[j])[0];
                    storesSelect.append("<option value='" + $(s).data("id") + "'>" + $(s).data("name") + "</option>");
                }
                tdStores.append(storesSelect);
                $(tr).append(tdStores);

                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Largo" name="long" type="number" style="width: 100px" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Ancho" name="width" type="number" style="width: 100px" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Alto" name="high" type="number" style="width: 100px" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="file" style="width:220px" name="flPicture1" /><input type="file" style="width:220px" name="flPicture2" /><input type="file" style="width:220px" name="flPicture3" /></td>');

                $("#tblBody").append($(tr));
                
                rows++;
                $("#slRows").val(rows);
            });
            
            $("#slRows").on("change", function(e) {
                var numRows = $(e.target).val();
                var diff = numRows - rows;
                
                if (diff > 0) {
                    var stores = $("[name=hdStore]");

                    for (var i=0; i<diff; i++) {
                        var tr = $(document.createElement("tr"));

                        $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                        $(tr).append('<td style="white-space:nowrap"><input id="' + Math.random() * 9999 + '" type="text" list="dlCustomers" data-required="true" data-name="Casillero" name="lockerNumber" style="width: 150px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Tracking number" name="trackingNumber" style="width: 200px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><div name="names">Nombres</div></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" data-required="true" data-name="Peso neto" name="weight" type="number" style="width: 100px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" data-required="true" data-name="Cantidad" name="quantity" type="number" style="width: 100px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" data-required="true" data-name="Contenido" name="content" type="text" style="width: 300px" class="input-text-underline" /></td>');

                        var tdStores = $(document.createElement("td"));
                        var storesSelect = $(document.createElement("select"));
                        storesSelect.attr("class", "select-underline");
                        storesSelect.attr("style", "width: 200px");
                        storesSelect.attr("name", "store");
                        storesSelect.attr("id", Math.random() * 9999);
                        storesSelect.attr("data-required", "0");
                        storesSelect.attr("data-name", "Tienda");
                        storesSelect.append("<option value='' selected>Tienda</option>");
                        for (var j=0; j<stores.length; j++) {
                            var s = $(stores[j])[0];
                            storesSelect.append("<option value='" + $(s).data("id") + "'>" + $(s).data("name") + "</option>");
                        }
                        tdStores.append(storesSelect);
                        $(tr).append(tdStores);

                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Largo" name="long" type="number" style="width: 100px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Ancho" name="width" type="number" style="width: 100px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" value="0" data-required="true" data-name="Alto" name="high" type="number" style="width: 100px" class="input-text-underline" /></td>');
                        $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="file" style="width:220px" name="flPicture1" /><input type="file" style="width:220px" name="flPicture2" /><input type="file" style="width:220px" name="flPicture3" /></td>');

                        $("#tblBody").append($(tr));
                    }
                    rows = numRows;
                } else {
                    alert("No puede disminuir el número de registros, utilice el botón eliminar fila.");
                }
            });
        </script>
    </body>
</html>