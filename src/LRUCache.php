<?php

namespace olcaytaner\DataStructure;

class LRUCache
{
    private int $cacheSize;
    private array $map;

    /**
     * A constructor of LRUCache class which takes cacheSize as input. It creates new Map and
     * Map.
     *
     * @param int $cacheSize Integer input defining cache size.
     */
    public function __construct(int $cacheSize){
        $this->cacheSize = $cacheSize;
        $this->map = [];
    }

    /**
     * The contains method takes a K type input key and returns true if the Map has the given key, false otherwise.
     *
     * @param string | int $key K type input key.
     * @return bool true if the Map has the given key, false otherwise.
     */
    public function contains(string | int $key): bool{
        return array_key_exists($key, $this->map);
    }

    /**
     * The get method takes K type input key and returns the least recently used value. First it checks whether the {@link Map}
     * has the given key, if so it gets the corresponding cacheNode. It removes that element from map
     * and adds it again to the beginning of the map since it is more likely to be used again. At the end, returns the
     * data value.
     *
     * @param string|int $key K type input key.
     * @return ?object data value if the Map has the given key, null otherwise.
     */
    public function get(string|int $key): ?object{
        if (array_key_exists($key, $this->map)) {
            $value = $this->map[$key];
            unset($this->map[$key]);
            $this->map[$key] = $value;
            return $value;
        }
        return null;
    }

    /**
     * The add method take a key and a data as inputs. First it checks the size of the Map, if it is full (i.e
     * equal to the given cacheSize) then it removes the last node. If it has space for new entries,
     * it creates new node with given inputs and puts it to the Map.
     *
     * @param string|int $key type input.
     * @param object $value type input
     */
    public function add(string|int $key, object $value): void{
        if (count($this->map) == $this->cacheSize) {
            $key = $this->map[0];
            unset($this->map[$key]);
        }
        $this->map[$key] = $value;
    }
}