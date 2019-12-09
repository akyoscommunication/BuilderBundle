<?php

namespace Akyos\BuilderBundle\Interfaces;

interface ComponentInterface
{
    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @param $params
     *
     * @return array
     */
    public function getParameters($params);
}
