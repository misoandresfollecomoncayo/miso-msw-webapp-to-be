<div id="rootAdd" class="popup">
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Fecha:</div>
        <div class="width-50"><input type="date" autofocus="on" class="input-text-underline" v-model="date" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Cliente:</div>
        <div class="width-50"><input class="input-text-underline" v-model="customer" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Producto:</div>
        <div class="width-50"><input class="input-text-underline" v-model="product" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Factura:</div>
        <div class="width-50"><input class="input-text-underline" v-model="invoice" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">TRM:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="trm" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Precio USD:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="usdPrice" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Valor pesos:</div>
        <div class="width-50">$ {{formatMoney(computedCOPPrice)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Envío uniexpress:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="uniexpressShippingPrice" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Costo total:</div>
        <div class="width-50">$ {{formatMoney(computedTotalPrice)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Venta:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="salePrice" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Envío nacional:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="nationalShippingPrice" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Total a pagar:</div>
        <div class="width-50">$ {{formatMoney(computedTotalToPay)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Abono:</div>
        <div class="width-50"><input type="number" class="input-text-underline" v-model="partialPayment" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Saldo:</div>
        <div class="width-50">$ {{formatMoney(computedPendingPayment)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Método de pago:</div>
        <div class="width-50"><input class="input-text-underline" v-model="paymentMethod" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Estado:</div>
        <div class="width-50"><input class="input-text-underline" v-model="status" /></div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Utilidad:</div>
        <div class="width-50">$ {{formatMoney(computedUtility)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Utilidad Santiago (70%):</div>
        <div class="width-50">$ {{formatMoney(computedUtilitySantiago)}}</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top padding-bottom width-100">
        <div class="width-50 text-weight-bold">Utilidad Julian (30%):</div>
        <div class="width-50">$ {{formatMoney(computedUtilityJulian)}}</div>
    </div>
    <!-- Actions -->
    <div class="margin-top-2x text-align-right">
        <button v-on:click="create()" class="button-red">GUARDAR</button>
    </div>
</div>
<script>
    var clubickCreateApp = new Vue({
        el: "#rootAdd",
        data: {
            date: "",
            customer: "",
            product: "",
            invoice: "",
            trm: 0,
            usdPrice: 0,
            uniexpressShippingPrice: 0,
            salePrice: 0,
            nationalShippingPrice: 0,
            partialPayment: 0,
            paymentMethod: "",
            status: ""
        },
        methods: {
            getCurrentDate() {
                var now = new Date();
                
                var dd = now.getDate();
                if (dd < 10) {
                    dd = "0" + dd;
                }
                
                var mm = now.getMonth() + 1;
                if (mm < 10) {
                    mm = "0" + mm;
                }
                
                return now.getFullYear() + "-" + mm + "-" + dd;
            },
            formatMoney(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            create() {
                // Send
                
                var fd = new FormData();
                fd.append("Date", this.date);
                fd.append("Customer", this.customer);
                fd.append("Product", this.product);
                fd.append("Invoice", this.invoice);
                fd.append("TRM", this.trm);
                fd.append("USDPrice", this.usdPrice);
                fd.append("COPPrice", this.computedCOPPrice);
                fd.append("UniexpressShippingPrice", this.uniexpressShippingPrice);
                fd.append("TotalPrice", this.computedTotalPrice);
                fd.append("SalePrice", this.salePrice);
                fd.append("NationalShippingPrice", this.nationalShippingPrice);
                fd.append("TotalToPay", this.computedTotalToPay);
                fd.append("PartialPayment", this.partialPayment);
                fd.append("PendingPayment", this.computedPendingPayment);
                fd.append("PaymentMethod", this.paymentMethod);
                fd.append("Status", this.status);
                fd.append("Utility", this.computedUtility);
                fd.append("UtilitySantiago", this.computedUtilitySantiago);
                fd.append("UtilityJulian", this.computedUtilityJulian);
                
                this.$http.post(URL_API + "Clubick/Create.php", fd).then(function(response) {
                    clubickApp.addItem(
                        response,
                        this.date,
                        this.customer,
                        this.product,
                        this.invoice,
                        this.trm,
                        this.usdPrice,
                        this.computedCOPPrice,
                        this.uniexpressShippingPrice,
                        this.computedTotalPrice,
                        this.salePrice,
                        this.nationalShippingPrice,
                        this.computedTotalToPay,
                        this.partialPayment,
                        this.computedPendingPayment,
                        this.paymentMethod,
                        this.status,
                        this.computedUtility,
                        this.computedUtilitySantiago,
                        this.computedUtilityJulian
                    );
                });
            }
        },
        mounted: function() {
            this.date = this.getCurrentDate();
        },
        computed: {
            computedCOPPrice() {
                return (this.trm * this.usdPrice).toFixed(0);
            },
            computedTotalPrice() {
                return (parseFloat(this.uniexpressShippingPrice) + parseFloat(this.computedCOPPrice)).toFixed(0);
            },
            computedTotalToPay() {
                return (this.salePrice - this.nationalShippingPrice).toFixed(0);
            },
            computedPendingPayment() {
                return (this.computedTotalToPay - this.partialPayment).toFixed(0);
            },
            computedUtility() {
                return (this.computedTotalToPay - this.computedTotalPrice).toFixed(0);
            },
            computedUtilitySantiago() {
                return (this.computedUtility * .7).toFixed(0);
            },
            computedUtilityJulian() {
                return (this.computedUtility * .3).toFixed(0);
            }
        }
    });
</script>