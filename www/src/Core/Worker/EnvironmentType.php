<?php

/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 07.01.17
 * Time: 21:06
 */

namespace TBCore\Worker;

abstract class EnvironmentType
{
    const TEST = 0;
    const STAGING = 1;
    const LIVE = 2;
}
