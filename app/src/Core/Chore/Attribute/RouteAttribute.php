<?php
namespace Editiel98\Chore\Attribute;

use Attribute;

#[Attribute()]
class RouteAttribute
{
    public function __construct(
        private string $path
    )
    {
        
    }
}