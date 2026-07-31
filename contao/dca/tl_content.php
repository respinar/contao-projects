<?php

declare(strict_types=1);

/*
 * This file is part of Contao Projects Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\DataContainer;

// Palettes
$GLOBALS['TL_DCA']['tl_content']['palettes']['project_list'] = '{type_legend},type;{project_legend},project_archives;{image_legend},size;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['project_reader'] = '{type_legend},type;{image_legend},size;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['project_archives'] = array
(
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_company_project_archive.title',
    'eval'                    => array('multiple'=>true, 'mandatory'=>true),
    'sql'                     => array('type'=>'blob', 'length'=>65535, 'notnull'=>false)
);
