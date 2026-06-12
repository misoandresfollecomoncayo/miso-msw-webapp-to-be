<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }
    
    $selectedProject = ProjectDAO::getProjectById(CloudEngineHTTP::getPostVar("ProjectId"));
    $modules = $selectedProject->getModules();
    $actors = $selectedProject->getActors();
?>
<div id="lyrBack" class="black-overlay" style="display: block !important">
    <div class="overflow-auto height-100 padding-6x mobile-padding-3x">
        <div id="frmAddRequirement" class="cursor-default background-color-white width-40 float-center padding-5x box-shadow border-radius">
            <!-- Description -->
            <div>
                <div class="text-weight-bold margin-bottom">Descripción</div>
                <textarea autofocus="true" data-required="true" data-name="Descripción" id="txtDescription" placeholder="Digite la descripción" class="input-text-underline" />
            </div>
            <!-- Module -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Módulo</div>
                <select data-required="true" data-name="Módulo" id="lsModule" class="select-underline">
                    <?php
                        foreach ($modules as $m) {
                            echo '<option value="' . $m->getIdProjectModule() . '">' . $m->getName() . '</option>';
                        }
                    ?>
                </select>
            </div>
            <!-- Actor -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Actor solicitó requerimiento</div>
                <select data-name="Actor" id="lsActor" class="select-underline">
                    <option value="">Ingeniero de requerimientos</option>
                    <?php
                        foreach ($actors as $a) {
                            echo '<option value="' . $a->getIdProjectActor() . '">' . $a->getNames() . '</option>';
                        }
                    ?>
                </select>
            </div>
            <!-- Priority -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Prioridad</div>
                <select data-required="true" data-name="Actor" id="lsPriority" class="select-underline">
                    <?php
                        $priorities = ["HIGH","MEDIUM","LOW"];
                        foreach ($priorities as $p) {
                            echo '<option value="' . $p . '">' . $p . '</option>';
                        }
                    ?>
                </select>
            </div>
            <!-- Complexity -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Complejidad</div>
                <select data-required="true" data-name="Complejidad" id="lsComplexity" class="select-underline">
                    <?php
                        $complexities = ["HIGH","MEDIUM","LOW"];
                        foreach ($complexities as $c) {
                            echo '<option value="' . $c . '">' . $c . '</option>';
                        }
                    ?>
                </select>
            </div>
            <!-- Start date -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Fecha inicio</div>
                <input type="date" data-required="true" data-name="Fecha inicio" id="txtStartDate" class="input-text-underline" />
            </div>
            <!-- End date -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Fecha finalización</div>
                <input type="date" data-required="true" data-name="Fecha finalización" id="txtEndDate" class="input-text-underline" />
            </div>
            <!-- Actions -->
            <div class="text-align-center margin-top-4x">
                <button id="btnAdd" class="button-blue margin-right">CREAR</button>
                <button id="btnCancel" class="button-white">CANCELAR</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#btnCancel").on("click", function() {
        removePopup();
    });
    
    $("#btnAdd").on("click", function() {
        var form = new Form($("#frmAddRequirement"));
        if (form.validate()) {
            $.ajax({
                url: URL_API + "ProjectRequirement/Add.php",
                type: "POST",
                data: {
                    Description: $("#txtDescription").val(),
                    IdProjectModule: $("#lsModule").val(),
                    IdProjectActor: $("#lsActor").val(),
                    Priority: $("#lsPriority").val(),
                    Complexity: $("#lsComplexity").val(),
                    StartDate: $("#txtStartDate").val(),
                    EndDate: $("#txtEndDate").val()
                },
                beforeSend: function() {
                    showPreload();
                },
                success: function(response) {
                    var r = JSON.parse(response);
                    if (r.type === "Exception") {
                        new Notification("ERROR", r.message);
                    } else {
                        document.location.reload();
                    }
                    closePreload();
                }
            });
        }
    });
</script>