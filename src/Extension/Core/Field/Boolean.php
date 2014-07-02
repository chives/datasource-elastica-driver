<?php

namespace FSi\Component\DataSource\Driver\Elastica\Extension\Core\Field;

use Elastica\Filter\AbstractMulti;
use Elastica\Filter\Term;
use Elastica\Query\Bool;
use FSi\Component\DataSource\Driver\Elastica\FieldInterface;

class Boolean extends AbstractField implements FieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq');

    public function buildQuery(Bool $query, AbstractMulti $filter)
    {
        $data = $this->getCleanParameter();
        if ($data === array() || $data === '' || $data === null) {
            return;
        }

        $termFilter = new Term();
        $termFilter->setTerm($this->getField(), $data);

        $filter->addFilter($termFilter);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'boolean';
    }
}
