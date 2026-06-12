<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Reportes")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }
    
    $reports = ReportDAO::getReports();
    $selectedReport = ReportDAO::getReportById(CloudEngineHTTP::getPostVar("Id"));
    
    $layout = new Layout();
    $layout->setTitle("Reportes");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Reportes", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div id="frmQuery" class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <input type="hidden" value="<?php echo ($selectedReport != null) ? $selectedReport->getIdReport() : "" ?>" id="hdIdReport" />
                <div class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Query -->
                    <div class="display-table width-100">
                        <div class="float-left width-25 text-weight-bold">Reporte</div>
                        <div class="float-left width-75">
                            <?php
                                $slReport = new CloudEngineHTMLSelect();
                                $slReport->addPropertie("class", "select-underline");
                                $slReport->addPropertie("id", "lsReport");
                                $slReport->addPropertie("data-required", "true");
                                $slReport->addPropertie("data-name", "Reporte");
                                $slReport->addOption("Selecciona el reporte", "");
                                foreach ($reports as $r) {
                                    $slReport->addOption($r->getName(), $r->getIdReport());
                                }
                                if ($selectedReport != null) {
                                    $slReport->setSelected($selectedReport->getIdReport());
                                }
                                $slReport->render();
                            ?>
                        </div>
                    </div>
                    <?php
                        if ($selectedReport != null) {
                            $filters = $selectedReport->getFilters();
                            foreach ($filters as $f) {
                                $code = '<div class="display-table width-100 margin-top-2x">';
                                $code .= '<div class="float-left width-25 text-weight-bold">' . $f->getName() . '</div>';
                                $code .= '<div class="float-left width-75">';
                                
                                switch ($f->getDataType()) {
                                    case ReportFilter::DATA_TYPE_DATE:
                                        $code .= '<input class="input-text-underline" type="date" data-name="' . $f->getName() . '" data-required="' . $f->isRequired() . '" name="filter" id="' . $f->getName() . '" />';
                                        break;
                                    case ReportFilter::DATA_TYPE_CUSTOMER:
                                        $customers = CustomerDAO::getCustomers();
                                        $slFilter = new CloudEngineHTMLSelect();
                                        $slFilter->addPropertie("class", "select-underline");
                                        $slFilter->addPropertie("id", $f->getName());
                                        $slFilter->addPropertie("name", "filter");
                                        $slFilter->addPropertie("data-required", $f->isRequired());
                                        $slFilter->addPropertie("data-name", $f->getName());
                                        $slFilter->addOption("Todos", "");
                                        foreach ($customers as $c) {
                                            $slFilter->addOption($c->getNames(), $c->getIdCustomer());
                                        }
                                        $slFilter->setSelected("");
                                        $code .= $slFilter->getCode();
                                        break;
                                    case ReportFilter::DATA_TYPE_COUNTRY:
                                        $slFilter = new CloudEngineHTMLSelect();
                                        $slFilter->addPropertie("class", "select-underline");
                                        $slFilter->addPropertie("id", $f->getName());
                                        $slFilter->addPropertie("name", "filter");
                                        $slFilter->addPropertie("data-required", $f->isRequired());
                                        $slFilter->addPropertie("data-name", $f->getName());
                                        $slFilter->addOption("Todos", "");
                                        foreach (CountryDAO::getCountries() as $c) {
                                            $slFilter->addOption($c->getName(), $c->getIdCountry());
                                        }
                                        $slFilter->setSelected("");
                                        $code .= $slFilter->getCode();
                                        break;
                                    case ReportFilter::DATA_TYPE_CITY:
                                        $slFilter = new CloudEngineHTMLSelect();
                                        $slFilter->addPropertie("class", "select-underline");
                                        $slFilter->addPropertie("id", $f->getName());
                                        $slFilter->addPropertie("name", "filter");
                                        $slFilter->addPropertie("data-required", $f->isRequired());
                                        $slFilter->addPropertie("data-name", $f->getName());
                                        $slFilter->addOption("Todas", "");
                                        foreach (CityDAO::getCities() as $c) {
                                            $slFilter->addOption($c->getCountry()->getName() . " - " . $c->getName(), $c->getIdCity());
                                        }
                                        $slFilter->setSelected("");
                                        $code .= $slFilter->getCode();
                                        break;
                                }
                                
                                $code .= '</div>';
                                $code .= '</div>';
                                echo $code;
                            }
                        }
                    ?>
                </div>
                <!-- Actions -->
                <div class="width-100 margin-top-4x text-align-right">
                    <button id="btnDownloadExcel" class="button-green-excel"><i class="fa fa-file-excel-o text-color-white margin-right-2x"></i>DESCARGAR EXCEL</button>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            $("#btnDownloadExcel").on("click", function(e) {
                var frmQuery = new Form($("#frmQuery"));
                if (frmQuery.validate()) {
                    var filtersFields = $("[name=filter]");
                    var filters = new Array();
                    
                    for (var i=0; i<filtersFields.length; i++) {
                        filters.push($(filtersFields[i]).val());
                    }
                    
                    $.ajax({
                        url: URL_API + "Reports/CheckDownload.php",
                        type: "POST",
                        data: {
                            IdReport: $("#lsReport").val(),
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
                                $.redirect(URL_API + "Reports/DownloadExcel.php", {IdReport: $("#lsReport").val(), Filters: JSON.stringify(filters)}, "POST", "_SELF");
                            }
                            closePreload();
                        }
                    });
                }
            });
            
            $("#lsReport").on("change", function(e) {
                var idReport = $(e.target).val();
                if (idReport !== "") {
                    $.redirect("index.php", { Id : idReport }, "POST");
                }
            });
        </script>
    </body>
</html>
