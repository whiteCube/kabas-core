<?php

namespace Kabas\Database;

interface ModelInterface
{
    public function getObjectName() : string;
    public function getRepository() : string;
    public function getStructurePath() : string;
    public function getRawFields();
    public function isTranslatable() : bool;
}
