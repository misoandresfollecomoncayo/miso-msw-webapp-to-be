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
    $layout->setTitle("Registrar pago");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Registrar pago", PUBLIC_PATH_PLATFORM . "Bills/index.php?Search=" . CloudEngineHTTP::getPostVar("Search") . "&Page=" . CloudEngineHTTP::getPostVar("Page")); ?>
            <div id="frmPayment" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- tabs -->
                <div class="display-table width-100 margin-bottom-4x text-align-right">
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabPDF" class="cursor-pointer display-inline-block padding-2x">Ver PDF</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabEdit" class="cursor-pointer display-inline-block padding-2x">Editar</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" class="profile-tab text-weight-bold display-inline-block padding-2x">Pagos</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabTracking" class="cursor-pointer display-inline-block padding-2x">Trazabilidad</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabAnnul" class="cursor-pointer display-inline-block padding-2x">Anular</div>
                </div>
                <input type="hidden" id="hdIdBill" value="<?php echo $bill->getIdBill(); ?>" />
                <!-- Actions -->
                <div class="width-100 display-table text-align-right">
                    <?php
                        if ($bill->getPaymentStatus() != "PAGADA") {
                            echo '<button id="btnElectronicPayment" class="button-blue display-inline-block text-decoration-none margin-right-2x">PAGAR AHORA</button>';
                        }
                    ?>
                    <button id="btnSave" class="button-red display-inline-block text-decoration-none">GUARDAR</button>
                </div>
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-4x">
                    <!-- Date, number, customer and sequence -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <div class="float-left width-100"><?php echo $bill->getCreatedTimestamp() ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Número</div>
                            <div class="float-left width-100"><?php echo $bill->getBillNumber() ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Remitente</div>
                            <div class="float-left width-100"><?php echo $bill->getFrom() ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Destinatario</div>
                            <div class="float-left width-100"><?php echo $bill->getTo() ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Total</div>
                            <div class="float-left width-100"><?php echo $bill->getTotal() . " " . $bill->getCurrency() ?></div>
                        </div>
                    </div>
                    <!-- Date, amount and payment method -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <input class="input-text-underline width-100" type="date" data-name="Fecha" data-required="true" id="txtDate" />
                        </div>
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Monto</div>
                            <input class="input-text-underline width-100" type="number" data-name="Monto" data-required="true" id="txtAmount" />
                        </div>
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Método de pago</div>
                            <?php
                                $methods = PaymentMethodDAO::getPaymentMethods();
                                $slPaymentMethod = new CloudEngineHTMLSelect();
                                $slPaymentMethod->addPropertie("class", "select-underline");
                                $slPaymentMethod->addPropertie("id", "slPaymentMethod");
                                $slPaymentMethod->addPropertie("data-required", "true");
                                $slPaymentMethod->addPropertie("data-name", "Método de pago");
                                $slPaymentMethod->addOption("Seleccione una opción", "");
                                foreach ($methods as $m) {
                                    $slPaymentMethod->addOption($m->getName(), $m->getIdPaymentMethod());
                                }
                                $slPaymentMethod->render();
                            ?>
                        </div>
                    </div>
                </div>
                <!-- History -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-4x">
                    <table id="tblHistory" class="stripe width-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Método de pago</th>
                                <th>Usuario registró</th>
                                <th>Fecha registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $payments = $bill->getPartialPayments();
                                
                                foreach ($payments as $p) {
                                    $code = "";
                                    $code .= "<tr>";
                                    $code .= "<td class='text-align-center'>" . $p->getDate() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getAmount() . "</td>";
                                    $code .= "<td class='text-align-center'>" . ($p->getPaymentMethod() != null ? $p->getPaymentMethod()->getName() : "") . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getUser()->getNames() . "</td>";
                                    $code .= "<td class='text-align-center'>" . $p->getCreatedTimestamp() . "</td>";
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
            $(document).ready( function () {
                $('#tblHistory').DataTable({
                    ordering: false,
                    pageLength: 25
                });
            });
            
            $("#btnElectronicPayment").on("click", function(e) {
                var entities = new Array();
                entities.push(new Entity($("#hdIdBill").val(), "BILL"));
                $.redirect("/Platform/ElectronicPayment/", { Entities : JSON.stringify(entities) });
            });
            
            $("#btnSave").on("click", function(e) {
                var frmPayment = new Form($("#frmPayment"));
                if (frmPayment.validate()) {
                    $.ajax({
                        url: URL_API + "Bill/PartialPayment.php",
                        type: "POST",
                        data: {
                            Date: $("#txtDate").val(),
                            Amount: $("#txtAmount").val(),
                            IdPaymentMethod: $("#slPaymentMethod").val(),
                            IdBill: $("#hdIdBill").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                                closePreload();
                            } else {
                                document.location.reload();
                            }
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
        </script>
    </body>
</html>
