<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $sessionUser = CloudEngineSession::getSessionObject();
    
    if (null == $sessionUser ||
        !$sessionUser->hasPermission(Permission::PROGRESS_VIEWER)) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("Visor de progreso");
    $layout->printHead();
    
    $projects = CloudEngineSession::getSessionObject()->getProjects();
    $selectedProject = ProjectDAO::getProjectById(CloudEngineHTTP::getGetVar("Id"));
?>
    <body>
        <input type="hidden" value="<?php echo null != $selectedProject ? $selectedProject->getIdProject() : "-1" ?>" id="hdSelectedProjectId" />
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Visor de progreso", "Views/Transversal/Dashboard.php"); ?>
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
            <div class="width-80 float-left canvas-height padding-5x" style="overflow: auto">
                <?php
                    if ($selectedProject != null) {
                        $datesRange = $selectedProject->getRequirementsDatesRange();
                        $projectRequirements = $selectedProject->getRequirementsByDateAsc();

                        echo '<table class="projects-viewer-table display-inline-block">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Requerimiento</th>';
                        echo '<th>Módulo</th>';
                        echo '<th>Prioridad</th>';
                        echo '<th>Complejidad</th>';

                        if ($datesRange != null) {                            
                            foreach ($datesRange as $key => $value) {
                                echo '<th>' . $value->format("d M Y") . '</th>';
                            }
                        }

                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($projectRequirements as $r) {                            
                            echo '<tr>';
                            echo '<td name="requirement" class="cursor-pointer" data-id="' . $r->getIdProjectRequirement() . '" style="border-left: 3px solid ' . $r->getStateColorHex() . ';">' . substr($r->getDescription(),0,50) . '</td>';
                            echo '<td class="text-align-center">' . $r->getProjectModule()->getName() . '</td>';
                            echo '<td class="text-align-center"><div class="padding text-size-xs border-radius text-color-white text-weight-bold ' . $r->getPriorityColor() . '">' . $r->getPriority() . '</div></td>';
                            echo '<td class="text-align-center"><div class="padding text-size-xs border-radius text-color-white text-weight-bold ' . $r->getComplexityColor() . '">' . $r->getComplexity() . '</div></td>';
                            
                            if ($datesRange != null) {
                                foreach ($datesRange as $key => $value) {
                                    $style = "";
                                    
                                    if ($r->getStartDate() <= $value &&
                                        $r->getEndDate()->modify("+1 day") >= $value) {
                                        
                                        if ($r->getState() == ProjectRequirement::STATE_COMPLETED) {
                                            $style = "projects-viewer-table-completed-field";
                                        } else if ($r->getState() == ProjectRequirement::STATE_PROGRESS) {
                                            $style = "projects-viewer-table-progress-field";
                                        } else {
                                            $currentDate = new DateTime();
                                            if ($r->getEndDate()->format("Y-m-d") < $currentDate->format("Y-m-d")) {
                                                $style = "projects-viewer-table-due-field";
                                            } else {
                                                $style = "projects-viewer-table-pending-field";
                                            }
                                        }
                                        
                                    }
                                    
                                    echo '<td class="' . $style . '"></td>';
                                }
                            }
                            
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                    }
                ?>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("[name=requirement]").on("click", function(e) {
                var id = $(e.target).data("id");
                showPopup(URL_PLATFORM + "Views/Requirements/Detail.php", { RequirementId: id });
            });
        </script>
    </body>
</html>