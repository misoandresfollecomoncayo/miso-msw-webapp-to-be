<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

$s = ShippingDAO::getShippingByNumber(30073);

echo ( $s->getTotalPartialPayments() . " " . $s->getTotal() . "<br/>");
echo ( round($s->getTotalPartialPayments(),2) < round($s->getTotal(),2));