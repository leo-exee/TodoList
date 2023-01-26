<?php
namespace App\Components;


use App\Entity\Action;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('action-item')]
class ActionItem
{
    public Action $action;
}