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
    $layout->setTitle("Trazabilidad");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Trazabilidad", PUBLIC_PATH_PLATFORM . "Bills/index.php?Search=" . CloudEngineHTTP::getPostVar("Search") . "&Page=" . CloudEngineHTTP::getPostVar("Page")); ?>
            <div id="frmTracking" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- tabs -->
                <div class="display-table width-100 padding-bottom-3x text-align-right">
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabPDF" class="cursor-pointer display-inline-block padding-2x">Ver PDF</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabEdit" class="cursor-pointer display-inline-block padding-2x">Editar</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabPayment" class="cursor-pointer display-inline-block padding-2x">Pagos</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" class="profile-tab text-weight-bold display-inline-block padding-2x">Trazabilidad</div>
                    <div data-id="<?php echo $bill->getIdBill() ?>" id="btnTabAnnul" class="cursor-pointer display-inline-block padding-2x">Anular</div>
                </div>
                <input type="hidden" id="hdIdBill" value="<?php echo $bill->getIdBill(); ?>" />
                <!-- Actions -->
                <div class="width-100 display-table text-align-right">
                    <button id="btnSave" class="button-red display-inline-block text-decoration-none">GUARDAR</button>
                </div>
                <!-- Form -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-4x">
                    <!-- Date, number, customer and sequence -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <div class="float-left width-100"><?php echo $bill->getCreatedTimestamp(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Número</div>
                            <div class="float-left width-100"><?php echo $bill->getBillNumber(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Remitente</div>
                            <div class="float-left width-100"><?php echo $bill->getFrom(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Destinatario</div>
                            <div class="float-left width-100"><?php echo $bill->getTo(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Total</div>
                            <div class="float-left width-100"><?php echo $bill->getTotal() . " " . $bill->getCurrency(); ?></div>
                        </div>
                    </div>
                    <!-- Tracking info -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <input class="width-100 input-text-underline" id="txtDate" type="date" data-name='Fecha' data-required='true' />
                        </div>
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Box No.</div>
                            <?php
                                $items = $bill->getItems();
                                $slBox = new CloudEngineHTMLSelect();
                                $slBox->addPropertie("class", "select-underline");
                                $slBox->addPropertie("id", "slBox");
                                $slBox->addPropertie("data-required", "true");
                                $slBox->addPropertie("data-name", "Box No.");
                                $slBox->addOption("Seleccione una opción", "");
                                foreach ($items as $i) {
                                    $slBox->addOption($i->getBoxNumber(), $i->getIdBillItem());
                                }
                                $slBox->render();
                            ?>
                        </div>
                        <div class="float-left width-1-3 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Novedad</div>
                            <datalist id="dlOptions">
                                <?php
                                    $options = ShippingTrackingOptionDAO::getShippingTrackingOptions();
                                    foreach ($options as $o) {
                                        echo '<option>' . $o->getText() . '</option>';
                                    }
                                ?>
                            </datalist>
                            <input class="width-100 input-text-underline" id="txtText" data-required="true" data-name="Novedad" list="dlOptions"  />
                        </div>
                    </div>
                </div>
                <!-- Tracking -->
                <div  class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x">
                    <table class="table width-100">
                        <thead>
                            <tr>
                                <th>Caja</th>
                                <th>Movimiento</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($items as $i) {
                                    $tracking = $i->getTracking();
                                    
                                    foreach ($tracking as $t) {
                                        $code = "<tr>";
                                        $code .= "<td>" . $i->getBoxNumber() . "</td>";
                                        $code .= "<td>" . $t->getDescription() . "</td>";
                                        $code .= "<td class='text-align-center'>" . substr($t->getCreatedTimestamp(),0,10) . "</td>";
                                        $code .= "<td class='text-align-center'>" . $t->getSystemUser()->getNames() . "</td>";
                                        $code .= "</tr>";
                                        echo $code;
                                    }
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
                var frmTracking = new Form($("#frmTracking"));
                if (frmTracking.validate()) {
                    $.ajax({
                        url: URL_API + "Bill/Tracking.php",
                        type: "POST",
                        data: {
                            IdBillItem: $("#slBox").val(),
                            Date: $("#txtDate").val(),
                            Text: $("#txtText").val()
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
