<div id="rootEdit" class="popup">
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
        <button v-on:click="save()" class="button-red">GUARDAR</button>
    </div>
</div>
<script>
    var data = {
        id: "",
        date: "",
        TRM: 3000,
        detail: "",
        realCostPurchaseUSD: 0,
        freightSaleUSD: 0,
        salePriceCOP: 0,
        freightUSD: 0,
        reference: ""
    };
    
    var editApp = new Vue({
        el: "#rootEdit",
        data: data,
        beforeMount() {
            showPreload();
            var fd = new FormData();
            fd.append("Id", "<?php echo $_GET["Id"] ?>");
            this.$http.post(URL_API + "PurchasesAgent/GetById.php", fd).then(function(response){
                this.id = response.body.idPurchasesAgentItem;
                this.date = response.body.date;
                this.TRM = response.body.TRM;
                this.detail = response.body.detail;
                this.realCostPurchaseUSD = response.body.realCostPurchaseUSD;
                this.freightSaleUSD = response.body.freightSaleUSD;
                this.salePriceCOP = response.body.salePriceCOP;
                this.freightUSD = response.body.freightUSD;
                this.reference = response.body.reference;
                closePreload();
            });
        },
        methods: {
            formatMoney(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            save() {
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
                fd.append("Id", this.id);
                fd.append("Date", this.date);
                fd.append("TRM", this.TRM);
                fd.append("Detail", this.detail);
                fd.append("RealCostPurchaseUSD", this.realCostPurchaseUSD);
                fd.append("FreightSaleUSD", this.freightSaleUSD);
                fd.append("SalePriceCOP", this.salePriceCOP);
                fd.append("FreightUSD", this.freightUSD);
                fd.append("Reference", this.reference);
                
                this.$http.post(URL_API + "PurchasesAgent/Edit.php", fd).then(function(response){
                    new Notification("SUCCESS", "Registro actualizado correctamente");
                    app.updateItem(response.body.body);
                });
            }
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