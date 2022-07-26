<?php

namespace Akyos\BuilderBundle\Interfaces;

interface ComponentInterface
{
    /**
     * @return string
     */
    public static function getTemplateName();

    /**
     * @param $params
     *
     * @return array
     */
    public function getParameters($params);
}
