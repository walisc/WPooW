<?php



use WPooWTests\WPooWTestsElements;
use WPooWTests\WPooWBaseTestCase;
use WPooWTests\WPooWTestsConsts;


include_once __DIR__ . '/../../wpAPI.php';


class ElementPermissionsTest extends WPooWBaseTestCase
{
    /**************************
    / HELP DATA & FUNCTIONS   *
    /**************************/
    protected static function getSamplePostTypeData($id){
        $baseSamplePostType = self::getBaseSamplePostTypeData();
        $baseSamplePostType['fields'] = [];


        $defaultPermissions = [
            wpAPIPermissions::ViewTable => '',
            wpAPIPermissions::AddPage => '',
            wpAPIPermissions::EditPage => '',
        ];

        $permissions=[
            'cru',
            'cr',
            'c',
            'ru',
            'u',
            'cu',
            'r',
            ''
        ];



        switch ($id) {
            case 1:
                $idIndex = 0;
                foreach ($defaultPermissions as $pageType => $dPermissions){

                    $fieldPermissions = $defaultPermissions;

                    foreach ($permissions as $permission){
                        $fieldPermissions[$pageType] = $permission;

                        array_push($baseSamplePostType['fields'], [
                            'id' => '_test_select_field_'.$idIndex,
                            'label' => "Sample Select Field (${pageType} - ${permission})",
                            'type' => WPooWTestsElements::TEXT,
                            'permissions' => $fieldPermissions,
                            'test_value' => 'Permissions Tests']);
                        $idIndex++;
                    }
                }
                break;
        }

        return $baseSamplePostType;

    }


    /**************************
    / TESTS                   *
    /**************************/

    /**
     * @WP_BeforeRun createTextElementsForPermissions
     */
    function testTextElementPermissions(){
        $this->loginToWPAdmin();
        $sampleData = static::getSamplePostTypeData(1);

        $this->goToAddPage($sampleData['id']);
        $this->checkPermissions($sampleData['id'], $sampleData['fields'], WPooWTestsConsts::PAGE_TYPE_ADD);

        $postID = $this->addPost($sampleData['id'], $sampleData['fields']);
        $this->goToEditPage($sampleData['id'], $postID);
        $this->checkPermissions($sampleData['id'], $sampleData['fields'], WPooWTestsConsts::PAGE_TYPE_EDIT);

        $this->navigateToPostTypeMenuItem($sampleData['id']);
        $this->checkPermissions($sampleData['id'], $sampleData['fields'], WPooWTestsConsts::PAGE_TYPE_GRID);

    }

    /**************************
    / WP_BEFORE RUN FUNCTIONS *
    /**************************/


    public static function createTextElementsForPermissions()
    {
        self::createPostType(new wpAPI(), static::getSamplePostTypeData(1));
    }


}