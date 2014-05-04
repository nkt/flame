<?php

namespace Flame\Grammar;

/**
 * Grammar
 * @author Gusakov Nikita <dev@nkt.me>
 */
class Grammar
{
    const QUOTE_CHAR = '"';
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    const TIME_FORMAT = 'H:i:s';

    /**
     * @param string $id
     *
     * @return string
     */
    public function buildId($id)
    {
        $parts = explode(' ', $id, 2);
        if (count($parts) === 2) {
            return $this->buildIdWithAlias($parts[0], $parts[1]);
        }

        return $this->wrap($id);
    }

    /**
     * @param string $id
     * @param string $alias
     *
     * @return string
     */
    public function buildIdWithAlias($id, $alias)
    {
        return $this->wrap($id) . ' AS ' . $this->wrap($alias);
    }

    /**
     * @param \DateTime|int|string $value
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function buildDateTime($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format(static::DATE_TIME_FORMAT);
        } elseif (is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);

            return $date->format(static::DATE_TIME_FORMAT);
        } elseif (is_string($value)) {
            $date = new \DateTime($value);

            return $date->format(static::DATE_TIME_FORMAT);
        }
        throw new \InvalidArgumentException('DateTime value should be int, string or \DateTime');
    }

    /**
     * @param \DateTime|int|string $value
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function buildTime($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format(static::TIME_FORMAT);
        } elseif (is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);

            return $date->format(static::TIME_FORMAT);
        } elseif (is_string($value)) {
            $date = new \DateTime($value);

            return $date->format(static::TIME_FORMAT);
        }
        throw new \InvalidArgumentException('Time value should be int, string or \DateTime');
    }

    /**
     * @param string $id
     *
     * @return string
     */
    protected function wrap($id)
    {
        return join('.', array_map(function ($id) {
            return static::QUOTE_CHAR . $id . static::QUOTE_CHAR;
        }, explode('.', $id, 3)));
    }
} 
