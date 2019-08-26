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

class RichTextAreaTest extends TextTest
{

    /**************************
     * / HELP DATA & FUNCTIONS   *
     * /**************************/

    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSampleData();

        switch ($id) {
            case 1:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_richtextarea_field_1',
                    'label' => 'Sample Rich Text Area Field 1',
                    'type' => WPooWTestsElements::RICHTEXTAREA,
                    'test_value' => preg_replace("/\r\n|\r|\n/", '', '<strong>Sample Text One</strong>
                                <ul>
                                    <li>This is sample text</li>
                                    <li>This is sample text 2</li>
                                    <li>This is sample text 3</li>
                                </ul>
                                <a href="https://www.centridsol.com">www.centridsol.com</a>')
                ]];
                break;
            case 2:
                $baseSamplePostType['fields'] = [[
                    'id' => '_test_richtextarea_field_1',
                    'label' => 'Sample Rich Text Area Field 1',
                    'type' => WPooWTestsElements::RICHTEXTAREA,
                    'test_value' =>  preg_replace("/\r\n|\r|\n/", '', '<strong>Sample Text One</strong>
                                <ul>
                                    <li>This is sample text</li>
                                    <li>This is sample text 2</li>
                                    <li>This is sample text 3</li>
                                </ul>
                                <a href="https://www.centridsol.com">www.centridsol.com</a>')
                    ],
                    [
                        'id' => '_test_richtextarea_field_2',
                        'label' => 'Sample Rich Text Area Field 2',
                        'type' => WPooWTestsElements::RICHTEXTAREA,
                        'test_value' =>  preg_replace("/\r\n|\r|\n/", '', '<h1><strong>Sample Text Two</strong></h1>
                                    <ol>
                                        <li>
                                    <blockquote>This is sample text</blockquote>
                                    </li>
                                        <li>
                                    <blockquote>This is sample text 2</blockquote>
                                    </li>
                                        <li>
                                    <blockquote>This is sample text 3</blockquote>
                                    </li>
                                    </ol>')
                    ]];
                break;
        }

        return $baseSamplePostType;
    }

    /**************************
     * / TESTS                   *
     * /**************************/


    // Using parents tests

    /**************************
     * / WP_BEFORE RUN FUNCTIONS *
     * /**************************/

    // Using parents class

}
