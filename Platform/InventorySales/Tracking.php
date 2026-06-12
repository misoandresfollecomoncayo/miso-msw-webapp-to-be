<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
    $invoice = InventoryInvoiceDAO::getById($_REQUEST["Id"]);
    $items = $invoice->getTracking();
?>
<div id='inventoryAddTrackingPopup' class="popup" style="display: flex; flex-direction: column; gap: 20px">
    <div style="font-size: 20px; font-weight: bold">Trazabilidad: <?php echo $invoice->fullInvoiceCode; ?></div>
    
    <table class='dataTable stripe'>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Movimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $counter = 0;
                foreach($items as $i) {
                    $code = "<tr class='" . ($counter % 2 == 0 ? "even" : "odd") . "'>";
                    $code .= "<td>" . $i->createdTimestamp . "</td>";
                    $code .= "<td>" . $i->user . "</td>";
                    $code .= "<td>" . $i->detail . "</td>";
                    $code .= "<tr>";
                    echo $code;
                    $counter++;
                }
            ?>
        </tbody>
    </table>
    
    <div style="display: flex; flex-direction: column; gap: 10px">
        <div style="font-weight: bold">Nuevo movimiento:</div>
        <input v-model='date' type="date" placeholder="Fecha" class='input-text-underline' />        
        <input v-model='detail' placeholder="Digite el nuevo movimiento" class='input-text-underline' />
        <div style="display: flex; justify-content: flex-end">
            <button v-on:click="save" :disabled='detail === "" || date === ""' class='button-green-excel'>GUARDAR</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    var inventoryAddTrackingPopupApp = new Vue({
        el: "#inventoryAddTrackingPopup",
        data: {
            date: "",
            detail: ""
        },
        methods: {
            save() {
                if (confirm("¿Confirma registrar el movimiento?")) {
                    showPreload();
                    var fd = new FormData();
                    fd.append("Id", "<?php echo $invoice->id; ?>");
                    fd.append("Date", this.date);                
                    fd.append("Detail", this.detail);
                    this.$http.post(URL_API + "InventoryInvoice/Tracking.php", fd).then(function(response){
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