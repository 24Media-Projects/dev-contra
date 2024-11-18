<?php


class BTW_ACF_Location_Group_Magazine_Template extends ACF_Location {

    public function initialize() {
        $this->name = 'group_magazine_template';
        $this->label = 'Group Magazine Template';
        $this->category = 'post';
        $this->object_type = 'post';
    }

    public function get_values( $rule ) {

        return BTW_Global_Settings::get_group_magazine_templates_choices();

    }

    public function match( $rule, $screen, $field_group ) {

        if( empty( $screen['group_magazine_template'] ) ){
            return false;
        }
        
        if( $screen['group_magazine_template'] == $rule['value'] && $rule['operator'] == '==' ){
            return true;
        }

        return false;

    }
}