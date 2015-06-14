<?php

namespace Genedys\Processor;


class JsonCalculator
{
    /**
     * @param string $json
     * @return mixed
     */
    public function calc($json)
    {
        $data = json_decode($json, true);

        return $this->process($data);
    }

    /**
     * @param array $data
     * @return array|bool
     */
    private function process(array $data)
    {
        switch ($data['type']) {
            case '+':
                return $this->add($data['value']);
            case '-':
                return $this->sub($data['value']);
            case '*':
                return $this->mult($data['value']);
            default:
                return false;
        }
    }

    /**
     * @param array $data
     * @return number
     */
    private function add(array $data)
    {
        $values = [];
        foreach ($data as $item) {
            if (strpos('+-*', $item['type'])) {
                $values[] = $this->process($item);
            } else {
                $values[] = $item['value'];
            }
        }

        return array_sum($values);
    }

    /**
     * @param array $data
     * @return number
     */
    private function sub(array $data)
    {
        $values = [];
        foreach ($data as $item) {
            if (strpos('+-*', $item['type'])) {
                $values[] = $this->process($item);
            } else {
                $values[] = $item['value'];
            }
        }

        $result = array_shift($values);
        foreach ($values as $value) {
            $result -= $value;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return number
     */
    private function mult(array $data)
    {
        $values = [];
        foreach ($data as $item) {
            if (strpos('+-*', $item['type'])) {
                $values[] = $this->process($item);
            } else {
                $values[] = $item['value'];
            }
        }

        return array_product($values);
    }
}