<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    /* public function getPage(){
        //$this->assign('title','静态文件测试');
        //$this->assign('info','这是一个后端数据');
        $this->display();
    }*/
    public function test(){
        $this->ajaxReturn('success');
    }
    public function _initialize() {
        if (!$this->checkMethodPost()) {
            $data = array(
                'status' => '-400',
                'info' => 'Bad Request Pls Use Method POST',
                'version' => '1.0'
            );
            $this->ajaxReturn($data);
        }
    }

    private function checkMethodPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function _empty(){
        $data = array(
            'status' => '-404',
            'info' => 'Not Found',
            'version' => '1.0'
        );
        $this->ajaxReturn($data);
    }
    public function borrow() {
        $readerId = I('post.readerId');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts_jy')->where("sfrzh = '$readerId'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TSMC'];
            $data[$i]['start'] = substr($var['JSRQ'],0,10);
            $data[$i]['finish'] = $var['YHRQ'];
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }

    public function readerInfo() {
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');
        $readerId = I('post.readerId');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }

        $info = M('t_ts_dz')->where("zjhm = '$readerId'")->select();
        $data['name'] = $info[0]['DZXM'];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
        $data['history'] = $info[0]['LJCC'];
        $data['borrow'] = $info[0]['YJCS'];
        $data['qianfei'] = $info[0]['QKJE'];

        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }

    public function nameSearch() {
        $begin = I('post.begin');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');
        $bookName = I('post.bookName');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts')->field("tm,ssh,zrz,gcdmc")->where("tm like '%$bookName%' AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,ssh,zrz,gcdmc')->limit($begin,6)->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['SSH'];
            $data[$i]['writer'] = $var['ZRZ'];
            $data[$i]['place'] = $var['GCDMC'];
            $i++;
        }

        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));

    }

    public function writerSearch() {
        $begin = I('post.begin');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');
        $bookWriter = I('post.bookWriter');
        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts')->field("tm,ssh,zrz,gcdmc")->where("zrz like '%$bookWriter%' AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,ssh,zrz,gcdmc')->limit($begin,6)->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['SSH'];
            $data[$i]['writer'] = $var['ZRZ'];
            $data[$i]['place'] = $var['GCDMC'];
            $i++;
        }

        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));

    }

    public function Board() {
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }

        $info = M('t_ts_dz')->order("ljcc + yjcs  desc")->limit(20)->select();

        $i = 0;
        foreach ($info as $var) {
            $data[$i]['name'] = $var['DZXM'];
            $data[$i]['xueyuan'] = $var['DWMC'];
            $data[$i]['rank'] = $var['LJCC'] + $var['YJCS'];
            $i++;
        }

        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }

    public function bookSearch(){
        $begin = I('post.begin');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');
        $content = I('post.content');
        $verify = sha1(sha1($time).md5($string)."redrock");
        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts')->field("tm,ssh,zrz,gcdmc")->where("(zrz like '%$content%' OR tm like '%$content%') AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,ssh,zrz,gcdmc')->limit($begin,12)->select();
        $char = array('[ABCDEGHJK]' => '社科借阅室', 'F' => '经济借阅室', '[NOPQRSTUVWXYZ]' => '科技借阅室', 'T[A-Z]{1}' => '借阅室', 'I' => '文学借阅室' );
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['SSH'];
            $data[$i]['writer'] = $var['ZRZ'];
            $data[$i]['place'] = $var['GCDMC'];
            foreach ($char as $key => $value) {
                $reg = "/^".$key."/";
                if(preg_match($reg, $var['SSH']))
                    $data[$i]['borrowroom'] = $value;
            }
            if(!isset($data[$i]['borrowroom']))    $data[$i]['borrowroom'] = "阅览室(不外借)";
            $i++;
        }

        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }



    /**
     * @author 叶成林
     */



    /**
     *  @param start 起始时间
     *
     *  @param end 结束时间
     *   
     *  按照时间查图书
     */
    public function libRecentBook()
    {
        $readerId = I('post.start');    //开始日期
        $readerId = I('post.end');      //结束日期

        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }

        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("rdrq between '$start' and '$end'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];                   //图书名称
            $data[$i]['bookTM'] = $var['TSTM'];                     //图书条码
            $data[$i]['writer'] = $var['ZRZ'];                      //作者
            $data[$i]['place'] = $var['GCDMC'];                      //馆藏地名称
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data,
        ));
    }
    /**
     *  @param readerId 学号
     *  历史借阅 通过学号查询历史借阅记录
     */
    public function libHistoryBook()
    {
        $readerId = I('post.readerId');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }

        $info = M('t_ts_jyls')->where("sfrzh = '$readerId'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TSMC'];                   //图书名称
            $data[$i]['bookTM'] = $var['TSTM'];                     //图书条码
        
            $data[$i]['finish'] = $var['HSRQ'];                     //还书日期
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data,
        ));
    }

    /**
     *  欠费金额 用学号查学生什么书欠了钱
     */
    public function libOwedBook()
    {
        $readerId = I('post.readerId');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts_qf')->where("sfrzh = '$readerId'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];     //书名
            $data[$i]['djh'] = $var['DJH'];         //书的登记号？
            $data[$i]['money'] = $var['YFJE'];      //应付金额
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }
    /**
     *  根据图书编号查询 判断书籍是否可借 可借返回1 不可解返回0
     */
    public function libIsAvailable() {

        $readerId = I('post.booktm');   //图书的编号
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $nowDate = date('Y-m-d');
        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts_jy')->where("tstm = '$booktm' and yhrq < '$nowDate'")->find();
        
        if ($info) {
            $data = 1;
        } else {
            $data = 0;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }
    /**
     *  根据图书登记号查询 
     */
    public function libFindBookByDJH()
    {
        $readerId = I('post.djh');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("djh = '$djh'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];     //书名
            $data[$i]['code'] = $var['TSTM'];       //书的编号
            $data[$i]['write'] = $var['ZRZ'];       //作者
            $data[$i]['place'] = $var['GCDMC'];     //馆藏地名称
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }

    /**
     *  根据图书条码(tstm字段)查询 
     */
    public function libFindBookBytstm()
    {
        $readerId = I('post.tstm');
        $time = I('post.timestamp');
        $string = I('post.string');
        $secret = I('post.secret');

        $verify = sha1(sha1($time).md5($string)."redrock");

        if ($verify != $secret) {
            $this->ajaxReturn(array(
                'status' => '-400',
                'info' => 'Secret is Error'
            ));
        }
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("tstm = '$tstm'")->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];     //书名
            
            $data[$i]['write'] = $var['ZRZ'];       //作者
            $data[$i]['place'] = $var['GCDMC'];     //馆藏地名称
            $i++;
        }
        $this->ajaxReturn(array(
            "status" => 200,
            "info" => "success",
            "data" => $data
        ));
    }
}
