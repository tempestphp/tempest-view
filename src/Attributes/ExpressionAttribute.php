<?php

declare(strict_types=1);

namespace Tempest\View\Attributes;

use Tempest\View\Attribute;
use Tempest\View\Element;
use Tempest\View\Elements\PhpDataElement;

final readonly class ExpressionAttribute implements Attribute
{
    public function __construct(
        private string $name,
    ) {
    }

    public function apply(Element $element): Element
    {
        return new PhpDataElement(
            $this->name,
            $element->getAttribute($this->name),
            $element->setAttribute(
                $this->name,
                sprintf('<?= %s ?>', $element->getAttribute($this->name))
            ),
        );
    }
}
