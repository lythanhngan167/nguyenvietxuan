<?php
defined('_JEXEC') or die('Restricted access');
?><?php

class acymcampaignClass extends acymClass
{
    var $table = 'campaign';
    var $pkey = 'id';
    const SENDING_TYPE_NOW = 'now';
    const SENDING_TYPE_SCHEDULED = 'scheduled';
    const SENDING_TYPE_AUTO = 'auto';
    const SENDING_TYPES = [
        self::SENDING_TYPE_NOW,
        self::SENDING_TYPE_SCHEDULED,
        self::SENDING_TYPE_AUTO,
    ];
    var $encodedColumns = ['sending_params'];

    public function decode($campaign, $decodeMail = true)
    {
        if (empty($campaign)) return $campaign;

        if (is_array($campaign)) {
            foreach ($campaign as $i => $oneCampaign) {
                $campaign[$i] = $this->decode($oneCampaign, false);
            }
        }

        foreach ($this->encodedColumns as $oneColumn) {
            if (!isset($campaign->$oneColumn)) continue;

            $campaign->$oneColumn = empty($campaign->$oneColumn) ? [] : json_decode($campaign->$oneColumn, true);
        }

        if ($decodeMail) {
            $mailClass = acym_get('class.mail');
            $campaign = $mailClass->decode($campaign);
        }

        return $campaign;
    }

    public function getAll($key = null)
    {
        $allCampaigns = parent::getAll($key);

        return $this->decode($allCampaigns);
    }

    public function getMatchingElements($settings = [])
    {
        $tagClass = acym_get('class.tag');
        $mailClass = acym_get('class.mail');

        $query = 'SELECT campaign.*, mail.name, mail_stat.sent AS subscribers, mail_stat.open_unique FROM #__acym_campaign AS campaign';
        $queryCount = 'SELECT campaign.* FROM #__acym_campaign AS campaign';


        $filters = [];
        $mailIds = [];

        $query .= ' JOIN #__acym_mail AS mail ON campaign.mail_id = mail.id';
        $queryCount .= ' JOIN #__acym_mail AS mail ON campaign.mail_id = mail.id';
        $query .= ' LEFT JOIN #__acym_mail_stat AS mail_stat ON campaign.mail_id = mail_stat.mail_id';

        if (!acym_isAdmin()) {
            $filters[] = 'mail.creator_id = '.intval(acym_currentUserId());
        }

        if (!empty($settings['tag'])) {
            $tagJoin = ' JOIN #__acym_tag AS tag ON campaign.mail_id = tag.id_element';
            $query .= $tagJoin;
            $queryCount .= $tagJoin;
            $filters[] = 'tag.name = '.acym_escapeDB($settings['tag']);
            $filters[] = 'tag.type = "mail"';
        }

        if (!empty($settings['search'])) {
            $filters[] = 'mail.name LIKE '.acym_escapeDB('%'.$settings['search'].'%');
        }

        if ($settings['status'] != 'generated') {
            $operator = $settings['element_tab'] == 'campaigns_auto' ? '=' : '!=';
            $filters[] = 'campaign.sending_type '.$operator.' '.acym_escapeDB(self::SENDING_TYPE_AUTO);
            $query .= ' WHERE ('.implode(') AND (', $filters).')';
            $queryCount .= ' WHERE ('.implode(') AND (', $filters).')';
        }

        $statusRequests = [
            'all' => '(campaign.parent_id IS NULL OR campaign.parent_id = 0)',
            'scheduled' => 'campaign.sending_type = '.acym_escapeDB(self::SENDING_TYPE_SCHEDULED).' AND (campaign.parent_id IS NULL OR campaign.parent_id = 0)',
            'sent' => 'campaign.sent = 1 AND (campaign.parent_id IS NULL OR campaign.parent_id = 0)',
            'draft' => 'campaign.draft = 1 AND (campaign.parent_id IS NULL OR campaign.parent_id = 0)',
        ];

        if ($settings['element_tab'] == 'campaigns_auto') {
            $statusRequests['generated'] = 'campaign.sending_type = '.acym_escapeDB(self::SENDING_TYPE_NOW).' AND campaign.parent_id  > 0';
        }

        if (empty($settings['status'])) $settings['status'] = 'all';
        $query .= empty($filters) ? ' WHERE ' : ' AND ';
        $query .= $statusRequests[$settings['status']];

        if (!empty($settings['ordering']) && !empty($settings['ordering_sort_order'])) {
            $table = in_array($settings['ordering'], ['name', 'creation_date']) ? 'mail' : 'campaign';
            $query .= ' ORDER BY '.$table.'.'.acym_secureDBColumn($settings['ordering']).' '.acym_secureDBColumn(strtoupper($settings['ordering_sort_order']));
        }


        if (empty($settings['offset']) || $settings['offset'] < 0) {
            $settings['offset'] = 0;
        }

        if (empty($settings['elementsPerPage']) || $settings['elementsPerPage'] < 1) {
            $pagination = acym_get('helper.pagination');
            $settings['elementsPerPage'] = $pagination->getListLimit();
        }


        $results['elements'] = $this->decode(acym_loadObjectList($query, '', $settings['offset'], $settings['elementsPerPage']));

        foreach ($results['elements'] as $oneCampaign) {
            array_push($mailIds, $oneCampaign->mail_id);
            $oneCampaign->tags = '';
        }

        $tags = $tagClass->getAllTagsByTypeAndElementIds('mail', $mailIds);
        $lists = $mailClass->getAllListsWithCountSubscribersByMailIds($mailIds);

        $urlClickClass = acym_get('class.urlclick');
        foreach ($results['elements'] as $i => $oneCampaign) {
            $results['elements'][$i]->tags = [];
            $results['elements'][$i]->lists = [];
            $results['elements'][$i]->automation_id = null;

            foreach ($tags as $tag) {
                if ($oneCampaign->id == $tag->id_element) {
                    $results['elements'][$i]->tags[] = $tag;
                }
            }

            foreach ($lists as $list) {
                if ($oneCampaign->mail_id == $list->mail_id) {
                    array_push($results['elements'][$i]->lists, $list);
                }
            }

            if ($settings['element_tab'] == 'campaigns_auto' && $settings['status'] != 'generated') {
                $this->getStatsCampaignAuto($results['elements'][$i], $urlClickClass);
            } else {
                $this->getStatsCampaign($results['elements'][$i], $urlClickClass);
            }
        }

        $results['total'] = acym_loadObjectList($queryCount);

        return $results;
    }

    private function getStatsCampaign(&$element, $urlClickClass)
    {
        $element->open = 0;
        if (!empty($element->subscribers)) {
            $element->open = number_format($element->open_unique / $element->subscribers * 100, 2);

            $clicksNb = $urlClickClass->getNumberUsersClicked($element->mail_id);
            $element->click = number_format($clicksNb / $element->subscribers * 100, 2);
        }
    }

    private function getStatsCampaignAuto(&$element, $urlClickClass)
    {
        $generatedMailsStats = acym_loadObjectList('SELECT mail_stat.* FROM #__acym_mail AS mail JOIN #__acym_mail_stat AS mail_stat ON mail.id = mail_stat.mail_id WHERE mail.id IN (SELECT mail_id FROM #__acym_campaign WHERE parent_id = '.intval($element->id).')');
        $element->open = 0;
        $element->click = 0;
        $element->subscribers = 0;
        if (empty($generatedMailsStats)) return;

        foreach ($generatedMailsStats as $key => $mailsStat) {
            $element->open += $mailsStat->open_unique;
            $element->click += $urlClickClass->getNumberUsersClicked($element->id);
            $element->subscribers += $mailsStat->sent;
        }

        if (!empty($element->subscribers)) {
            $element->open = number_format($element->open / $element->subscribers * 100, 2);
            $element->click = number_format($element->click / $element->subscribers * 100, 2);
        }
    }

    public function getOneById($id)
    {
        return $this->decode(acym_loadObject('SELECT campaign.* FROM #__acym_campaign AS campaign WHERE campaign.id = '.intval($id)));
    }

    public function getOneByIdWithMail($id)
    {
        $query = 'SELECT campaign.*, mail.name, mail.subject, mail.body, mail.from_name, mail.from_email, mail.reply_to_name, mail.reply_to_email, mail.bcc, mail.links_language, mail.tracking
                FROM #__acym_campaign AS campaign
                JOIN #__acym_mail AS mail ON campaign.mail_id = mail.id
                WHERE campaign.id = '.intval($id);

        return $this->decode(acym_loadObject($query));
    }

    public function get($identifier, $column = 'id')
    {
        return $this->decode(acym_loadObject('SELECT campaign.* FROM #__acym_campaign AS campaign WHERE campaign.'.acym_secureDBColumn($column).' = '.acym_escapeDB($identifier)));
    }

    public function getAllCampaignsNameMailId()
    {
        $query = 'SELECT m.id, m.name 
                FROM #__acym_campaign AS c 
                LEFT JOIN #__acym_mail AS m ON c.mail_id = m.id';

        return $this->decode(acym_loadObjectList($query));
    }

    public function getOneCampaignByMailId($mailId)
    {
        return $this->decode(acym_loadObject('SELECT * FROM #__acym_campaign WHERE mail_id = '.intval($mailId)));
    }

    public function getAutoCampaignFromGeneratedMailId($mailId)
    {
        $queryCampaign = 'SELECT * FROM #__acym_campaign WHERE id = (SELECT parent_id FROM #__acym_campaign WHERE mail_id = '.intval($mailId).')';

        return $this->decode(acym_loadObject($queryCampaign));
    }

    public function manageListsToCampaign($listsIds, $mailId, $unselectedListIds = [])
    {
        if (!empty($unselectedListIds)) {
            acym_arrayToInteger($unselectedListIds);
            acym_query('DELETE FROM #__acym_mail_has_list WHERE mail_id = '.intval($mailId).' AND list_id IN ('.implode(', ', $unselectedListIds).')');
        }

        acym_arrayToInteger($listsIds);
        if (empty($listsIds)) return false;

        $values = [];
        $listsIds = array_unique($listsIds);
        foreach ($listsIds as $id) {
            array_push($values, '('.intval($mailId).', '.intval($id).')');
        }

        if (!empty($values)) {
            acym_query('INSERT IGNORE INTO #__acym_mail_has_list (`mail_id`, `list_id`) VALUES '.implode(',', $values));
        }

        return true;
    }

    public function save($campaignToSave)
    {
        $campaign = clone $campaignToSave;
        if (isset($campaign->tags)) {
            $tags = $campaign->tags;
            unset($campaign->tags);
        }

        foreach ($campaign as $oneAttribute => $value) {
            if (in_array($oneAttribute, $this->encodedColumns)) {
                $campaign->$oneAttribute = json_encode(empty($value) ? [] : $value);
            } else {
                if (empty($value)) continue;
                $campaign->$oneAttribute = strip_tags($value);
            }
        }

        $campaignID = parent::save($campaign);

        if (!empty($campaignID) && isset($tags)) {
            $tagClass = acym_get('class.tag');
            $tagClass->setTags('mail', $campaign->mail_id, $tags);
        }

        return $campaignID;
    }

    public function onlyManageableCampaigns(&$elements)
    {
        if (acym_isAdmin()) return;

        $idCurrentUser = acym_currentUserId();
        if (empty($idCurrentUser)) return;

        $manageable = acym_loadResultArray(
            'SELECT campaign.id 
            FROM #__acym_campaign AS campaign 
            JOIN #__acym_mail AS mail ON campaign.mail_id = mail.id 
            WHERE mail.creator_id = '.intval($idCurrentUser)
        );
        $elements = array_intersect($elements, $manageable);
    }

    public function delete($elements)
    {
        if (!is_array($elements)) $elements = [$elements];
        acym_arrayToInteger($elements);
        $this->onlyManageableCampaigns($elements);

        if (empty($elements)) return 0;

        $mailsToDelete = [];
        foreach ($elements as $id) {
            $mailsToDelete[] = acym_loadResult('SELECT mail_id FROM #__acym_campaign WHERE id = '.intval($id));
            acym_query('UPDATE #__acym_campaign SET mail_id = NULL WHERE id = '.intval($id));
        }

        $mailClass = acym_get('class.mail');
        $mailClass->delete($mailsToDelete);

        return parent::delete($elements);
    }

    public function send($campaignID, $result = 0)
    {
        $campaign = $this->getOneById($campaignID);

        if (empty($campaign->mail_id)) {
            $this->errors[] = 'Mail not found';

            return false;
        }

        $lists = acym_loadResultArray('SELECT list_id FROM #__acym_mail_has_list WHERE mail_id = '.intval($campaign->mail_id));
        if (empty($lists)) {
            $this->errors[] = acym_translation('ACYM_NO_LIST_SELECTED');

            return false;
        }
        acym_arrayToInteger($lists);

        $date = acym_date('now', 'Y-m-d H:i:s', false);
        if (empty($result)) {
            $conditions = [
                '`user`.`active` = 1',
                '`ul`.`status` = 1',
                '`ul`.`list_id` IN ('.implode(',', $lists).')',
            ];
            if ($this->config->get('require_confirmation', 1) == 1) $conditions[] = '`user`.`confirmed` = 1';

            $insertQuery = 'INSERT IGNORE INTO `#__acym_queue` (`mail_id`, `user_id`, `sending_date`) 
                        SELECT '.intval($campaign->mail_id).', ul.`user_id`, '.acym_escapeDB($date).' 
                        FROM `#__acym_user_has_list` AS `ul` 
                        JOIN `#__acym_user` AS `user` ON `user`.`id` = `ul`.`user_id` ';

            if (!empty($campaign->sending_params['resendTarget']) && 'new' === $campaign->sending_params['resendTarget']) {
                $insertQuery .= ' LEFT JOIN `#__acym_user_stat` AS `us` ON `us`.`user_id` = `user`.`id` AND `us`.`mail_id` = '.intval($campaign->mail_id);
                $conditions[] = '`us`.`user_id` IS NULL';
            }

            $insertQuery .= ' WHERE '.implode(' AND ', $conditions);
            $result = acym_query($insertQuery);
        }

        if ($campaign->sending_type == self::SENDING_TYPE_NOW) {
            $campaign->sending_date = $date;
            $campaign->draft = 0;
            $this->save($campaign);
        }

        $mailStatClass = acym_get('class.mailstat');
        $mailStat = $mailStatClass->getOneRowByMailId($campaign->mail_id);

        if (empty($mailStat)) {
            $mailStat = [];
            $mailStat['mail_id'] = intval($campaign->mail_id);
            $mailStat['total_subscribers'] = 0;
        } else {
            $mailStat = get_object_vars($mailStat);
        }

        $mailStat['total_subscribers'] += intval($result);
        $mailStat['send_date'] = $date;

        if (!empty($mailStat['sent'])) unset($mailStat['sent']);

        $mailStatClass->save($mailStat);

        if ($result === 0) {
            $this->errors[] = acym_translation('ACYM_NO_USERS_FOUND');

            return false;
        }

        acym_query('UPDATE `#__acym_campaign` SET `sent` = 1, `active` = 1 WHERE `mail_id` = '.intval($campaign->mail_id));

        return $result;
    }

    public function getCampaignForDashboard()
    {
        $query = 'SELECT campaign.*, mail.name as name FROM #__acym_campaign as campaign LEFT JOIN #__acym_mail as mail ON campaign.mail_id = mail.id WHERE `active` = 1 AND `sending_type` = '.acym_escapeDB(self::SENDING_TYPE_SCHEDULED).' AND `sent` = 0 LIMIT 3';

        return $this->decode(acym_loadObjectList($query));
    }

    public function getOpenRateOneCampaign($mail_id)
    {
        $query = 'SELECT sent, open_unique FROM #__acym_mail_stat 
                    WHERE mail_id = '.intval($mail_id).' LIMIT 1';

        return acym_loadObject($query);
    }

    public function getOpenRateAllCampaign()
    {
        $query = 'SELECT SUM(sent) as sent, SUM(open_unique) as open_unique FROM #__acym_mail_stat';

        return acym_loadObject($query);
    }

    public function getBounceRateAllCampaign()
    {
        $query = 'SELECT SUM(sent) as sent, SUM(bounce_unique) as bounce_unique FROM #__acym_mail_stat';

        return acym_loadObject($query);
    }


    public function getBounceRateOneCampaign($mail_id)
    {
        $query = 'SELECT sent, bounce_unique FROM #__acym_mail_stat 
                    WHERE mail_id = '.intval($mail_id).' LIMIT 1';

        return acym_loadObject($query);
    }

    public function getOpenByMonth($mail_id = '', $start = '', $end = '')
    {
        $query = 'SELECT COUNT(user_id) as open, DATE_FORMAT(open_date, \'%Y-%m\') as open_date FROM #__acym_user_stat WHERE open > 0';
        $query .= empty($mail_id) ? '' : ' AND  `mail_id`='.intval($mail_id);
        $query .= ' AND `open_date` > "0000-00-00"';
        $query .= empty($start) ? '' : ' AND `open_date` >= '.acym_escapeDB($start);
        $query .= empty($end) ? '' : ' AND `open_date` <= '.acym_escapeDB($end);
        $query .= ' GROUP BY MONTH(open_date), YEAR(open_date) ORDER BY open_date';

        return acym_loadObjectList($query);
    }

    public function getOpenByWeek($mail_id = '', $start = '', $end = '')
    {
        $query = 'SELECT COUNT(user_id) as open, DATE_FORMAT(open_date, \'%Y-%m-%d\') as open_date FROM #__acym_user_stat WHERE open > 0';
        $query .= empty($mail_id) ? '' : ' AND  `mail_id`='.intval($mail_id);
        $query .= ' AND `open_date` > "0000-00-00"';
        $query .= empty($start) ? '' : ' AND `open_date` >= '.acym_escapeDB($start);
        $query .= empty($end) ? '' : ' AND `open_date` <= '.acym_escapeDB($end);
        $query .= ' GROUP BY WEEK(open_date), YEAR(open_date) ORDER BY open_date';

        return acym_loadObjectList($query);
    }

    public function getOpenByDay($mail_id = '', $start = '', $end = '')
    {
        $query = 'SELECT COUNT(user_id) as open, DATE_FORMAT(open_date, \'%Y-%m-%d\') as open_date FROM #__acym_user_stat WHERE open > 0';
        $query .= empty($mail_id) ? '' : ' AND  `mail_id`='.intval($mail_id);
        $query .= ' AND `open_date` > "0000-00-00"';
        $query .= empty($start) ? '' : ' AND `open_date` >= '.acym_escapeDB($start);
        $query .= empty($end) ? '' : ' AND `open_date` <= '.acym_escapeDB($end);
        $query .= ' GROUP BY DAYOFYEAR(open_date), YEAR(open_date) ORDER BY open_date';

        return acym_loadObjectList($query);
    }

    public function getOpenByHour($mail_id = '', $start = '', $end = '')
    {
        $query = 'SELECT COUNT(user_id) as open, DATE_FORMAT(open_date, \'%Y-%m-%d %H:00:00\') as open_date FROM #__acym_user_stat WHERE open > 0';
        $query .= empty($mail_id) ? '' : ' AND  `mail_id`='.intval($mail_id);
        $query .= ' AND `open_date` > "0000-00-00 00:00:00"';
        $query .= empty($start) ? '' : ' AND `open_date` >= '.acym_escapeDB($start);
        $query .= empty($end) ? '' : ' AND `open_date` <= '.acym_escapeDB($end);
        $query .= ' GROUP BY HOUR(open_date), DAYOFYEAR(open_date), YEAR(open_date) ORDER BY open_date';

        return acym_loadObjectList($query);
    }

    public function getLastNewsletters($params)
    {
        $querySelect = 'SELECT mail.*, campaign.sending_date ';
        $queryCountSelect = 'SELECT COUNT(*) FROM (SELECT DISTINCT mail.id ';

        $query = 'FROM #__acym_campaign AS campaign
                  JOIN #__acym_mail AS mail ON campaign.mail_id = mail.id ';

        if (isset($params['userId']) || isset($params['lists'])) {
            $query .= 'JOIN #__acym_mail_has_list AS maillist ON mail.id = maillist.mail_id ';
            if (isset($params['userId'])) $query .= 'JOIN #__acym_user_has_list AS userlist ON maillist.list_id = userlist.list_id ';
        }

        $where = 'WHERE campaign.active = 1 AND campaign.sent = 1 AND mail.type = "standard" ';

        if (isset($params['lists'])) {
            acym_arrayToInteger($params['lists']);
            $where .= 'AND maillist.list_id IN ('.implode(', ', $params['lists']).') ';
        }

        if (isset($params['userId'])) {
            $where .= 'AND userlist.user_id = '.intval($params['userId']).' ';
        }

        $query .= $where;

        $endQuerySelect = 'GROUP BY mail.id ';
        $endQuerySelect .= 'ORDER BY campaign.sending_date DESC';

        $page = isset($params['page']) ? $params['page'] : 0;
        $numberPerPage = isset($params['numberPerPage']) ? $params['numberPerPage'] : 0;
        $lastNewsletters = isset($params['limit']) ? $params['limit'] : 0;

        if (!empty($page) && !empty($numberPerPage)) {
            if (!empty($lastNewsletters)) {
                $limit = $page * $numberPerPage > $lastNewsletters ? fmod($lastNewsletters, $numberPerPage) : $numberPerPage;
            } else {
                $limit = $numberPerPage;
            }

            $offset = ($params['page'] - 1) * $numberPerPage;
            $endQuerySelect .= ' LIMIT '.intval($offset).', '.intval($limit);
        } elseif (!empty($lastNewsletters)) {
            $limit = $lastNewsletters;

            $endQuerySelect .= ' LIMIT '.intval($limit);
        }

        $return = [];

        $return['count'] = acym_loadResult($queryCountSelect.$query.') AS r ');
        $return['matchingNewsletters'] = $this->decode(acym_loadObjectList($querySelect.$query.$endQuerySelect));

        $userClass = acym_get('class.user');
        $userEmail = acym_currentUserEmail();
        $user = $userClass->getOneByEmail($userEmail);

        foreach ($return['matchingNewsletters'] as $i => $oneNewsletter) {
            acym_trigger('replaceContent', [&$oneNewsletter]);
            acym_trigger('replaceUserInformation', [&$oneNewsletter, &$user, false]);

            $return['matchingNewsletters'][$i] = $oneNewsletter;
        }

        return $return;
    }

    public function getListsForCampaign($mailId)
    {
        $query = 'SELECT list_id FROM #__acym_mail_has_list WHERE mail_id = '.intval($mailId);

        return acym_loadResultArray($query);
    }

    public function triggerAutoCampaign()
    {
        $activeAutoCampaigns = acym_loadObjectList(
            'SELECT campaign.*, mail.name 
            FROM #__acym_campaign AS campaign 
            JOIN #__acym_mail AS mail ON campaign.`mail_id` = mail.`id` 
            WHERE `active` = 1 AND `sending_type` = '.acym_escapeDB(self::SENDING_TYPE_AUTO)
        );
        $activeAutoCampaigns = $this->decode($activeAutoCampaigns);

        if (empty($activeAutoCampaigns)) return;

        $mailClass = acym_get('class.mail');
        $time = time();

        foreach ($activeAutoCampaigns as $campaign) {
            $step = new stdClass();
            $step->triggers = $campaign->sending_params;
            $step->last_execution = $campaign->last_generated;
            $step->next_execution = $campaign->next_trigger;

            $data = ['time' => $time];
            $execute = !empty($step->next_execution) && $step->next_execution <= $data['time'];
            acym_trigger('onAcymExecuteTrigger', [&$step, &$execute, &$data], 'plgAcymTime');
            $campaign->next_trigger = $step->next_execution;
            if (!$execute) {
                $this->save($campaign);
                continue;
            }

            $campaignMail = $mailClass->getOneById($campaign->mail_id);

            $lastGenerated = $campaign->last_generated;
            $shouldGenerate = $this->_updateAutoCampaign($campaign, $campaignMail, $time);
            $this->save($campaign);

            if (!$shouldGenerate) continue;

            $generatedCampaign = $this->_generateCampaign($campaign, $campaignMail, $lastGenerated, $mailClass);

            if (empty($campaign->sending_params['need_confirm_to_send'])) $this->send($generatedCampaign->id);
        }
    }

    private function shouldGenerateCampaign($campaign, $campaignMail)
    {
        $results = acym_trigger('generateByCategory', [&$campaignMail]);

        foreach ($results as $oneResult) {
            if (isset($oneResult->status) && !$oneResult->status) {
                $this->messages[] = acym_translation_sprintf('ACYM_CAMPAIGN_NOT_GENERATED', $campaign->name, $oneResult->message);

                return false;
            }
        }

        return true;
    }

    private function _updateAutoCampaign(&$campaign, $campaignMail, $time)
    {
        if (!$this->shouldGenerateCampaign($campaign, $campaignMail)) return false;

        if (empty($campaign->sending_params['number_generated'])) {
            $campaign->sending_params['number_generated'] = 1;
        } else {
            $campaign->sending_params['number_generated']++;
        }
        $campaign->last_generated = $time;

        return true;
    }

    private function _generateCampaign($campaign, $campaignMail, $lastGenerated, $mailClass)
    {
        $newMail = $this->_generateMailAutoCampaign($campaignMail, $campaign->sending_params['number_generated']);
        $newCampaign = new stdClass();
        $newCampaign->mail_id = $newMail->id;
        $newCampaign->parent_id = $campaign->id;
        $newCampaign->active = 1;
        $newCampaign->draft = 1;
        $newCampaign->sending_type = self::SENDING_TYPE_NOW;
        $newCampaign->sent = 0;
        $newCampaign->last_generated = $lastGenerated;

        $newCampaign->id = $this->save($newCampaign);

        acym_trigger('replaceContent', [&$newMail]);
        $mailClass->save($newMail);

        return $newCampaign;
    }

    private function _generateMailAutoCampaign($newMail, $generatedMail)
    {
        $mailId = $newMail->id;
        unset($newMail->id);
        $newMail->name .= ' #'.$generatedMail;

        $mailClass = acym_get('class.mail');
        $newMail->id = $mailClass->save($newMail);
        $this->_setListToGeneratedCampaign($mailId, $newMail->id);

        return $newMail;
    }

    private function _setListToGeneratedCampaign($parentMailId, $newMailId)
    {
        $mailClass = acym_get('class.mail');
        $lists = $mailClass->getAllListsByMailId($parentMailId);
        $listIds = [];
        foreach ($lists as $list) {
            $listIds[] = $list->id;
        }

        return $this->manageListsToCampaign($listIds, $newMailId);
    }

    public function getLastGenerated($mailId)
    {
        return acym_loadResult(
            'SELECT `last_generated` 
            FROM #__acym_campaign 
            WHERE `mail_id` = '.intval($mailId)
        );
    }

    public function getAllCampaignsGenerated()
    {
        $query = 'SELECT id FROM #__acym_campaign WHERE parent_id IS NOT NULL AND sending_type = '.acym_escapeDB(self::SENDING_TYPE_NOW).' AND draft = 1 AND active = 1 AND sent = 0';

        return acym_loadObjectList($query);
    }
}

