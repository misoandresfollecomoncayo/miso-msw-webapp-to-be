<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Ecuador")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $ecuador = EcuadorDAO::getById(CloudEngineHTTP::getPostVar("Id"));
    
    if ($ecuador == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Ecuador");
    }
    
    $layout = new Layout();
    $layout->setTitle("Ver lista");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Ver lista", PUBLIC_PATH_PLATFORM . "Ecuador/"); ?>
            <div id="frmEcuador" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" id="hdId" value="<?php echo $ecuador->getIdEcuador() ?>" />
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Bill -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Factura</div>
                        <div class="float-left width-75">
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                    echo '<input autofocus="on" class="input-text-underline" data-required="true" data-name="Factura" id="txtBill" value="' . $ecuador->getBillNumber() . '" />';
                                } else {
                                    echo $ecuador->getBillNumber();
                                }
                            ?>
                        </div>
                    </div>
                    <!-- Customer -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Cliente</div>
                        <div class="float-left width-75">
                            <?php
                                if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                    echo '<input class="input-text-underline" data-required="true" data-name="Cliente" id="txtCustomer"value="' . $ecuador->getCustomerNames() . '" />';
                                } else {
                                    echo $ecuador->getCustomerNames();
                                }
                            ?>
                        </div>
                    </div>
                    <!-- Quantity -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Cantidad</div>
                        <div class="float-left width-75"><?php echo $ecuador->getCompletedQuantity() . " / " . $ecuador->getQuantity() ?></div>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tblBody">
                            <?php
                                $items = $ecuador->getItems();
                                $index = 0;
                                
                                foreach ($items as $i) {
                                    $code = '<tr>';
                                    
                                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                        if ($index > 0 && $i->getStatus() == EcuadorItem::STATUS_PENDING) {
                                            $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i><i name="btnDeleteRow" class="fa fa-trash button-gray"></i></td>';
                                        } else {
                                            $code .= '<td class=""><i name="btnCloneRow" class="fa fa-chevron-down button-gray margin-right"></i></td>';
                                        }

                                        if ($i->getStatus() == EcuadorItem::STATUS_PENDING) {
                                            $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Cantidad" name="quantity" class="input-text-underline" value="' . $i->getQuantity() . '" /></td>';
                                            $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Secuencia" name="sequence" class="input-text-underline" value="' . $i->getSequence() . '" /></td>';
                                            $code .= '<td style="white-space: nowrap"><input id="' . rand(0, 9999) . '" type="text" data-required="true" data-name="Descripción" name="description" class="input-text-underline" value="' . $i->getDescription() . '" /></td>';
                                        } else {
                                            $code .= '<td style="white-space: nowrap">' . $i->getQuantity() . '</td>';
                                            $code .= '<td style="white-space: nowrap">' . $i->getSequence() . '</td>';
                                            $code .= '<td style="white-space: nowrap">' . $i->getDescription() . '</td>';
                                        }
                                    } else {
                                        $code .= '<td></td>';   // Options
                                        $code .= '<td>' . $i->getQuantity() . '</td>'; // Quantity
                                        $code .= '<td>' . $i->getSequence() . '</td>'; // Sequence
                                        $code .= '<td>' . $i->getDescription() . '</td>'; // Description
                                    }
                                    
                                    if ($i->getStatus() == EcuadorItem::STATUS_PENDING) {
                                        $code .= "<td name='btnProcess' data-id='" . $i->getId() . "' class='text-align-center text-decoration-underline cursor-pointer'>Procesar</td>";
                                    } else {
                                        if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                            $code .= "<td name='btnReverse' data-id='" . $i->getId() . "' class='text-align-center text-decoration-underline cursor-pointer'>Reversar</td>";
                                        } else {
                                            $code .= '<td></td>';
                                        }
                                    }
                                    
                                    $code .= '</tr>';
                                    echo $code;
                                    $index ++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                        echo '<!-- Actions --><div class="width-100 margin-top-4x text-align-right"><button id="btnSave" class="button-red">GUARDAR</button></div>';
                    }
                ?>
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
                $(tr).append('<td></td>');
                $("#tblBody").append($(tr));
            });
            
            $(document).on("click", "[name=btnDeleteRow]", function(e) {
                $(e.target).parent().parent().remove();
                calculateTotal();
            });
            
            $(document).on("click", "[name=btnProcess]", function(e) {
                var id = $(this).data("id");
                $.ajax({
                    url: URL_API + "Ecuador/Process.php",
                    type: "POST",
                    data: {
                        Id: id
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function(response) {
                        document.location.reload();
                    }
                });
            });
            
            $(document).on("click", "[name=btnReverse]", function(e) {
                var id = $(this).data("id");
                $.ajax({
                    url: URL_API + "Ecuador/Reverse.php",
                    type: "POST",
                    data: {
                        Id: id
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function(response) {
                        document.location.reload();
                    }
                });
            });
            
            $("#btnSave").on("click", function(e) {
                var frmEcuador = new Form($("#frmEcuador"));
                if (frmEcuador.validate()) {
                    var items = new Array();
                    
                    var rows = $("#tblItems").find("tbody").find("tr");
                    
                    for (var i=0; i<rows.length; i++) {
                        if ($(rows[i]).find("[name=quantity]").length > 0) {
                            items.push(new Item($(rows[i]).find("[name=quantity]").val(),$(rows[i]).find("[name=sequence]").val(),$(rows[i]).find("[name=description]").val()));
                        }
                    }
                    
                    $.ajax({
                        url: URL_API + "Ecuador/Edit.php",
                        type: "POST",
                        data: {
                            Id: $("#hdId").val(),
                            Bill: $("#txtBill").val(),
                            Customer: $("#txtCustomer").val(),
                            Items: JSON.stringify(items)
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            document.location.reload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
