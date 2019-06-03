<?php

namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateLinkCheckExistenceUpdateCandidateValidator extends ConstraintValidator
{
    private $message = 'This candidate was already added';

    /**
     * @var CandidateLinkExistenceLogic
     */
    private $candidateVacancyExistenceLogic;

    private $vacancyIds;


    public function __construct(CandidateLinkExistenceLogic $candidateVacancyExistenceLogic)
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
        if (!$constraint instanceof CandidateLinkCheckExistenceUpdateCandidate) {
            throw new UnexpectedTypeException($constraint, CandidateLinkCheckExistenceUpdateCandidate::class);
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
