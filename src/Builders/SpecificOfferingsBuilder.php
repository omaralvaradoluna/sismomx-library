<?php
namespace CodeandoMexico\Sismomx\Core\Builders;

use CodeandoMexico\Sismomx\Core\Abstracts\Builders\BuilderAbstract;
use CodeandoMexico\Sismomx\Core\Dictionaries\GoogleSheetsApiV4\SpecificOfferingsDictionary;
use CodeandoMexico\Sismomx\Core\Dtos\SpecificOfferingsDto;
use CodeandoMexico\Sismomx\Core\Interfaces\Builders\SpecificOfferingsBuilderInterface;
use CodeandoMexico\Sismomx\Core\Traits\Base\DatesHelper;

/**
 * Class SpecificOfferingsBuilder
 * @package CodeandoMexico\Sismomx\Core\Builders
 */
class SpecificOfferingsBuilder extends BuilderAbstract implements SpecificOfferingsBuilderInterface
{
    use DatesHelper;
    /**
     * @var SpecificOfferingsDto
     */
    protected $builtable;

    /**
     * SpecificOfferingsBuilder constructor.
     * @Inject
     * @param SpecificOfferingsDto $dto
     */
    public function __construct(SpecificOfferingsDto $dto)
    {
        $this->builtable = $dto;
    }

    /**
     * @inheritdoc
     */
    public function internalBuild()
    {
        $this->buildEncodedKey();
        $this->builtable->id = $this->values->getValue(SpecificOfferingsDictionary::ID);
        $this->builtable->moreInformation = $this->values->getValue(SpecificOfferingsDictionary::MORE_INFORMATION);
        $this->builtable->contact = $this->values->getValue(SpecificOfferingsDictionary::CONTACT);
        $this->builtable->notes = $this->values->getValue(SpecificOfferingsDictionary::NOTES);
        $this->builtable->expiresAt = $this->values->getValue(SpecificOfferingsDictionary::EXPIRES_AT);
        $this->builtable->offeringDetails = $this->values->getValue(SpecificOfferingsDictionary::OFFERING_DETAILS);
        $this->builtable->offeringFrom = $this->values->getValue(SpecificOfferingsDictionary::OFFERING_FROM);
        $this->builtable->updatedAt = $this->stringToDate(
            $this->values->getValue(SpecificOfferingsDictionary::UPDATED_AT),
            'Y/d/m H:i'
        );
        $this->builtable->createdAt = $this->stringToDate(
            $this->values->getValue(SpecificOfferingsDictionary::CREATED_AT),
            'Y-m-d H:i:s',
            'now'
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function buildEncodedKey()
    {
        $preKey = [
            SpecificOfferingsDictionary::OFFERING_FROM => $this->values->getValue(
                SpecificOfferingsDictionary::OFFERING_FROM
            ),
            SpecificOfferingsDictionary::NOTES => $this->values->getValue(
                SpecificOfferingsDictionary::NOTES
            ),
            SpecificOfferingsDictionary::CONTACT => $this->values->getValue(
                SpecificOfferingsDictionary::CONTACT
            ),
            SpecificOfferingsDictionary::OFFERING_DETAILS => $this->values->getValue(
                SpecificOfferingsDictionary::OFFERING_DETAILS
            ),
            SpecificOfferingsDictionary::MORE_INFORMATION => $this->values->getValue(
                SpecificOfferingsDictionary::MORE_INFORMATION
            ),
            SpecificOfferingsDictionary::UPDATED_AT => $this->values->getValue(
                SpecificOfferingsDictionary::UPDATED_AT
            ),
            SpecificOfferingsDictionary::EXPIRES_AT => $this->values->getValue(
                SpecificOfferingsDictionary::EXPIRES_AT
            ),
        ];
        $this->builtable->encodedKey = hash('sha256',json_encode($preKey));
        return $this;
    }
}
