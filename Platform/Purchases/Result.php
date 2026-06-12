<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Mercancía")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $results = json_decode(CloudEngineHTTP::getPostVar("Result"));
    
    if (count($results) == 0) {
        header("location:index.php");
    }
    
    $layout = new Layout();
    $layout->setTitle("Resultado recepción de mercancía");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Resultado recepción de mercancía", PUBLIC_PATH_PLATFORM . "Purchases/"); ?>
            <div class="padding-5x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 display-table margin-bottom-4x text-align-right">
                    <a href="Create.php" class="button-red display-inline-block text-decoration-none">CARGAR MÁS</a>
                </div>
                <!-- Table -->
                <div class="width-100 padding-4x background-color-white border-radius box-shadow display-table">
                    <table class="table stripe">
                        <thead>
                            <tr>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($results as $r) {
                                    $code = '<tr>';
                                    $code .= '<td style="white-space: nowrap">' . $r . '</td>';
                                    $code .= '</tr>';
                                    echo $code;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
