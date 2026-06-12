<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Inventario")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject();

    $layout = new Layout();
    $layout->setTitle("Inventario");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" v-show='cargado' style="opacity: 0" class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php
                if (CloudEngineSession::getSessionObject()->getEmail() == "santiago@uniexpresssolutions.com"
                        || CloudEngineSession::getSessionObject()->getEmail() == "maria@uniexpresssolutions.com"
			|| CloudEngineSession::getSessionObject()->getEmail() == "Fauo6417@gmail.com") {
                    echo('<a href="Create.php" style="width: 45px; height: 45px; background-color: #4caf50; border-radius: 100%; position: absolute; z-index: 1000; left: calc(100% - 100px); top: calc(100% - 100px); box-shadow: 0 2px 2px 0 rgb(0 0 0 / 25%); display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none"><i style="color: white;" class="fa fa-plus"></i></a>');
                }
            ?>
            <?php $layout->printSessionBar("Inventario", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <div style="background-color: white; padding: 10px; display: flex; justify-content: space-between; margin-bottom: 10px; align-items: flex-end; border-radius: 5px">
                    <div style="display: flex; gap: 10px">
                        <div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                            <div style="font-weight: bold; font-size: 20px; text-align: center">{{items.length}}</div>
                            <div style="text-align: center">Productos</div>
                        </div>
                        <?php
                            if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                                echo ('<div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                                    <div style="font-weight: bold; font-size: 20px; text-align: center">$ {{formatCurrency(computedTotalCost)}}</div>
                                    <div style="text-align: center">Costo total</div>
                                </div>
                                <div style="padding: 10px; border-bottom: 2px solid #3f51b5">
                                    <div style="font-weight: bold; font-size: 20px; text-align: center">$ {{formatCurrency(computedUtilities)}}</div>
                                    <div style="text-align: center">Utilidad</div>
                                </div>');
                            }
                        ?>
                    </div>
                    <div style="display: flex; gap: 10px">
                        <?php
                            if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() != "Clubick") {
                                echo ('<button v-on:click="sellSelectedProducts" :disabled="getSelectedItems().length === 0" class="button-green-excel">Vender productos seleccionados</button>
                                <button v-on:click="generateReport" class="button-blue">Generar reporte</button>');
                            }
                        ?>
                    </div>
                </div>
                
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <div style="display: flex; gap: 5px; align-items: center">
                        <div style="font-weight: bold">Buscador:</div>
                        <div>
                            <input v-model='search' placeholder='Buscador' type="search" style='width: 300px; border: 1px solid rgba(0,0,0,.1); border-radius: 5px; padding: 7px' />
                            <div v-show='search !== ""'>{{getAvailableItems().length}} resultado(s)</div>
                        </div>
                    </div>
                    <table class="stripe width-100 dataTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Fecha creado</th>
                                <th>Inv</th>
                                <th>Producto</th>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador"
                                            || $sessionUser->getObject()->getRole()->getName() == "Clubick") {
                                        echo ('<th>TRM</th>
                                        <th>Costo USD</th>
                                        <th>Costo COP</th>
                                        <th>Costo envío internacional</th>
                                        <th>Costo envío nacional</th>
                                        <th>Costo total</th>');
                                    }
                                ?>
                                <th>Precio de venta</th>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador"
                                            || $sessionUser->getObject()->getRole()->getName() == "Clubick") {
                                        echo ('<th>Utilidad</th>');
                                    }
                                ?>
                                <th>Trazabilidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :class='index % 2 === 0 ? "odd": "even"' v-for='(i, index) in getAvailableItems()'>
                                <td><input v-model="i.selected" type="checkbox" /></td>
                                <td style="text-align: center">{{i.createdTimestamp.substr(0,10)}}</td>
                                <td>{{i.fullInvoiceCode}}</td>
                                <td>{{i.product}}</td>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador"
                                            || $sessionUser->getObject()->getRole()->getName() == "Clubick") {
                                        echo ('<td style="text-align: center">$ {{formatCurrency(i.trm)}}</td>
                                        <td style="text-align: center">$ {{formatCurrency(i.usdPrice)}}</td>
                                        <td style="text-align: center">$ {{formatCurrency(i.copPrice)}}</td>
                                        <td style="text-align: center">$ {{formatCurrency(i.internationalShippingPrice)}}</td>
                                        <td style="text-align: center">$ {{formatCurrency(i.nationalShippingPrice)}}</td>
                                        <td style="text-align: center">$ {{formatCurrency(i.totalCost)}}</td>');
                                    }
                                ?>
                                <td style="text-align: center">$ {{formatCurrency(i.salePrice)}}</td>
                                <?php
                                    if ($sessionUser->getObject()->getRole()->getName() == "Administrador"
                                            || $sessionUser->getObject()->getRole()->getName() == "Clubick") {
                                        echo ('<td style="text-align: center">$ {{formatCurrency(i.utility)}}</td>');
                                    }
                                ?>
                                <td>{{i.lastTracking}}</td>
                                <td>
                                    <?php
                                        if ($sessionUser->getObject()->getRole()->getName() != "Clubick") {
                                            echo "<button v-on:click='openRowActions(event,i)'><i class='fa fa-ellipsis-h'></i></button>";
                                        }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="display: flex; gap: 5px; align-items: center; margin-top: 20px">
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
                    search: "",
                    items: [],
                    itemsPerPage: 50,
                    currentPage: 1,
                    cargado: false
                },
                mounted() {
                    showPreload();
                    this.loadAvailableItems();
                },
                methods: {
                    getPagesNumber() {
                        return Math.ceil(this.items.length / this.itemsPerPage);
                    },
                    getAvailableItems() {
                        let result = [];
                        
                        if (this.search === "") {
                            let start = ((this.currentPage - 1) * this.itemsPerPage);
                            let end = start + this.itemsPerPage;

                            for (let i=start; i<end && i<this.items.length; i++) {
                                result.push(this.items[i]);
                            }
                        } else {
                            for (let i=0; i<this.items.length; i++) {
                                let temp = this.items[i];
                                if (temp.fullInvoiceCode.toLowerCase().indexOf(this.search.toLowerCase()) > -1
                                        || temp.product.toLowerCase().indexOf(this.search.toLowerCase()) > -1
                                        || temp.completeTracking.toLowerCase().indexOf(this.search.toLowerCase()) > -1) {
                                    result.push(temp);
                                }
                            }
                        }
                        
                        return result;
                    },
                    loadAvailableItems() {
                        this.$http.get(URL_API + "Inventory/GetAll.php").then(function(response){
                            let json = response.body;
                            for (let i=0; i<json.length; i++) {
                                let item = json[i];
                                item.selected = false;
                                this.items.push(item);
                            }
                            this.cargado = true;
                            this.$el.style.opacity = 1;
                            closePreload();
                        });
                    },
                    formatCurrency(value) {
			try {
			    value=value.toFixed(2);
			} catch(e){}
                        if (value !== undefined) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    getSelectedItems() {
                        let selectedItems = [];
                        for (let i=0; i<this.items.length; i++) {
                            if (this.items[i].selected) {
                                selectedItems.push(this.items[i]);
                            }
                        }
                        return selectedItems;
                    },
                    sellSelectedProducts() {
                        let ids = "";
                
                        for (let i=0; i<this.items.length; i++) {
                            if (this.items[i].selected) {
                                ids += this.items[i].id + ",";
                            }
                        }
                        
                        let sellPopup = new Popup(URL_PLATFORM + "Inventory/Sell.php?Ids=" + ids);
                        sellPopup.show();
                    },
                    openRowActions(e, item) {
                        e.stopPropagation();
                
                        let options = [
                            <?php
                                if (CloudEngineSession::getSessionObject()->getEmail() == "santiago@uniexpresssolutions.com"
                                        || CloudEngineSession::getSessionObject()->getEmail() == "maria@uniexpresssolutions.com"
                                        || CloudEngineSession::getSessionObject()->getEmail() == "andresfollecomoncayo@gmail.com"
					|| CloudEngineSession::getSessionObject()->getEmail() == "Fauo6417@gmail.com") {
                                    echo ('{
                                        text : "Editar",
                                        fn: function() {
                                            $.redirect("Edit.php", {Id: item.id});
                                        }
                                    },');
                                }

                                if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() != "Clubick") {
                                    echo('{
                                        text : "Trazabilidad",
                                        fn: function() {
                                            let addTrackingPopup = new Popup(URL_PLATFORM + "Inventory/Tracking.php?Id=" + item.id);
                                            addTrackingPopup.show();
                                        }
                                    }');
                                }
                            ?>
                        ];

                        new NewContextMenu(e, options);
                    },
                    generateReport() {
                        let reportPopup = new Popup(URL_PLATFORM + "Inventory/PopupGenerateReport.php");
                        reportPopup.show();
                    }
                },
                computed: {
                    computedUtilities() {
                        let result = 0;
                
                        this.items.forEach(i => {
                            result = parseFloat(result) + parseFloat(i.utility);
                        });
                
                        return result;
                    },
                    computedTotalCost() {
                        let result = 0;
                
                        this.items.forEach(i => {
                            result = parseFloat(result) + parseFloat(i.totalCost);
                        });
                
                        return result;
                    }
                }
            });
        </script>
    </body>
</html>
