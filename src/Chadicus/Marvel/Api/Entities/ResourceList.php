<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;

/**
 * Resource lists are collections of summary views within the context of another entity type.
 */
class ResourceList
{
    /**
     * The number of total available resources in this list.
     *
     * @var integer
     */
    private $available;

    /**
     * The number of resources returned in this resource list (up to 20).
     *
     * @var integer
     */
    private $returned;

    /**
     * The path to the list of full view representations of the items in this resource list.
     *
     * @var string
     */
    private $collectionURI;

    /**
     * A list of summary views of the items in this resource list.
     *
     * @var array[]
     */
    private $items;

    /**
     * Construct a new ResourceList.
     *
     * @param integer $available     The number of total available resources in this list.
     * @param integer $returned      The number of resources returned in this resource list (up to 20).
     * @param string  $collectionURI The path to the list of full view representations of the items in this resource list.
     * @param array[] $items         A list of summary views of the items in this resource list.
     */
    final public function __construct($available, $returned, $collectionURI, array $items)
    {
        Util::throwIfNotType(['int' => [$available, $returned], 'string' => [$collectionURI]], false, true);

        $this->available = $available;
        $this->returned = $returned;
        $this->collectionURI = $collectionURI;
        $this->items = $items;

    }

    /**
     * Returns the number of total available resources in this list.
     *
     * @return integer
     */
    final public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Returns the number of resources returned in this resource list (up to 20).
     *
     * @return integer
     */
    final public function getReturned()
    {
        return $this->returned;
    }

    /**
     * Returns the path to the list of full view representations of the items in this resource list.
     *
     * @return string
     */
    final public function getCollectionURI()
    {
        return $this->collectionURI;
    }

    /**
     * Returns the list of summary views of the items in this resource list.
     *
     * @return array[]
     */
    final public function getItems()
    {
        return $this->items;
    }

    /**
     * Filters the given array $input into a ResourceList.
     *
     * @param array $input The value to be filtered.
     *
     * @return ResourceList
     *
     * @throws \Chadicus\Filter\Exception Thrown if the input did not pass validation.
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'available' => [['uint']],
            'returned' => [['uint']],
            'collectionURI' => [['string']],
            'items' => [['ofArrays', ['resourceURI' => [['string']], 'name' => [['string']], 'type' => [['string']]]]],
        ];

        list($success, $result, $error) = \DominionEnterprises\Filterer::filter($filters, $input);
        if (!$success) {
            throw new \Chadicus\Filter\Exception($error);
        }

        return new ResourceList(
            Arrays::get($result, 'available', 0),
            Arrays::get($result, 'returned', 0),
            Arrays::get($result, 'collectionURI'),
            Arrays::get($result, 'items', [])
        );
    }
}
