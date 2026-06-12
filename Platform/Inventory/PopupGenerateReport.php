<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
?>
<div id='inventoryReportPopup' class="popup" style="display: flex; flex-direction: column; gap: 20px">
    <div style="font-size: 20px; font-weight: bold">Generar reporte de inventario</div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center">
        <div style="font-weight: bold">Fecha inicial:</div>
        <input v-model='startDate' type='date' class='input-text-underline' />
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-items: center">
        <div style="font-weight: bold">Fecha final:</div>
        <input v-model='endDate' type='date' class='input-text-underline' />
    </div>
    <div style="display: flex; justify-content: flex-end">
        <button v-on:click="generate" :disabled='startDate === "" || endDate === ""' class='button-green-excel'>GENERAR</button>
    </div>
</div>
<script type="text/javascript">
    var inventoryReportPopupApp = new Vue({
        el: "#inventoryReportPopup",
        data: {
            startDate: "",
            endDate: ""
        },
        methods: {
            generate() {
                let filters = new Array();
                filters.push(this.startDate);
                filters.push(this.endDate);
                
                $.ajax({
                    url: URL_API + "Reports/CheckDownload.php",
                    type: "POST",
                    data: {
                        IdReport: 10,
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
                            $.redirect(URL_API + "Reports/DownloadExcel.php", {IdReport: 10, Filters: JSON.stringify(filters)}, "POST", "_SELF");
                        }
                        closePreload();
                    }
                });
            }
        }
    });
</script>