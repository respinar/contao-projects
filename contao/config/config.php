<?php

declare(strict_types=1);

/*
 * This file is part of Contao Projects Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Respinar\ProjectsBundle\Model\ProjectArchiveModel;
use Respinar\ProjectsBundle\Model\ProjectModel;

// Back end modules
$GLOBALS['BE_MOD']['company']['projects'] = array
(
    'tables' => array('tl_company_project_archive', 'tl_company_project', 'tl_content'),
);

// Add permissions
$GLOBALS['TL_PERMISSIONS'][] = 'projects';

// Models
$GLOBALS['TL_MODELS']['tl_company_project_archive'] = ProjectArchiveModel::class;
$GLOBALS['TL_MODELS']['tl_company_project'] = ProjectModel::class;
