<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Inventario")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Crear producto");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear producto", PUBLIC_PATH_PLATFORM . "Inventory"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmBill" class="width-100 padding-4x background-color-white border-radius box-shadow margin-top-3x" style="display: flex; flex-direction: column; gap: 20px">
                    <div>
                        <div style="font-weight: bold">* Producto:</div>
                        <input v-model="product" class="input-text-underline" type="text" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* TRM:</div>
                        <input v-model="trm" class="input-text-underline" type="number" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* Precio USD:</div>
                        <input v-model="usdPrice" class="input-text-underline" type="number" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* Precio COP:</div>
                        <div class="input-text-underline">$ {{formatCurrency(computedCOPPrice)}}</div>
                    </div>
                    <div>
                        <div style="font-weight: bold">* Precio envío internacional:</div>
                        <input v-model="internationalShippingPrice" class="input-text-underline" type="number" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* Precio envío nacional:</div>
                        <input v-model="nationalShippingPrice" class="input-text-underline" type="number" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* Costo total:</div>
                        <div class="input-text-underline">$ {{formatCurrency(computedTotalCost)}}</div>
                    </div>
                    <div>
                        <div style="font-weight: bold">* Precio de venta:</div>
                        <input v-model="salePrice" class="input-text-underline" type="number" />
                    </div>
                    <div>
                        <div style="font-weight: bold">* Utilidad:</div>
                        <div class="input-text-underline" style="font-size: 30px; font-weight: bold">$ {{formatCurrency(computedUtility)}}</div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button v-on:click='save' id="btnSave" :disabled="product === ''" class="button-red">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            let app = new Vue({
                el: "#root",
                data: {
                    product: "",
                    trm: 0,
                    usdPrice: 0,
                    internationalShippingPrice: 0,
                    nationalShippingPrice: 0,
                    salePrice: 0
                },
                computed: {
                    computedCOPPrice() {
                        return parseFloat(this.trm * this.usdPrice).toFixed(2);
                    },
                    computedTotalCost() {
                        return parseFloat(parseFloat(this.computedCOPPrice) + parseFloat(this.internationalShippingPrice) + parseFloat(this.nationalShippingPrice)).toFixed(2);
                    },
                    computedUtility() {
                        return parseFloat(this.salePrice - this.computedTotalCost).toFixed(2);
                    }
                },
                methods: {
                    formatCurrency(value) {
                        if (value !== undefined) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    async save() {
                        if (confirm("¿Confirma crear el producto?")) {
                            var fd = new FormData();
                            fd.append("Product", this.product);
                            fd.append("TRM", this.trm);
                            fd.append("USDPrice", this.usdPrice);
                            fd.append("COPPrice", this.computedCOPPrice);
                            fd.append("InternationalShippingPrice", this.internationalShippingPrice);
                            fd.append("NationalShippingPrice", this.nationalShippingPrice);
                            fd.append("TotalCost", this.computedTotalCost);
                            fd.append("SalePrice", this.salePrice);
                            fd.append("Utility", this.computedUtility);

                            this.$http.post(URL_API + "Inventory/Create.php", fd).then(function(response) {
                                document.location.href = URL_PLATFORM + "Inventory/";
                            });
                        }
                    }
                }
            });
        </script>
    </body>
</html>
