<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

use Cloud\Engine\PHP\HTTP\CloudEngineSession;

CloudEngineSession::destroy();

header("location:index.php");