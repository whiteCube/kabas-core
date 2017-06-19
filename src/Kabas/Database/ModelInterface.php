<?php

namespace Kabas\Database;

interface ModelInterface
{
    public function getDriverInstance();

    public function getObjectName() : string;

    public function getRepository() : string;

    public function getStructurePath() : string;

    public function getFields();
}
