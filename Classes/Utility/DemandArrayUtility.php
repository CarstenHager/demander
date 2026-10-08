<?php

declare(strict_types=1);

namespace Pixelant\Demander\Utility;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;

/**
 * Utility for processing and modifying demand arrays.
 */
class DemandArrayUtility
{
    /**
     * Returns a string with tablename-fieldname.
     *
     * @param string $table
     * @param string $field
     * @return string
     */
    public static function tableAndFieldNameToPropertyName(string $table, string $field): string
    {
        return $table . '-' . $field;
    }

    /**
     * Returns tablename-fieldname as [tablename, fieldname].
     *
     * @param string $string
     * @return array|null
     */
    public static function propertyNameToTableAndFieldName(string $string): ?array
    {
        return array_pad(explode('-', $string, 2), 2, '');
    }

    /**
     * Converts a demand array into a composite query expression.
     *
     * @param array $properties
     * @param ExpressionBuilder $expressionBuilder
     * @param string $conjunction
     * @return CompositeExpression
     */
    public static function toExpression(array $properties, ExpressionBuilder $expressionBuilder, string $conjunction = 'and'): CompositeExpression
    {
        $expressions = [];
        if (isset($properties['field'], $properties['alias']) && array_key_exists('value', $properties)) {
            $field = $properties['alias'] . '.' . $properties['field'];
            $expressions[] = self::convertRestrictionToExpression($field, $properties, $expressionBuilder);
            foreach ($properties['additionalRestriction'] ?? [] as $key => $restriction) {
                [$table, $column] = array_pad(explode('-', $key, 2), 2, '');
                if ($column !== '') {
                    $conjunction = $restriction['conjunction'] ?? $conjunction;
                    $alias = $table === ($properties['table'] ?? '') ? $properties['alias'] : $table;
                    $expressions[] = self::convertRestrictionToExpression($alias . '.' . $column, $restriction, $expressionBuilder);
                }
            }
        } else {
            foreach ($properties as $key => $property) {
                if (is_array($property)) {
                    $expressions[] = self::toExpression($property, $expressionBuilder, $key === 'or' ? 'or' : 'and');
                }
            }
        }
        return $conjunction === 'or' ? $expressionBuilder->or(...$expressions) : $expressionBuilder->and(...$expressions);
    }

    /**
     * Removes dots at the end of array keys when config fetches from TypoScript.
     *
     * @param array $array
     * @return array
     */
    public static function removeDotsFromKeys(array $array): array
    {
        $filteredArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_int($key)) {
                    $key = trim($key, '.');
                }
                $filteredArray[$key] = self::removeDotsFromKeys($value);
            } else {
                if (!is_int($key)) {
                    $key = trim($key, '.');
                }
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }

    /**
     * Looping through restrictions and looks for numeric values to transform it into integers.
     *
     * @param array $restrictionsArray
     * @return array
     */
    public static function restrictionsToInt(array $restrictionsArray): array
    {
        $restrictions = [];

        foreach ($restrictionsArray as $key => $restriction) {
            if (is_array($restriction)) {
                $restrictions[$key] = self::restrictionsToInt($restriction);
            } else {
                $value = (is_numeric($restriction)) ? (int)$restriction : $restriction;
                $restrictions[$key] = $value;
            }
        }

        return array_replace($restrictionsArray, $restrictions);
    }

    /**
     * @param string $fieldname
     * @param array $restrictions
     * @param ExpressionBuilder $expressionBuilder
     * @return string
     */
    public static function convertRestrictionToExpression(string $fieldname, array $restrictions, ExpressionBuilder $expressionBuilder): string
    {
        $value = $restrictions['value'] ?? null;
        $operator = $restrictions['operator'] ?? '=';
        // Die API nimmt einen ExpressionBuilder entgegen. Dessen literal() quotiert Werte
        // über die aktive Datenbankverbindung; Benutzereingaben werden nie als SQL eingesetzt.
        $quote = static function ($item) use ($expressionBuilder): string {
            if (!is_scalar($item) && $item !== null) {
                throw new \InvalidArgumentException('Demand values must be scalar.', 1728300001);
            }
            return (string)$expressionBuilder->literal((string)$item);
        };
        if ($operator === 'in') {
            $values = is_array($value) ? $value : explode(',', (string)$value);
            return $values === [] ? '1=0' : $expressionBuilder->in($fieldname, array_map($quote, $values));
        }
        if ($operator === '-') {
            if (!is_array($value) && preg_match('/^(-?\d+(?:\.\d+)?)-(-?\d+(?:\.\d+)?)$/', (string)$value, $matches)) {
                $value = ['min' => $matches[1], 'max' => $matches[2]];
            }
            if (!is_array($value) || !isset($value['min'], $value['max'])) {
                return '1=0';
            }
            return (string)$expressionBuilder->and(
                $expressionBuilder->gte($fieldname, $quote($value['min'])),
                $expressionBuilder->lte($fieldname, $quote($value['max']))
            );
        }
        $methods = ['=' => 'eq', '>' => 'gt', '>=' => 'gte', '<' => 'lt', '<=' => 'lte', '<>' => 'neq'];
        if (!isset($methods[$operator]) || (!is_scalar($value) && $value !== null)) {
            return '1=0';
        }
        return $expressionBuilder->{$methods[$operator]}($fieldname, $quote($value));
    }
}
