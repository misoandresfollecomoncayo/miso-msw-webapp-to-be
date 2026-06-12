<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/CloudEngineAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineHTTP;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null == CloudEngineSession::getSessionObject()) {
        header("location:index.php");
    }

    $layout = new Layout();
    $layout->setTitle("CSS Compiler");
    $layout->printHead();
    
    $CSSFiles = CSSFileDAO::getCSSFiles();
    $selectedCSSFile = CSSFileDAO::getCSSFileById(CloudEngineHTTP::getGetVar("Id"));
?>
    <body>
        <input type="hidden" value="<?php echo null != $selectedCSSFile ? $selectedCSSFile->getIdCSSFIle() : "-1" ?>" id="hdSelectedCSSFileId" />
        <?php $layout->printMainBar(); ?>
        <div class="width-80 background-color-studio height-100 float-left canvas-width" style="overflow: hidden">
            <div class="width-20 padding-3x float-left height-100">
                <?php
                    foreach ($CSSFiles as $f) {
                        if ($selectedCSSFile != null && $selectedCSSFile->getIdCSSFIle() == $f->getIdCSSFile()) {
                            echo '<div class="background-darken width-100 cursor-default padding-2x display-table border-radius text-color-white text-weight-bold">' . $f->getName() . '</div>';
                        } else {
                            echo '<a href="?Id=' . $f->getIdCSSFile() . '" class="text-decoration-none on-hover-darken width-100 cursor-pointer padding-2x display-table border-radius text-color-white">' . $f->getName() . '</a>';
                        }
                    }
                ?>
            </div>
            <div class="width-80 background-color-white padding-4x float-left height-100" style="overflow: auto">
                <div class="width-100 margin-bottom-3x font-size-l">
                    Compilador CSS
                </div>
                <div class="width-100 margin-bottom-3x text-align-right">
                    <?php
                        if ($selectedCSSFile != null) {
                            echo '<button id="btnAddClass" class="border-none border-radius padding-2x background-color-blue font-color-white font-weight-bold button-shadow font-size-s cursor-pointer on-hover-opacity">AGREGAR CLASE</button>
                            <button id="btnCompile" class="border-none border-radius padding-2x background-color-blue font-color-white font-weight-bold button-shadow font-size-s cursor-pointer on-hover-opacity">COMPILAR</button>';
                        }
                    ?>
                </div>
                <div class="width-100">
                    <?php
                        if ($selectedCSSFile != null) {
                            $CSSClasses = $selectedCSSFile->getClasses();
                            $CSSMediaQueries = $selectedCSSFile->getMediaQueries();
                            
                            foreach ($CSSClasses as $c) {
                                $properties = $c->getProperties();
                                
                                echo '<div class="width-100 display-table background-color-light-gray padding-2x">';
                                echo '<div class="font-weight-bold font-size-m float-left">' . $c->getName() . '</div>';
                                echo '<div name="btnDuplicateClass" data-id="' . $c->getIdCSSClass() . '" class="font-color-blue cursor-pointer float-right font-size-m margin-left-2x">Duplicar</div>';
                                echo '<div name="btnEditClass" data-id="' . $c->getIdCSSClass() . '" class="font-color-blue cursor-pointer float-right font-size-m">Editar</div>';
                                echo '</div>';
                                echo '<div class="background-color-light-gray padding-2x font-size-s">' . $c->getDescription() . '</div>';
                                echo '<div class="border-dark padding-2x margin-bottom-2x font-size-s">';
                                
                                foreach ($properties as $p) {
                                    echo "<b>" . $p->getKey() . ":</b> " . $p->getValue() . ";<br/>";
                                }
                                
                                echo '</div>';
                            }
                            
                            foreach ($CSSMediaQueries as $m) {
                                $mediaQueryClasses = $m->getClasses();
                                
                                echo '<div class="border-dark width-100 padding-2x">';
                                echo '<div class="display-table width-100 margin-bottom-2x">';
                                echo '<div class="font-weight-bold font-size-m float-left">' . $m->getQuery() . '</div>';
                                echo '<div name="btnEditMediaQuery" data-id="' . $m->getIdCSSMediaQuery() . '" class="font-color-blue cursor-pointer float-right font-size-m">Editar</div>';
                                echo '</div>';
                                
                                foreach ($mediaQueryClasses as $c) {
                                    $properties = $c->getProperties();

                                    echo '<div class="width-100 display-table background-color-light-gray padding-2x">';
                                    echo '<div class="font-weight-bold font-size-m float-left">' . $c->getName() . '</div>';
                                    echo '<div name="btnDuplicateClass" data-id="' . $c->getIdCSSClass() . '" class="font-color-blue cursor-pointer float-right font-size-m margin-left-2x">Duplicar</div>';
                                    echo '<div name="btnEditClass" data-id="' . $c->getIdCSSClass() . '" class="font-color-blue cursor-pointer float-right font-size-m">Editar</div>';
                                    echo '</div>';
                                    echo '<div class="background-color-light-gray padding-2x font-size-s">' . $c->getDescription() . '</div>';
                                    echo '<div class="border-dark padding-2x margin-bottom-2x font-size-s">';

                                    foreach ($properties as $p) {
                                        echo "<b>" . $p->getKey() . ":</b> " . $p->getValue() . ";<br/>";
                                    }

                                    echo '</div>';
                                }
                                
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script>
            $("#btnAddClass").on("click", function() {
                var selectedCSSFileId = $("#hdSelectedCSSFileId").val();
                if ("-1" === selectedCSSFileId) {
                    alert("Debe seleccionar un archivo CSS.");
                } else {
                    showPopup(URL_POPUPS + "CSSCompiler/AddCSSClass.php", { CSSFileId: selectedCSSFileId });
                }
            });
            
            $("[name=btnEditClass]").on("click", function(e) {
                var CSSClassId = $(e.target).data("id");
                showPopup(URL_POPUPS + "CSSCompiler/EditCSSClass.php", { CSSClassId: CSSClassId });
            });
            
            $("[name=btnDuplicateClass]").on("click", function(e) {
                var CSSClassId = $(e.target).data("id");
                showPopup(URL_POPUPS + "CSSCompiler/DuplicateCSSClass.php", { CSSClassId: CSSClassId });
            });
            
            $("#btnCompile").on("click", function(e) {
                $.redirect(URL_API + "CSSCompiler/Compile.php", { IdCSSFile : $("#hdSelectedCSSFileId").val() }, "POST", "_BLANK");
            });
        </script>
    </body>
</html>