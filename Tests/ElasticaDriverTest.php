<?php

namespace FSi\Component\DataSource\Driver\Elastica\Tests;

use FSi\Component\DataSource\Driver\Elastica\ElasticaDriver;
use FSi\Component\DataSource\Driver\Elastica\Extension\Core\CoreDriverExtension;
use FSi\Component\DataSource\Driver\Elastica\ElasticaFieldInterface;
use FSi\Component\DataSource\Field\FieldTypeInterface;

class ElasticaDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testDriverHasExtensions()
    {
        $driver = new ElasticaDriver(
            array(new CoreDriverExtension()),
            $this->getMock('Elastica\SearchableInterface')
        );

        $this->assertTrue($driver->hasFieldType('text'));
        $this->assertTrue($driver->hasFieldType('number'));
        $this->assertTrue($driver->hasFieldType('entity'));
        $this->assertTrue($driver->hasFieldType('date'));
        $this->assertTrue($driver->hasFieldType('time'));
        $this->assertTrue($driver->hasFieldType('datetime'));
        $this->assertTrue($driver->hasFieldType('boolean'));

        $this->assertFalse($driver->hasFieldType('unknown-field'));
    }

    public function fieldNameProvider()
    {
        return array(
            array('text'),
            array('number'),
            array('entity'),
            array('date'),
            array('time'),
            array('datetime'),
            array('boolean')
        );
    }

    /**
     * @dataProvider fieldNameProvider
     * @param $fieldName
     */
    public function testFields($fieldName)
    {
        $driver = new ElasticaDriver(
            array(new CoreDriverExtension()),
            $this->getMock('Elastica\SearchableInterface')
        );

        $this->assertTrue($driver->hasFieldType($fieldName));

        /** @var FieldTypeInterface $field */
        $field = $driver->getFieldType($fieldName);
        $this->assertTrue($field instanceof FieldTypeInterface);
        $this->assertTrue($field instanceof ElasticaFieldInterface);

        $comparisons = $field->getAvailableComparisons();
        $this->assertGreaterThan(0, count($comparisons));
    }
}
