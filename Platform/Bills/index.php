<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Facturas manuales")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Facturas manuales");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Facturas manuales", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 margin-bottom-4x" style="display: flex; justify-content: space-between">
                    <div style="display: flex; align-items: center">
                        <div class="text-weight-bold margin-right-3x">Filtros:</div>
                        <select id="slCompany" class="float-left select-underline margin-right-3x" style="width: 200px">
                            <option value="0">Todas las empresas</option>
                            <?php
                                $companies = ShipmentCompanyDAO::getShipmentCompanies();
                                foreach ($companies as $c) {
                                    echo '<option value="' . $c->getIdShipmentCompany() . '">' . $c->getName() . '</option>';
                                }
                            ?>
                        </select>
                        <select id="slCountry" class="float-left select-underline margin-right-3x" style="width: 200px">
                            <option value="0">Todos los países</option>
                            <?php
                                $countries = CountryDAO::getCountries();
                                foreach ($countries as $c) {
                                    echo '<option value="' . $c->getIdCountry() . '">' . $c->getName() . '</option>';
                                }
                            ?>
                        </select>
                        <select id="slOrder" class="float-left select-underline" style="width: 200px">
                            <option value="DATE">Por fecha</option>
                            <option value="BOX">Por box No.</option>
                        </select>
                    </div>
                    <div>
                        <button id="btnMasivePay" class="button-blue display-inline-block text-decoration-none">PAGAR MASIVO</button>
                        <a href="Create.php" class="button-red display-inline-block text-decoration-none">CREAR</a>
                    </div>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table id="tblBills" class="stripe width-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Fecha</th>
                                <th>Número</th>
                                <th>Casillero</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Total</th>
                                <th>Box #</th>
                                <th>Empresa</th>
                                <th>Pagado</th>
                                <th></th>
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
                var url = new URL(window.location.href);
                var search = url.searchParams.get("Search");
                var page = url.searchParams.get("Page");
                
                $('#tblBills').DataTable({
                    ajax: { 
                        url: URL_API + 'Bill/DataTables.php',
                        data: function (d) {
                            d.company = $("#slCompany").val(),
                            d.order = $("#slOrder").val(),
                            d.country = $("#slCountry").val()
                        }
                    },
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    pageLength: 25,
                    initComplete: function () {                    
                        if (page !== null && page !== "") {
                            this.api().page(parseInt(page - 1)).draw( 'page' );
                        }
                    }
                });
                
                if (search !== null && search !== "") {
                    $('#tblBills').DataTable().search(search);
                    $('#tblBills').DataTable().ajax.reload();
                }
            });
            
            class ContextMenu {
                
                constructor(caller) {
                    this.caller = caller;
                    
                    if ($(this.caller).data("status") !== "ANULADA") {
                        this.options = [
                            {name : "btnView", text : "Ver"},
                            {name : "btnEdit", text : "Editar"},
                            {name : "btnPay", text : "Registrar pago"},
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
            
            $("#slCompany").on("change", function() {
                $('#tblBills').DataTable().ajax.reload();
            });
            
            $("#slCountry").on("change", function() {
                $('#tblBills').DataTable().ajax.reload();
            });
            
            $("#slOrder").on("change", function() {
                $('#tblBills').DataTable().ajax.reload();
            });
            
            $(document).on("click", "[name=btnView]", function() {
                $.redirect(URL_API + "PDF/Bill.php", {IdBill: $(this).data("id")}, "POST", "_blank");
            });
            
            $(document).on("click", "[name=btnEdit]", function() {
                $.redirect("Edit.php", {IdBill: $(this).data("id"), Search: $('#tblBills').DataTable().search(), Page: parseInt($('#tblBills').DataTable().page() + 1)});
            });
            
            $(document).on("click", "[name=btnPay]", function() {
                $.redirect("Pay.php", {IdBill: $(this).data("id"), Search: $('#tblBills').DataTable().search(), Page: parseInt($('#tblBills').DataTable().page() + 1)}, "POST");
            });
            
            $(document).on("click", "[name=btnTracking]", function() {
                $.redirect("Tracking.php", {IdBill: $(this).data("id"), Search: $('#tblBills').DataTable().search(), Page: parseInt($('#tblBills').DataTable().page() + 1)}, "POST");
            });
            
            $(document).on("click", "[name=btnAnnull]", function() {
                if (confirm("¿Confirma anular el registro?")) {
                    $.ajax({
                        url: URL_API + "Bill/Annull.php",
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
            
            $(document).on("click", "[name=btnShowBoxes]", function() {
                var parent = $(this).parent();
                var m = new MessageBox(parent.text());
                m.show();
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
                    $.redirect("/Platform/Bills/MasivePayment.php", {Ids: JSON.stringify(ids)});
                } else {
                    alert("Debe seleccionar un o más registros")
                }
            });
        </script>
    </body>
</html>