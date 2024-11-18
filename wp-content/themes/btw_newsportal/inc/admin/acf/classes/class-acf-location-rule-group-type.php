<?php


class BTW_ACF_Location_Group_Type extends ACF_Location {

    public function initialize() {
        $this->name = 'group_type';
        $this->label = 'Group Type';
        $this->category = 'post';
        $this->object_type = 'post';
    }

    public function get_values( $rule ) {

        return [
            'hp' => 'hp',
			'bon' => 'bon',
			'magazine' => 'magazine',
        ];

    }

    public function match( $rule, $screen, $field_group ) {

        if( empty( $screen['group_type'] ) ){
            return false;
        }
        
        if( $screen['group_type'] == $rule['value'] && $rule['operator'] == '==' ){
            return true;
        }

        return false;

    }
}