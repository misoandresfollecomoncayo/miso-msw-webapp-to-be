<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Agente de compras")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Agente de compras");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Agente de compras", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <button id="btnAdd" class="button-red display-inline-block text-decoration-none">AGREGAR</button>
                </div>
                <!-- Resume -->
                <div class="margin-bottom-4x">
                    <template>
                        <div style="display:flex; align-items: center; justify-content: space-evenly" class="padding-4x border-radius background-color-white">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center">
                                <div class="text-weight-bold text-size-l">$ {{formatMoney(totalCOP)}}</div>
                                <div>Total utilidad COP</div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center">
                                <div class="text-weight-bold text-size-l">$ {{formatMoney(paidCOP)}}</div>
                                <div>Pagado COP</div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center">
                                <div class="text-weight-bold text-size-l">$ {{formatMoney(pendingCOP)}}</div>
                                <div>Por cobrar COP</div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Table -->
                <div class="width-100 background-color-white border-radius box-shadow overflow-auto">
                    <table id="tblList" class="table stripe width-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>TRM</th>
                                <th>Artículo</th>
                                <th>Costo real compra USD</th>
                                <th>Flete venta USD</th>
                                <th>Total costo USD</th>
                                <th>Total costo COP</th>
                                <th>Precio venta COP</th>
                                <th>Utilidad COP</th>
                                <th>Flete USD</th>
                                <th>Referencia</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template>
                                <tr v-for="(i, index) in items">
                                    <td class="text-align-center" style="white-space: nowrap">{{i.date}}</td>
                                    <template>
                                        <td v-if="i.status == 'PENDING'"><div v-on:click='changeStatus(index)' class="cursor-pointer background-color-orange padding text-size-xs text-color-white text-weight-bold text-align-center border-radius">PENDIENTE</div></td>
                                        <td v-else><div v-on:click='changeStatus(index)' class="cursor-pointer background-color-green padding text-size-xs text-color-white text-weight-bold text-align-center border-radius">PAGADO</div></td>
                                    </template>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.TRM)}} COP</td>
                                    <td style="white-space: nowrap; cursor: pointer" v-on:click='showMore(index)'>{{cutText(i.detail)}}</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.realCostPurchaseUSD)}} USD</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.freightSaleUSD)}} USD</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.totalCostUSD)}} USD</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.totalCostCOP)}} COP</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.salePriceCOP)}} COP</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.utilityCOP)}} COP</td>
                                    <td class="text-align-center" style="white-space: nowrap">$ {{formatMoney(i.freightUSD)}} USD</td>
                                    <td>{{i.reference}}</td>
                                    <td v-on:click='editItem(index)' class="text-decoration-underline text-align-center cursor-pointer">Editar</td>
                                    <td v-on:click='deleteItem(index)' class="text-decoration-underline text-align-center cursor-pointer">Eliminar</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            var data = {
                items: new Array()
            };
            
            var app = new Vue({
                el: "#root",
                data: data,
                beforeMount() {
                    showPreload();
                    this.$http.get(URL_API + "PurchasesAgent/Get.php").then(function(response){
                        var json = response.body;
                        for (var i=0; i<json.length; i++) {
                            this.items.push(json[i]);
                        }
                        closePreload();
                    });
                },
                methods: {
                    showMore(index) {
                        var m = new MessageBox(this.items[index].detail);
                        m.show();
                    },
                    cutText(value) {
                        if (value.length > 50) {
                            return value.substr(0,47) + "...";
                        }
                        return value;
                    },
                    formatMoney(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },
                    addItem(data) {
                        this.items.push(JSON.parse(data));
                    },
                    updateItem(data) {
                        var obj = JSON.parse(data);
                        var id = obj.idPurchasesAgentItem;
                        for (var i=0; i<this.items.length; i++) {
                            var item = this.items[i];
                            if (item.idPurchasesAgentItem === id) {
                                this.items[i].date = obj.date;
                                this.items[i].TRM = obj.TRM;
                                this.items[i].detail = obj.detail;
                                this.items[i].realCostPurchaseUSD = obj.realCostPurchaseUSD;
                                this.items[i].freightSaleUSD = obj.freightSaleUSD;
                                this.items[i].totalCostUSD = obj.totalCostUSD;
                                this.items[i].totalCostCOP = obj.totalCostCOP;
                                this.items[i].salePriceCOP = obj.salePriceCOP;
                                this.items[i].utilityCOP = obj.utilityCOP;
                                this.items[i].freightUSD = obj.freightUSD;
                                this.items[i].reference = obj.reference;
                            }
                        }
                    },
                    changeStatus(index) {
                        if (confirm("¿Confirma cambiar el estado del registro?")) {
                            showPreload();
                            var fd = new FormData();
                            fd.append("Id", this.items[index].idPurchasesAgentItem);
                            this.$http.post(URL_API + "PurchasesAgent/ChangeStatus.php", fd).then(function(response){
                                this.items[index].status = response.body.body;
                                closePreload();
                            });
                        }
                    },
                    editItem(index) {
                        var p = new Popup("https://www.uniexpresssolutions.com/Platform/PurchasesAgent/Edit.php?Id=" + this.items[index].idPurchasesAgentItem);
                        p.show();
                    },
                    deleteItem(index) {
                        if (confirm("¿Confirma eliminar el registro?")) {
                            showPreload();
                            var fd = new FormData();
                            fd.append("Id", this.items[index].idPurchasesAgentItem);
                            this.$http.post(URL_API + "PurchasesAgent/Delete.php", fd).then(function(response){
                                this.items.splice(index,1);
                                closePreload();
                            });
                        }
                    }
                },
                computed: {
                    totalCOP() {
                        var value = 0;
                        for (var i=0; i<this.items.length; i++) {
                            value += parseFloat(this.items[i].utilityCOP);
                        }
                        return value.toFixed(0);
                    },
                    paidCOP() {
                        var value = 0;
                        for (var i=0; i<this.items.length; i++) {
                            if (this.items[i].status === "PAID") {
                                value += parseFloat(this.items[i].utilityCOP);
                            }
                        }
                        return value.toFixed(0);
                    },
                    pendingCOP() {
                        var value = 0;
                        for (var i=0; i<this.items.length; i++) {
                            if (this.items[i].status === "PENDING") {
                                value += parseFloat(this.items[i].utilityCOP);
                            }
                        }
                        return value.toFixed(0);
                    }
                }
            });
            
            $("#btnAdd").on("click", function() {
                var p = new Popup("https://www.uniexpresssolutions.com/Platform/PurchasesAgent/Add.php");
                p.show();
            });
        </script>
    </body>
</html>