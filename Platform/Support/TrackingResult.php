<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\Utils\CloudEngineGoogleRecaptcha;

if (!isset($_REQUEST["Captcha"]) || !CloudEngineGoogleRecaptcha::isValid($_REQUEST["Captcha"], GOOGLE_RECAPTCHA_PRIVATE_KEY)) {
    echo "Invalid reCaptcha.";
    exit();
}

if (!isset($_REQUEST["Number"])) {
    echo "Number is required.";
    exit();
}

/*if (!isset($_REQUEST["Locker"])) {
    echo "Locker is required.";
    exit();
}*/

$shipment = ShippingDAO::getShippingByNumber($_REQUEST["Number"]);
$bill = BillDAO::getBillByNumber($_REQUEST["Number"]);

if ($bill == null && $shipment == null) {
    echo "Invalid number.";
    exit();
}

if ($shipment != null && $shipment->getPurchases()[0]->getCustomer()->getLockerNumber() != $_REQUEST["Locker"]) {
    echo "Invalid locker.";
    exit();
}

$layout = new Layout();
$layout->setTitle(Internationalization::trackingTitle());
$layout->printHead();
?>
    <body class="padding-5x background-color-light-gray mobile-padding-3x display-table">
        <div class="width-20 float-left mobile-hide">&nbsp;</div>
        <div class="width-60 float-left background-color-white padding-5x border-radius box-shadow">
            <!-- Logotype -->
            <div class="width-100 padding-5x margin-bottom-4x logotype"></div>
            <!-- Shipment -->
            <div class="display-table width-100 padding-top-2x padding-bottom-2x border-bottom-dark">
                <div class="width-1-3 float-left">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::createdTimestamp(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getCreatedTimestamp() : $bill->getCreatedTimestamp()); ?></div>
                </div>
                <div class="width-1-3 float-left mobile-margin-top-2x">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::trackingNumber(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getShippingNumber() : $bill->getBillNumber()); ?></div>
                </div>
                <div class="width-1-3 float-left mobile-margin-top-2x">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::weight(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getNetWeight() : $bill->getWeight()); ?></div>
                </div>
            </div>
            <div class="display-table width-100 padding-top-2x padding-bottom-2x border-bottom-dark margin-bottom-4x">
                <div class="width-1-3 float-left">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::customer(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getPurchases()[0]->getCustomer()->getNames() : $bill->getFrom()); ?></div>
                </div>
                <div class="width-1-3 float-left mobile-margin-top-2x">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::country(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getPurchases()[0]->getCustomer()->getCity()->getCountry()->getName() : ""); ?></div>
                </div>
                <div class="width-1-3 float-left mobile-margin-top-2x">
                    <div class="text-weight-bold text-size-s text-align-center"><?php echo Internationalization::city(); ?></div>
                    <div class="text-size-m margin-top-2x text-align-center"><?php echo ($shipment != null ? $shipment->getPurchases()[0]->getCustomer()->getCity()->getName() : ""); ?></div>
                </div>
            </div>
            <!-- Tracking -->
            <?php
                if ($shipment != null) {
                    $code = "<div class='display-table width-100 margin-bottom-2x'>";
                    $code .= "<div class='float-left width-30 text-size-2 text-align-center text-weight-bold'>" . Internationalization::timestamp() . "</div>";
                    $code .= "<div class='float-left width-70 text-size-s text-align-center text-weight-bold'>" . Internationalization::movement() . "</div>";
                    $code .= "</div>";
                    echo $code;
                
                    $tracking = $shipment->getTracking();
                    foreach ($tracking as $t) {
                        $code = "<div class='display-table width-100 margin-bottom background-color-light-gray'>";
                        $code .= "<div class='float-left width-30 text-size-s text-color-white text-weight-bold text-align-center padding background-color-green'>" . $t->getCreatedTimestamp() . "</div>";
                        $code .= "<div class='float-left width-70 text-size-s padding'>" . $t->getDescription() . "</div>";
                        $code .= "</div>";
                        echo $code;
                    }
                } else {
                    $code = "<div class='display-table width-100 margin-bottom-2x'>";
                    $code .= "<div class='float-left width-20 text-size-2 text-align-center text-weight-bold'>" . Internationalization::timestamp() . "</div>";
                    $code .= "<div class='float-left width-20 text-size-2 text-align-center text-weight-bold'>" . Internationalization::boxNumber() . "</div>";
                    $code .= "<div class='float-left width-60 text-size-s text-align-center text-weight-bold'>" . Internationalization::movement() . "</div>";
                    $code .= "</div>";
                    echo $code;
                
                    foreach ($bill->getItems() as $i) {
                        foreach ($i->getTracking() as $t) {
                            $code = "<div class='display-table width-100 margin-bottom background-color-light-gray'>";
                            $code .= "<div class='float-left width-20 text-size-s text-color-white text-weight-bold text-align-center padding background-color-green'>" . $t->getCreatedTimestamp() . "</div>";
                            $code .= "<div class='float-left width-20 text-size-s text-align-center padding'>" . $i->getBoxNumber() . "</div>";
                            $code .= "<div class='float-left width-60 text-size-s padding'>" . $t->getDescription() . "</div>";
                            $code .= "</div>";
                            echo $code;
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>