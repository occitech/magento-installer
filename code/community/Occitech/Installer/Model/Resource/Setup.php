<?php

class Occitech_Installer_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    public function createProductAttributeSet($label, $parentAttributeSet = null)
    {
        $entityTypeId = Mage::getModel('eav/entity')
            ->setType('catalog_product')
            ->getTypeId();

        $this->createAttributeSet($label, $entityTypeId, $parentAttributeSet);
    }

    private function createAttributeSet($label, $entityTypeId, $parentAttributeSet = null)
    {
        $AttributeSet = Mage::getModel('eav/entity_attribute_set');

        $AttributeSet->setEntityTypeId($entityTypeId)
            ->setAttributeSetName($label)
            ->save();

        if (null !== $parentAttributeSet) {
            $this->inheritFromParentAttributeSet($parentAttributeSet, $AttributeSet, $entityTypeId);
        }
    }

    private function inheritFromParentAttributeSet($parentAttributeSet, $AttributeSet, $entityTypeId)
    {
        $parentAttributeId = $AttributeSet->getCollection()
            ->addFieldToFilter('entity_type_id', array('eq' => $entityTypeId))
            ->addFieldToFilter('attribute_set_name', array('eq' => $parentAttributeSet))
            ->getFirstItem()
            ->getAttributeSetId();
        $AttributeSet->initFromSkeleton($parentAttributeId)
            ->save();
    }

    public function createCMSBlock(array $block)
    {
        $CMSBlock = Mage::getModel('cms/block');
        $CMSBlock->setData($block);
        $CMSBlock->setStores(array(0));
        $CMSBlock->save();
    }

    public function createCMSBlocks(array $blocks) {
        foreach ($blocks as $block) {
            $this->createCMSBlock($block);
        }
    }
}
