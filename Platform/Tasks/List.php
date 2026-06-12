<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\Utils\CloudEngineStrings;
    
    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Agenda")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $tasks = TaskDAO::getTasksByDate(CloudEngineHTTP::getPostVar("Date"));
    
    if (count($tasks) == 0) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Tasks");
    }
    
    $layout = new Layout();
    $layout->setTitle("Lista de tareas");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Lista de tareas", PUBLIC_PATH_PLATFORM . "Tasks/"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div class="text-align-center text-size-m text-weight-bold margin-bottom-3x"><?php echo CloudEngineStrings::timestampToShortHumanFormat(CloudEngineHTTP::getPostVar("Date")) ?></div>
                <?php
                    foreach ($tasks as $t) {
                        $code = '<div class="width-100 padding background-color-white border-radius box-shadow margin-bottom-2x display-table">';
                        
                        $code .= '<div class="width-20 float-left">';
                        $code .= "<button name='btnDelete' data-id='" . $t->getIdTask() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding:4px 8px !important'><i class='fa fa-trash'></i></button>";
                        
                        if ($t->getStatus() == Task::STATUS_PENDING) {
                            $code .= "<button name='btnProcess' data-id='" . $t->getIdTask() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding:4px 8px !important'>PENDIENTE</button>";
                            $code .= "<button name='btnEdit' data-id='" . $t->getIdTask() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding:4px 8px !important'>EDITAR</button>";
                        } else if ($t->getStatus() == Task::STATUS_PROCESS) {
                            $code .= "<button name='btnProcess' data-id='" . $t->getIdTask() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding:4px 8px !important; background: #2196f3 !important; color:white !important'>PROCESO</button>";
                            $code .= "<button name='btnEdit' data-id='" . $t->getIdTask() . "' class='mobile-margin-top-4x button-gray float-left margin-right' style='padding:4px 8px !important'>EDITAR</button>";
                        } else {
                            $code .= "<div class='mobile-margin-top-4x background-color-green float-left border-radius padding-2x text-weight-bold text-color-white margin-right' style='font-size:13px !important; padding:4px 8px !important'>REALIZADO</div>";
                        }
                        
                        $code .= '</div>';
                        
                        $code .= '<div class="width-80 float-left">';
                        $code .= '<div class="margin-bottom float-left width-100 text-size-m text-weight-bold" style="white-space:normal">' . ($t->getHighPriority() == Task::HIGH_PRIORITY ? "<i class='fa fa-star margin-right-2x' style='font-size:15px !important'></i>" : "") . " " . $t->getTitle() . '</div>';
                        $code .= '<div class="float-left width-100" style="white-space:normal"><b>Bodega: </b>' . ($t->getWarehouse() != null ? $t->getWarehouse()->getName() : "Sin información") . '</div>';
                        $code .= '<div class="float-left width-100" style="white-space:normal">' . $t->getDescription() . '</div>';
                        $code .= '<div class="float-left width-100 text-size-xs margin-top-2x" style="white-space:normal"><b class="text-size-xs">Usuario creó:</b> ' . ($t->getCreatorSystemUser() != null ? $t->getCreatorSystemUser()->getNames() : "Sin información") . '</div>';
                        $code .= '<div class="float-left width-100 text-size-xs" style="white-space:normal"><b class="text-size-xs">Usuario realizando:</b> ' . ($t->getProcessorSystemUser() != null ? $t->getProcessorSystemUser()->getNames() : "Sin información") . '</div>';
                        $code .= '<div class="float-left width-100 text-size-xs" style="white-space:normal"><b class="text-size-xs">Usuario completó:</b> ' . ($t->getCompletedSystemUser() != null ? $t->getCompletedSystemUser()->getNames() : "Sin información") . '</div>';
                        $code .= '</div>';
                        
                        $code .= '</div>';
                        echo $code;
                    }
                ?>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("[name=btnDelete]").on("click", function() {
                if (confirm("¿Confirma eliminar la tarea?")) {
                    $.ajax({
                        url: URL_API + "Task/Delete.php",
                        type: "POST",
                        data: {
                            IdTask: $(this).data("id")
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function() {
                            document.location.reload();
                        }
                    });
                }
            });
            
            $("[name=btnEdit]").on("click", function() {
                $.redirect("Edit.php", {IdTask: $(this).data("id")});
            });
            
            $("[name=btnProcess]").on("click", function() {
                $.ajax({
                    url: URL_API + "Task/Process.php",
                    type: "POST",
                    data: {
                        IdTask: $(this).data("id")
                    },
                    beforeSend: function() {
                        showPreload();
                    },
                    success: function() {
                        document.location.reload();
                    }
                });
            });
        </script>
    </body>
</html>