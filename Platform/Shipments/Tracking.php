<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Consultar envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $shipping = ShippingDAO::getShippingById(CloudEngineHTTP::getPostVar("IdShipment"));
    
    if ($shipping == null) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Shipments");
    }
    
    $layout = new Layout();
    $layout->setTitle("Trazabilidad");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Trazabilidad", PUBLIC_PATH_PLATFORM . "Shipments"); ?>
            <div id="frmTracking" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- tabs -->
                <div class="display-table width-100 padding-bottom-3x text-align-right">
                    <div data-id="<?php echo $shipping->getIdShipping() ?>" id="btnTabPDF" class="cursor-pointer display-inline-block padding-2x">Ver PDF</div>
                    <div data-id="<?php echo $shipping->getIdShipping() ?>" id="btnTabEdit" class="cursor-pointer display-inline-block padding-2x">Editar</div>
                    <div data-id="<?php echo $shipping->getIdShipping() ?>" id="btnTabPay" class="cursor-pointer display-inline-block padding-2x">Pagos</div>
                    <div data-id="<?php echo $shipping->getIdShipping() ?>" class="profile-tab text-weight-bold display-inline-block padding-2x">Trazabilidad</div>
                    <div data-id="<?php echo $shipping->getIdShipping() ?>" id="btnTabAnnul" class="cursor-pointer display-inline-block padding-2x">Anular</div>
                </div>
                <input type="hidden" id="hdIdShipping" value="<?php echo $shipping->getIdShipping(); ?>" />
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
                            <div class="float-left width-100"><?php echo $shipping->getCreatedTimestamp(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Número</div>
                            <div class="float-left width-100"><?php echo $shipping->getShippingNumber(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Cliente</div>
                            <div class="float-left width-100"><?php echo $shipping->getPurchases()[0]->getCustomer()->getNames() ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Secuencia</div>
                            <div class="float-left width-100"><?php echo $shipping->getSequenceNumber(); ?></div>
                        </div>
                        <div class="float-left width-20 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Total</div>
                            <div class="float-left width-100"><?php echo $shipping->getTotal(); ?> USD</div>
                        </div>
                    </div>
                    <!-- Tracking info -->
                    <div class="display-table padding-bottom-3x width-100">
                        <div class="float-left width-50 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Fecha</div>
                            <input class="width-100 input-text-underline" id="slDate" type="date" data-name='Fecha' data-required='true' />
                        </div>
                        <div class="float-left width-50 padding-2x" >
                            <div class="float-left width-100 text-weight-bold margin-bottom-2x">Novedad</div>
                            <datalist id="dlOptions">
                                <?php
                                    $options = ShippingTrackingOptionDAO::getShippingTrackingOptions();
                                    foreach ($options as $o) {
                                        echo '<option>' . $o->getText() . '</option>';
                                    }
                                ?>
                            </datalist>
                            <input class="width-100 input-text-underline" id="txtText" list="dlOptions"  />
                        </div>
                    </div>
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
                                $tracking = $shipping->getTracking();
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
                var frmTracking = new Form($("#frmTracking"));
                if (frmTracking.validate()) {
                    $.ajax({
                        url: URL_API + "Shipment/Tracking.php",
                        type: "POST",
                        data: {
                            IdShipping: $("#hdIdShipping").val(),
                            Date: $("#slDate").val(),
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
                $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $("#btnTabEdit").on("click", function() {
                $.redirect("../Shipments/Edit.php", {IdShipment: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabPay").on("click", function() {
                $.redirect("../Shipments/Payment.php", {IdShipment: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabTracking").on("click", function() {
                $.redirect("../Shipments/Tracking.php", {IdShipment: $(this).data("id")}, "POST", "_self");
            });
            
            $("#btnTabAnnul").on("click", function() {
                if (confirm("¿Confirma anular la factura?")) {
                    $.ajax({
                        url: URL_API + "Shipment/Annull.php",
                        data: {
                            Id: $(this).data("id")
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            document.location.href = "../Shipments/";
                        }
                    });
                }
            });
        </script>
    </body>
</html>
