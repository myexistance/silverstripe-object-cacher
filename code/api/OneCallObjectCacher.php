<?php


class OneCallObjectCacher extends Object
{

    /**
     *
     * @var array
     */
    private static $_object_store = array();

    /**
     *
     * @var array
     */
    private static $_reference_to_full_key = array();


    public static function get_one($callerClass, $filter = "", $cache = true, $orderby = "")
    {
    }

    /**
     *
     * @var array
     */
    private static $_list_cache = array();


    public static function clear_all()
    {
        self::$_object_store = array();
        self::$_reference_to_full_key = array();
        self::$_list_cache = array();
    }


    public static function clear_objects()
    {
        self::$_object_store = array();
        self::$_reference_to_full_key = array();
    }

    public static function clear_lists()
    {
        self::$_list_cache = array();
    }


    /**
     * stores any dataobject
     * @param DataObject
     *
     * @return DataObject
     */
    public static function store_object($object, $key = null)
    {
        $originalKey = $key;
        $key .= '_'.$object->ClassName.'_'.$object->ID;
        self::$_reference_to_full_key[$originalKey] = $key;
        self::$_object_store[$key] = $object;

        return $object;
    }

    /**
     * stores any dataobject
     * @param string $key
     * @param string $className (optional)
     * @param int $id (optional)
     *
     * @return DataObject
     */
    public static function retrieve_object($key, $className = '', $id = 0)
    {
        $originalKey = $key;
        $key .= '_'.$className.'_'.$id;
        if (isset(self::$_object_store[$key])) {
        } else {
            if (isset(self::$_object_store[self::$_reference_to_full_key[$originalKey]])) {
                return self::$_object_store[self::$_reference_to_full_key[$originalKey]];
            }
        }

        return $object;
    }

    /**
     * clears all the references to one object...
     * returns true if found and false it not found ...
     *
     * @param  string $className
     * @param  int    $id
     *
     * @return bool
     */
    public static function clear_one_object($className, $id)
    {
        $keyEnd = '_'.$className.'_'.$id;
        $keyEndLength = strlen($keyEnd);
        foreach (self::$_object_store as $key => $ignore) {
            if (substr($key, -$keyEndLength) === $keyEnd) {
                $referenceKey = subst($key, 0, strlen($key) - $keyEndLength);
                unset(self::$_object_store[$key]);
                unset(self::$_reference_to_full_key[$referenceKey]);
                return true;
            }
        }

        return false;
    }

    /**
     * return a data list
     * @param SS_List
     * @param string $key
     *
     * @return SS_List
     */
    public static function store_list($dataList, $key)
    {
        self::$_list_cache[$key] = $dataList->column('ID');

        return $dataList;
    }

    /**
     *
     * @param  string $key
     *
     * @return array
     */
    public static function retrieve_list($key)
    {
        return self::$_list_cache[$key];
    }
}
