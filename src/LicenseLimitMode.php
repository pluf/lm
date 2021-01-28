<?php
namespace Pluf\Lm;

class LicenseLimitMode
{

    /**
     * The limit is critical and must be checked
     *
     * @var string
     */
    const CRITICLA = "criticla";

    /**
     * The limit is not criticla and may ignore
     *
     * @var string
     */
    const NONE_CRITICAL = "none-critical";
}

