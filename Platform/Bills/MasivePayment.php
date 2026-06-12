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
    
    $ids = json_decode(CloudEngineHTTP::getPostVar("Ids"));
    
    if (count($ids) == 0) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Bills");
    }
    
    $bills = array();
    $totalUSD = 0;
    $totalCOP = 0;
    
    foreach ($ids as $id) {
        array_push($bills, BillDAO::getBillById($id));
    }
    
    foreach ($bills as $b) {
        if ($b->getCurrency() == "USD") {
            $totalUSD += $b->getPendingPayment();
        } else {
            $totalCOP += $b->getPendingPayment();
        }
    }
    
    $layout = new Layout();
    $layout->setTitle("Pago masivo");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Pago masivo", PUBLIC_PATH_PLATFORM . "Bills/index.php") ; ?>
            <div id="frmPayment" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table text-align-right">
                    <button id="btnElectronicPayment" class="button-blue display-inline-block text-decoration-none margin-right-2x">PAGO ELECTRÓNICO</button>
                    <button id="btnSave" class="button-red display-inline-block text-decoration-none">GUARDAR</button>
                </div>
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-4x">
                    <table id="tblItems" class="table">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Remitente</th>
                                <th>Pendiente</th>
                                <th>Pagar</th>
                                <th>Fecha</th>
                                <th>Método</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $paymentMethods = PaymentMethodDAO::getPaymentMethods();
                            
                                foreach ($bills as $b) {
                                    $code = "<tr data-id='" . $b->getIdBill() . "'>";
                                    $code .= "<td class='text-align-center'>" . $b->getBillNumber() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $b->getFrom() . "</td>";
                                    $code .= "<td class='text-align-center'>$ " . number_format($b->getPendingPayment(),2) . " " . $b->getCurrency() . "</td>";
                                    $code .= "<td><input id='" . rand(0, 999) . "' data-required='true' data-name='Pagar' name='amount' class='input-text-underline' type='number' value='" . $b->getPendingPayment() . "' /></td>";
                                    $code .= "<td><input id='" . rand(0, 999) . "' data-required='true' data-name='Fecha' name='date' class='input-text-underline' type='date' value='" . date("Y-m-d") . "' /></td>";
                                    $code .= "<td><select id='" . rand(0, 999) . "' data-required='true' data-name='Método de pago' name='method' class='input-text-underline'>";
                                    $code .= "<option value=''>Seleccione un método</option>";
                                    
                                    foreach ($paymentMethods as $m) {
                                        $code .= "<option value='" . $m->getIdPaymentMethod() . "'>" . $m->getName() . "</option>";
                                    }
                                    
                                    $code .= "</select></td>";
                                    $code .= "</tr>";
                                    echo $code;
                                }
                                
                                $code = "";
                                $code .= "<tr>";
                                $code .= "<td></td>";
                                $code .= "<td class='text-align-center text-weight-bold text-size-xs'>TOTAL</td>";
                                $code .= "<td class='text-align-center text-weight-bold'>" . number_format($totalUSD,2) . " USD</td>";
                                $code .= "<td></td>";
                                $code .= "<td></td>";
                                $code .= "<td></td>";
                                $code .= "</tr>";
                                echo $code;
                                
                                $code = "";
                                $code .= "<tr>";
                                $code .= "<td></td>";
                                $code .= "<td class='text-align-center text-weight-bold text-size-xs'>TOTAL</td>";
                                $code .= "<td class='text-align-center text-weight-bold'>" . number_format($totalCOP,2) . " COP</td>";
                                $code .= "<td></td>";
                                $code .= "<td></td>";
                                $code .= "<td></td>";
                                $code .= "</tr>";
                                echo $code;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnElectronicPayment").on("click", function(e) {
                var entities = new Array();
                
                var rows = $("#tblItems").find("tbody").find("tr");
                
                for (var i=0; i<rows.length; i++) {
                    var r = $(rows[i]);
                    entities.push(new Entity(r.data("id"), "BILL"));
                }

                $.redirect("/Platform/ElectronicPayment/", { Entities : JSON.stringify(entities) });
            });
            
            function send(index, items) {
                $.ajax({
                    url: URL_API + "Bill/PartialPayment.php",
                    type: "POST",
                    data: {
                        Date: items[index].date,
                        Amount: items[index].amount,
                        IdPaymentMethod: items[index].method,
                        IdBill: items[index].id
                    },
                    success: function(response) {
                        if (index < items.length-1) {
                            index ++;
                            send(index, items);
                        } else {
                            document.location.href = "/Platform/Bills/index.php";
                        }
                    }
                });
            }
            
            $("#btnSave").on("click", function(e) {
                var frmPayment = new Form($("#frmPayment"));
                if (frmPayment.validate()) {
                    var items = new Array();
                    var rows = $("#tblItems").find("tbody").find("tr");
                    
                    for (var i=0; i<rows.length; i++) {
                        var r = $(rows[i]);
                        items.push({
                            id: r.data("id"),
                            amount: r.find("[name=amount]").val(),
                            date: r.find("[name=date]").val(),
                            method: r.find("[name=method]").val()
                        });
                    }
                    
                    showPreload();
                    send(0, items);
                }
            });
        </script>
    </body>
</html>
