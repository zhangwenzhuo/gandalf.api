<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:12
 */

namespace App\Validators;

class DecisionStructValidator
{
    public function decision($attribute, $value)
    {
        if (!is_array($value) or !isset($value['fields']) or !isset($value['rules']) or !is_array($value['rules'])) {
            return false;
        }

        $fields = ['title', 'alias', 'type', 'source'];
        foreach ($value['fields'] as $request_field) {
            foreach ($fields as $field) {
                if (!array_key_exists($field, $request_field)) {
                    return false;
                }
            }
        }

        $table_aliases = array_map(function ($value) {
            return $value['alias'];
        }, $value['fields']);

        $rules_fields = ['decision', 'description', 'conditions'];
        $condition_fields = ['field_alias', 'condition', 'value'];

        foreach ($value['rules'] as $item) {
            if (!is_array($item)) {
                return false;
            }

            foreach ($rules_fields as $key) {
                if (!array_key_exists($key, $item)) {
                    return false;
                }
            }

            if (!is_array($item['conditions'])) {
                return false;
            }
            foreach ($item['conditions'] as $condition) {
                if (count($condition_fields) != count($condition)) {
                    return false;
                }

                foreach ($condition_fields as $table_field) {
                    if (!array_key_exists($table_field, $condition)) {
                        return false;
                    }
                    if (!in_array($condition['field_alias'], $table_aliases)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}