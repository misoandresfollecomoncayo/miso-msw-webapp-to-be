<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (CloudEngineSession::getSessionObject() == null ||
        !CloudEngineSession::getSessionObject()->hasPermission(Permission::PROFILE)) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("Perfil");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Perfil"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <div id="frmProfile" class="width-100 padding-4x background-color-white border-radius box-shadow">
                    <!-- Names -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Nombres</div>
                        <div class="float-left width-75 label-underline"><?php echo CloudEngineSession::getSessionObject()->getNames(); ?></div>
                    </div>
                    <!-- Email -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Correo electrónico</div>
                        <div class="float-left width-75 label-underline"><?php echo CloudEngineSession::getSessionObject()->getEmail(); ?></div>
                    </div>
                    <!-- Role -->
                    <div class="display-table padding-top-3x padding-bottom-3x width-100">
                        <div class="float-left width-25 text-weight-bold">Rol</div>
                        <div class="float-left width-75 label-underline"><?php echo CloudEngineSession::getSessionObject()->getRole()->getName(); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
    </body>
</html>