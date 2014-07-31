<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 28.07.14
 * Time: 07:50
 */

class Database
{
    public function fetchTemplates()
    {
        // fetch all templates depending on the user, company, category, advertiser
        //TODO get templates from database depending on user, company and so on

        return array(0 => array('id' => 4711,
            'user' => 'Zoidberg',
            'companyId' => '4',
            'template' => 'ttest_2.svg',
            'advertiserId' => '122')
        );
    }

    public function fetchTemplatesNext()
    {
        // fetch all templates depending on the user, company, category, advertiser
        //TODO get templates from database depending on user, company and so on

        return array(0 => array('id' => 4711,
            'user' => 'Zoidberg',
            'companyId' => '4',
            'template' => 'ttest_2.svg',
            'advertiserId' => '122')
        );
    }

    public function fetchTemplateById($id)
    {
        //TODO get templates from database depending on user, company and so on

        return array('id' => 4711,
                     'user' => 'Zoidberg',
                     'companyId' => '4',
                     'template' => 'ttest_2.svg',
                     'advertiserId' => '122'
        );
    }
}
