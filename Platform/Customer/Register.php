<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\Design\CloudEngineHTMLSelect;
    use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
    use Cloud\Engine\PHP\HTTP\CloudEngineSession;
    
    if (null != CloudEngineSession::getSessionObject()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php");
    }
    
    $layout = new Layout();
    $layout->setTitle(Internationalization::newCustomer());
    $layout->addJSFile("https://www.google.com/recaptcha/api.js?onload=recaptchaAdjust&render=explicit");
    $layout->printHead();
?>
    <body class="background-color-light-gray padding-5x mobile-padding-3x display-table">
        <div class="width-20 float-left mobile-hide">&nbsp;</div>
        <div class="width-60 float-left background-color-white padding-5x border-radius box-shadow" id="frmNewCustomer">
            <!-- Logotype -->
            <div class="width-100 padding-5x margin-bottom-4x logotype" style="background-size: contain"></div>
            <!-- Tabs -->
            <div id="tabs" class="width-100 display-table margin-bottom-4x">
                <div id="tabPersonal" class="cursor-default width-25 float-left text-align-center padding-3x text-weight-bold new-customer-tab"><?php echo Internationalization::personalInfo(); ?></div>
                <div id="tabContact" class="cursor-default width-25 float-left text-align-center padding-3x"><?php echo Internationalization::contactInfo(); ?></div>
                <div id="tabAccount" class="cursor-default width-25 float-left text-align-center padding-3x"><?php echo Internationalization::accessInfo(); ?></div>
                <div id="tabLegal" class="cursor-default width-25 float-left text-align-center padding-3x"><?php echo Internationalization::legalInfo(); ?></div>
            </div>
            <!-- Personal -->
            <div id="frmPersonal">
                <!-- First names -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerNamesLabel(); ?></div>
                    <div class="float-right width-70"><input autofocus="on" class="input-text-underline" type="text" data-required="true" data-name="<?php echo Internationalization::newCustomerNamesLabel(); ?>" id="txtNames" /></div>
                </div>
                <!-- Gender -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerGenderLabel(); ?></div>
                    <div class="float-right width-70">
                        <select class="select-underline" data-required="true" data-name="<?php echo Internationalization::newCustomerGenderLabel(); ?>" id="lsGender">
                            <?php
                                if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                                    echo '<option value="">Seleccione su género</option><option value="FEMALE">Mujer</option><option value="MALE">Hombre</option>';
                                } else {
                                    echo '<option value="">Choose your gender</option><option value="FEMALE">Female</option><option value="MALE">Male</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Birthdate -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerBirthdateLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="date" data-required="true" data-name="<?php echo Internationalization::newCustomerBirthdateLabel(); ?>" id="txtBirthdate" /></div>
                </div>
                <!-- Language -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerLanguageLabel(); ?></div>
                    <div class="float-right width-70">
                        <select class="select-underline" data-required="true" data-name="<?php echo Internationalization::newCustomerLanguageLabel(); ?>" id="lsLanguage">
                            <?php
                                if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                                    echo '<option value="ENGLISH">English</option><option selected value="SPANISH">Español</option>';
                                } else {
                                    echo '<option selected value="ENGLISH">English</option><option value="SPANISH">Español</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Document type -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerDocumentTypeLabel(); ?></div>
                    <div class="float-right width-70">
                        <?php
                            $documentTypes = DocumentTypeDAO::getDocumentTypes();
                            $slDocumentType = new CloudEngineHTMLSelect();
                            $slDocumentType->addPropertie("class", "select-underline");
                            $slDocumentType->addPropertie("id", "lsDocumentType");
                            $slDocumentType->addPropertie("data-required", "false");
                            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                                $slDocumentType->addOption("Seleccione su tipo de documento", "");
                                foreach ($documentTypes as $d) {
                                    $slDocumentType->addOption($d->getName(), $d->getIdDocumentType());
                                }
                            } else {
                                $slDocumentType->addOption("Choose your document type", "");
                                foreach ($documentTypes as $d) {
                                    $slDocumentType->addOption($d->getName(), $d->getIdDocumentType());
                                }
                            }
                            $slDocumentType->render();
                        ?>
                    </div>
                </div>
                <!-- Document number -->
                <div class="display-table padding-top-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerDocumentNumberLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="text" data-required="false" id="txtDocumentNumber" /></div>
                </div>
                <!-- Actions -->
                <div class="text-align-center margin-top-5x">
                    <a href="../" class="button-white margin-right-2x text-decoration-none"><?php echo Internationalization::backButton(); ?></a>
                    <button id="btnPersonalNext" class="button-red"><?php echo Internationalization::nextButton(); ?></button>
                </div>
            </div>
            <!-- Contact -->
            <div class="display-none" id="frmContact">
                <!-- Country -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerCountryLabel(); ?></div>
                    <div class="float-right width-70">
                        <?php
                            $countries = CountryDAO::getCountries();
                            $slCountry = new CloudEngineHTMLSelect();
                            $slCountry->addPropertie("class", "select-underline");
                            $slCountry->addPropertie("id", "lsCountry");
                            $slCountry->addPropertie("data-required", "true");
                            $slCountry->addPropertie("data-name", Internationalization::newCustomerCountryLabel());
                            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                                $slCountry->addOption("Seleccione su país", "");
                            } else {
                                $slCountry->addOption("Choose your country", "");
                            }
                            foreach ($countries as $c) {
                                $slCountry->addOption($c->getName(), $c->getIdCountry());
                            }
                            $slCountry->render();
                        ?>
                    </div>
                </div>
                <!-- City -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerCityLabel(); ?></div>
                    <div class="float-right width-70">
                        <?php
                            $slCity = new CloudEngineHTMLSelect();
                            $slCity->addPropertie("class", "select-underline");
                            $slCity->addPropertie("id", "lsCity");
                            $slCity->addPropertie("data-required", "true");
                            $slCity->addPropertie("data-name", Internationalization::newCustomerCityLabel());
                            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                                $slCity->addOption("Seleccione su ciudad", "");
                            } else {
                                $slCity->addOption("Choose your city", "");
                            }
                            $slCity->render();
                        ?>
                    </div>
                </div>
                <!-- Address -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerAddressLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="text" data-required="true" data-name="<?php echo Internationalization::newCustomerAddressLabel(); ?>" id="txtAddress" /></div>
                </div>
                <!-- Telephone -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerPhoneLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="tel" data-required="true" data-name="<?php echo Internationalization::newCustomerPhoneLabel(); ?>" id="txtTelephone" /></div>
                </div>
                <!-- Telephone 2 -->
                <div class="display-table padding-top-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerPhone2Label(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="tel" data-required="false" id="txtTelephone2" /></div>
                </div>
                <!-- Actions -->
                <div class="text-align-center margin-top-5x">
                    <button id="btnContactBack" class="button-white margin-right-2x"><?php echo Internationalization::backButton(); ?></button>
                    <button id="btnContactNext" class="button-red"><?php echo Internationalization::nextButton(); ?></button>
                </div>
            </div>
            <!-- Account -->
            <div class="display-none" id="frmAccount">
                <!-- Email -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerEmailLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="email" data-required="true" data-name="<?php echo Internationalization::newCustomerEmailLabel(); ?>" id="txtEmail" /></div>
                </div>
                <!-- Password -->
                <div class="display-table padding-top-3x padding-bottom-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerPasswordLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="password" data-required="true" data-name="<?php echo Internationalization::newCustomerPasswordLabel(); ?>" id="txtPassword" /></div>
                </div>
                <!-- Password confirm -->
                <div class="display-table padding-top-3x width-100">
                    <div class="float-left width-25 text-weight-bold"><?php echo Internationalization::newCustomerConfirmPasswordLabel(); ?></div>
                    <div class="float-right width-70"><input class="input-text-underline" type="password" data-required="true" data-name="<?php echo Internationalization::newCustomerConfirmPasswordLabel(); ?>" id="txtPasswordConfirm" /></div>
                </div>
                <!-- Actions -->
                <div class="text-align-center margin-top-5x">
                    <button id="btnAccountBack" class="button-white margin-right-2x"><?php echo Internationalization::backButton(); ?></a>
                    <button id="btnAccountNext" class="button-red"><?php echo Internationalization::nextButton(); ?></button>
                </div>
            </div>
            <!-- Legal -->
            <div class="display-none" id="frmLegal">
                <!-- Terms and conditions -->
                <div id="lblContract" style="height: 200px; overflow: auto;" class="text-align-justify display-inline-block width-100"><?php echo Internationalization::newCustomerTermsAndConditions(); ?></div>
                <!-- Accept -->
                <div class="display-table padding-top-3x width-100">
                    <div class="float-left width-100 text-weight-bold">
                        <input class="float-left margin-right-4x" type="checkbox" data-required="true" data-name="<?php echo Internationalization::newCustomerTermsAndConditionsLabel(); ?>" id="chkLegal" />
                        <label for="chkLegal" class="float-left cursor-pointer"><?php echo Internationalization::newCustomerTermsAndConditionsLabel(); ?></label>
                    </div>
                </div>
                <!-- Captcha -->
                <div id="GRecaptchaParent" class="margin-top-4x width-100 text-align-center">
                    <div class="g-recaptcha display-inline-block" id="GRecaptcha"></div>
                </div>
                <!-- Actions -->
                <div class="text-align-center margin-top-5x">
                    <button id="btnLegalBack" class="button-white margin-right-2x"><?php echo Internationalization::backButton(); ?></button>
                    <button id="btnFinish" class="button-red"><?php echo Internationalization::sendButton(); ?></button>
                </div>
            </div>
        </div>
        <?php
            $layout->printJSScripts();
        ?>
        <script>
            var recaptchaAdjust = function() {
                var scale = 0;
                
                if ($("#tabs").innerWidth() < 304) {     // Mobile
                    scale = $("#tabs").innerWidth() / 304;
                    $('#GRecaptcha').css("transform-origin", "0 0");
                } else {
                    scale = .9;
                }
                
                $('#GRecaptcha').css("transform", "scale(" + scale + ")");
                $("#GRecaptchaParent").width($("#tabs").innerWidth());
                
                grecaptcha.render('GRecaptcha', {
                    'sitekey' : '6LcsrDsUAAAAALW1WXfoJLY2UXgPspenPWmo8rrO'
                });
            };
            
            $("#btnPersonalNext").on("click", function(e) {
                var frmPersonal = new Form($("#frmPersonal"));
                if (frmPersonal.validate()) {
                    $("#frmPersonal").hide();
                    $("#frmContact").show();
                    changeToTab($("#tabContact"));
                }
            });
            
            $("#btnContactBack").on("click", function(e) {
                $("#frmPersonal").show();
                $("#frmContact").hide();
                changeToTab($("#tabPersonal"));
            });
            
            $("#btnContactNext").on("click", function(e) {
                var frmContact = new Form($("#frmContact"));
                if (frmContact.validate()) {
                    $("#frmContact").hide();
                    $("#frmAccount").show();
                    changeToTab($("#tabAccount"));
                }
            });
            
            $("#btnAccountBack").on("click", function(e) {
                $("#frmContact").show();
                $("#frmAccount").hide();
                changeToTab($("#tabContact"));
            });
            
            $("#btnAccountNext").on("click", function(e) {
                var frmAccount = new Form($("#frmAccount"));
                if (frmAccount.validate()) {
                    $("#frmAccount").hide();
                    $("#frmLegal").show();
                    changeToTab($("#tabLegal"));
                }
            });
            
            $("#btnLegalBack").on("click", function(e) {
                $("#frmAccount").show();
                $("#frmLegal").hide();
                changeToTab($("#tabAccount"));
            });
            
            $("#btnFinish").on("click", function(e) {
                var frmLegal = new Form($("#frmLegal"), true);
                if (frmLegal.validate()) {
                    $.ajax({
                        url: URL_API + "Customer/Register.php",
                        type: "POST",
                        data: {
                            Names: $("#txtNames").val(),
                            Gender: $("#lsGender").val(),
                            Birthdate: $("#txtBirthdate").val(),
                            Language: $("#lsLanguage").val(),
                            IdDocumentType: $("#lsDocumentType").val(),
                            DocumentNumber: $("#txtDocumentNumber").val(),
                            IdCity: $("#lsCity").val(),
                            Address: $("#txtAddress").val(),
                            Telephone: $("#txtTelephone").val(),
                            Telephone2: $("#txtTelephone2").val(),
                            Email: $("#txtEmail").val(),
                            Password: $("#txtPassword").val(),
                            Captcha: grecaptcha.getResponse()
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            if (r.type === "Exception") {
                                new Notification("ERROR", r.message);
                                grecaptcha.reset();
                                closePreload();
                            } else {
                                document.location.href = "Thanks.php";
                            }
                        }
                    });
                }
            });
            
            $("#lsCountry").on("change", function(e) {
                var idCountry = $(e.target).val();
                if (idCountry !== "") {
                    $.ajax({
                        url: URL_API + "City/GetByCountry.php",
                        type: "POST",
                        data: {
                            IdCountry: idCountry
                        },
                        beforeSend: function() {
                            showPreload();
                        },
                        success: function(response) {
                            var r = JSON.parse(response);
                            $("#lsCity").empty();
                            for (var i in r) {
                                $("#lsCity").append($("<option>", {value: r[i].id, text: r[i].name}));
                            }
                            closePreload();
                        }
                    });
                }
            });
            
            function changeToTab(tab) {
                $("#tabPersonal").removeClass("text-weight-bold new-customer-tab");
                $("#tabContact").removeClass("text-weight-bold new-customer-tab");
                $("#tabAccount").removeClass("text-weight-bold new-customer-tab");
                $("#tabLegal").removeClass("text-weight-bold new-customer-tab");
                tab.addClass("text-weight-bold new-customer-tab");
            }
        </script>
    </body>
</html>