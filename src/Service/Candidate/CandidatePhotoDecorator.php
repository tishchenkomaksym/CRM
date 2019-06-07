<?php


namespace App\Service\Candidate;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CandidatePhotoDecorator
{

    private $targetDirectory;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct($targetDirectory, ParameterBagInterface $params)
    {
        $this->targetDirectory = $targetDirectory;
        $this->params = $params;
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid('', true)).'.'.$file->guessExtension();

            $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function photoNotNull(Candidate $candidate)
    {
        $photoNotNull = $candidate->getPhoto() !== null;
        if ($photoNotNull) {
            $candidate->setPhoto(
                new File($this->params->get('uploads_directory') . '/' . $candidate->getPhoto())
            );
        }
        return $candidate;
    }

    public function receivedCvNotNull(CandidateVacancy $candidateVacancy): void
    {
        $cvNotNull = $candidateVacancy->getReceivedCv() !== null;
        if ($cvNotNull) {
            $candidateVacancy->setReceivedCv(
                new File($this->params->get('uploads_directory') . '/' . $candidateVacancy->getReceivedCv())
            );
        }
    }

    public function receivedCvNotNullCandidateLink(CandidateLink $candidateLink): void
    {
        $cvNotNull = $candidateLink->getReceivedCv() !== null;
        if ($cvNotNull) {
            $candidateLink->setReceivedCv(
                new File($this->params->get('uploads_directory') . '/' . $candidateLink->getReceivedCv())
            );
        }
    }
}