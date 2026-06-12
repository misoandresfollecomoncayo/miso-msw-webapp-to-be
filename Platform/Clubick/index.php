<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Ventas Clubick")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Clubick");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Clubick", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <div style="background-color: white; height: 100%; overflow: auto; border-radius: 5px">
                    <div v-if="role !== 'Clubick'" style="display: grid; grid-template-columns: 1fr 150px; gap: 10px; padding: 10px; border-bottom: 1px solid rgba(0,0,0,.1)">
                        <input v-model="search" style="border: 1px solid rgba(0,0,0,.25); border-radius: 5px; padding: 10px" placeholder="Buscador" />
                        <button v-on:click="create" class="button-green-excel" style="display: flex; align-items: center"><i style="color: white; margin-right: 10px" class="fa fa-plus"></i>Nuevo registro</button>
                    </div>
                    <div v-else style="display: grid; grid-template-columns: 1fr; gap: 10px; padding: 10px; border-bottom: 1px solid rgba(0,0,0,.1)">
                        <input v-model="search" style="border: 1px solid rgba(0,0,0,.25); border-radius: 5px; padding: 10px" placeholder="Buscador" />
                    </div>
                    <div style="width: 100%; height: calc(100% - 59px); overflow: auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Documento</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Producto</th>
                                    <th>TRM</th>
                                    <th>Precio USD</th>
                                    <th>Precio pesos</th>
                                    <th>Envío Uniexpress</th>
                                    <th>Costo total</th>
                                    <th>Venta</th>
                                    <th>Envío nacional</th>
                                    <th>Total a pagar</th>
                                    <th>Pagado</th>
                                    <th>Saldo</th>
                                    <th>Estado</th>
                                    <th>Utilidad</th>
                                    <th>Utilidad Santiago</th>
                                    <th>Utilidad Julian</th>
                                    <th>Acciones</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>$ {{formatMoney(getTotalToPayTotal())}}</th>
                                    <th></th>
                                    <th></th>
                                    <th>$ {{formatMoney(getPaidTotal())}}</th>
                                    <th>$ {{formatMoney(getPendingPaymentTotal())}}</th>
                                    <th></th>
                                    <th>$ {{formatMoney(getUtilityTotal())}}</th>
                                    <th>$ {{formatMoney(getUtilitySantiagoTotal())}}</th>
                                    <th>$ {{formatMoney(getUtilityJulianTotal())}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="padding: 0" v-for="i in computedItems">
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">{{i.invoiceNumber}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.date" type="date" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.customer" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.customerDocument" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.customerAddress" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.customerPhone" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.product" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        $ <input v-model="i.trm" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        $ <input v-model="i.usdPrice" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getCOPPrice(i))}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        $ <input v-model="i.UniexpressShippingPrice" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getTotalPrice(i))}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        $ <input v-model="i.salePrice" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        $ <input v-model="i.nationalShippingPrice" :disabled="role === 'Clubick'"  style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getTotalToPay(i))}}</td>
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(i.paid)}}</td>
                                    <td style="white-space: nowrap; text-align: center; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(i.pendingPayment)}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">
                                        <input v-model="i.status" style="border:none" />
                                    </td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getUtility(i))}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getUtilitySantiago(i))}}</td>
                                    <td style="white-space: nowrap; text-align: center; padding: 0; border: 1px solid rgba(0,0,0,.25)">$ {{formatMoney(getUtilityJulian(i))}}</td>
                                    <td style="padding: 0; display: flex">
                                        <button v-on:click="save(i)" style="width: 100%">Guardar</button>
                                        <button v-on:click="pay(i)" style="width: 100%">Pagos</button>
                                        <button v-on:click="printInvoice(i)" style="width: 100%">Factura</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            let clubickEditPopup = null;
            
            var clubickApp = new Vue({
                el: "#root",
                data: {
                    items: [],
                    search: "",
                    role: "<?php echo CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() ?>"
                },
                mounted() {
                    showPreload();
                    this.$http.get(URL_API + "Clubick/GetAll.php").then(function(response){
                        let json = response.body;
                        for (let i=0; i<json.length; i++) {
                            this.items.push(json[i]);
                        }
                        closePreload();
                    });
                },
                computed: {
                    computedItems() {
                        if (this.search === "") {
                            return this.items; 
                        } else {
                            let result = [];
                            
                            this.items.forEach(i => {
                                if (i.invoiceNumber.toLowerCase().indexOf(this.search) > -1
                                        || i.date.toLowerCase().indexOf(this.search) > -1
                                        || i.customer.toLowerCase().indexOf(this.search) > -1
                                        || i.customerDocument.toLowerCase().indexOf(this.search) > -1
                                        || i.customerAddress.toLowerCase().indexOf(this.search) > -1
                                        || i.customerPhone.toLowerCase().indexOf(this.search) > -1
                                        || i.product.toLowerCase().indexOf(this.search) > -1
                                        || i.status.toLowerCase().indexOf(this.search) > -1) {
                                    result.push(i);
                                }
                            });
                            
                            return result;
                        }
                    }
                },
                methods: {
                    pay(item) {
                        if (item.id === undefined) {
                            alert("Debe guardar antes de agregar pagos.");
                        } else {
                            var clubickPaymentPopup = new Popup("/Platform/Clubick/Payment.php?Id=" + item.id);
                            clubickPaymentPopup.show();
                        }
                    },
                    create() {
                        this.items.push({
                            invoiceNumber: "CB" + (1000 + this.items.length)
                        });
                    },
                    edit(item) {
                        clubickEditPopup = new Popup("/Uniexpress/Platform/Clubick/Edit.php?Id=" + item.id);
                        clubickEditPopup.show();
                    },
                    formatMoney(value) {
                        if (value !== undefined) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    },
                    addItem(id, date, customer, product, invoice, trm, usdPrice, copPrice, uniexpressShippingPrice, totalPrice, salePrice, nationalShippingPrice, totalToPay, partialPayment, pendingPayment, paymentMethod, status, utility, utilitySantiago, utilityJulian) {
                        let obj = new Object();
                        obj.id = id;
                        obj.date = date;
                        obj.customer = customer;
                        obj.product = product;
                        obj.invoice = invoice;
                        obj.trm = trm;
                        obj.usdPrice = usdPrice;
                        obj.copPrice = copPrice;
                        obj.UniexpressShippingPrice = uniexpressShippingPrice;
                        obj.totalPrice = totalPrice;
                        obj.salePrice = salePrice;
                        obj.nationalShippingPrice = nationalShippingPrice;
                        obj.totalToPay = totalToPay;
                        obj.partialPayment = partialPayment;
                        obj.pendingPayment = pendingPayment;
                        obj.paymentMethod = paymentMethod;
                        obj.status = status;
                        obj.utility = utility;
                        obj.utilitySantiago = utilitySantiago;
                        obj.utilityJulian = utilityJulian;
                        this.items.push(obj);
                    },
                    updateItem(id, date, customer, product, invoice, trm, usdPrice, copPrice, uniexpressShippingPrice, totalPrice, salePrice, nationalShippingPrice, totalToPay, partialPayment, pendingPayment, paymentMethod, status, utility, utilitySantiago, utilityJulian) {
                        for (let i=0; i<this.items.length; i++) {
                            let item = this.items[i];
                            if (item.id === id) {
                                Vue.set(this.items[i], "date", date);
                                Vue.set(this.items[i], "customer", customer);
                                Vue.set(this.items[i], "product", product);
                                Vue.set(this.items[i], "invoice", invoice);
                                Vue.set(this.items[i], "trm", trm);
                                Vue.set(this.items[i], "usdPrice", usdPrice);
                                Vue.set(this.items[i], "copPrice", copPrice);
                                Vue.set(this.items[i], "UniexpressShippingPrice", uniexpressShippingPrice);
                                Vue.set(this.items[i], "totalPrice", totalPrice);
                                Vue.set(this.items[i], "salePrice", salePrice);
                                Vue.set(this.items[i], "nationalShippingPrice", nationalShippingPrice);
                                Vue.set(this.items[i], "totalToPay", totalToPay);
                                Vue.set(this.items[i], "partialPayment", partialPayment);
                                Vue.set(this.items[i], "pendingPayment", pendingPayment);
                                Vue.set(this.items[i], "paymentMethod", paymentMethod);
                                Vue.set(this.items[i], "status", status);
                                Vue.set(this.items[i], "utility", utility);
                                Vue.set(this.items[i], "utilitySantiago", utilitySantiago);
                                Vue.set(this.items[i], "utilityJulian", utilityJulian);
                            }
                        }
                    },
                    printInvoice(item) {
                        if (item.id === undefined) {
                            alert("Debe guardar antes de imprimir la factura.");
                        } else {
                            $.redirect(URL_API + "PDF/Clubick.php", {Id: item.id}, "POST", "_blank");
                        }
                    },
                    getCOPPrice(item) {
                        return parseFloat(item.trm * item.usdPrice).toFixed(0);
                    },
                    getTotalPrice(item) {
                        return parseFloat(parseFloat(item.UniexpressShippingPrice) + parseFloat(this.getCOPPrice(item))).toFixed(0);
                    },
                    getTotalToPay(item) {
                        return parseFloat(item.salePrice - item.nationalShippingPrice).toFixed(0);
                    },
                    getTotalToPayTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(this.getTotalToPay(i));
                        });
                        return total;
                    },
                    getPaidTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(i.paid);
                        });
                        return total;
                    },
                    getPendingPaymentTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(i.pendingPayment);
                        });
                        return total;
                    },
                    getPendingPayment(item) {
                        return parseFloat(this.getTotalToPay(item) - item.partialPayment).toFixed(0);
                    },
                    getUtility(item) {
                        return parseFloat(this.getTotalToPay(item) - this.getTotalPrice(item)).toFixed(0);
                    },
                    getUtilitySantiago(item) {
                        return parseFloat(this.getUtility(item) * .7).toFixed(0);
                    },
                    getUtilityJulian(item) {
                        return parseFloat(this.getUtility(item) * .3).toFixed(0);
                    },
                    getUtilityTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(this.getUtility(i));
                        });
                        return total;
                    },
                    getUtilitySantiagoTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(this.getUtilitySantiago(i));
                        });
                        return total;
                    },
                    getUtilityJulianTotal() {
                        let total = 0;
                        this.items.forEach(i => {
                            total += parseFloat(this.getUtilityJulian(i));
                        });
                        return total;
                    },
                    save(item) {
                        let errors = false;
                        
                        if (item.date === undefined
                                || item.date === "") {
                            alert("Debe ingresar una fecha.");
                            errors = true;
                        } else if (item.customer === undefined
                                || item.customer === "") {
                            alert("Debe ingresar el cliente.");
                            errors = true;
                        } else if (item.customerDocument === undefined
                                || item.customerDocument === "") {
                            alert("Debe ingresar el documento del cliente.");
                            errors = true;
                        } else if (item.customerAddress === undefined
                                || item.customerAddress === "") {
                            alert("Debe ingresar la dirección del cliente.");
                            errors = true;
                        } else if (item.customerPhone === undefined
                                || item.customerPhone === "") {
                            alert("Debe ingresar el teléfono del cliente.");
                            errors = true;
                        } else if (item.product === undefined
                                || item.product === "") {
                            alert("Debe ingresar el producto.");
                            errors = true;
                        } else if (item.trm === undefined
                                || item.trm === "") {
                            alert("Debe ingresar la TRM.");
                            errors = true;
                        } else if (item.usdPrice === undefined
                                || item.usdPrice === "") {
                            alert("Debe ingresar el precio en dólares.");
                            errors = true;
                        } else if (item.UniexpressShippingPrice === undefined
                                || item.UniexpressShippingPrice === "") {
                            alert("Debe ingresar el precio de envío Uniexpress.");
                            errors = true;
                        } else if (item.salePrice === undefined
                                || item.salePrice === "") {
                            alert("Debe ingresar el precio de venta.");
                            errors = true;
                        } else if (item.nationalShippingPrice === undefined
                                || item.nationalShippingPrice === "") {
                            alert("Debe ingresar el precio de envío nacional.");
                            errors = true;
                        } else if (item.status === undefined
                                || item.status === "") {
                            alert("Debe ingresar el estado.");
                            errors = true;
                        }
                        
                        if (!errors) {
                            var fd = new FormData();
                            fd.append("Id", item.id);
                            fd.append("Date", item.date);
                            fd.append("Customer", item.customer);
                            fd.append("CustomerDocument", item.customerDocument);
                            fd.append("CustomerAddress", item.customerAddress);
                            fd.append("CustomerPhone", item.customerPhone);
                            fd.append("Product", item.product);
                            fd.append("TRM", item.trm);
                            fd.append("USDPrice", item.usdPrice);
                            fd.append("COPPrice", this.getCOPPrice(item));
                            fd.append("UniexpressShippingPrice", item.UniexpressShippingPrice);
                            fd.append("TotalPrice", this.getTotalPrice(item));
                            fd.append("SalePrice", item.salePrice);
                            fd.append("NationalShippingPrice", item.nationalShippingPrice);
                            fd.append("TotalToPay", this.getTotalToPay(item));
                            fd.append("Status", item.status);
                            fd.append("Utility", this.getUtility(item));
                            fd.append("UtilitySantiago", this.getUtilitySantiago(item));
                            fd.append("UtilityJulian", this.getUtilityJulian(item));

                            this.$http.post(URL_API + "Clubick/Save.php", fd).then(function(response) {
                                new Notification("","Registro almacenado correctamente.");
                                item.id = response.body.body;
                            });
                        }
                    }
                }
            });
        </script>
    </body>
</html>