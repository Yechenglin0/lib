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
            $data[$i]['tstm'] = $var['TSTM'];
            
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
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("tm like '%$bookName%' AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,tstm,zrz,gcdmc')->limit($begin,6)->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['TSTM'];
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
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("zrz like '%$bookWriter%' AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,tstm,zrz,gcdmc')->limit($begin,6)->select();
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['TSTM'];
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
        $end = I('post.end');
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
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("(zrz like '%$content%' OR tm like '%$content%') AND ztbs = '41' AND gcdmc != '报损库' AND gcdmc != '丢失' AND gcdmc != '教阅室（教阅库）'")->group('tm,tstm,zrz,gcdmc')->limit($begin,$end)->select();
        $char = array('[ABCDEGHJK]' => '社科借阅室', 'F' => '经济借阅室', '[NOPQRSTUVWXYZ]' => '科技借阅室', 'T[A-Z]{1}' => '借阅室', 'I' => '文学借阅室' );
        $i = 0;
        foreach ($info as $var) {
            $data[$i]['bookName'] = $var['TM'];
            $data[$i]['code'] = $var['TSTM'];
            $data[$i]['writer'] = $var['ZRZ'];
            $data[$i]['place'] = $var['GCDMC'];
            foreach ($char as $key => $value) {
                $reg = "/^".$key."/";
                if(preg_match($reg, $var['tstm']))
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
     *  按照时间查图书
     */
    public function libRecentBook()
    {
        $num = I('post.num');
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

        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->order("rdrq desc")->group('tm,tstm,zrz,gcdmc')->limit($num)->select();
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
     *  根据图书登记号查询 
     */
    public function libFindBookByDJH()
    {
        $djh = I('post.djh');
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
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("djh = '$djh'")->find();
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
        $tstm = I('post.tstm');
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
        $info = M('t_ts')->field("tm,tstm,zrz,gcdmc")->where("tstm = '$tstm'")->find();
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
