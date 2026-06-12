<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $sessionUser = CloudEngineSession::getSessionObject();
?>
<div id='inventorySalesReportPopup' class="popup" style="display: flex; flex-direction: column; gap: 20px">
    <div style="font-size: 20px; font-weight: bold">Generar reporte de ventas: <?php echo $_REQUEST["SellingCompany"] ?></div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center">
        <div style="font-weight: bold">Fecha inicial:</div>
        <input v-model='startDate' type='date' class='input-text-underline' />
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center">
        <div style="font-weight: bold">Fecha final:</div>
        <input v-model='endDate' type='date' class='input-text-underline' />
    </div>
    <div style="display: flex; justify-content: flex-end; gap: 10px">
        <button v-on:click="generate" :disabled='startDate === "" || endDate === ""' class='button-green-excel'>REPORTE INVENTARIO</button>
        <button v-on:click="generatePaymentsReport" :disabled='startDate === "" || endDate === ""' class='button-green-excel'>REPORTE PAGOS</button>
        <?php
            /*if ($sessionUser->getObject()->getRole()->getName() == "Administrador") {
                echo ("<button v-on:click=\"generatePaymentsReport\" :disabled='startDate === \"\" || endDate === \"\"' class='button-green-excel'>REPORTE PAGOS</button>");
            }*/
        ?>
    </div>
</div>
<script type="text/javascript">
    var inventorySalesReportPopupApp = new Vue({
        el: "#inventorySalesReportPopup",
        data: {
            sellingCompany: "<?php echo $_REQUEST["SellingCompany"] ?>",
            startDate: "",
            endDate: ""
        },
        methods: {
            generate() {
                let filters = new Array();
                filters.push(this.sellingCompany);
                filters.push(this.startDate);
                filters.push(this.endDate);
                
                $.ajax({
                    url: URL_API + "Reports/CheckDownload.php",
                    type: "POST",
                    data: {
                        IdReport: 9,
                        Filters: JSON.stringify(filters)
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function(response) {
                        var r = JSON.parse(response);
                        if (r.type === "Exception") {
                            new Notification("ERROR", r.message);
                        } else {
                            new Notification("SUCCESS", r.body);
                            $.redirect(URL_API + "Reports/DownloadExcel.php", {IdReport: 9, Filters: JSON.stringify(filters)}, "POST", "_SELF");
                        }
                        closePreload();
                    }
                });
            },
            generatePaymentsReport() {
                let filters = new Array();
                filters.push(this.startDate);
                filters.push(this.endDate);
                filters.push(this.sellingCompany);
                
                $.ajax({
                    url: URL_API + "Reports/CheckDownload.php",
                    type: "POST",
                    data: {
                        IdReport: 11,
                        Filters: JSON.stringify(filters)
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function(response) {
                        var r = JSON.parse(response);
                        if (r.type === "Exception") {
                            new Notification("ERROR", r.message);
                        } else {
                            new Notification("SUCCESS", r.body);
                            $.redirect(URL_API + "Reports/DownloadExcel.php", {IdReport: 11, Filters: JSON.stringify(filters)}, "POST", "_SELF");
                        }
                        closePreload();
                    }
                });
            }
        }
    });
</script>