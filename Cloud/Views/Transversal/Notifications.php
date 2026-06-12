<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    $sessionUser = CloudEngineSession::getSessionObject();
    
    if (null == $sessionUser ||
        !$sessionUser->hasPermission(Permission::NOTIFICATIONS)) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("Notificaciones");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Notificaciones"); ?>
            <div class="fa-proje padding-top-5x padding-bottom-5x padding-left-7x padding-right-7x mobile-padding-3x canvas-height overflow-auto">
                
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
    </body>
</html>