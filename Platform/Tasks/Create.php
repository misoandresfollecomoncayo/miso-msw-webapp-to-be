<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Agenda")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Crear tarea");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Crear tarea", PUBLIC_PATH_PLATFORM . "Tasks/"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmTask" class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Title -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Título</div>
                        <div class="float-left width-75"><input autofocus="on" class="input-text-underline" type="text" data-required="true" data-name="Título" id="txtTitle" /></div>
                    </div>
                    <!-- Description -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Descripción</div>
                        <div class="float-left width-75">
                            <textarea style="height: 150px" class="input-text-underline" data-required="true" data-name="Descripción" id="txtDescription"></textarea>
                        </div>
                    </div>
                    <!-- Priority -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Prioridad</div>
                        <div class="float-left width-75">
                            <?php
                                $slPriority = new CloudEngineHTMLSelect();
                                $slPriority->addPropertie("class", "select-underline");
                                $slPriority->addPropertie("id", "slPriority");
                                $slPriority->addPropertie("data-required", "true");
                                $slPriority->addPropertie("data-name", "Prioridad");
                                $slPriority->addOption("Seleccione una opción", "");
                                $slPriority->addOption("Normal", "0");
                                $slPriority->addOption("Alta", "1");
                                $slPriority->render();
                            ?>
                        </div>
                    </div>
                    <!-- Country -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">País</div>
                        <div class="float-left width-75">
                            <?php
                                $countries = CountryDAO::getCountries();
                                $slCountry = new CloudEngineHTMLSelect();
                                $slCountry->addPropertie("class", "select-underline");
                                $slCountry->addPropertie("id", "slCountry");
                                $slCountry->addPropertie("data-required", "true");
                                $slCountry->addPropertie("data-name", "Prioridad");
                                $slCountry->addOption("Seleccione una opción", "");
                                foreach ($countries as $c) {
                                    $slCountry->addOption($c->getName(), $c->getIdCountry());
                                }
                                $slCountry->render();
                            ?>
                        </div>
                    </div>
                    <!-- Warehouse -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Bodega</div>
                        <div class="float-left width-75">
                            <?php
                                $warehouses = WarehouseDAO::getWarehouses();
                                $slWarehouse = new CloudEngineHTMLSelect();
                                $slWarehouse->addPropertie("class", "select-underline");
                                $slWarehouse->addPropertie("id", "slWarehouse");
                                $slWarehouse->addPropertie("data-required", "true");
                                $slWarehouse->addPropertie("data-name", "Bodega");
                                $slWarehouse->addOption("Seleccione una opción", "");
                                foreach ($warehouses as $w) {
                                    $slWarehouse->addOption($w->getName(), $w->getIdWarehouse());
                                }
                                $slWarehouse->render();
                            ?>
                        </div>
                    </div>
                    <!-- Date -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Fecha</div>
                        <div class="float-left width-75"><input class="input-text-underline" type="date" data-required="true" data-name="Fecha" id="txtDate" /></div>
                    </div>
                    <!-- Actions -->
                    <div class="display-table padding-top-4x width-100 text-align-right">
                        <button id="btnSave" class="button-red">CREAR</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            $("#btnSave").on("click", function(e) {
                var frmTask = new Form($("#frmTask"));
                if (frmTask.validate()) {
                    $.ajax({
                        url: URL_API + "Task/Create.php",
                        type: "POST",
                        data: {
                            Title: $("#txtTitle").val(),
                            Description: $("#txtDescription").val(),
                            Priority: $("#slPriority").val(),
                            IdCountry: $("#slCountry").val(),
                            IdWarehouse: $("#slWarehouse").val(),
                            Date: $("#txtDate").val()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            document.location.href = "index.php";
                        }
                    });
                }
            });
        </script>
    </body>
</html>