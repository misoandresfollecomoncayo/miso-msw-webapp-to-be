<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }
    
    $requirement = ProjectRequirementDAO::getRequirementById(CloudEngineHTTP::getPostVar("RequirementId"));
?>
<div id="lyrBack" class="black-overlay" style="display: block !important">
    <div class="overflow-auto height-100 padding-6x mobile-padding-3x">
        <div id="frmAddRequirement" class="cursor-default background-color-white width-60 float-center padding-5x box-shadow border-radius">
            <!-- Description -->
            <div>
                <div class="text-weight-bold margin-bottom">Descripción</div>
                <div class="margin-bottom font-size-m"><?php echo $requirement->getDescription(); ?></div>
            </div>
            <!-- Actor -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Actor solicitó requerimiento</div>
                <div class="margin-bottom font-size-m"><?php echo ($requirement->getProjectActor() != null ? $requirement->getProjectActor()->getNames() : "Ing. de requerimientos"); ?></div>
            </div>
            <!-- Completed timestamp -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Fecha completado</div>
                <div class="margin-bottom font-size-m"><?php echo $requirement->getCompletedTimestamp() ?></div>
            </div class="margin-top-3x">
            <!-- Completed user -->
            <div class="margin-top-3x">
                <div class="text-weight-bold margin-bottom">Implementado por</div>
                <div class="margin-bottom font-size-m"><?php echo ($requirement->getCompletedUser() != null ? $requirement->getCompletedUser()->getNames() : "") ?></div>
            </div>
            <!-- Tracking -->
            <table class="table margin-top-3x">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Acción</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $tracking = $requirement->getTracking();
                        foreach ($tracking as $t) {
                            echo "<tr>";
                            echo "<td class='text-align-center'>" . $t->getTimestamp() . "</td>";
                            echo "<td class='text-align-center'>" . $t->getAction() . "</td>";
                            echo "<td>" . $t->getUser()->getNames() . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <!-- Actions -->
            <div class="text-align-center margin-top-4x">
                <button id="btnCancel" class="button-white">CERRAR</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#btnCancel").on("click", function() {
        removePopup();
    });
</script>