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
use Contao\Controller;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Input;
use Contao\PageModel;
use Contao\Template;
use Respinar\ProjectsBundle\Model\ProjectArchiveModel;
use Respinar\ProjectsBundle\Model\ProjectModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('project_reader', category: 'projects')]
class ProjectReaderController extends AbstractContentElementController
{
    public function __construct(
        private readonly Studio $studio,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $alias = Input::get('auto_item');

        if (!$alias) {
            return new Response('');
        }

        $project = ProjectModel::findOneBy('alias', $alias);

        if ($project === null) {
            return new Response('');
        }

        $archive = ProjectArchiveModel::findByPk($project->pid);

        if ($archive === null) {
            return new Response('');
        }

        // Check publishing status and time window
        $time = time();
        if (!$project->published || ($project->start && $project->start > $time) || ($project->stop && $project->stop <= $time)) {
            return new Response('');
        }

        // Set page meta data
        $page = $GLOBALS['objPage'] ?? null;
        if ($page instanceof PageModel) {
            if ($project->pageTitle) {
                $page->pageTitle = $project->pageTitle;
            }
            if ($project->description) {
                $page->description = $project->description;
            }
            if ($project->robots) {
                $page->robots = $project->robots;
            }
        }

        // Compile project content elements lazily
        $id = $project->id;

        $text = Template::once(function () use ($id): string {
            $strText = '';
            $objElement = ContentModel::findPublishedByPidAndTable($id, 'tl_company_project');

            if ($objElement !== null) {
                foreach ($objElement as $element) {
                    $strText .= Controller::getContentElement($element->id);
                }
            }

            return $strText;
        });

        $hasText = Template::once(static function () use ($id): bool {
            return ContentModel::countPublishedByPidAndTable($id, 'tl_company_project') > 0;
        });

        // Back link to overview page
        $backLink = '';
        if ($archive->overviewPage) {
            $overviewPage = PageModel::findByPk($archive->overviewPage);
            if ($overviewPage !== null) {
                $backLink = $overviewPage->getFrontendUrl();
            }
        }

        $figure = null;
        if ($project->singleSRC) {
            $figure = $this->studio
                ->createFigureBuilder()
                ->from($project->singleSRC)
                ->setSize($model->size)
                ->buildIfResourceExists();
        }

        $template->set('project', array(
            'title' => $project->title,
            'alias' => $project->alias,
            'date' => $project->date,
            'completionDate' => $project->completionDate,
            'summary' => $project->summary,
            'featured' => $project->featured,
        ));
        $template->set('figure', $figure);
        $template->set('text', $text);
        $template->set('hasText', $hasText);
        $template->set('back_link', $backLink);

        return $template->getResponse();
    }
}
