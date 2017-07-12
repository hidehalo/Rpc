<?php

namespace Stub\Client\ClientStubInterface;

interface ClientStubInterface
{
    public function import();

    public function procedure();

    public function encode();

    public function send();
}