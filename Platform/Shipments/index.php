<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Consultar envíos")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Envíos");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Envíos", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 margin-bottom-4x" style="display: flex; justify-content: flex-end">
                    <button id="btnMasivePay" class="button-blue display-inline-block text-decoration-none">PAGAR MASIVO</button>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblShipments" class="stripe width-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Fecha</th>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Secuencia</th>
                                <th>Empresa</th>
                                <th>Total</th>
                                <th>Peso</th>
                                <th>Estado</th>
                                <th>Trazabilidad</th>
                                <th></th>   <!-- Tracking date -->
                                <th></th>   <!-- Save -->
                                <th>Entregado</th>   <!-- Delivered -->
                                <th></th>   <!-- Options -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            var contextMenu = null;
    
            $(document).ready( function () {
                $('#tblShipments').DataTable({
                    ajax: {
                        url: URL_API + 'Shipment/DataTables2.php'
                    },
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    pageLength: 25
                });
            });
            
            class ContextMenu {
                
                constructor(caller) {
                    this.caller = caller;
                    
                    if ($(this.caller).data("status") !== "ANULADA") {
                        this.options = [
                            {name : "btnView", text : "Ver"},
                            {name : "btnEdit", text : "Editar"},
                            {name : "btnPayment", text : "Registrar pago"},
                            {name : "btnTracking", text : "Trazabilidad"},
                            {name : "btnAnnull", text : "Anular"}
                        ];
                    } else {
                        this.options = [
                            {name : "btnView", text : "Ver"}
                        ];
                    }
                    
                    this.callerX = $(caller).offset().left;
                    this.callerY = $(caller).offset().top;
                    this.callerWidth = $(caller).width();
                    this.callerHeight = $(caller).height();
                    
                    this.divContainer = $(document.createElement("div"));
                    this.divContainer.addClass("context-menu");
                    
                    for (var i=0; i<this.options.length; i++) {
                        var option = this.options[i];
                        
                        var item = $(document.createElement("div"));
                        item.attr("name", option.name);
                        item.data("id", $(caller).attr("id"));
                        item.html(option.text);
                        
                        $(this.divContainer).append(item);
                    }
                    
                    $(document.body).append(this.divContainer);
                    
                    this.containerHeight = this.divContainer.height();
                    this.containerWidth = this.divContainer.width();
                    
                    this.divContainer.css("left", (this.callerX - this.containerWidth) + "px");
                    this.divContainer.css("top", (this.callerY + this.callerHeight) + "px");
                }
                
                dismiss() {
                    this.divContainer.remove();
                    contextMenu = null;
                }
                
            }
            
            $(document).on("click", function(e) {
                if (contextMenu !== null) {
                    contextMenu.dismiss();
                }
            });
            
            $(document).on("click", "[name=btnContextMenu]", function(e) {
                e.stopPropagation();
                if (contextMenu !== null) { contextMenu.dismiss(); }
                contextMenu = new ContextMenu(this);
            });
            
            $(document).on("click", "[name=btnPayment]", function(e) {
                $.redirect("Payment.php", {IdShipment: $(this).data("id")}, "POST");
            });
            
            $(document).on("click", "[name=btnView]", function(e) {
                $.redirect(URL_API + "PDF/Shipment.php", {IdShipment: $(this).data("id")}, "POST", "_blank");
            });
            
            $(document).on("click", "[name=btnTracking]", function(e) {
                $.redirect("Tracking.php", {IdShipment: $(this).data("id")}, "POST");
            });
            
            $(document).on("click", "[name=btnEdit]", function(e) {
                $.redirect("Edit.php", {IdShipment: $(this).data("id")}, "POST");
            });
            
            $(document).on("click", "[name=btnSaveTracking]", function(e) {
                var id = $(this).data("id");
                if (confirm("¿Confirma guardar la trazabilidad?")) {
                    $.ajax({
                        url: URL_API + "Shipment/Tracking.php",
                        type: "POST",
                        data: {
                            IdShipping: id,
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
                        url: URL_API + "Shipment/Deliver.php",
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
            
            $(document).on("click", "[name=btnAnnull]", function(e) {
                if (confirm("¿Confirma anular el registro?")) {
                    $.ajax({
                        url: URL_API + "Shipment/Annull.php",
                        data: {
                            Id: $(this).data("id")
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
            
            $("#btnMasivePay").on("click", function() {
                var checks = $("[name=chkPay]");
                var ids = new Array();
                for (var i=0; i<checks.length; i++) {
                    if ($(checks[i]).prop("checked")) {
                        ids.push($(checks[i]).data("id"));
                    }
                }
                
                if (ids.length > 0) {
                    $.redirect("/Platform/Shipments/MasivePayment.php", {Ids: JSON.stringify(ids)});
                } else {
                    alert("Debe seleccionar un o más registros")
                }
            });
        </script>
    </body>
</html>