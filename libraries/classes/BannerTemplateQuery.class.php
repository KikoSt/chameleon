<?php
/**
 * BannerTemplateQueryModel
 *
 * Fetches the templates filtered by given filter parameters
 *
 * currently (2014-10-14): only category ids are possible
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Thomas Hummel <thomas.hummel@mediadecision.com>
 * @license Proprietary/Closed Source
 */

class BannerTemplateQuery implements JsonSerializable
{
    private $categoryIds;

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'categoryIds' => $this->getCategoryIds()
        );
    }

    /**
     * @return mixed
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * @param array $categoryIds
     */
    public function setCategoryIds(Array $categoryIds)
    {
        $this->categoryIds = $categoryIds;
    }
} 