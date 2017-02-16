<?php
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/8/24
 * Time: 10:03
 */

/*
 * 工具类，为了实现老系统数据与新系统数据之间的转换
 */
require_once("lib/MysqliDb.php");


class UpdateDB
{
    //旧数据库实例
    protected $oldDB;
    //新数据库实例
    protected $newDB;

    public function __construct()
    {

        $this->oldDB = new MysqliDb (Array(
            'host' => '192.168.110.200',
            'username' => 'root',
            'password' => 'bachangV3.0@mysql',
            'db' => 'ecq3_old',
            'port' => 3306,
            'prefix' => 'p_',
            'charset' => 'utf8'));

        $this->newDB = new MysqliDb (Array(
            'host' => '192.168.110.200',
            'username' => 'root',
            'password' => 'bachangV3.0@mysql',
            'db' => 'ECQ3_development',
            'port' => 3306,
            'prefix' => 'p_',
            'charset' => 'utf8'));
    }

    //对P_section 表的数据处理
    public function upsection()
    {
        $count = 1;
        $flag = TRUE;
        do {
            $rows = $this->oldDB->rawQuery("select a.*,b.VideoUrl,b.VideoTime,c.CtfID FROM p_section as a LEFT JOIN p_video as b on a.VideoCode=b.VideoCode LEFT JOIN p_ctf as c on a.CtfCode=c.CtfCode LIMIT $count,1000 ");
            $count += count($rows);

            if (count($rows) < 1000) {
                $flag = FALSE;
            }

            if (!empty($rows)) {

                foreach ($rows as $key => $value) {

                    $data[$key] = Array(
                        "SectionName" => $value['SectionName'],
                        "SectionDoc" => $value['SectionDoc'],
                        "SectionDocType" => $value['SectionDocType'],
                        "SectionPoint" => $value['SectionPoint'],
                        "SectionDiff" => $value['SectionDiff'],
                        "VideoUrl" => $value['VideoUrl'],
                        "VideoTime" => $value['VideoTime'],
                        "CtfID" => $value['CtfID'],
                        "SectionType" => $value['SectionType'],
                        "SectionDesc" => $value['SectionDesc'],
                        "IsSysSection" => $value['IsSysSection'],
                        "SectionGoal" => $value['SectionGoal'],
                        "SceneUUID" => $value['SceneUUID'],
                        "SectionCode" => $value['SectionCode']);
                }

                $this->newDB->insertMulti('section_copy', $data);
            }

        } while ($flag);

    }

}

$test= new \UpdateDB();
$a=$test->upsection();
var_dump($a);exit;