<?php

namespace Nonces;

/**
 * Interface ConfigInterface
 * @package Nonces
 */
interface ConfigInterface
{

    /**
     * @return int
     */
    public function getLifespan();

    /**
     * @return string
     */
    public function getAlgorithm();

    /**
     * @return string
     */
    public function getSalt();

    /**
     * @return string
     */
    public function getSessionToken();

    /**
     * @return int
     */
    public function getUserId();

}