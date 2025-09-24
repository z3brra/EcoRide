<?php

namespace App\Security\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class BypassSettlementLock {}

?>