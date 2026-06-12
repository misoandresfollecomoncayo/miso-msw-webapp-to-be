<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }
    
    $requirement = ProjectRequirementDAO::getRequirementById(CloudEngineHTTP::getPostVar("RequirementId"));
    $module = ProjectModuleDAO::getProjectModuleById($requirement->getIdProjectModule());
    $project = ProjectDAO::getProjectById($module->getIdProject());
?>
<div id="lyrBack" class="black-overlay" style="display: block !important">
    <div class="overflow-auto height-100 padding-6x mobile-padding-3x">
        <input type="hidden" value="<?php echo $requirement->getIdProjectRequirement(); ?>" id="hdRequirementId" />
        <div id="frmEditRequirement" class="cursor-default background-color-white width-40 float-center padding-5x box-shadow border-radius">
            <!-- Description -->
            <div>
                <div class="text-weight-bold margin-bottom ">Descripción</div>
                <textarea autofocus="true" data-required="true" data-name="Descripción" id="txtDescription" placeholder="Digite la descripción" class="input-text-underline"><?php echo $requirement->getDescription(); ?></textarea>
            </div>
            <!-- Modules -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Módulo</div>
                <select data-required="true" data-name="Módulo" id="lsModule" class="input-text-underline">
                    <?php
                        $modules = $project->getModules();
                        foreach ($modules as $m) {
                            if ($m->getIdProjectModule() == $module->getIdProjectModule()) {
                                echo '<option selected value="' . $m->getIdProjectModule() . '">' . $m->getName() . '</option>';
                            } else {
                                echo '<option value="' . $m->getIdProjectModule() . '">' . $m->getName() . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <!-- State -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Estado</div>
                <select data-required="true" data-name="Estado" id="lsState" class="input-text-underline">
                    <?php
                        $states = ["PENDING", "PROGRESS"];
                        foreach ($states as $s) {
                            if ($requirement->getState() == $s) {
                                echo '<option selected value="' . $s . '">' . $s . '</option>';
                            } else {
                                echo '<option value="' . $s . '">' . $s . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <!-- Actor -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Actor del sistema</div>
                <select data-required="true" data-name="Actor" id="lsActor" class="input-text-underline">
                    <?php
                        $actors = $project->getActors();
                        foreach ($actors as $a) {
                            if ($requirement->getProjectActor() == $a) {
                                echo '<option selected value="' . $a->getIdProjectActor() . '">' . $a->getNames() . '</option>';
                            } else {
                                echo '<option value="' . $a->getIdProjectActor() . '">' . $a->getNames() . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <!-- Priority -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Prioridad</div>
                <select data-required="true" data-name="Actor" id="lsPriority" class="input-text-underline">
                    <?php
                        $priorities = ["HIGH","MEDIUM","LOW"];
                        foreach ($priorities as $p) {
                            if ($requirement->getPriority() == $p) {
                                echo '<option selected value="' . $p . '">' . $p . '</option>';
                            } else {
                                echo '<option value="' . $p . '">' . $p . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <!-- Complexity -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Complejidad</div>
                <select data-required="true" data-name="Complejidad" id="lsComplexity" class="input-text-underline">
                    <?php
                        $complexities = ["HIGH","MEDIUM","LOW"];
                        foreach ($complexities as $c) {
                            if ($requirement->getComplexity() == $c) {
                                echo '<option selected value="' . $c . '">' . $c . '</option>';
                            } else {
                                echo '<option value="' . $c . '">' . $c . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <!-- Start date -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Fecha inicio</div>
                <input type="date" data-required="true" data-name="Fecha inicio" id="txtStartDate" class="input-text-underline" value="<?php echo $requirement->getStartDate()->format("Y-m-d"); ?>" />
            </div>
            <!-- End date -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom ">Fecha finalización</div>
                <input type="date" data-required="true" data-name="Fecha finalización" id="txtEndDate" class="input-text-underline" value="<?php echo $requirement->getEndDate()->format("Y-m-d"); ?>" />
            </div>
            <!-- Actions -->
            <div class="text-align-center margin-top-4x">
                <button id="btnEdit" class="button-blue margin-right">GUARDAR</button>
                <button id="btnCancel" class="button-white">CANCELAR</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#btnCancel").on("click", function() {
        removePopup();
    });
    
    $("#btnEdit").on("click", function() {
        var form = new Form($("#frmEditRequirement"));
        if (form.validate()) {
            $.ajax({
                url: URL_API + "ProjectRequirement/Edit.php",
                type: "POST",
                data: {
                    IdProjectRequirement: $("#hdRequirementId").val(),
                    Description: $("#txtDescription").val(),
                    IdProjectModule: $("#lsModule").val(),
                    State: $("#lsState").val(),
                    IdActor: $("#lsActor").val(),
                    Priority: $("#lsPriority").val(),
                    Complexity: $("#lsComplexity").val(),
                    StartDate: $("#txtStartDate").val(),
                    EndDate: $("#txtEndDate").val()
                },
                beforeSend: function() {
                    //mostrarPrecarga();
                },
                success: function(response) {
                    var r = JSON.parse(response);
                    if (r.type === "Exception") {
                        form.setResponse("EXCEPTION", r.message);
                    } else {
                        document.location.reload();
                    }
                }
            });
        }
    });
</script>