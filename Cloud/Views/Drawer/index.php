<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject()) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("Project Drawer");
    $layout->printHead();
    
    $projects = CloudEngineSession::getSessionObject()->getProjects();
    $selectedProject = ProjectDAO::getProjectById(CloudEngineHTTP::getGetVar("Id"));
?>
    <body>
        <input type="hidden" value="<?php echo null != $selectedProject ? $selectedProject->getIdProject() : "-1" ?>" id="hdSelectedProjectId" />
        <?php $layout->printMainBar(); ?>
        <div class="width-80 background-color-light-gray height-100 float-left canvas-width" style="overflow: hidden">
            <div class="width-20 padding-3x float-left height-100">
                <?php
                    foreach ($projects as $p) {
                        if ($selectedProject != null && $selectedProject->getIdProject() == $p->getIdProject()) {
                            echo '<div class="width-100 font-size-m padding-2x display-table border-radius background-color-gray font-color-white">';
                            echo '<div class="font-size-m margin-bottom-2x font-color-white font-weight-bold">' . $p->getName() . '</div>';
                            echo '<div class="width-100; background-color-white border-radius-complete project-completed-percent-bar-height">';
                            echo '<div class="project-completed-percent-bar-color project-completed-percent-bar-height border-radius-complete" style="width:' . $p->getCompletedPercent() . '%"></div></div>';
                            echo '<div class="font-size-s margin-top"><div class="font-color-white float-left font-weight-bold">' . count($p->getCompletedRequirements()) . '/' . count($p->getRequirementsByPriority()) . '</div><div class="float-right font-color-white font-weight-bold">' . $p->getCompletedPercent() . '%</div></div>';
                            echo '</div>';
                        } else {
                            echo '<a href="?Id=' . $p->getIdProject() . '" class="text-decoration-none on-hover-darkness width-100 cursor-pointer font-size-m padding-2x display-table border-radius">' . $p->getName() . '</a>';
                        }
                    }
                ?>
            </div>
            <div id="studioCanvas" class="width-80 background-color-studio float-left height-100"></div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            var stage = new Konva.Stage({
                container: 'studioCanvas',
                width: $("#studioCanvas").innerWidth(),
                height: $("#studioCanvas").innerHeight()
              });

              var layer = new Konva.Layer();

              var rect = new Konva.Rect({
                x: 50,
                y: 50,
                width: 100,
                height: 50,
                fill: 'yellow',
                draggable: true
              });

              // add the shape to the layer
              layer.add(rect);

              // add the layer to the stage
              stage.add(layer);
        </script>
    </body>
</html>