<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Filter;
use Elastica\Query\BoolQuery;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Entity extends AbstractField implements ElasticaFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq');

    /**
     * {@inheritdoc}
     */
    public function buildQuery(BoolQuery $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if (empty($data)) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $idFieldName = $this->getOption('identifier_field');

        $filter->addFilter(
            new Filter\Terms(
                sprintf("%s.%s", $this->getField(), $idFieldName),
                array($accessor->getValue($data, $idFieldName))
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        parent::initOptions();

        $this->getOptionsResolver()
            ->setDefaults(array('identifier_field' => 'id'))
            ->setAllowedTypes(
                array(
                    'identifier_field' => array('string')
                )
            )
        ;
    }
}
