<?php
namespace CodeandoMexico\Sismomx\Core\Builders;

use CodeandoMexico\Sismomx\Core\Abstracts\Builders\BuilderAbstract;
use CodeandoMexico\Sismomx\Core\Dictionaries\GoogleSheetsApiV4\LinkDictionary;
use CodeandoMexico\Sismomx\Core\Dtos\LinkDto;
use CodeandoMexico\Sismomx\Core\Interfaces\Builders\LinkBuilderInterface;
use CodeandoMexico\Sismomx\Core\Traits\Base\DatesHelper;

/**
 * Class LinkBuilder
 * @package CodeandoMexico\Sismomx\Core\Builders
 * @Injectable(scope="prototype")
 */
class LinkBuilder extends BuilderAbstract implements LinkBuilderInterface
{
    use DatesHelper;
    /**
     * @var LinkDto
     */
    protected $builtable;

    /**
     * LinkBuilder constructor.
     * @Inject
     * @param LinkDto $dto
     */
    public function __construct(LinkDto $dto)
    {
        $this->builtable = $dto;
    }

    /**
     * @inheritdoc
     */
    public function internalBuild()
    {
        $this->buildEncodedKey();
        $this->builtable->id = $this->values->getValue(LinkDictionary::ID);
        $this->builtable->description = $this->values->getValue(LinkDictionary::DESCRIPTION);
        $this->builtable->url = $this->values->getValue(LinkDictionary::URL);
        $this->builtable->createdAt = $this->stringToDate(
            $this->values->getValue(LinkDictionary::CREATED_AT),
            'Y-m-d H:i:s',
            'now'
        );
        return $this;
    }

    /**
     * @return $this
     */
    protected function buildEncodedKey()
    {
        $preKey = [
            LinkDictionary::DESCRIPTION => $this->values->getValue(LinkDictionary::DESCRIPTION),
            LinkDictionary::URL => $this->values->getValue(LinkDictionary::URL),
        ];
        $this->builtable->encodedKey = hash('sha256',json_encode($preKey));
        return $this;
    }
}
