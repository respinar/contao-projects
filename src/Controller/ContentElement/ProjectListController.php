<?php

declare(strict_types=1);

/*
 * This file is part of Contao Projects Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\ProjectsBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Respinar\ProjectsBundle\Model\ProjectArchiveModel;
use Respinar\ProjectsBundle\Model\ProjectModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('project_list', category: 'projects')]
class ProjectListController extends AbstractContentElementController
{
    public function __construct(
        private readonly Studio $studio,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $archives = array_map('intval', (array) StringUtil::deserialize($model->project_archives, true));
        $archives = array_filter($archives);

        if (empty($archives)) {
            return new Response('');
        }

        $time = time();
        $columns = array(
            'pid IN (' . implode(',', array_fill(0, count($archives), '?')) . ')',
            'published=1',
            "(start='' OR start<=$time)",
            "(stop='' OR stop>$time)"
        );

        $values = $archives;

        $projectCollection = ProjectModel::findBy($columns, $values, array('order' => 'date DESC'));

        if ($projectCollection === null) {
            return new Response('');
        }

        // Preload all images in one query
        $uuids = array();
        foreach ($projectCollection as $project) {
            if ($project->singleSRC) {
                $uuids[] = $project->singleSRC;
            }
        }
        if (!empty($uuids)) {
            FilesModel::findMultipleByUuids($uuids);
        }

        $projects = array();
        foreach ($projectCollection as $project) {
            $archive = ProjectArchiveModel::findByPk($project->pid);
            $href = '';

            if ($archive !== null && $archive->jumpTo) {
                $page = PageModel::findByPk($archive->jumpTo);
                if ($page !== null) {
                    $href = $page->getFrontendUrl('/' . $project->alias);
                }
            }

            $figure = null;
            if ($project->singleSRC) {
                $figureBuilder = $this->studio
                    ->createFigureBuilder()
                    ->from($project->singleSRC)
                    ->setSize($model->size);

                // For list: wrap image with link to reader if no link exists yet
                if ($href) {
                    $figureBuilder->setLinkHref($href);
                }

                $figure = $figureBuilder->buildIfResourceExists();
            }

            $projects[] = array(
                'title' => $project->title,
                'alias' => $project->alias,
                'date' => $project->date,
                'completionDate' => $project->completionDate,
                'summary' => $project->summary,
                'featured' => $project->featured,
                'figure' => $figure,
                'href' => $href,
            );
        }

        $template->set('projects', $projects);

        return $template->getResponse();
    }
}
