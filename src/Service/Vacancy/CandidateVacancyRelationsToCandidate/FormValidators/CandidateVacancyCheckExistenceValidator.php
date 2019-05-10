<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;


use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilder;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CandidateVacancyCheckExistenceValidator extends ConstraintValidator
{
    private $message = 'This vacancy was already added';
    /**
     * @var ExistsCandidateBuilder
     */
    private $existsCandidateBuilder;
    /**
     * @var CandidateVacancyExistenceLogic
     */
    private $candidateVacancyExistenceLogic;


    public function __construct(ExistsCandidateBuilder $existsCandidateBuilder,
                                CandidateVacancyExistenceLogic $candidateVacancyExistenceLogic)
    {
        $this->existsCandidateBuilder = $existsCandidateBuilder;
        $this->candidateVacancyExistenceLogic = $candidateVacancyExistenceLogic;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */

    public function validate($value, Constraint $constraint):void
    {
        if (!$constraint instanceof CandidateVacancyCheckExistence) {
            throw new UnexpectedTypeException($constraint, CandidateVacancyCheckExistence::class);
        }

        $form = $this->context->getObject();
        if ($form instanceof Form) {
            $name = $form->get('name')->getData();
            $surname = $form->get('surname')->getData();
            $candidate = $this->existsCandidateBuilder->build($name, $surname);
            if (($candidate !== null) && $this->candidateVacancyExistenceLogic->existence($candidate->getId(), [$constraint->vacancy->getId()]) !== null) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }


        }
    }
}