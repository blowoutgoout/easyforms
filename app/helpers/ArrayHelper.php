<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\helpers;

use Yii;

/**
 * Class ArrayHelper
 * @package app\helpers
 * @extends \yii\helpers\ArrayHelper
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /**
     * Filter array by column and key value.
     *
     * @param array $array The array to iterate over.
     * @param mixed $value The filtered value.
     * @param mixed $column_key The column of values where to filter.
     * @return array The filtered array.
     * @throws \InvalidArgumentException If $array is neither an array.
     */
    public static function filter($array, $value = null, $column_key = null)
    {

        if (!is_array($array)) {
            throw new \InvalidArgumentException(Yii::t("app", "The first parameter must be an array."));
        }

        if ($value && $column_key) {
            $filteredArray = array_filter($array, function ($field) use ($column_key, $value) {
                return isset($field[$column_key]) && $field[$column_key] == $value;
            });
            return $filteredArray;
        }

        return array_filter($array); // Remove null, false and '' values
    }

    /**
     * Exclude array by column and key value(s)
     *
     * If the column does not exist, return true
     * If the value es boolean, return true if the column exist
     *
     * @param array $array The array to iterate over.
     * @param mixed $value The filtered value.
     * @param mixed $column_key The column of values where to filter.
     * @return array The filtered array.
     * @throws \InvalidArgumentException If $array is neither an array.
     */
    public static function exclude($array, $value = null, $column_key = null)
    {

        if (!is_array($array)) {
            throw new \InvalidArgumentException(Yii::t("app", "The first parameter must be an array."));
        }

        if ($value && $column_key) {
            $filteredArray = array_filter($array, function ($field) use ($column_key, $value) {
                if (is_bool($value) || is_string($value)) {
                    if (isset($field[$column_key])) {
                        if ($field[$column_key] !== $value) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } elseif (is_array($value)) {
                    if (isset($field[$column_key])) {
                        if (!in_array($field[$column_key], $value)) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    throw new \InvalidArgumentException(Yii::t("app", "The value must be boolean, string or array."));
                }
            });
            return $filteredArray;
        }

        return array_filter($array); // Remove null, false and '' values
    }

    /**
     * Group arrays by a column key
     *
     * @param $array
     * @param $column_key
     * @return array
     */
    public static function group($array, $column_key)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException(Yii::t("app", "The first parameter must be an array."));
        }
        $group = array();
        foreach ($array as $val) {
            $group[$val[$column_key]][] = $val;
        }
        return $group;
    }

    /**
     * Return the first value/array of an array
     *
     * @param $arrays
     * @return array
     */
    public static function first($arrays)
    {
        if (!is_array($arrays)) {
            throw new \InvalidArgumentException(Yii::t("app", "The first parameter must be an array."));
        }
        $firsts = array();
        foreach ($arrays as $array) {
            if (isset($array[0]) && is_array($array[0])) {
                // $first = array_shift(array_slice($array, 0, 1)); // Older PHP versions
                $first = array_values($array)[0]; // Need PHP 5.4+
                array_push($firsts, $first);
            } else {
                return $array;
            }
        }
        return $firsts;
    }

    /**
     * Return the values from a single column in the input array.
     * Provides functionality for array_column() to projects using PHP earlier than 5.5 version.
     * See https://github.com/ramsey/array_column
     *
     * @param $array
     * @param string $column_key The column of values to return.
     * @param string $index_key The column to use as the index/keys for the returned array.
     * @return array Returns an array of values representing a single column from the input array
     * @throws \InvalidArgumentException If $array is neither an array.
     */
    public static function column($array, $column_key, $index_key)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException(Yii::t("app", "The first parameter must be an array."));
        }

        return array_column($array, $column_key, $index_key);
    }
}
