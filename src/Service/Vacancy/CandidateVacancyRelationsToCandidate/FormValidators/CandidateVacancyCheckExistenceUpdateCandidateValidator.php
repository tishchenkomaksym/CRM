<?php

namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateVacancyCheckExistenceUpdateCandidateValidator extends ConstraintValidator
{
    private $message = 'This candidate was already added';

    /**
     * @var CandidateVacancyExistenceLogic
     */
    private $candidateVacancyExistenceLogic;

    private $vacancyIds;


    public function __construct(CandidateVacancyExistenceLogic $candidateVacancyExistenceLogic)
    {
        $this->candidateVacancyExistenceLogic = $candidateVacancyExistenceLogic;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */

    public function validate($value, Constraint $constraint):void
    {
        if (!$constraint instanceof CandidateVacancyCheckExistenceUpdateCandidate) {
            throw new UnexpectedTypeException($constraint, CandidateVacancyCheckExistenceUpdateCandidate::class);
        }
        $form = $this->context->getObject();
        if ($form instanceof Form) {
            $vacancyArray = $form->get('vacancy')->getData();
            $candidateId = $form->getData()->getId();
            foreach($vacancyArray as $vacancyIds){
                $this->vacancyIds = $vacancyIds;
            }

            if (!empty($this->candidateVacancyExistenceLogic->existence($candidateId, $this->vacancyIds))) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
