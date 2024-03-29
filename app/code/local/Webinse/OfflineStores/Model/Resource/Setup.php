<?php
class Webinse_OfflineStores_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{

    /**
     * Setup attributes for offlinestore entity type
     */
    public function getDefaultEntities()
    {
        $entities = array(
            'offlinestore' => array(
                'entity_model'                   => 'webinseofflinestores/offlinestore',
                'table'                          => 'webinseofflinestores/table_offlinestores',
                'attribute_model'                => 'webinseofflinestores/attribute',
                'entity_attribute_collection'    => 'webinseofflinestores/attribute_collection',
                'additional_attribute_table'     => 'webinseofflinestores/attribute_additional',
                'attributes' => array(
                    // offline store attributes
                    'name' => array(
                        'group' => 'General Information',
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Name',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => true,
                        'unique' => false,
                        'sort_order'         => 100,
                        'position'           => 100,
                    ),
                    'image' => array(
                        'group' => 'General Information',
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Image',
                        'input' => 'image',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'sort_order'         => 120,
                        'position'           => 120,
                    ),
                    'short_description' => array(
                        'group' => 'General Information',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Short Description',
                        'input' => 'textarea',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'sort_order'         => 140,
                        'position'           => 140,
                    ),
                    'description' => array(
                        'group' => 'General Information',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'input_renderer' => 'webinseofflinestores/system_config_source_wysiwyg',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'sort_order'         => 160,
                        'position'           => 160,
                    ),
                    'disposition' => array(
                        'group' => 'General Information',
                        'type' => 'int',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Position',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'sort_order'         => 180,
                        'position'           => 180,
                    ),
                    'status' => array(
                        'group' => 'General Information',
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Status',
                        'input' => 'select',
                        'class' => '',
                        'source' => 'webinseofflinestores/system_config_source_status',
                        'global' => 0,
                        'visible' => true,
                        'required' => false,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                        'sort_order'         => 200,
                        'position'           => 200,
                        'option' =>
                            array (
                                'values' =>
                                    array (
                                        0 => 'Disable',
                                        1 => 'Enable'
                                    ),
                            ),
                    ),
                    // offline store address attributes
                    'street'             => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'Street',
                        'input'              => 'text',
                        'sort_order'         => 70,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 70,
                        'required'           => true,
                    ),
                    'city'               => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'City',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 80,
                        'required'           => true,
                    ),
                    'country_id'         => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'Country',
                        'input'              => 'select',
                        'source'             => 'customer/entity_address_attribute_source_country',
                        'sort_order'         => 90,
                        'position'           => 90,
                        'required'           => true,
                    ),
                    'region'             => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'State/Province',
                        'input'              => 'text',
                        'backend'            => 'customer/entity_address_attribute_backend_region',
                        'required'           => false,
                        'sort_order'         => 100,
                        'position'           => 100,
                    ),
                    'region_id'          => array(
                        'group'              => 'Address Information',
                        'type'               => 'int',
                        'label'              => 'State/Province',
                        'input'              => 'hidden',
                        'source'             => 'customer/entity_address_attribute_source_region',
                        'required'           => false,
                        'sort_order'         => 100,
                        'position'           => 100,
                    ),
                    'postcode'           => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'Zip/Postal Code',
                        'input'              => 'text',
                        'sort_order'         => 110,
                        'validate_rules'     => 'a:0:{}',
                        'data'               => 'customer/attribute_data_postcode',
                        'position'           => 110,
                        'required'           => true,
                    ),
                    'telephone'          => array(
                        'group'              => 'Address Information',
                        'type'               => 'varchar',
                        'label'              => 'Telephone',
                        'input'              => 'text',
                        'sort_order'         => 120,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 120,
                    )
                ),
            )
        );
        return $entities;
    }
}