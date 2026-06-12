<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $invoice = InventoryInvoiceDAO::getById($_REQUEST["Id"]);
    $items = $invoice->getPayments();
?>
<div id='inventoryAddPaymentPopup' class="popup" style="display: flex; flex-direction: column; gap: 20px">
    <div style="font-size: 20px; font-weight: bold">Pagos: <?php echo $invoice->fullInvoiceCode; ?></div>
    
    <table class='dataTable stripe'>
        <thead>
            <tr>
                <th>Fecha real</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Monto</th>
                <th>Método</th>
            </tr>
        </thead>
        <tbody>
            <?php                
                $counter = 0;
                foreach($items as $i) {
                    $method = $i->getPaymentMethod();
                    $code = "<tr class='" . ($counter % 2 == 0 ? "even" : "odd") . "'>";
                    $code .= "<td>" . $i->createdTimestamp . "</td>";
                    $code .= "<td>" . $i->date . "</td>";
                    $code .= "<td>" . $i->user . "</td>";
                    $code .= "<td style='text-align:center'>$ " . number_format($i->amount, 2) . "</td>";
                    $code .= "<td>" . ($method != null ? $method->name : '') . "</td>";
                    $code .= "<tr>";
                    echo $code;
                    $counter++;
                }
            ?>
        </tbody>
    </table>
    
    <div style="display: flex; flex-direction: column; gap: 10px">
        <div style="font-weight: bold">Nuevo pago:</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px">
            <div>
                <div style="font-weight: bold">Fecha:</div>
                <?php
                    if (CloudEngineSession::getSessionObject()->getObject()->getRole()->getName() == "Administrador") {
                        echo '<input type="date" v-model="date" placeholder="Fecha" class="input-text-underline" />';
                    } else {
                        echo '<div>{{ date }}</div>';
                    }

                ?>
            </div>
            <div>
            <div style="font-weight: bold">Monto:</div>
                <input type="number" v-model='amount' placeholder="Digite el monto" class='input-text-underline' />
            </div>
            <div>
                <div style="font-weight: bold">Método de pago:</div>
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
            <button v-on:click="save" :disabled='date === "" || amount === 0 || amount === "" || idPaymentMethod === ""' class='button-green-excel'>GUARDAR</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    var inventoryAddTrackingPopupApp = new Vue({
        el: "#inventoryAddPaymentPopup",
        data() {
            return {
                date: "",
                amount: 0,
                idPaymentMethod: ""
            }
        },
        mounted() {
            this.date = this.computedGetCurrentDate;
        },
        computed: {
            computedGetCurrentDate() {
                let currentDate = new Date();
                let year = currentDate.toLocaleString("default", {year: "numeric"});
                let month = currentDate.toLocaleString("default", {month: "2-digit"});
                let day = currentDate.toLocaleString("default", {day: "2-digit"});

                return year + "-" + month + "-" + day;
            }
        },
        methods: {
            save() {
                if (confirm("¿Confirma registrar el pago?")) {
                    showPreload();
                    var fd = new FormData();
                    fd.append("Id", "<?php echo $invoice->id; ?>");
                    fd.append("Date", this.date);
                    fd.append("Amount", this.amount);
                    fd.append("IdPaymentMethod", this.idPaymentMethod);
                    this.$http.post(URL_API + "InventoryInvoice/Pay.php", fd).then(function(response){
                        let responseJson = response.body;

                        if (responseJson.type === "Exception") {
                            new Notification("ERROR",responseJson.message);
                        } else {
                            document.location.reload();
                        }
                        closePreload();
                    });
                }
            }
        }
    });
</script>