<?php

namespace Auth\Service;

/**
 * Class ActiveDirectoryResponseStatus
 *
 * @package Auth\Service
 *
 * @author  Damien Lagae <damien.lagae@enabel.be>
 */
class ActiveDirectoryResponseStatus
{
    const NOTHING_TO_DO = 0;
    const ACTION_NEEDED = 1;
    const DONE = 100;
    const FAILED = 200;
    const EXCEPTION = 500;
}
