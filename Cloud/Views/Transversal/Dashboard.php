<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (CloudEngineSession::getSessionObject() == null ||
        !CloudEngineSession::getSessionObject()->hasPermission(Permission::DASHBOARD)) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    $sessionUser = CloudEngineSession::getSessionObject();
    
    $layout = new Layout();
    $layout->setTitle("Dashboard");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Dashboard"); ?>
            <div class="padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                <!-- Content -->
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
    </body>
</html>