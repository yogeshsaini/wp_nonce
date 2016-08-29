<?php

namespace Nonces;

/**
 * Class Nonce
 * @package Nonces
 */
class Nonce implements NonceInterface, ConfigurableInterface
{
    use GeneratorTrait, ConfigurableTrait;

    /**
     * @var string|int
     */
    private $action;
    /**
     * @var string
     */
    private $algorithm = 'md5';
    /**
     * @var int
     */
    private $lifespan = 86400;
    /**
     * @var string
     */
    private $salt;
    /**
     * @var string
     */
    private $sessionToken;
    /**
     * @var int
     */
    private $userId;

    /**
     * Nonce constructor.
     *
     * @param string|int $action Action string to use in Nonce generation
     * @param ConfigInterface $config
     */
    public function __construct($action = -1, ConfigInterface $config = null)
    {
        $this->action = $action;

        if (is_null($config)) {
            $config = new Config();
        }

        $this->lifespan     = $config->getLifespan();
        $this->algorithm    = $config->getAlgorithm();
        $this->salt         = $config->getSalt();
        $this->sessionToken = $config->getSessionToken();
        $this->userId       = $config->getUserId();
    }
    
    /**
     * Generates the nonce string
     *
     * @return hash generated string
     */
    public function generate()
    {
        return $this->hash($this->data());
    }

    /**
     * Return nonce string if object is casted to string
     *
     * @return hash generated nonce string
     */
    public function __toString()
    {
        return $this->generate();
    }

    

}