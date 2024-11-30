<?php

namespace olcaytaner\DataStructure;

class CounterHashMap
{
    private array $hashMap;

    public function __construct()
    {
        $this->hashMap = [];
    }

    /**
     * The put method takes a K type input. If this map contains a mapping for the key, it puts this key after
     * incrementing its value by one. If his map does not contain a mapping, then it directly puts key with the value of 1.
     *
     * @param int | string $key to put.
     */
    public function put(int | string $key): void{
        if (array_key_exists($key, $this->hashMap)) {
            $this->hashMap[$key] = $this->hashMap[$key] + 1;
        } else {
            $this->hashMap[$key] = 1;
        }
    }

    /**
     * The putNTimes method takes a K and an integer N as inputs. If this map contains a mapping for the key, it puts this key after
     * incrementing its value by N. If his map does not contain a mapping, then it directly puts key with the value of N.
     *
     * @param int | string $key to put.
     * @param int $n   to increment value.
     */
    public function putNTimes(int | string $key, int $n): void{
        if (array_key_exists($key, $this->hashMap)) {
            $this->hashMap[$key] += $n;
        } else {
            $this->hashMap[$key] = $n;
        }
    }

    /**
     * The count method takes a K as input, if this map contains a mapping for the key, it returns the value corresponding
     * this key, 0 otherwise.
     *
     * @param int | string $key to get value.
     * @return int the value corresponding given key, 0 if it is not mapped.
     */
    public function count(int | string $key): int{
        if (array_key_exists($key, $this->hashMap)) {
            return $this->hashMap[$key];
        } else {
            return 0;
        }
    }

    /**
     * The sumOfCounts method loops through the values contained in this map and accumulates the counts of these values.
     *
     * @return int accumulated counts.
     */
    public function sumOfCounts(): int{
        $sum = 0;
        foreach ($this->hashMap as $key => $value) {
            $sum += $value;
        }
        return $sum;
    }

    /**
     * The max method takes a threshold as input and loops through the mappings contained in this map. It accumulates the
     * count values and if the current entry's count value is greater than maxCount, which is initialized as 0,
     * it updates the maxCount as current count and maxKey as the current count's key.
     * <p>
     * At the end of the loop, if the ratio of maxCount/total is greater than the given threshold it returns maxKey,
     * else null.
     *
     * @param float $threshold double value.
     * @return int | string | null type maxKey if greater than the given threshold, null otherwise.
     */
    public function max(float $threshold): int | string | null
    {
        $maxCount = 0;
        $total = 0;
        foreach ($this->hashMap as $key => $value) {
            $total += $value;
            if ($value > $maxCount) {
                $maxCount = $value;
                $maxKey  = $key;
            }
        }
        if ($maxCount / $total > $threshold) {
            return $maxKey;
        } else {
            return null;
        }
    }

    /**
     * The add method adds value of each key of toBeAdded to the current counterHashMap.
     *
     * @param array $toBeAdded CounterHashMap to be added to this counterHashMap.
     */
    public function add(array $toBeAdded): void{
        foreach ($toBeAdded as $key => $value) {
            $this->putNTimes($key, $value);
        }
    }

    /**
     * The topN method takes an integer N as inout. It creates an {@link Array} result and loops through the
     * mappings contained in this map and adds each entry to the result {@link Array}. Then sort this {@link Array}
     * according to their values and returns an {@link Array} which is a sublist of result with N elements.
     *
     * @param int $n Integer value for defining size of the sublist.
     * @return array a sublist of N element.
     */
    public function topN(int $n): array {
        $result = [];
        foreach ($this->hashMap as $key => $value) {
            $result[$key] = $value;
        }
        for ($i = 0; $i < count($result); $i++) {
            for ($j = $i + 1; $j < count($result); $j++) {
                if ($result[$i] < $result[$j]) {
                    $tmp = $result[$i];
                    $result[$i] = $result[$j];
                    $result[$j] = $tmp;
                }
            }
        }
        return array_slice($result, 0, $n, true);
    }
}