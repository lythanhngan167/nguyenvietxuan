<?php
defined('_JEXEC') or die('Restricted access');
?><?php

class QueueController extends acymController
{
    public function __construct()
    {
        parent::__construct();
        $this->breadcrumb[acym_translation('ACYM_QUEUE')] = acym_completeLink('queue');
        $this->setDefaultTask('campaigns');
    }

    public function campaigns()
    {
        acym_setVar('layout', 'campaigns');
        $pagination = acym_get('helper.pagination');

        if (acym_level(1) && $this->config->get('cron_last', 0) < (time() - 43200)) {
            acym_enqueueMessage(acym_translation('ACYM_CREATE_CRON_REMINDER').' <a id="acym__queue__configure-cron" href="'.acym_completeLink('configuration&tab=queue').'">'.acym_translation('ACYM_GOTO_CONFIG').'</a>', 'warning');
        }

        $searchFilter = acym_getVar('string', 'cqueue_search', '');
        $tagFilter = acym_getVar('string', 'cqueue_tag', '');
        $status = acym_getVar('string', 'cqueue_status', '');

        $campaignsPerPage = $pagination->getListLimit();
        $page = acym_getVar('int', 'cqueue_pagination_page', 1);

        $queueClass = acym_get('class.queue');
        $matchingElements = $queueClass->getMatchingCampaigns(
            [
                'search' => $searchFilter,
                'tag' => $tagFilter,
                'status' => $status,
                'campaignsPerPage' => $campaignsPerPage,
                'offset' => ($page - 1) * $campaignsPerPage,
            ]
        );

        $campaignClass = acym_get('class.campaign');

        $pagination->setStatus($matchingElements['total'], $page, $campaignsPerPage);

        $viewData = [
            'allElements' => $matchingElements['elements'],
            'pagination' => $pagination,
            'search' => $searchFilter,
            'tag' => $tagFilter,
            'tags' => acym_get('class.tag')->getAllTagsByType('mail'),
            'numberPerStatus' => $matchingElements['status'],
            'status' => $status,
            'campaignClass' => $campaignClass,
        ];

        $this->breadcrumb[acym_translation('ACYM_CAMPAIGNS')] = acym_completeLink('queue');
        parent::display($viewData);
    }

    public function automated()
    {
    }

    public function detailed()
    {
        acym_setVar("layout", "detailed");
        $pagination = acym_get('helper.pagination');

        $searchFilter = acym_getVar('string', 'dqueue_search', '');
        $tagFilter = acym_getVar('string', 'dqueue_tag', '');

        $elementsPerPage = $pagination->getListLimit();
        $page = acym_getVar('int', 'dqueue_pagination_page', 1);

        $queueClass = acym_get('class.queue');
        $matchingElements = $queueClass->getMatchingResults(
            [
                'search' => $searchFilter,
                'tag' => $tagFilter,
                'elementsPerPage' => $elementsPerPage,
                'offset' => ($page - 1) * $elementsPerPage,
            ]
        );

        $pagination->setStatus($matchingElements['total'], $page, $elementsPerPage);

        $viewData = [
            'allElements' => $matchingElements['elements'],
            'pagination' => $pagination,
            'search' => $searchFilter,
            'tag' => $tagFilter,
            'tags' => acym_get('class.tag')->getAllTagsByType('mail'),
        ];

        $this->breadcrumb[acym_translation('ACYM_QUEUE_DETAILED')] = acym_completeLink('queue&task=detailed');
        parent::display($viewData);
    }

    public function scheduleReady()
    {
        $queueClass = acym_get('class.queue');
        $queueClass->scheduleReady();
    }

    public function continuesend()
    {
        if ($this->config->get('queue_type') == 'onlyauto') {
            acym_setNoTemplate();
            acym_display(acym_translation('ACYM_ONLYAUTOPROCESS'), 'warning');

            exit;
        }

        $newcrontime = time() + 120;
        if ($this->config->get('cron_next') < $newcrontime) {
            $newValue = new stdClass();
            $newValue->cron_next = $newcrontime;
            $this->config->save($newValue);
        }

        $mailid = acym_getCID('id');

        $totalSend = acym_getVar('int', 'totalsend', 0);
        if (empty($totalSend)) {
            $query = 'SELECT COUNT(queue.user_id) FROM #__acym_queue AS queue LEFT JOIN #__acym_campaign AS campaign ON queue.mail_id = campaign.mail_id WHERE (campaign.id IS NULL OR campaign.active = 1) AND queue.sending_date < '.acym_escapeDB(acym_date('now', 'Y-m-d H:i:s', false));
            if (!empty($mailid)) {
                $query .= ' AND queue.mail_id = '.intval($mailid);
            }
            $totalSend = acym_loadResult($query);
        }

        $alreadySent = acym_getVar('int', 'alreadysent', 0);

        $helperQueue = acym_get('helper.queue');
        $helperQueue->id = $mailid;
        $helperQueue->report = true;
        $helperQueue->total = $totalSend;
        $helperQueue->start = $alreadySent;
        $helperQueue->pause = $this->config->get('queue_pause');
        $helperQueue->process();

        acym_setNoTemplate();
        exit;
    }

    public function cancelSending()
    {
        $mailId = acym_getVar('int', 'acym__queue__cancel__mail_id');

        if (!empty($mailId)) {
            $hasStat = acym_loadResult("SELECT COUNT(*) FROM #__acym_user_stat WHERE mail_id = ".intval($mailId));

            $result = [];

            $result[] = acym_query('DELETE FROM #__acym_queue WHERE mail_id = '.intval($mailId));
            $result[] = acym_query('UPDATE #__acym_mail_stat SET total_subscribers = sent WHERE mail_id = '.intval($mailId));
            $result[] = acym_query('UPDATE #__acym_campaign SET active = 1 WHERE mail_id = '.intval($mailId));
            if (empty($hasStat)) {
                $result[] = acym_query('UPDATE #__acym_campaign SET draft = "1", sent = "0", sending_date = NULL WHERE mail_id = '.intval($mailId));
                $result[] = acym_query('DELETE FROM #__acym_mail_stat WHERE mail_id = '.intval($mailId));
            }
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_QUEUE_CANCEL_CAMPAIGN'), "error");
        }
        $this->campaigns();
    }

    public function playPauseSending()
    {
        $active = acym_getVar("int", "acym__queue__play_pause__active__new_value");
        $campaignId = acym_getVar("int", "acym__queue__play_pause__campaign_id");

        if (!empty($campaignId)) {
            $queueClass = acym_get('class.queue');
            $queueClass->unpauseCampaign($campaignId, $active);
        } else {
            if (!empty($active)) {
                acym_enqueueMessage(acym_translation('ACYM_ERROR_QUEUE_RESUME'), "error");
            } else {
                acym_enqueueMessage(acym_translation('ACYM_ERROR_QUEUE_PAUSE'), "error");
            }
        }

        $this->campaigns();
    }

    public function emptyQueue()
    {
        acym_checkToken();

        $queueClass = acym_get('class.queue');
        $deleted = $queueClass->emptyQueue();
        acym_enqueueMessage(acym_translation_sprintf('ACYM_EMAILS_REMOVED_QUEUE', $deleted));

        $this->campaigns();
    }
}

