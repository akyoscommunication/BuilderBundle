<?php

declare(strict_types=1);

namespace Akyos\BuilderBundle\Interfaces;

interface ComponentInterface
{
    /**
     * @return string
     */
    public static function getTemplateName();

    /**
     * @param $params
     * @return array
     */
    public function getParameters($params);
}
