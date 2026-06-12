<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
    
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $products = [];
    $ids = explode(",", $_REQUEST["Ids"]);
    foreach ($ids as $id) {
        if ($id != "") {
            array_push($products, InventoryDAO::getById($id));
        }
    }

    $sessionObject = CloudEngineSession::getSessionObject();
?>
<div id="rootSellPopup" class="popup" style="background-color: #f3f3f3;">
    <div v-if="screen === 'resume'" style="display: flex; flex-direction: column; gap: 20px">
        <div style="font-weight: bold; font-size: 20px">Vender productos seleccionados</div>

        <div style="display: flex; flex-direction: column; gap: 10px; background-color: white; border-radius: 5px; overflow: hidden">
            <table class="dataTable stripe">
                <thead>
                    <tr>
                        <th>Inv</th>
                        <th>Producto</th>
                        <th>Total a pagar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :class='index % 2 === 0 ? "odd": "even"' v-for="(p, index) in products">
                        <td>{{p.fullInvoiceCode}}</td>
                        <td>{{p.product}}</td>
                        <td style="text-align: center">$ {{formatCurrency(p.salePrice)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 20px">Total: $ {{formatCurrency(computedTotal)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px; padding: 10px; background-color: white; border-radius: 5px">
            <div style="font-weight: bold">¿Cuál empresa realiza la venta?</div>
            <div style="display: flex; gap: 20px">
                <div v-on:click='sellingCompany = "Uniexpress"' :style='"border: " + (sellingCompany === "Uniexpress" ? "2px solid #2196f3" : "1px solid rgba(0,0,0,.1)")' style="cursor: pointer; height: 80px; width: 50%; border: 1px solid rgba(0,0,0,.1); border-radius: 5px; background-position: center; background-repeat: no-repeat; background-size: 50%; background-image: url(../../Static/Images/logotype.svg)"></div>
                <div v-on:click='sellingCompany = "Clubick"' :style='"border: " + (sellingCompany === "Clubick" ? "2px solid #2196f3" : "1px solid rgba(0,0,0,.1)")' style="cursor: pointer; height: 80px; width: 50%; border: 1px solid rgba(0,0,0,.1); border-radius: 5px; background-position: center; background-repeat: no-repeat; background-size: 50%; background-image: url(../../Static/Images/clubick.png)"></div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px; padding: 10px; background-color: white; border-radius: 5px">
            <div style="display: flex; justify-content: space-between; align-items: center">
                <div style="font-weight: bold">Información del cliente</div>
                <a href="../InventoryCustomers/Create.php" target="_blank" style="color: #2196f3; font-size: 12px">Crear nuevo cliente +</a>
            </div>
            <input v-model="customerDocument" v-on:keypress="searchCustomer" placeholder="Digite el documento del cliente y presione enter" class="input-text-underline" />
            <table v-if="customer !== null">
                <tr>
                    <td style="font-weight: bold">Nombres:</td>
                    <td>{{customer.names}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">País:</td>
                    <td>{{customer.country}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Ciudad:</td>
                    <td>{{customer.city}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Dirección:</td>
                    <td>{{customer.address}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Teléfono:</td>
                    <td>{{customer.phoneNumber}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Correo electrónico:</td>
                    <td>{{customer.email}}</td>
                </tr>
                <tr>
                    <td colspan="2"><a style='color: #2196f3' target="_blank" :href='"../InventoryCustomers/Edit.php?Id=" + customer.id'>Editar cliente</a></td>
                </tr>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px; padding: 10px; background-color: white; border-radius: 5px">
            <div style="font-weight: bold">Digite el monto y método de pago</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px">
                <?php
                    if ($sessionObject->getObject()->getRole()->getName() == "Administrador") {
                        echo '<input type="date" v-model="date" placeholder="Fecha" class="input-text-underline" />';
                    } else {
                        echo '<div>{{ date }}</div>';
                    }
                ?>

                <input v-model="amountPaid" type="number" placeholder="Digite el monto pagado" class="input-text-underline" />
                <select class="input-text-underline" v-model="idPaymentMethod">
                    <option value="">Método de pago</option>
                    <?php
                        $methods = InventoryPaymentMethodDAO::getAll();
                        foreach ($methods as $m) {
                            echo("<option value='" . $m->id . "'>" . $m->name . "</option>");
                        }
                    ?>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end">
            <button class="button-green-excel" v-on:click="save" :disabled="sellingCompany === '' || customer === null || amountPaid === ''">Finalizar venta</button>
        </div>
    </div>
    <div v-else style='display: flex; flex-direction: column; gap: 20px'>
        <div style="display: flex; justify-content: center">
            <div style='width: 80px; height: 80px; background-color: #4caf50; border-radius: 100%; display: flex; align-items: center; justify-content: center'>
                <i style='font-size: 40px; color: white' class='fa fa-check'></i>
            </div>
        </div>
        <div style="text-align: center; font-weight: bold; font-size: 20px">La venta se realizó correctamente.</div>
        <div style="text-align: center; font-size: 20px">Factura generada: <a style='color: #2196f3; font-size: 20px'>UN1001</a></div>
    </div>
</div>
<script>
    var sellPopupApp = new Vue({
        el: "#rootSellPopup",
        data: {
            screen: "resume",
            products: <?php echo json_encode($products); ?>,
            sellingCompany: "",
            customerDocument: "",
            customer: null,
            date: "",
            amountPaid: 0,
            idPaymentMethod: ""
        },
        mounted() {
            this.date = this.computedGetCurrentDate;
        },
        computed: {
            computedTotal() {
                let total = 0;
                for (let i=0; i<this.products.length; i++) {
                    total = parseFloat(parseFloat(total) + parseFloat(this.products[i].salePrice)).toFixed(0);
                }
                return parseFloat(total).toFixed(0);
            },
            computedGetCurrentDate() {
                let currentDate = new Date();
                let year = currentDate.toLocaleString("default", {year: "numeric"});
                let month = currentDate.toLocaleString("default", {month: "2-digit"});
                let day = currentDate.toLocaleString("default", {day: "2-digit"});

                return year + "-" + month + "-" + day;
            }
        },
        methods: {
            formatCurrency(value) {
                if (value !== undefined) {
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            },
            searchCustomer(e) {
                if (e.key === "Enter") {
                    showPreload();
                    var fd = new FormData();
                    fd.append("Document", this.customerDocument);
                    this.$http.post(URL_API + "InventoryCustomer/GetByDocument.php", fd).then(function(response){
                        let responseJson = response.body;
                        
                        if (responseJson.type === "Exception") {
                            new Notification("ERROR","El cliente no existe.");
                            this.customer = null;
                        } else {
                            this.customer = JSON.parse(responseJson.body)[0];
                        }
                        closePreload();
                    });
                }
            },
            save() {
                if (confirm("¿Confirma finalizar la venta?")) {
                    let productsIds = "";
                    for (let i=0; i<this.products.length; i++) {
                        productsIds += this.products[i].id + ",";
                    }
                    
                    showPreload();
                    var fd = new FormData();
                    fd.append("ProductsIds", productsIds);
                    fd.append("SellingCompany", this.sellingCompany);
                    fd.append("IdInventoryCustomer", this.customer.id);
                    fd.append("Date", this.date);
                    fd.append("AmountPaid", this.amountPaid);
                    fd.append("IdPaymentMethod", this.idPaymentMethod);
                    this.$http.post(URL_API + "InventoryInvoice/Create.php", fd).then(function(response){
                        let responseJson = response.body;
                        
                        if (responseJson.type === "Exception") {
                            new Notification("ERROR",responseJson.message);
                        } else {
                            if (this.sellingCompany === "Clubick") {
                                document.location.href = URL_PLATFORM + "InventorySales/index.php?Company=Clubick";
                            } else {
                                document.location.href = URL_PLATFORM + "InventorySales/index.php?Company=Uniexpress";
                            }
                        }
                        closePreload();
                    });
                }
            }
        }
    });
</script>