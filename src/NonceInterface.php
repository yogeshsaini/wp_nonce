<?php

namespace Nonces;

interface NonceInterface
{

    public function __toString();

    public function generate();

}