<?php

namespace Wicked_Block_Conditions;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

/**
 * Iterates through an array of objects.
 */
abstract class Object_Collection implements \Countable, \Iterator {

    /**
     * @var integer The current index within the collection.
     */
    protected $index = 0;

    /**
     * @var array Holds the collection's objects.
     */
    protected $items = array();

    /**
     * @var WP_Rule The current rule.
     */
    protected $item = null;

    /**
     * Count implementation.
     */
    public function count(): int {
        return count( $this->items );
    }

    /**
     * Current implementation.
     */
    public function current(): mixed {
        return $this->items[ $this->index ];
    }

    /**
     * Key implementation.
     */
    public function key(): mixed {
        return $this->index;
    }

    /**
     * Next implementation.
     */
    public function next(): void {
        ++$this->index;
    }

    /**
     * Rewind implementation.
     */
    public function rewind(): void {
        $this->index = 0;
    }

    /**
     * Valid implementation.
     */
    public function valid(): bool {
        return isset( $this->items[ $this->index ] );
    }

	/**
	 * Add an item to the collection.
	 */
	public abstract function add( $item );

	/**
	 * Sorts the items in the collection by their 'order' property (if one exists).
	 */
	public function sort() {
		usort( $this->items, function( $a, $b ) {
			if ( isset( $a->order ) && isset( $b->order ) ) {
				if ( $a->order == $b->order ) {
					return 0;
				}
				return ( $a->order < $b->order ) ? -1 : 1;
			}
			return 0;
		} );
		return $this->items;
	}

	/**
	 * Filters the items within the collection.
	 *
	 * @see wp_filter_object_list()
	 *
	 * @param array $args
	 *  An array of property value pairs to filter by.
	 * @param string $operator
	 *  The comparision operator to use. Either 'and' or 'or'.
	 * @return Object_Collection
	 *  A new collection with the matching items.
	 */
	public function filter( $args = array(), $operator = 'and' ) {
		// Get current class name
		$class = get_class( $this );

		// Instantiate a new collection
		$collection = new $class();

		// Filter the items in the current collection
		$items = wp_filter_object_list( $this->items, $args, $operator );

		// Now add them to our new collection
		foreach ( $items as $item ) {
			$collection->add( $item );
		}

		return $collection;
	}

	/**
	 * Adds the item to the collection if it is the correct type, otherwise
	 * throws an error.
	 *
	 * @param mixed $item
	 *  The item to add.
	 * @param class
	 *  The class ob object that the item must be.
	 */
	protected function add_if( $item, $type ) {
		if ( is_a( $item, $type ) ) {
			$this->items[] = $item;
		} else {
			throw new \Exception( __( 'Item must be ', 'wicked-logic' ) . $type );
		}
	}

	/**
	 * Returns whether or not the collection is empty.
	 *
	 * @return boolen
	 *  True if the collection contains no items, false otherwise.
	 */
	public function is_empty(): bool {
		return $this->count() < 1;
	}
}
