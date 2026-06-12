<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (CloudEngineSession::getSessionObject() == null ||
        !CloudEngineSession::getSessionObject()->hasPermission(Permission::REQUIREMENTS)) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    $layout = new Layout();
    $layout->setTitle("Requerimientos de software");
    $layout->printHead();
    
    $projects = CloudEngineSession::getSessionObject()->getProjects();
    $selectedProject = ProjectDAO::getProjectById(CloudEngineHTTP::getGetVar("Id"));
?>
    <body>
        <input type="hidden" value="<?php echo null != $selectedProject ? $selectedProject->getIdProject() : "-1" ?>" id="hdSelectedProjectId" />
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Requerimientos de software", PUBLIC_PATH_PLATFORM . "Views/Transversal/Dashboard.php"); ?>
            <div class="width-20 background-color-studio padding-3x float-left canvas-height">
                <?php
                    foreach ($projects as $p) {
                        if ($selectedProject != null && $selectedProject->getIdProject() == $p->getIdProject()) {
                            echo '<div class="width-100 padding-2x display-table border-radius background-darken">';
                            echo '<div class="margin-bottom-2x text-color-white text-weight-bold">' . $p->getName() . '</div>';
                            echo '<div class="width-100; background-color-white project-completed-percent-bar-height border-radius">';
                            echo '<div class="background-color-green project-completed-percent-bar-height border-radius" style="width:' . $p->getCompletedPercent() . '%"></div></div>';
                            echo '<div class="margin-top"><div class="text-color-white text-size-xs float-left text-weight-bold">' . count($p->getCompletedRequirements()) . '/' . count($p->getRequirementsByPriority()) . '</div><div class="float-right text-color-white text-weight-bold text-size-xs">' . $p->getCompletedPercent() . '%</div></div>';
                            echo '</div>';
                        } else {
                            echo '<a href="?Id=' . $p->getIdProject() . '" class="text-decoration-none on-hover-darken width-100 cursor-pointer padding-2x display-table border-radius text-color-white">' . $p->getName() . '</a>';
                        }
                    }
                ?>
            </div>
            <div class="width-80 float-left overflow-auto canvas-height padding-5x">
                <div class="display-table width-100 margin-bottom-4x">
                    <div class="float-right">
                        <button class="button-blue" id="btnAddRequirement">AGREGAR REQUERIMIENTO</button>
                    </div>
                </div>
                <div class="width-100 background-color-white border-radius padding-4x box-shadow">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Fecha creado</th>
                                <th>Módulo</th>
                                <th>Descripción</th>
                                <th>Prioridad</th>
                                <th>Complejidad</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($selectedProject != null) {
                                    $counter = 1;
                                    $requirements = $selectedProject->getRequirementsByPriority();
                                    foreach ($requirements as $r) {
                                        echo '<tr>';
                                        echo '<td class="text-align-center">' . $counter . '</td>';
                                        echo '<td class="text-align-center">' . $r->getCreatedTracking()->getTimestamp() . '</td>';
                                        echo '<td>' . $r->getProjectModule()->getName() . '</td>';
                                        echo '<td>' . $r->getDescription() . '</td>';
                                        echo '<td class="text-align-center"><div class="padding text-size-xs border-radius text-color-white text-weight-bold ' . $r->getPriorityColor() . '">' . $r->getPriority() . '</div></td>';
                                        echo '<td class="text-align-center"><div class="padding text-size-xs border-radius text-color-white text-weight-bold ' . $r->getComplexityColor() . '">' . $r->getComplexity() . '</div></td>';
                                        echo '<td class="text-align-center"><div class="padding text-size-xs border-radius text-color-white text-weight-bold ' . $r->getStateColor() . '">' . $r->getState() . '</div></td>';
                                        echo '<td class="text-align-center">';
                                        echo '<div name="btnDetailRequirement" data-id="' . $r->getIdProjectRequirement() . '" class="font-color-blue cursor-pointer">Detalle</div>';
                                        if ($r->getState() != ProjectRequirement::STATE_COMPLETED) {
                                            echo '<div name="btnCompleteRequirement" data-id="' . $r->getIdProjectRequirement() . '" class="font-color-blue cursor-pointer">Implementado</div>';
                                            echo '<div name="btnEditRequirement" data-id="' . $r->getIdProjectRequirement() . '" class="font-color-blue cursor-pointer">Editar</div>';
                                            echo '<div name="btnDeleteRequirement" data-id="' . $r->getIdProjectRequirement() . '" class="font-color-blue cursor-pointer">Eliminar</div>';
                                        }
                                        echo '</td></tr>';
                                        $counter++;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnAddRequirement").on("click", function() {
                var selectedProjectId = $("#hdSelectedProjectId").val();
                if ("-1" === selectedProjectId) {
                    alert("Debe seleccionar un proyecto.");
                } else {
                    showPopup(URL_PLATFORM + "Views/Requirements/Add.php", { ProjectId: selectedProjectId });
                }
            });
            
            $("[name=btnDetailRequirement]").on("click", function(e) {
                var requirementId = $(e.target).data("id");
                showPopup(URL_PLATFORM + "Views/Requirements/Detail.php", { RequirementId: requirementId });
            });
            
            $("[name=btnCompleteRequirement]").on("click", function(e) {
                var requirementId = $(e.target).data("id");
                if (confirm("¿Confirma requerimiento realizado?")) {
                    $.ajax({
                        url: URL_API + "ProjectRequirement/Complete.php",
                        type: "POST",
                        data: {
                            IdProjectRequirement: requirementId
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            document.location.reload();
                        }
                    });
                }
            });
            
            $("[name=btnEditRequirement]").on("click", function(e) {
                var requirementId = $(e.target).data("id");
                showPopup(URL_PLATFORM + "Views/Requirements/Edit.php", { RequirementId: requirementId });
            });
            
            $("[name=btnDeleteRequirement]").on("click", function(e) {
                var requirementId = $(e.target).data("id");
                if (confirm("¿Confirma eliminar el requerimiento?")) {
                    $.ajax({
                        url: URL_API + "ProjectRequirement/Delete.php",
                        type: "POST",
                        data: {
                            IdProjectRequirement: requirementId
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            document.location.reload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>