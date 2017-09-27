<?php

namespace Kabas\Database;

interface ModelInterface
{
    public function getObjectName() : string;
    public function getRepositoryName() : string;
    public function getStructureFilename() : string;
    public function getStructurePath() : string;
    public function getRepositoryPath($locale = null) : string;
    public function getRepositories() : array;
    public function getRawFields();
    public function isTranslatable() : bool;
}
