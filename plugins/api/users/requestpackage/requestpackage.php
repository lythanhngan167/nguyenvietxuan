<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use Joomla\CMS\Form\Form;
use api\model\form\RequestPackageForm;
jimport('joomla.user.user');

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceRequestpackage extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'requestpackage/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/customers",
     *     tags={"Customers"},
     *     summary="Get customers list",
     *     description="Get customers list",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Get project list",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/CustomerQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerBiz")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function post()
    {
        $data = $this->getRequestData();
        $result = array();
        $result = [];
        if(isset($data['type'])){
            $type = $data['type'];
            switch($type) {
                case 'requestData':
                    $result['provinces'] = $this->_getProvinces();
                    $result['services'] = $this->_getServices();
                    $result['companies'] = $this->_getCompany();
                break;
                case 'request':
                    $result = $this->_registerRequestPackage($data);
                break;
                default:
                    ApiError::raiseError('400', 'Bad request');
                    return false;
                break;
            }
            
        } else {
            ApiError::raiseError('400', 'Bad request');
            return false;
        }
        $this->plugin->setResponse($result);
        
    }

    private function _getProvinces() {
        return array(
            (object)array(
                'id' => '',
                'name' => 'T???nh/TP'
            ),
            (object)array(
                'id' => '1',
                'name' => 'An Giang'
            ),
            (object)array(
                'id' => '2',
                'name' => 'B?? R???a - V??ng T??u'
            ),
            (object)array(
                'id' => '3',
                'name' => 'B???c Li??u'
            ),
            (object)array(
                'id' => '4',
                'name' => 'B???c K???n'
            ),
            (object)array(
                'id' => '5',
                'name' => 'B???c Giang'
            ),
            (object)array(
                'id' => '6',
                'name' => 'B???c Ninh'
            ),
            (object)array(
                'id' => '7',
                'name' => 'B???n Tre'
            ),
            (object)array(
                'id' => '8',
                'name' => 'B??nh D????ng'
            ),
            (object)array(
                'id' => '9',
                'name' => 'B??nh ?????nh'
            ),
            (object)array(
                'id' => '10',
                'name' => 'B??nh Ph?????c'
            ),
            (object)array(
                'id' => '11',
                'name' => 'B??nh Thu???n'
            ),
            (object)array(
                'id' => '12',
                'name' => 'C?? Mau'
            ),
            (object)array(
                'id' => '13',
                'name' => 'Cao B???ng'
            ),
            (object)array(
                'id' => '14',
                'name' => 'C???n Th??'
            ),
            (object)array(
                'id' => '15',
                'name' => '???? N???ng'
            ),
            (object)array(
                'id' => '16',
                'name' => '?????k L???k'
            ),
            (object)array(
                'id' => '17',
                'name' => '?????k N??ng'
            ),
            (object)array(
                'id' => '18',
                'name' => '?????ng Nai'
            ),
            (object)array(
                'id' => '19',
                'name' => '?????ng Th??p'
            ),
            (object)array(
                'id' => '20',
                'name' => '??i???n Bi??n'
            ),
            (object)array(
                'id' => '21',
                'name' => 'Gia Lai'
            ),
            (object)array(
                'id' => '22',
                'name' => 'H?? Giang'
            ),
            (object)array(
                'id' => '23',
                'name' => 'H?? Nam'
            ),
            (object)array(
                'id' => '24',
                'name' => 'H?? N???i'
            ),
            (object)array(
                'id' => '25',
                'name' => 'H?? T??nh'
            ),
            (object)array(
                'id' => '26',
                'name' => 'H???i D????ng'
            ),
            (object)array(
                'id' => '27',
                'name' => 'H???i Ph??ng'
            ),
            (object)array(
                'id' => '28',
                'name' => 'H??a B??nh'
            ),
            (object)array(
                'id' => '29',
                'name' => 'H???u Giang'
            ),
            (object)array(
                'id' => '30',
                'name' => 'H??ng Y??n'
            ),
            (object)array(
                'id' => '31',
                'name' => 'TP. H??? Ch?? Minh'
            ),
            (object)array(
                'id' => '32',
                'name' => 'Kh??nh H??a'
            ),
            (object)array(
                'id' => '33',
                'name' => 'Ki??n Giang'
            ),
            (object)array(
                'id' => '34',
                'name' => 'Kon Tum'
            ),
            (object)array(
                'id' => '35',
                'name' => 'Lai Ch??u'
            ),
            (object)array(
                'id' => '36',
                'name' => 'L??o Cai'
            ),
            (object)array(
                'id' => '37',
                'name' => 'L???ng S??n'
            ),
            (object)array(
                'id' => '38',
                'name' => 'L??m ?????ng'
            ),
            (object)array(
                'id' => '39',
                'name' => 'Long An'
            ),
            (object)array(
                'id' => '40',
                'name' => 'Nam ?????nh'
            ),
            (object)array(
                'id' => '41',
                'name' => 'Ngh??? An'
            ),
            (object)array(
                'id' => '42',
                'name' => 'Ninh B??nh'
            ),
            (object)array(
                'id' => '43',
                'name' => 'Ninh Thu???n'
            ),
            (object)array(
                'id' => '44',
                'name' => 'Ph?? Th???'
            ),
            (object)array(
                'id' => '45',
                'name' => 'Ph?? Y??n'
            ),
            (object)array(
                'id' => '46',
                'name' => 'Qu???ng B??nh'
            ),
            (object)array(
                'id' => '47',
                'name' => 'Qu???ng Nam'
            ),
            (object)array(
                'id' => '48',
                'name' => 'Qu???ng Ng??i'
            ),
            (object)array(
                'id' => '49',
                'name' => 'Qu???ng Ninh'
            ),
            (object)array(
                'id' => '50',
                'name' => 'Qu???ng Tr???'
            ),
            (object)array(
                'id' => '51',
                'name' => 'S??c Tr??ng'
            ),
            (object)array(
                'id' => '52',
                'name' => 'S??n La'
            ),
            (object)array(
                'id' => '53',
                'name' => 'T??y Ninh'
            ),
            (object)array(
                'id' => '54',
                'name' => 'Th??i B??nh'
            ),
            (object)array(
                'id' => '55',
                'name' => 'Th??i Nguy??n'
            ),
            (object)array(
                'id' => '56',
                'name' => 'Thanh H??a'
            ),
            (object)array(
                'id' => '57',
                'name' => 'Th???a Thi??n - Hu???'
            ),
            (object)array(
                'id' => '58',
                'name' => 'Ti???n Giang'
            ),
            (object)array(
                'id' => '59',
                'name' => 'Tr?? Vinh'
            ),
            (object)array(
                'id' => '60',
                'name' => 'Tuy??n Quang'
            ),
            (object)array(
                'id' => '61',
                'name' => 'V??nh Long'
            ),
            (object)array(
                'id' => '62',
                'name' => 'V??nh Ph??c'
            ),
            (object)array(
                'id' => '63',
                'name' => 'Y??n B??i'
            )
        );
    }

    private function _getServices() {
        return array(
            (object)array(
                'id' => 9,
                'name' => 'B???o hi???m nh??n th???'
            ),
            (object)array(
                'id' => 10,
                'name' => 'B???o hi???m s???c kho???'
            ),
            (object)array(
                'id' => 11,
                'name' => 'B???o hi???m du l???ch'
            ),
            (object)array(
                'id' => 12,
                'name' => 'B???o hi???m ?? t??'
            ),
            (object)array(
                'id' => 13,
                'name' => 'B???o hi???m nh??'
            ),
            (object)array(
                'id' => 14,
                'name' => 'B???o hi???m b???nh hi???m ngh??o'
            ),
            (object)array(
                'id' => 15,
                'name' => 'C??c lo???i b???o hi???m kh??c'
            )
            
        );
    }

    private function _getCompany() {
        $company = array(
            9 => array(
                'AIA Vi???t Nam',
                'Aviva Vi???t Nam',
                'BIDV MetLife',
                'B???o Vi???t Nh??n Th???',
                'Cathay Life',
                'Chubb Life Vi???t Nam',
                'Dai-ichi Life Vi???t Nam',
                'FWD Vietnam',
                'Fubon Vi???t Nam',
                'Generali Vi???t Nam',
                'Hanwha Life Vi???t Nam',
                'MAP Life',
                'Manulife Vi???t Nam',
                'Ph?? H??ng Life',
                'Prudential',
                'Sun Life Vi???t Nam',
                'VCLI'
            ),
            10 => array(
                'AAA',
                'BIC',
                'B???o Long',
                'B???o Minh',
                'B???o hi???m B???o Vi???t',
                'B???o hi???m Liberty',
                'GIC',
                'Generali Vi???t Nam',
                'Pjico',
                'UIC',
                'VBI'
            ),
            11 => array(
                'AAA',
                'BIC',
                'B???o Long',
                'B???o Minh',
                'B???o hi???m B???o Vi???t',
                'Cathay',
                'Chubb Insurance',
                'GIC',
                'MIC',
                'MSIG',
                'PTI',
                'Pjico',
                'UIC',
                'VASS',
                'VBI',
                'XTI'
            ),
            12 => array(
                'AAA',
                'ABIC',
                'BHV',
                'BIC',
                'B???o Long',
                'B???o Minh',
                'B???o hi???m B???o Vi???t',
                'B???o hi???m Liberty',
                'B???o hi???m Ph?? H??ng',
                'GIC',
                'MIC',
                'PTI',
                'Pjico',
                'UIC',
                'VBI',
                'VNI',
                'XTI'
            ),
            13 => array(
                'BIC',
                'B???o hi???m B???o Vi???t',
                'MIC',
                'VBI'
            ),
            14 => array(
                'B???o hi???m B???o Vi???t',
                'B???o hi???m Liberty',
                'Chubb Insurance',
                'PTI'
            ),
            15 => array(
                ''
            )
        );

        return $company;
    }

    private function _registerRequestPackage($data) {
        $form = new RequestPackageForm();
        $form->setAttributes($data);
        $user = JFactory::getUser();
        
        // Check user login
        if($user->id){
            if($form->validate()){
                $data = $form->toArray();
                $data['state'] = 1;
                $data['status'] = 'new';
                $data['id'] = 0;

                //save form logic from requestpackageform.php
                require_once JPATH_ROOT . '/components/com_request_package/models/requestpackageform.php';
                $requestPackage = new Request_packageModelRequestpackageForm();
                $table = $requestPackage->getTable();
                if($table->save($data) === true){
                    //Save customer
                    JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_registration/models', 'RegistrationModel');
                    $model = JModelLegacy::getInstance('RegistrationForm', 'RegistrationModel', array('ignore_request' => true));

                    $data['project_id'] = PROJECT_REQUEST_PACKAGE;
                    $data['reference_id'] = $table->id;
                    $data['reference_type'] = 'request_package';
                    $data['category_id'] = DATA_NEW;
                    $data['province'] = $table->province; // chua dung
                    $new_customer = $model->createCustomer($data);
                    $new_customer = $model->save($data);
                    return true;
                } else {
                    ApiError::raiseError('301', 'Error save');
                    return false;
                }
            }else{
                ApiError::raiseError('101', $form->getFirstError());
                return false;
            }
        }else{
            ApiError::raiseError('401', 'User do not login');
            return false;
        }
        
    }

}
