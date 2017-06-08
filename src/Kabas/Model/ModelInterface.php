<?php

namespace Kabas\Model;

interface ModelInterface
{
    public function makeDriverInstance();

    public function getObjectName() : string;

    public function getRepository() : string;

    public function getStructurePath() : string;
}
