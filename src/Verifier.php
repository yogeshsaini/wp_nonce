<?php

namespace Nonces;

class Verifier implements VerifierInterface, ConfigurableInterface
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
     * Verifier constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
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
     * @param string $nonce
     * @param string|int $action
     *
     * @return bool|int
     */
    public function verify($nonce, $action = -1)
    {
        $this->action = $action;
        $nonce        = (string)$nonce;

        if (empty($nonce)) {
            return false;
        }
        $user = wp_get_current_user();
        $uid = (int) $user->ID;
        if ( ! $uid ) {
            /**
             * Filter whether the user who generated the nonce is logged out.
             * @param int    $uid    ID of the nonce-owning user.
             * @param string $action The nonce action.
             */
            $uid = apply_filters( 'nonce_user_logged_out', $uid, $action );
        }
        $token = wp_get_session_token();
        $i = wp_nonce_tick();
        // Nonce generated 0-12 hours ago
        $expected = substr( wp_hash( $i . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10 );
        if ( hash_equals( $expected, $nonce ) ) {
            return 1;
        }
        
        // Nonce generated 12-24 hours ago
        $expected = substr( wp_hash( ( $i - 1 ) . '|' . $action . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );
        if ( hash_equals( $expected, $nonce ) ) {
            return 2;
        }
        /**
        * Fires when nonce verification fails.
        * @param string     $nonce  The invalid nonce.
        * @param string|int $action The nonce action.
        * @param WP_User    $user   The current user object.
        * @param string     $token  The user's session token.
        */
       do_action( 'wp_verify_nonce_failed', $nonce, $action, $user, $token );
        return false;
    }

}