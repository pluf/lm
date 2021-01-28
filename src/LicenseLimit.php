<?php
namespace Pluf\Lm;

# [Entity]
# [Table("lm_license_limits")]
class LicenseLimit
{

    # [Id]
    # [Columne("id")]
    private $id;

    # [Columne("key")]
    private string $key;

    # [Columne("value")]
    private string $value;

    # [Columne("mode")]
    private string $mode;
}

