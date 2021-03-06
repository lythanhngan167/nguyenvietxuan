<?php

use api\model\dao\RequestPackageDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceRequestPackageList extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'requestpackagelist/';
        return $routes;
    }

    public function post()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        $userGroup = JAccess::getGroupsByUser($user->id, false);
        $result = array();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;

        if ($user->id > 0 && (int)$userGroup[0] == 2) {
            $params['where'][] = 'created_by = ' . (int)$user->id;
            $dao = new RequestPackageDao();
            $result['requests'] = $dao->getRequests($params);
            $result['provinces'] = $this->_getProvinces();
            $result['services'] = $this->_getServices();
            $result['companies'] = $this->_getCompany();
        }
        
        $this->plugin->setResponse($result);
    }


    private function _getProvinces() {
        return array(
            (object)array(
                'id' => '',
                'name' => 'Tỉnh/TP'
            ),
            (object)array(
                'id' => '1',
                'name' => 'An Giang'
            ),
            (object)array(
                'id' => '2',
                'name' => 'Bà Rịa - Vũng Tàu'
            ),
            (object)array(
                'id' => '3',
                'name' => 'Bạc Liêu'
            ),
            (object)array(
                'id' => '4',
                'name' => 'Bắc Kạn'
            ),
            (object)array(
                'id' => '5',
                'name' => 'Bắc Giang'
            ),
            (object)array(
                'id' => '6',
                'name' => 'Bắc Ninh'
            ),
            (object)array(
                'id' => '7',
                'name' => 'Bến Tre'
            ),
            (object)array(
                'id' => '8',
                'name' => 'Bình Dương'
            ),
            (object)array(
                'id' => '9',
                'name' => 'Bình Định'
            ),
            (object)array(
                'id' => '10',
                'name' => 'Bình Phước'
            ),
            (object)array(
                'id' => '11',
                'name' => 'Bình Thuận'
            ),
            (object)array(
                'id' => '12',
                'name' => 'Cà Mau'
            ),
            (object)array(
                'id' => '13',
                'name' => 'Cao Bằng'
            ),
            (object)array(
                'id' => '14',
                'name' => 'Cần Thơ'
            ),
            (object)array(
                'id' => '15',
                'name' => 'Đà Nẵng'
            ),
            (object)array(
                'id' => '16',
                'name' => 'Đắk Lắk'
            ),
            (object)array(
                'id' => '17',
                'name' => 'Đắk Nông'
            ),
            (object)array(
                'id' => '18',
                'name' => 'Đồng Nai'
            ),
            (object)array(
                'id' => '19',
                'name' => 'Đồng Tháp'
            ),
            (object)array(
                'id' => '20',
                'name' => 'Điện Biên'
            ),
            (object)array(
                'id' => '21',
                'name' => 'Gia Lai'
            ),
            (object)array(
                'id' => '22',
                'name' => 'Hà Giang'
            ),
            (object)array(
                'id' => '23',
                'name' => 'Hà Nam'
            ),
            (object)array(
                'id' => '24',
                'name' => 'Hà Nội'
            ),
            (object)array(
                'id' => '25',
                'name' => 'Hà Tĩnh'
            ),
            (object)array(
                'id' => '26',
                'name' => 'Hải Dương'
            ),
            (object)array(
                'id' => '27',
                'name' => 'Hải Phòng'
            ),
            (object)array(
                'id' => '28',
                'name' => 'Hòa Bình'
            ),
            (object)array(
                'id' => '29',
                'name' => 'Hậu Giang'
            ),
            (object)array(
                'id' => '30',
                'name' => 'Hưng Yên'
            ),
            (object)array(
                'id' => '31',
                'name' => 'TP. Hồ Chí Minh'
            ),
            (object)array(
                'id' => '32',
                'name' => 'Khánh Hòa'
            ),
            (object)array(
                'id' => '33',
                'name' => 'Kiên Giang'
            ),
            (object)array(
                'id' => '34',
                'name' => 'Kon Tum'
            ),
            (object)array(
                'id' => '35',
                'name' => 'Lai Châu'
            ),
            (object)array(
                'id' => '36',
                'name' => 'Lào Cai'
            ),
            (object)array(
                'id' => '37',
                'name' => 'Lạng Sơn'
            ),
            (object)array(
                'id' => '38',
                'name' => 'Lâm Đồng'
            ),
            (object)array(
                'id' => '39',
                'name' => 'Long An'
            ),
            (object)array(
                'id' => '40',
                'name' => 'Nam Định'
            ),
            (object)array(
                'id' => '41',
                'name' => 'Nghệ An'
            ),
            (object)array(
                'id' => '42',
                'name' => 'Ninh Bình'
            ),
            (object)array(
                'id' => '43',
                'name' => 'Ninh Thuận'
            ),
            (object)array(
                'id' => '44',
                'name' => 'Phú Thọ'
            ),
            (object)array(
                'id' => '45',
                'name' => 'Phú Yên'
            ),
            (object)array(
                'id' => '46',
                'name' => 'Quảng Bình'
            ),
            (object)array(
                'id' => '47',
                'name' => 'Quảng Nam'
            ),
            (object)array(
                'id' => '48',
                'name' => 'Quảng Ngãi'
            ),
            (object)array(
                'id' => '49',
                'name' => 'Quảng Ninh'
            ),
            (object)array(
                'id' => '50',
                'name' => 'Quảng Trị'
            ),
            (object)array(
                'id' => '51',
                'name' => 'Sóc Trăng'
            ),
            (object)array(
                'id' => '52',
                'name' => 'Sơn La'
            ),
            (object)array(
                'id' => '53',
                'name' => 'Tây Ninh'
            ),
            (object)array(
                'id' => '54',
                'name' => 'Thái Bình'
            ),
            (object)array(
                'id' => '55',
                'name' => 'Thái Nguyên'
            ),
            (object)array(
                'id' => '56',
                'name' => 'Thanh Hóa'
            ),
            (object)array(
                'id' => '57',
                'name' => 'Thừa Thiên - Huế'
            ),
            (object)array(
                'id' => '58',
                'name' => 'Tiền Giang'
            ),
            (object)array(
                'id' => '59',
                'name' => 'Trà Vinh'
            ),
            (object)array(
                'id' => '60',
                'name' => 'Tuyên Quang'
            ),
            (object)array(
                'id' => '61',
                'name' => 'Vĩnh Long'
            ),
            (object)array(
                'id' => '62',
                'name' => 'Vĩnh Phúc'
            ),
            (object)array(
                'id' => '63',
                'name' => 'Yên Bái'
            )
        );
    }

    private function _getServices() {
        return array(
            (object)array(
                'id' => 9,
                'name' => 'Bảo hiểm nhân thọ'
            ),
            (object)array(
                'id' => 10,
                'name' => 'Bảo hiểm sức khoẻ'
            ),
            (object)array(
                'id' => 11,
                'name' => 'Bảo hiểm du lịch'
            ),
            (object)array(
                'id' => 12,
                'name' => 'Bảo hiểm ô tô'
            ),
            (object)array(
                'id' => 13,
                'name' => 'Bảo hiểm nhà'
            ),
            (object)array(
                'id' => 14,
                'name' => 'Bảo hiểm bệnh hiểm nghèo'
            ),
            (object)array(
                'id' => 15,
                'name' => 'Các loại bảo hiểm khác'
            )
            
        );
    }

    private function _getCompany() {
        $company = array(
            9 => array(
                'AIA Việt Nam',
                'Aviva Việt Nam',
                'BIDV MetLife',
                'Bảo Việt Nhân Thọ',
                'Cathay Life',
                'Chubb Life Việt Nam',
                'Dai-ichi Life Việt Nam',
                'FWD Vietnam',
                'Fubon Việt Nam',
                'Generali Việt Nam',
                'Hanwha Life Việt Nam',
                'MAP Life',
                'Manulife Việt Nam',
                'Phú Hưng Life',
                'Prudential',
                'Sun Life Việt Nam',
                'VCLI'
            ),
            10 => array(
                'AAA',
                'BIC',
                'Bảo Long',
                'Bảo Minh',
                'Bảo hiểm Bảo Việt',
                'Bảo hiểm Liberty',
                'GIC',
                'Generali Việt Nam',
                'Pjico',
                'UIC',
                'VBI'
            ),
            11 => array(
                'AAA',
                'BIC',
                'Bảo Long',
                'Bảo Minh',
                'Bảo hiểm Bảo Việt',
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
                'Bảo Long',
                'Bảo Minh',
                'Bảo hiểm Bảo Việt',
                'Bảo hiểm Liberty',
                'Bảo hiểm Phú Hưng',
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
                'Bảo hiểm Bảo Việt',
                'MIC',
                'VBI'
            ),
            14 => array(
                'Bảo hiểm Bảo Việt',
                'Bảo hiểm Liberty',
                'Chubb Insurance',
                'PTI'
            ),
            15 => array(
                ''
            )
        );

        return $company;
    }
}
