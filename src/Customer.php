<?php
namespace Pluf\Lm;

use Pluf\Data\Attribute\Entity;
use Pluf\Data\Attribute\Table;
use Pluf\Data\Attribute\Id;
use Pluf\Data\Attribute\Column;

#[Entity]
#[Table("lm_customer")]
class Customer
{

    #[Id]
    #[Column("id")]
    public int $id = 0;

    #[Column("title")]
    public ?string $title = "";

    #[Column("title")]
    public ?string $description = "";
}

