<?php


namespace App\Form\SDT\FormValidators;


use Symfony\Component\Validator\Constraint;

class UpdateDate extends Constraint
{
    public $message = 'Only TOM have posibility to create sdt before salary report ';
}