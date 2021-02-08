<?php
namespace Pluf\Lm;

use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Columne;

#[Entity]
#[Table("lm_license_signes")]
class LicenseSigne
{
    # [Id]
    # [Columne("id")]
    public ?int $id;
}

