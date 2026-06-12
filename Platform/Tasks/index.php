<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Agenda")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $sessionUser = CloudEngineSession::getSessionObject()->getObject();
    
    $month = CloudEngineHTTP::getPostVar("Month");
    $year = CloudEngineHTTP::getPostVar("Year");
    
    if ($month == null && $year == null) {
        $month = date("m");
        $year = date("Y");
    }
    
    $daysOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    $layout = new Layout();
    $layout->setTitle("Agenda");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <input type="hidden" id="hdCurrentDate" value="<?php echo $year . "-" . $month . "-" . date("d"); ?>" />
        <div class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Agenda", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x canvas-height overflow-auto">
                <div class="width-100 padding-3x background-color-white border-radius box-shadow height-100">
                    <!-- Query -->
                    <div id="frmQuery" class="display-table width-100 text-align-center padding-bottom-2x">
                        <?php
                            $slMonth = new CloudEngineHTMLSelect();
                            $slMonth->addPropertie("style", "width: 100px; background: transparent; border: none");
                            $slMonth->addPropertie("id", "slMonth");
                            $slMonth->addOption("Enero", "01");
                            $slMonth->addOption("Febrero", "02");
                            $slMonth->addOption("Marzo", "03");
                            $slMonth->addOption("Abril", "04");
                            $slMonth->addOption("Mayo", "05");
                            $slMonth->addOption("Junio", "06");
                            $slMonth->addOption("Julio", "07");
                            $slMonth->addOption("Agosto", "08");
                            $slMonth->addOption("Septiembre", "09");
                            $slMonth->addOption("Octubre", "10");
                            $slMonth->addOption("Noviembre", "11");
                            $slMonth->addOption("Diciembre", "12");
                            $slMonth->setSelected($month);
                            $slMonth->render();
                            
                            $slYear = new CloudEngineHTMLSelect();
                            $slYear->addPropertie("class", "margin-left-2x");
                            $slYear->addPropertie("style", "width: 100px; background: transparent; border: none");
                            $slYear->addPropertie("id", "slYear");
                            for ($i=2018; $i<=2050; $i++) {
                                $slYear->addOption($i, $i);
                            }
                            $slYear->setSelected($year);
                            $slYear->render();
                        ?>
                        <button id="btnQuery" class="margin-left-2x button-white">CONSULTAR</button>
                        <a href="Create.php" class="margin-left-2x button-white text-decoration-none">CREAR</a>
                        <!--<div id="btnPrevious" class="button-white display-inline-block float-left"><i class="fa fa-arrow-left"></i></div>
                        <div id="btnNext" class="button-white display-inline-block float-right"><i class="fa fa-arrow-right"></i></div>-->
                    </div>
                    <!-- Days -->
                    <div id="cardsContainer" style="height: calc(100% - 50px); overflow-y: auto" class="width-100">
                        <?php
                            $code = "";
                            $whiteSpaces = 0;
                            $firstDateTime = DateTime::createFromFormat("Y-m-d", $year . "-" . $month . "-01");
                            $firstDayName = $firstDateTime->format("l");
                            
                            // Print days headers
                            $code .= "<div class='float-left width-20' style='padding:1px'><div class='padding text-align-center text-weight-bold text-size-m' style='background-color:#FFEB3B'>Lunes</div></div>";
                            $code .= "<div class='float-left width-20' style='padding:1px'><div class='padding text-align-center text-weight-bold text-size-m' style='background-color:#FFEB3B'>Martes</div></div>";
                            $code .= "<div class='float-left width-20' style='padding:1px'><div class='padding text-align-center text-weight-bold text-size-m' style='background-color:#FFEB3B'>Miércoles</div></div>";
                            $code .= "<div class='float-left width-20' style='padding:1px'><div class='padding text-align-center text-weight-bold text-size-m' style='background-color:#FFEB3B'>Jueves</div></div>";
                            $code .= "<div class='float-left width-20' style='padding:1px'><div class='padding text-align-center text-weight-bold text-size-m' style='background-color:#FFEB3B'>Viernes</div></div>";
                            
                            switch ($firstDayName) {
                                case "Tuesday":
                                    $whiteSpaces = 1;
                                    break;
                                case "Wednesday":
                                    $whiteSpaces = 2;
                                    break;
                                case "Thursday":
                                    $whiteSpaces = 3;
                                    break;
                                case "Friday":
                                    $whiteSpaces = 4;
                                    break;
                            }
                            
                            for ($i=0; $i<$whiteSpaces; $i++) {
                                $code .= "<div class='float-left width-20'>&nbsp</div>";
                            }
                            
                            echo $code;
                            
                            for ($i=1; $i<=$daysOfMonth; $i++) {
                                $todayStyle = "";
                                $today = date("Y-m-d");
                                $dateTime = DateTime::createFromFormat("Y-m-d", $year . "-" . $month . "-" . $i);
                                $dayName = $dateTime->format("l");
                                $completed = 0;
                                $pending = 0;
                                
                                if ($dayName != "Saturday" && $dayName != "Sunday") {
                                    $tasks = TaskDAO::getTasksByDate($year . "-" . $month . "-" . $i);

                                    $code = "";
                                    
                                    if ($today == $dateTime->format("Y-m-d")) {
                                        $todayStyle = "border: 5px solid orange";
                                    }
                                    
                                    $code .= "<div class='float-left width-20' style='padding:1px; height:calc(20% - 7px); " . $todayStyle . "'>";
                                    $code .= "<div name='dayCard' class='cursor-pointer' data-date='" . $year . "-" . $month . "-" . ($i > 9 ? $i : '0' . $i) . "' style='height:100%; background-color:#fffacf;'>";
                                    $code .= "<div class='text-weight-bold text-size-m padding text-align-right' style='background-color:'>" . $i . "</div>";

                                    foreach ($tasks as $t) {
                                        if ($t->getStatus() == Task::STATUS_PENDING) {
                                            $pending ++;
                                        } else {
                                            $completed ++;
                                        }
                                    }

                                    if ($completed > 0 || $pending > 0) {
                                        if ($completed > 0) {
                                            $code .= "<div class='padding'><div class='padding border-radius text-size-xs text-weight-bold text-color-white background-color-green'>Completadas: " . $completed . "</div></div>";
                                        }
                                        if ($pending > 0) {
                                            $code .= "<div class='padding'><div class='padding border-radius text-size-xs text-weight-bold text-color-white background-color-yellow'>Pendientes: " . $pending . "</div></div>";
                                        }
                                    }
                                    
                                    $code .= "</div>";
                                    $code .= "</div>";
                                    echo $code;
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            $("#btnQuery").on("click", function() {
                $.redirect("index.php", {Year: $("#slYear").val(), Month: $("#slMonth").val()});
            });
            
            $("[name=dayCard]").on("click", function() {
                $.redirect("List.php", {Date: $(this).data("date")});
            });
        </script>
    </body>
</html>