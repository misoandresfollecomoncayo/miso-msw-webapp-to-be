<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:../" . PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php");
    }
    
    $layout = new Layout();
    $layout->setTitle(Internationalization::newCustomer());
    $layout->printHead();
?>
    <body class="background-color-light-gray padding-5x mobile-padding-3x display-table">
        <div class="width-20 float-left mobile-hide">&nbsp;</div>
        <div class="width-60 float-left background-color-white padding-5x border-radius box-shadow" id="frmNewCustomer">
            <!-- Logotype -->
            <div class="width-100 padding-5x margin-bottom-4x logotype" style="background-size: contain"></div>
            <div>
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="width-100 text-align-center text-size-m text-weight-bold"><?php echo Internationalization::lockerSuccessfullyCreatedLabel(); ?></div>
                    <div class="width-100 margin-top-4x text-align-center"><?php echo Internationalization::lockerSuccessfullyCreatedBodyLabel(); ?></div>
                </div>
                <!-- Actions -->
                <div class="text-align-center margin-top-5x">
                    <a href="../" class="button-white margin-right-2x text-decoration-none"><?php echo Internationalization::homeButton(); ?></a>
                </div>
            </div>
        </div>
    </body>
</html>