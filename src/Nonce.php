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
    public function generate($action = -1)
    {
        $user = wp_get_current_user();
        $uid = (int) $user->ID;
        if ( ! $uid ) {
            /** This filter is documented in wp-includes/pluggable.php */
            $uid = apply_filters( 'nonce_user_logged_out', $uid, $action );
        }
    
        $token = wp_get_session_token();
        $i = wp_nonce_tick();
    
        return substr( wp_hash( $i . '|' . $action . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );
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