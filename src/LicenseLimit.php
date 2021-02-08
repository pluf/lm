<?php
namespace Pluf\Lm;

use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Columne;

# [Entity]
# [Table("lm_license_limits")]
class LicenseLimit
{

    # [Id]
    # [Columne("id")]
    public ?int $id;

    # [Columne("key")]
    public ?string $key;

    # [Columne("value")]
    public ?string $value;

    # [Columne("mode")]
    public ?string $mode;
}

