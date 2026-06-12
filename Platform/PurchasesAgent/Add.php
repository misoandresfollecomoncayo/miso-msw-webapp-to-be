<div id="rootAdd" class="popup">
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Fecha</div>
        <div class="width-50">
            <input type="date" autofocus="on" class="input-text-underline" v-model="date" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">TRM</div>
        <div class="width-50">
            <input type="number" class="input-text-underline" v-model="TRM" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Artículo</div>
        <div class="width-50">
            <input class="input-text-underline" v-model="detail" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Costo real compra USD</div>
        <div class="width-50">
            <input type="number" class="input-text-underline" v-model="realCostPurchaseUSD" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Flete venta USD</div>
        <div class="width-50">
            <input type="number" class="input-text-underline" v-model="freightSaleUSD" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Total costo USD</div>
        <div class="width-50">$ {{formatMoney(totalCostUSD)}} USD</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Total costo COP</div>
        <div class="width-50">$ {{formatMoney(totalCostCOP)}} COP</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Precio venta COP</div>
        <div class="width-50">
            <input type="number" class="input-text-underline" v-model="salePriceCOP" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Utilidad COP</div>
        <div class="width-50">$ {{formatMoney(utilityCOP)}} COP</div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Flete USD</div>
        <div class="width-50">
            <input type="number" class="input-text-underline" v-model="freightUSD" />
        </div>
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between" class="padding-top-3x padding-bottom-3x width-100">
        <div class="width-25 text-weight-bold">Referencia</div>
        <div class="width-50">
            <input class="input-text-underline" v-model="reference" />
        </div>
    </div>
    <!-- Actions -->
    <div class="margin-top-2x text-align-right">
        <button v-on:click="add()" class="button-red">GUARDAR</button>
    </div>
</div>
<script>
    var addApp = new Vue({
        el: "#rootAdd",
        data: {
            date: "",
            TRM: 3000,
            detail: "",
            realCostPurchaseUSD: 0,
            freightSaleUSD: 0,
            salePriceCOP: 0,
            freightUSD: 0,
            reference: ""
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
            add() {
                if (this.TRM === "") {
                    alert("Debe ingresar: TRM");
                    return;
                }
                if (this.detail === "") {
                    alert("Debe ingresar: Artículo");
                    return;
                }
                if (this.realCostPurchaseUSD === "") {
                    alert("Debe ingresar: Costo real compra USD");
                    return;
                }
                if (this.freightSaleUSD === "") {
                    alert("Debe ingresar: Flete venta USD");
                    return;
                }
                if (this.salePriceCOP === "") {
                    alert("Debe ingresar: Precio venta COP");
                    return;
                }
                if (this.freightUSD === "") {
                    alert("Debe ingresar: Flete USD");
                    return;
                }
                
                // Send
                
                var fd = new FormData();
                fd.append("Date", this.date);
                fd.append("TRM", this.TRM);
                fd.append("Detail", this.detail);
                fd.append("RealCostPurchaseUSD", this.realCostPurchaseUSD);
                fd.append("FreightSaleUSD", this.freightSaleUSD);
                fd.append("SalePriceCOP", this.salePriceCOP);
                fd.append("FreightUSD", this.freightUSD);
                fd.append("Reference", this.reference);
                
                this.$http.post(URL_API + "PurchasesAgent/Add.php", fd).then(function(response){
                    app.addItem(response.body.body);    // Add to main table
                    this.TRM = 3000;
                    this.detail = "";
                    this.realCostPurchaseUSD = 0;
                    this.freightSaleUSD = 0;
                    this.salePriceCOP = 0;
                    this.freightUSD = 0;
                    this.reference = '';
                });
            }
        },
        mounted: function() {
            this.date = this.getCurrentDate();
        },
        computed: {
            totalCostUSD() {
                return parseFloat(this.realCostPurchaseUSD) + parseFloat(this.freightSaleUSD);
            },
            totalCostCOP() {
                return this.totalCostUSD * this.TRM;
            },
            utilityCOP() {
                return this.salePriceCOP - this.totalCostCOP;
            }
        }
    });
</script>