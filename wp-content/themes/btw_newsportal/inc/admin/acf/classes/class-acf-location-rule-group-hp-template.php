<?php


class BTW_ACF_Location_Group_Hp_Template extends ACF_Location {

    public function initialize() {
        $this->name = 'group_hp_template';
        $this->label = 'Group HP Template';
        $this->category = 'post';
        $this->object_type = 'post';
    }

    public function get_values( $rule ) {

        return BTW_Global_Settings::get_group_hp_templates_choices();

    }

    public function match( $rule, $screen, $field_group ) {

        if( empty( $screen['group_hp_template'] ) ){
            return false;
        }
        
        if( $screen['group_hp_template'] == $rule['value'] && $rule['operator'] == '==' ){
            return true;
        }

        return false;

    }
}