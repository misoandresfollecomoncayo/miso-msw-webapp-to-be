<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject()
            || (!CloudEngineSession::getSessionObject()->hasPermission("Ventas Clubick")
            && !CloudEngineSession::getSessionObject()->hasPermission("Ventas Uniexpress"))) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject();

    $layout = new Layout();
    $layout->setTitle("Ventas " . $_REQUEST["Company"]);
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" style="opacity: 0" v-show='cargado' class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Ventas "  . $_REQUEST["Company"], PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <div style="background-color: white; padding: 10px; display: flex; justify-content: space-between; margin-bottom: 10px; align-items: flex-end; border-radius: 5px">
                    <div style="display: flex; gap: 10px">
                        <div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                            <div style="font-weight: bold; font-size: 20px; text-align: center">{{products}}</div>
                            <div style="text-align: center">Productos</div>
                        </div>
                        <?php
                            if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                echo ('<div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                                    <div style="font-weight: bold; font-size: 20px; text-align: center">$ {{formatCurrency(utilities)}}</div>
                                    <div style="text-align: center">Utilidad</div>
                                </div>
                                <div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                                    <div style="font-weight: bold; font-size: 20px; text-align: center">$ {{formatCurrency(paid)}}</div>
                                    <div style="text-align: center">Pagado</div>
                                </div>
                                <div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                                    <div style="font-weight: bold; font-size: 20px; text-align: center">$ {{formatCurrency(pending)}}</div>
                                    <div style="text-align: center">Pendiente</div>
                                </div>');
                            }
                        ?>
                    </div>
                    <button v-on:click='generateReport' class="button-blue">Generar reporte</button>
                </div>
                
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <div style="display: flex; gap: 5px; align-items: center">
                        <div style="font-weight: bold">Buscador:</div>
                        <div>
                            <input v-model='search' v-on:keyup.enter="loadItems" placeholder='Buscador' type="search" style='width: 300px; border: 1px solid rgba(0,0,0,.1); border-radius: 5px; padding: 7px' />
                            <div v-show='search !== ""'>{{getItems().length}} resultado(s)</div>
                        </div>
                    </div>
                    <table class="stripe width-100 dataTable">
                        <thead>
                            <tr>
                                <th>Fecha venta</th>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                        echo ('<th>TRM</th>
                                        <th>Precio USD</th>
                                        <th>Precio COP</th>
                                        <th>Precio envío internacional</th>
                                        <th>Precio envío nacional</th>
                                        <th>Costo total</th>');
                                    }
                                ?>
                                <th>Precio de venta</th>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                        echo ('<th>Utilidad</th>');
                                    }
                                ?>
                                <th>Pagado</th>
                                <th>Pendiente</th>
                                <th>Estado</th>
                                <th>Trazabilidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :class='index % 2 === 0 ? "odd": "even"' v-for='(i, index) in getItems()'>
                                <td style="white-space: nowrap; text-align: center">{{i.invoiceCreatedTimestamp.substring(0,10)}}</td>
                                <td style="text-align: center">{{i.invoiceNumber}}</td>
                                <td>{{i.customer}}</td>
                                <td>{{i.product}}</td>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                        echo ('<td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.trm)}}</td>
                                        <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.usdPrice)}}</td>
                                        <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.copPrice)}}</td>
                                        <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.internationalShippingPrice)}}</td>
                                        <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.nationalShippingPrice)}}</td>
                                        <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.totalCost)}}</td>');
                                    }
                                ?>
                                <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.salePrice)}}</td>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                        echo ('<td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.utility)}}</td>');
                                    }
                                ?>
                                <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.paid)}}</td>
                                <td style="white-space: nowrap; text-align: center">$ {{formatCurrency(i.pending)}}</td>
                                <td style="text-align: center">
                                    <span :style="'background-color: ' + getStatusColor(i)" style="color: white; padding: 2px; border-radius: 5px; font-size: 11px; text-align: center; font-weight: bold; white-space: nowrap">{{i.status}}</span>
                                </td>
                                <td>{{i.lastTracking}}</td>
                                <td>
                                    <button v-on:click='openRowActions(event,i)'><i class='fa fa-ellipsis-h'></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="display: flex; gap: 5px; align-items: center; margin-top: 20px; flex-wrap:wrap">
                        <div style="font-weight: bold">Página:</div>
                        <div :style="'background-color:' + (currentPage === p ? '#4caf50' : '#f3f3f3') + ';color:' + (currentPage === p ? 'white' : 'black') + ';font-weight: ' + (currentPage === p ? 'bold' : 'normal')" v-on:click='currentPage = p' style="min-width: 25px; cursor: pointer; padding: 5px; border-radius: 5px; text-align: center" v-for="p in getPagesNumber()">{{ p }}</div>
                    </div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            let app = new Vue({
                el: "#root",
                data: {
                    products: 0,
                    utilities: 0,
                    paid: 0,
                    pending: 0,
                    search: "",
                    items: [],
                    pages: 1,
                    currentPage: 1,
                    cargado: false,
                    sellingCompany: "<?php echo $_REQUEST["Company"]; ?>"
                },
                mounted() {
                    this.loadItems();
                },
                watch: {
                    currentPage(n) {
                        this.loadItems();
                    }
                },
                methods: {
                    loadItems() {
                        showPreload();
                        let fd = new FormData();
                        fd.append("Company", "<?php echo $_REQUEST["Company"]; ?>");
                        fd.append("Page", this.currentPage);
                        fd.append("Search", this.search);
                        this.$http.post(URL_API + "InventoryInvoice/GetByCompany.php", fd).then(function(response){
                            this.products = response.body.body.products;
                            this.utilities = response.body.body.utilities;
                            this.paid = response.body.body.paid;
                            this.pending = response.body.body.pending;
                            this.items = response.body.body.items;
                            this.pages = response.body.body.pages;
                            closePreload();
                            this.cargado = true;
                            this.$el.style.opacity = 1;
                        });
                    },
                    getItems() {
                        return this.items;
                    },
                    getStatusColor(item) {
                        let result = "";
                
                        if (item.status === "PAGADA") {
                            result = "#4caf50";
                        } else if (item.status === "PAGOS PARCIALES") {
                            result = "#ff9800";
                        } else {
                            result = "#f44336";
                        }
                
                        return result;
                    },
                    formatCurrency(value) {
                        if (value !== undefined) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    printInvoice(item) {
                        $.redirect(URL_API + "PDF/SaleInvoice.php", {Id: item.invoiceId}, "POST", "_blank");
                    },
                    openRowActions(e, item) {
                        e.stopPropagation();
                
                        let self = this;
                
                        let options = item.status !== "ANULADA" ? [
                            <?php
                                if (CloudEngineSession::getSessionObject()->getEmail() == "santiago@uniexpresssolutions.com"
                                        || CloudEngineSession::getSessionObject()->getEmail() == "maria@uniexpresssolutions.com"
                                        || CloudEngineSession::getSessionObject()->getEmail() == "andresfollecomoncayo@gmail.com"
					|| CloudEngineSession::getSessionObject()->getEmail() == "Fauo6417@gmail.com") {
                                    echo (
                                    '{
                                        text : "Anular",
                                        fn: function() {
                                            if (confirm("¿Confirma anular la venta?")) {
                                                showPreload();
                                                let fd = new FormData();
                                                fd.append("InvoiceId", item.invoiceId);
                                                self.$http.post(URL_API + "InventoryInvoice/CancelAnInvoice.php", fd).then(function(response){
                                                    document.location.reload();
                                                });
                                            }
                                        }
                                    },
                                    {
                                        text : "Editar",
                                        fn: function() {
                                            $.redirect(URL_PLATFORM + "Inventory/Edit.php", {Id: item.itemId}, "POST", "_blank");
                                        }
                                    },');
                                }
                                if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Vendedor"
                                        || CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                                    echo (
                                    '{
                                        text : "Trazabilidad",
                                        fn: function() {
                                            let addTrackingPopup = new Popup(URL_PLATFORM + "InventorySales/Tracking.php?Id=" + item.invoiceId);
                                            addTrackingPopup.show();
                                        }
                                    },
                                    {
                                        text : "Pagos",
                                        fn: function() {
                                            let addTrackingPopup = new Popup(URL_PLATFORM + "InventorySales/Pay.php?Id=" + item.invoiceId);
                                            addTrackingPopup.show();
                                        }
                                    },');
                                }
                            ?>
                            {
                                text : "Ver PDF",
                                fn: function() {
                                    self.printInvoice(item);
                                }
                            }
                        ] : [{
                            text : "Trazabilidad",
                            fn: function() {
                                let addTrackingPopup = new Popup(URL_PLATFORM + "InventorySales/Tracking.php?Id=" + item.invoiceId);
                                addTrackingPopup.show();
                            }
                        }];

                        new NewContextMenu(e, options);
                    },
                    generateReport() {
                        let reportPopup = new Popup(URL_PLATFORM + "InventorySales/PopupGenerateReport.php?SellingCompany=" + this.sellingCompany);
                        reportPopup.show();
                    },
                    getPagesNumber() {
                        return this.pages;
                    }
                },
                computed: {
                    
                }
            });
        </script>
    </body>
</html>
