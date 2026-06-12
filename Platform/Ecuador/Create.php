<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject()
            || !CloudEngineSession::getSessionObject()->hasPermission("Ecuador")
            || CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() != "Administrador") {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Crear registro");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear registro", PUBLIC_PATH_PLATFORM . "Ecuador"); ?>
            <div id="frmEcuador" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Bill -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Factura</div>
                        <div class="float-left width-75"><input autofocus="on" class="input-text-underline" data-required="true" data-name="Factura" id="txtBill" /></div>
                    </div>
                    <!-- Customer -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Cliente</div>
                        <div class="float-left width-75"><input class="input-text-underline" data-required="true" data-name="Cliente" id="txtCustomer" /></div>
                    </div>
                </div>
                <!-- Items -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table id="tblItems" class="table stripe">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Cantidad</th>
                                <th>Secuencia</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            <?php
                                $code = '<tr>';
                                $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Cantidad" name="quantity" class="input-text-underline" /></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Secuencia" name="sequence" class="input-text-underline" /></td>';
                                $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>';
                                $code .= '</tr>';
                                echo $code;
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnSave" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            function Item(quantity,sequence,description) {
                this.quantity = quantity;
                this.sequence = sequence;
                this.description = description;
            }
            
            $(document).on("click", "[name=btnCloneRow]", function() {
                var tr = $(document.createElement("tr"));
                $(tr).append('<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Cantidad" name="quantity" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Secuencia" name="sequence" class="input-text-underline" /></td>');
                $(tr).append('<td style="white-space: nowrap"><input id="' + Math.random() * 9999 + '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" /></td>');
                $("#tblBody").append($(tr));
            });
            
            $(document).on("click", "[name=btnDeleteRow]", function(e) {
                $(e.target).parent().parent().remove();
                calculateTotal();
            });
            
            $("#btnSave").on("click", function(e) {
                var frmEcuador = new Form($("#frmEcuador"));
                if (frmEcuador.validate()) {
                    var items = new Array();
                    
                    var rows = $("#tblItems").find("tbody").find("tr");
                    
                    for (var i=0; i<rows.length; i++) {
                        items.push(new Item($(rows[i]).find("[name=quantity]").val(),$(rows[i]).find("[name=sequence]").val(),$(rows[i]).find("[name=description]").val()));
                    }
                    
                    $.ajax({
                        url: URL_API + "Ecuador/Create.php",
                        type: "POST",
                        data: {
                            Bill: $("#txtBill").val(),
                            Customer: $("#txtCustomer").val(),
                            Items: JSON.stringify(items)
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            new Notification("SUCCESS", r.body);
                            //frmEcuador.reset();
                            closePreload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
