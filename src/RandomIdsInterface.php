<?php
/**
 * @author Will Lin <will@cn09.com>
 */

namespace RandomIds;
interface RandomIdsInterface
{
    /**
     * This function is the main function,it will pop an ID from the random store
     * @param int $lastId if your random store accidentally lost, library will create a new high-level storage store,all
     * new IDs will much larger than the $lastId
     * @return integer ID
     */
    function getId(int $lastId);


    /**
     * Set the number of ID generated at a time
     * @param int $limit
     */
    function setLimit(int $limit);
}