<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2019/07/29
 * Time: 3:08 PM
 */

use WPooWTests\WPooWTestsElements;

include_once __DIR__ . '/../../wpAPI.php';
include_once  __DIR__. '/TextTest.php';

class TextAreaTest extends TextTest {

    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/

    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSampleData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                        'id' => '_test_textarea_field_1',
                        'label' => 'Sample Text Area Field 1',
                        'type' => WPooWTestsElements::TEXTAREA,
                        'test_value' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'
                    ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [
                    [
                        'id' => '_test_textarea_field_1',
                        'label' => 'Sample Text Area Field 1',
                        'type' => WPooWTestsElements::TEXTAREA,
                        'test_value' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'
                    ],
                    [
                        'id' => '_test_textarea_field_2',
                        'label' => 'Sample Text Area Field 2',
                        'type' => 'textarea',
                        'test_value' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum'
                    ]];
                break;
        }

        return $baseSamplePostType;

    }

    /**************************
    / TESTS                   *
    /**************************/

    // Using parent tests


    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/

    // Using parent class

}
