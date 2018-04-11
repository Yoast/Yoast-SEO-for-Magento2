<?php

namespace MaxServ\YoastSeo\Api\Data;

interface AnalysisTemplateInterface
{
    const TABLE = 'yoastseo_analysis_template';
    const KEY_TEMPLATE_ID = 'template_id';
    const KEY_ENTITY_TYPE = 'entity_type';
    const KEY_CONTENT = 'content';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getTemplateId();

    /**
     * @param int $templateId
     * @return $this
     */
    public function setTemplateId($templateId);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $entityType
     * @return $this
     */
    public function setEntityType($entityType);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);
}
