<?php

interface ModorganizerRepository {
    function search();
    function getDownloadMetafiles(array $dirPaths): Array;
    function getModMetafiles(string $dirPath): Array;
}
