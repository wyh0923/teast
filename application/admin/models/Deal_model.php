<?php

/**
 * Created by PhpStorm.
 * User: whx
 * Date: 2016/9/29
 * Time: 9:29
 */
class Deal_model extends CI_Model
{

     /***
     * 对原系统库结构进行处理
     */
    public function updateStructure()
    {

        try {
            $database=$this->db->database;
            //p_architecture 查询字段类型  修改 ArchitectureParent,PublicTime 字段
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_architecture'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=$architecture->result();
                foreach($array as $v){
                    if($v->column_name == 'ArchitectureParent'){
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_architecture MODIFY ArchitectureParent varchar(64) DEFAULT '0'";
                           $res=$this->db->query($alter);
                        }
                    }
                    if($v->column_name == 'PublicTime'){
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_architecture MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_architecture set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                }
            }

            //p_architecture_package  新增ArchitectureID,PackageID  修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_architecture_package'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_architecture_package MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_architecture_package set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }
                

                if(!empty($array)){
                    if(!in_array('ArchitectureID',$array)){//不存在添加字段
                        $alter ="alter table p_architecture_package add column  ArchitectureID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('PackageID',$array)){//不存在添加字段
                        $alter ="alter table p_architecture_package add column  PackageID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                }
            }

            //p_class    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_class'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                foreach($architecture->result() as $v){
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_class MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_class set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }
                

                if(!empty($array)){
                    if(!in_array('ClassID',$array)){//不存在添加字段
                        if(in_array('ID',$array)){
                            //添加CtfID 设为自增主键
                            $alter ="alter table p_class change  ID ClassID int(11) unsigned not null auto_increment";
                            $res=$this->db->query($alter);
                        }
                    }
                    if(!in_array('TeacherID',$array)){//不存在添加字段
                        $alter ="alter table p_class add column  TeacherID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                } 
            }
            //p_class_user  新增UserID,ClassID
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_class_user'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('UserID',$array)){//不存在添加字段
                        $alter ="alter table p_class_user add column  UserID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ClassID',$array)){//不存在添加字段
                        $alter ="alter table p_class_user add column  ClassID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                }
            }



            //p_course    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_course'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_course MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_course set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql); 
                        }
                    }
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_course MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_course set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql); 
                        }
                    }
                }
            }

            //p_course_section     新增CourseID,SectionID  修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_course_section'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_course_section MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_course_section set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }

                if(!empty($array)){
                    if(!in_array('SectionID',$array)){//不存在添加字段
                        $alter ="alter table p_course_section add column  SectionID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('CourseID',$array)){//不存在添加字段
                        $alter ="alter table p_course_section add column  CourseID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                }  
            }


            //p_ctf     新增CtfID,AuthorID  修改 PublicTime,CtfCreateTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_ctf'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_ctf MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_ctf set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'CtfCreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_ctf MODIFY CtfCreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_ctf set CtfCreateTime=UNIX_TIMESTAMP(CtfCreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('CtfID',$array)){//不存在添加字段
                        //先把原有的primary key 删除 在添加
                        $alter ="alter table p_ctf drop primary key";
                        $res=$this->db->query($alter);
                        if($res){
                            //添加CtfID 设为自增主键
                            $alter ="alter table p_ctf add column  CtfID int(11) not null  primary key auto_increment FIRST";
                            $res=$this->db->query($alter);
                        }
                    }
                    if(!in_array('AuthorID',$array)){//不存在添加字段
                        $alter ="alter table p_ctf add column  AuthorID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                }  
            }  

            //p_exam    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_exam'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_exam MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_exam set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_exam MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_exam set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('ExamID',$array)){//不存在添加字段
                        //先把原有的primary key 删除 在添加
                        $alter ="alter table p_exam drop primary key";
                        $res=$this->db->query($alter);
                        if($res){
                            //添加CtfID 设为自增主键
                            $alter ="alter table p_exam add column  ExamID int(11) unsigned not null  primary key auto_increment FIRST";
                            $res=$this->db->query($alter);
                        }
                    }
                    if(!in_array('TeacherID',$array)){//不存在添加字段
                        $alter ="alter table p_exam add column  TeacherID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                }  

            }

            //p_exam_question    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_exam_question'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_exam_question MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_exam_question set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('ExamID',$array)){//不存在添加字段
                        $alter ="alter table p_exam_question add column  ExamID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionID',$array)){//不存在添加字段
                        $alter ="alter table p_exam_question add column  QuestionID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                } 
            }

            //p_log    修改 CreateTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_log'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_log MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_log set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                $array[]=$v->column_name;  
                }
                if(!empty($array)){
                    if(!in_array('UserID',$array)){//不存在添加字段
                        $alter ="alter table p_log add column  UserID int(11) unsigned DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                } 
            }
            //p_package    修改 PackageParent,CreateTime，PublicTime
            $sql="select column_name,data_type,COLUMN_DEFAULT from information_schema.columns where table_schema='$database' and  table_name = 'p_package'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_package MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                            //更新时间
                            $sql="update p_package set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_package MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_package set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'PackageParent'){//修改时间字段类型
                        if($v->COLUMN_DEFAULT ==null){
                            //修改字段信息
                           $alter ="alter table p_package MODIFY PackageParent varchar(64) DEFAULT '0'";
                           $res=$this->db->query($alter);
                        }
                    }
                }
            }

            //p_package_course     新增CourseID,PackageID  修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_package_course'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_package_course MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_package_course set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('CourseID',$array)){//不存在添加字段
                        $alter ="alter table p_package_course add column  CourseID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('PackageID',$array)){//不存在添加字段
                        $alter ="alter table p_package_course add column  PackageID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                }  
            }


            //p_package_exam    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_package_exam'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_package_exam MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_package_exam set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('PackageID',$array)){//不存在添加字段
                        $alter ="alter table p_package_exam add column  PackageID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ExamID',$array)){//不存在添加字段
                        $alter ="alter table p_package_exam add column  ExamID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                }

            }

            //p_practice_instance    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_practice_instance'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('QuestionID',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column  QuestionID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('TaskCode',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column  TaskCode varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionDesc',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column  QuestionDesc text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionPriv',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionPriv  tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionAnswer',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionAnswer  text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionLink',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionLink varchar(60) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionLinkType',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionLinkType tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionDiff',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionDiff tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionScore',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionScore int(10) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionScene',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionScene tinyint(4) DEFAULT '1'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ResourceUrl',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column ResourceUrl varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ResourceName',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column ResourceName varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('judge',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column judge int(4) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionType',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionType tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionChoose',$array)){//不存在添加字段
                        $alter ="alter table p_practice_instance add column QuestionChoose text";
                        $res=$this->db->query($alter);
                    }

                }

            }


            //p_question     新增ResourceUrl,ResourceName  修改 CreateTime,UpdateTime,PublicTime，QuestionLink
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_question'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_question MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_question set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_question MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_question set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'UpdateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_question MODIFY UpdateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_question set UpdateTime=UNIX_TIMESTAMP(UpdateTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('ResourceUrl',$array)){//不存在添加字段
                        $alter ="alter table p_question add column  ResourceUrl varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ResourceName',$array)){//不存在添加字段
                        $alter ="alter table p_question add column  ResourceName varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                }
            }
            //p_question_instance    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_question_instance'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('QuestionID',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  QuestionID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('TaskCode',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  TaskCode varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionDesc',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  QuestionDesc text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionType',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  QuestionType tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionChoose',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  QuestionChoose text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionAuthor',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column  QuestionAuthor int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionPriv',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionPriv  tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionAnswer',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionAnswer  text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionLink',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionLink varchar(60) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionLinkType',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionLinkType tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionDiff',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionDiff tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionScore',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionScore int(10) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionScene',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column QuestionScene tinyint(4) DEFAULT '1'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ResourceUrl',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column ResourceUrl varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ResourceName',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column ResourceName varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('UpdateTime',$array)){//不存在添加字段
                        $alter ="alter table p_question_instance add column UpdateTime varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }

                }

            }

            //p_section_instance    修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_section_instance'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'FinishedTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_section_instance MODIFY FinishedTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_section_instance set FinishedTime=UNIX_TIMESTAMP(FinishedTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }
                if(!empty($array)){
                    if(!in_array('SectionID',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column  SectionID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('TaskCode',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column  TaskCode varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionName',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column  SectionName varchar(100) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionDoc',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SectionDoc  text";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionDocType',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SectionDocType int(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionDiff',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SectionDiff int(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('VideoUrl',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column VideoUrl varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('VideoTime',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column VideoTime int(11) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SceneUUID',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SceneUUID varchar(60) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('CtfID',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column CtfID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionType',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SectionType tinyint(4) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionDesc',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SectionDesc varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('IsSysSection',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column IsSysSection tinyint(4) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SceneInstanceUUID',$array)){//不存在添加字段
                        $alter ="alter table p_section_instance add column SceneInstanceUUID varchar(64) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }

                }

            }


            //p_section     新增SectionID,VideoUrl,VideoTime,CtfID  修改 PublicTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_section'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'PublicTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_section MODIFY PublicTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_section set PublicTime=UNIX_TIMESTAMP(PublicTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('SectionID',$array)){//不存在添加字段
                        //删除ScetionID
                        if(in_array('ScetionID',$array)){
                            //添加SectionID 设为自增主键
                            $alter ="alter table p_section change ScetionID SectionID int(11) unsigned  not null  auto_increment";
                            $res=$this->db->query($alter);
                        }
                        
                    }
                    if(!in_array('VideoUrl',$array)){//不存在添加字段
                        $alter ="alter table p_section add column  VideoUrl  varchar(255) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('VideoTime',$array)){//不存在添加字段
                        $alter ="alter table p_section add column  VideoTime int(11) NOT NULL DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('CtfID',$array)){//不存在添加字段
                        $alter ="alter table p_section add column  CtfID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('VideoCode',$array)){//不存在添加字段
                        $alter ="alter table p_section add column  VideoCode varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }

                }  
            }

            //p_section_question     新增SectionID,QuestionID  
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_section_question'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('SectionID',$array)){//不存在添加字段
                        $alter ="alter table p_section_question add column  SectionID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('QuestionID',$array)){//不存在添加字段
                        $alter ="alter table p_section_question add column  QuestionID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ischeck',$array)){//不存在添加字段
                        $alter ="alter table p_section_question add column ischeck  tinyint(4) DEFAULT '1'";
                        $res=$this->db->query($alter);
                    }
                }  
            }

             //p_section_tool     新增ToolID,SectionID
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_section_tool'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                }  
                if(!empty($array)){
                    if(!in_array('ToolID',$array)){//不存在添加字段
                        $alter ="alter table p_section_tool add column  ToolID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('SectionID',$array)){//不存在添加字段
                        $alter ="alter table p_section_tool add column  SectionID int(11) NOT NULL";
                        $res=$this->db->query($alter);
                    }
                }  
            }

            //p_task    修改 TaskStartTime,TaskEndTime,TaskFinishedTime,CreateTime
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_task'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'TaskStartTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_task MODIFY TaskStartTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_task set TaskStartTime=UNIX_TIMESTAMP(TaskStartTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'TaskEndTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_task MODIFY TaskEndTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_task set TaskEndTime=UNIX_TIMESTAMP(TaskEndTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'TaskFinishedTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_task MODIFY TaskFinishedTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_task set TaskFinishedTime=UNIX_TIMESTAMP(TaskFinishedTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_task MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_task set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    $array[]=$v->column_name;
                } 
                if(!empty($array)){
                    if(!in_array('TaskCode',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  TaskCode varchar(50) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('TeacherID',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  TeacherID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('PackageID',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  PackageID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ExamID',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  ExamID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('StudentID',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  StudentID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('ClassID',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  ClassID int(11) DEFAULT NULL";
                        $res=$this->db->query($alter);
                    }
                    if(!in_array('TeaEnd',$array)){//不存在添加字段
                        $alter ="alter table p_task add column  TeaEnd int(10) DEFAULT '0'";
                        $res=$this->db->query($alter);
                    }
                }

            }

            //p_tool    修改 Created
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_tool'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'Created'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_tool MODIFY Created varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_tool set Created=UNIX_TIMESTAMP(Created)";
                            $this->db->query($sql);
                        }
                    }
                }   
            }

            //p_tool_types    修改 Created
            $sql="select column_name,data_type,COLUMN_DEFAULT from information_schema.columns where table_schema='$database' and  table_name = 'p_tool_types'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    if($v->column_name == 'Pid'){//修改时间字段类型
                        if($v->COLUMN_DEFAULT ==NULL){
                            //修改字段信息
                           $alter ="alter table p_tool_types MODIFY Pid int(11) NOT NULL DEFAULT '0'";
                           $res=$this->db->query($alter);
                        }
                    }
                }   
            }

            //p_user    修改 CreateTime,LastLoginTime  
            $sql="select column_name,data_type from information_schema.columns where table_schema='$database' and  table_name = 'p_user'";
            $architecture=$this->db->query($sql);
            if(!empty($architecture) &&  $architecture->num_rows()>0){
                $array=null;
                foreach($architecture->result() as $v){
                    $array[]=$v->column_name;
                    if($v->column_name == 'CreateTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_user MODIFY CreateTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_user set CreateTime=UNIX_TIMESTAMP(CreateTime)";
                            $this->db->query($sql);
                        }
                    }
                    if($v->column_name == 'LastLoginTime'){//修改时间字段类型
                        if($v->data_type !='varchar'){
                            //修改字段信息
                           $alter ="alter table p_user MODIFY LastLoginTime varchar(50) DEFAULT NULL";
                           $res=$this->db->query($alter);
                           //更新时间
                            $sql="update p_user set LastLoginTime=UNIX_TIMESTAMP(LastLoginTime)";
                            $this->db->query($sql);
                        }
                    }
                }
                if(!in_array('UserID',$array)){//不存在添加字段
                    //先把原有的primary key 删除 在添加
                    $alter ="alter table p_user drop primary key";
                    $res=$this->db->query($alter);
                    if($res){
                        //添加CtfID 设为自增主键
                        $alter ="alter table p_user add column  UserID int(11) not null  primary key auto_increment FIRST";
                        $res=$this->db->query($alter);
                    }
                }
            }
            //p_menu 创建
            $sqlExist = "show tables like 'p_menu'";
            $isExist = $this->db->query($sqlExist)->result();
            if(empty($isExist)){
                $sql="CREATE TABLE `p_menu` (";
                $sql.="     `id` int(11) NOT NULL AUTO_INCREMENT,";
                $sql.="      `title` varchar(30) NOT NULL COMMENT '标题',";
                $sql.="      `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父id,为0的代表是根菜单',";
                $sql.="      `url` varchar(100) NOT NULL COMMENT 'URL',";
                $sql.="      `icon` varchar(20) NOT NULL COMMENT '图标',";
                $sql.="      `role_id` int(11) NOT NULL COMMENT '角色ID,1管理员,2教员,3学员',";
                $sql.="      `index` int(11) NOT NULL DEFAULT '1',";
                $sql.="      `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(1:正常,0:停用)',";
                $sql.="      PRIMARY KEY (`id`),";
                $sql.="      KEY `pid` (`pid`),";
                $sql.="      KEY `role_id` (`role_id`) USING BTREE";
                $sql.="    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='菜单表';";
                $this->db->query($sql);
                //插入数据
                $this->insertMenu();
            }

            //p_issue 创建
            $sqlExist = "show tables like 'p_issue'";
            $isExist = $this->db->query($sqlExist)->result();
            if(empty($isExist)){
                $sql=" CREATE TABLE `p_issue` (";
                $sql.=" `SceneTemplateUUID` varchar(60) NOT NULL COMMENT '场景UUID',";
                $sql.=" `SceneInstanceUUID` varchar(50) DEFAULT NULL,";
                $sql.=" `TaskUUID` varchar(64) DEFAULT NULL,";
                $sql.=" `CreateTime` varchar(50) NOT NULL,";
                $sql.=" UNIQUE KEY `sceneuuid` (`SceneTemplateUUID`)";
                $sql.=" ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教员下发表'; ";
                $sql=$this->db->query($sql);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /***
     * 对系统库数据进行处理
     */
    public function updateDate(){
        try {
            //p_architecture_package  同步数据  ArchitectureID,PackageID
            $sql="update p_architecture_package join(";
            $sql.=" select p_architecture_package.ID,p_package.PackageID,p_architecture.ArchitectureID from p_architecture_package ";
            $sql.=" left join p_package on  p_package.PackageCode = p_architecture_package .PackageCode "; 
            $sql.=" left join p_architecture on  p_architecture.ArchitectureCode = p_architecture_package.ArchitectureCode";
            $sql.=" where p_architecture_package.PackageID =0 and p_architecture_package.ArchitectureID=0";
            $sql.="  ) b  set  p_architecture_package.ArchitectureID=b.ArchitectureID,p_architecture_package.PackageID=b.PackageID  where  p_architecture_package.ID=b.ID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'architecture_package';
            }

            //p_ctf  同步数据  CtfID,AuthorID
            $sql="update p_ctf join(";
            $sql.=" select p_ctf.CtfID,p_user.UserID from p_ctf ";
            $sql.=" left join p_user on  p_user.UserCode = p_ctf .CtfAuthorCode "; 
            $sql.=" where p_ctf.AuthorID =0";
            $sql.="  ) b  set  p_ctf.AuthorID=b.UserID  where  p_ctf.CtfID=b.CtfID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_ctf';
            }

            //p_package_course  同步数据  CourseID,PackageID
            $sql="update p_package_course join(";
            $sql.=" select p_package_course.PcID,p_package.PackageID,p_course.CourseID from p_package_course ";
            $sql.=" left join p_package on  p_package.PackageCode = p_package_course .PackageCode "; 
            $sql.=" left join p_course on  p_course.CourseCode = p_package_course.CourseCode";
            $sql.=" where p_package_course.CourseID =0 and p_package_course.PackageID=0";
            $sql.="  ) b  set  p_package_course.CourseID=b.CourseID,p_package_course.PackageID=b.PackageID  where  p_package_course.PcID=b.PcID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package_course';
            }

            //p_course_section  同步数据  CourseID,SectionID
            $sql="update p_course_section join(";
            $sql.=" select p_course_section.CsID,p_section.SectionID,p_course.CourseID from p_course_section ";
            $sql.=" left join p_section on  p_section.SectionCode = p_course_section .SectionCode "; 
            $sql.=" left join p_course on  p_course.CourseCode = p_course_section.CourseCode";
            $sql.=" where p_course_section.CourseID =0 and p_course_section.SectionID=0";
            $sql.="  ) b  set  p_course_section.CourseID=b.CourseID,p_course_section.SectionID=b.SectionID  where  p_course_section.CsID=b.CsID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_course_section';
            }

            //p_section  同步数据  CtfID
            $sql="update p_section join(";
            $sql.=" select p_section.SectionID,p_ctf.CtfID,p_video.VideoUrl,p_video.VideoTime from p_section ";
            $sql.=" left join p_ctf on  p_ctf.CtfCode = p_section .CtfCode ";
            $sql.=" left join p_video on  p_video.VideoCode = p_section .VideoCode ";
            $sql.=" where p_section.CtfID is null";
            $sql.="  ) b  set  p_section.CtfID=b.CtfID,p_section.VideoUrl=b.VideoUrl,p_section.VideoTime=b.VideoTime  where  p_section.SectionID=b.SectionID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_section';
            }

            //p_section_question  同步数据  SectionID,QuestionID
            $sql="update p_section_question join(";
            $sql.=" select p_section_question.SqID,p_section.SectionID,p_question.QuestionID from p_section_question ";
            $sql.=" left join p_section on  p_section.SectionCode = p_section_question .SectionCode "; 
            $sql.=" left join p_question on  p_question.QuestionCode = p_section_question.QuestionCode";
            $sql.=" where p_section_question.QuestionID =0 and p_section_question.SectionID=0";
            $sql.="  ) b  set  p_section_question.QuestionID=b.QuestionID,p_section_question.SectionID=b.SectionID  where  p_section_question.SqID=b.SqID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_section_question';
            }

            //p_section_tool  同步数据  SectionID,ToolID
            $sql="update p_section_tool join(";
            $sql.=" select p_section_tool.ID as tid,p_section.SectionID,p_tool.ID from p_section_tool ";
            $sql.=" left join p_section on  p_section.SectionCode = p_section_tool .SectionCode "; 
            $sql.=" left join p_tool on  p_tool.ToolCode = p_section_tool.ToolCode";
            $sql.=" where p_section_tool.ToolID =0 and p_section_tool.SectionID=0";
            $sql.="  ) b  set  p_section_tool.ToolID=b.ID,p_section_tool.SectionID=b.SectionID  where  p_section_tool.ID=b.tid";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_section_tool';
            }

            //p_question  同步数据  ResourceUrl,ResourceName
            $sql="update p_question join(";
            $sql.=" select p_question.QuestionID,p_question_resource.ResourceUrl,p_question_resource.ResourceName from p_question ";
            $sql.=" left join p_question_resource on  p_question_resource.QuestionCode = p_question .QuestionCode "; 
            $sql.=" where p_question.ResourceUrl is null ";
            $sql.="  ) b  set  p_question.ResourceName=b.ResourceName,p_question.ResourceUrl=b.ResourceUrl  where  p_question.QuestionID=b.QuestionID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_question';
            }
            $sql="update p_package join(";
            $sql.=" select p_package.PackageID,p_package.PackageCode,p.PackageParent  from  p_package ";
            $sql.=" left join p_package as p on  p_package.PackageCode=p.PackageParent and p.PackageType=2 "; 
            $sql.=" where p_package.PackageType=1 and p.PackageParent is not null) a set p_package.PackageParent=a.PackageID where p_package.PackageParent=a.PackageParent "; 
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package';
            }

            $sql="UPDATE p_package SET PackageParent='0' WHERE PackageType=1";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package';
            }


            //p_class  同步数据  TeacherID
            $sql="update p_class join(";
            $sql.=" select p_class.ClassID,p_user.UserID from p_class ";
            $sql.=" left join p_user on  p_user.UserCode = p_class .ClassCode "; 
            $sql.=" where p_class.TeacherID is null";
            $sql.="  ) b  set  p_class.TeacherID=b.UserID  where  p_class.ClassID=b.ClassID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_class';
            }

            //p_class_user  同步数据  UserID,ClassID
            $sql="update p_class_user join(";
            $sql.=" select p_class_user.ID,p_user.UserID,p_class.ClassID from p_class_user ";
            $sql.=" left join p_user on  p_user.UserCode = p_class_user .UserCode "; 
            $sql.=" left join p_class on  p_class_user.ClassCode = p_class .ClassCode "; 
            $sql.=" where p_class_user.UserID is null";
            $sql.="  ) b  set  p_class_user.UserID=b.UserID,p_class_user.ClassID=b.ClassID  where  p_class_user.ID=b.ID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_class_user';
            }

            //p_exam  同步数据  TeacherID
            $sql="update p_exam join(";
            $sql.=" select p_exam.ExamID,p_user.UserID from p_exam ";
            $sql.=" left join p_user on  p_user.UserCode = p_exam .TeacherCode "; 
            $sql.=" where p_exam.TeacherID is null";
            $sql.="  ) b  set  p_exam.TeacherID=b.UserID  where  p_exam.ExamID=b.ExamID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_exam';
            }

            //p_exam_question  同步数据  UserID,ClassID
            $sql="update p_exam_question join(";
            $sql.=" select p_exam_question.ID,p_exam.ExamID,p_question.QuestionID from p_exam_question ";
            $sql.=" left join p_exam on  p_exam.ExamCode = p_exam_question .ExamCode "; 
            $sql.=" left join p_question on  p_exam_question.QuestionCode = p_question .QuestionCode "; 
            $sql.=" where p_exam_question.ExamID is null";
            $sql.="  ) b  set  p_exam_question.ExamID=b.ExamID,p_exam_question.QuestionID=b.QuestionID  where  p_exam_question.ID=b.ID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_exam_question';
            }

            //p_log  同步数据  TeacherID
            $sql="update p_log join(";
            $sql.=" select p_log.LogID,p_user.UserID from p_log ";
            $sql.=" left join p_user on  p_user.UserCode = p_log .UserCode "; 
            $sql.=" where p_log.UserID is null";
            $sql.="  ) b  set  p_log.UserID=b.UserID  where  p_log.LogID=b.LogID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_log';
            }

            //p_package_exam  同步数据  UserID,ClassID
            $sql="update p_package_exam join(";
            $sql.=" select p_package_exam.ID,p_package.PackageID,p_exam.ExamID from p_package_exam ";
            $sql.=" left join p_package on  p_package.PackageCode = p_package_exam .PackageCode "; 
            $sql.=" left join p_exam on  p_package_exam.ExamCode = p_exam .ExamCode "; 
            $sql.=" where p_package_exam.PackageID is null";
            $sql.="  ) b  set  p_package_exam.PackageID=b.PackageID,p_package_exam.ExamID=b.ExamID  where  p_package_exam.ID=b.ID";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package_exam';
            }

            $sql="UPDATE p_architecture SET ArchitectureParent='0' WHERE ArchitectureParent='----------------'";
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package';
            }

            $sql="update p_architecture join(";
            $sql.=" select p_architecture.ArchitectureID,p_architecture.ArchitectureCode,p.ArchitectureParent  from  p_architecture ";
            $sql.=" left join p_architecture as p on  p_architecture.ArchitectureCode=p.ArchitectureParent "; 
            $sql.=" where  p.ArchitectureParent is not null) a set p_architecture.ArchitectureParent=a.ArchitectureID where p_architecture.ArchitectureParent=a.ArchitectureParent "; 
            $res=$this->db->query($sql);
            if(!$res){
                return 'p_package';
            }

        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function insertMenu(){
        $sql=" INSERT INTO `p_menu` VALUES ('1', '系统管理', '0', 'System/info', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('2', '系统状态', '1', 'System/info', 'fa-line-chart', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('3', '系统设置', '1', 'System/config', 'fa-pie-chart', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('4', '节点管理', '1', 'System/server', 'fa-object-group', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('5', '虚拟化管理', '1', 'System/virtual', 'fa-tasks', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('6', '授权查询', '1', 'System/license', 'fa-newspaper-o', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('7', '知识体系管理', '0', 'Subject/mysystem', '', '2', '3', '1');
INSERT INTO `p_menu` VALUES ('8', '体系管理', '7', 'javascript:;', 'fa-archive', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('9', '我的体系', '8', 'Subject/mysystem', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('10', '课程管理', '7', 'javascript:;', 'fa-book', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('11', '我的课程', '10', 'Subject/mybook', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('12', '试卷管理', '7', 'javascript:;', 'fa-copy', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('13', '我的试卷', '12', 'Subject/myexam', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('14', '题目管理', '7', 'javascript:;', 'fa-question-circle', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('15', '所有题目', '14', 'Subject/questionlist', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('16', '工具库管理', '7', 'javascript:;', 'fa-wrench', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('17', '所有工具', '16', 'Subject/toollist', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('18', '分类管理', '16', 'Subject/toolcate', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('19', '添加工具', '16', 'Subject/addtool', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('20', '实训内容管理', '0', 'Train/ctflist', '', '2', '4', '1');
INSERT INTO `p_menu` VALUES ('21', 'CTF实训管理', '20', 'Train/ctflist', 'fa-newspaper-o', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('22', '场景管理', '20', 'javascript:;', 'fa-object-ungroup', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('23', '场景模板管理', '22', 'Train/scenelist', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('24', '场景模板制作', '22', 'Train/scenecreate', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('25', '虚拟机模板管理', '22', 'Train/vmlist', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('26', '班级人员管理', '0', 'Classstaff/myclass', '', '2', '2', '1');
INSERT INTO `p_menu` VALUES ('27', '教员管理', '26', 'AdminUserCtl/userlist', '', '0', '1', '1');
INSERT INTO `p_menu` VALUES ('28', '所有教员', '27', 'AdminUserCtl/userlist', '', '0', '1', '1');
INSERT INTO `p_menu` VALUES ('29', '新建教员', '27', 'AdminUserCtl/addteacher', '', '0', '1', '1');
INSERT INTO `p_menu` VALUES ('30', '班级管理', '26', 'javascript:;', 'fa-sitemap', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('31', '我的班级', '30', 'Classstaff/myclass', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('32', '新建班级', '30', 'Classstaff/addclass', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('33', '学员管理', '26', 'javascript:;', 'fa-user', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('34', '所有学员', '33', 'Classstaff/allstudents', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('35', '新建学员', '33', 'Classstaff/addstudent', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('36', '教学任务管理', '0', 'Education/edubook', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('37', '学习任务管理', '36', 'javascript:;', 'fa fa-edit', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('38', '新建学习任务', '37', 'Education/edubook', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('39', '已下发学习任务', '37', 'Education/studylist', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('40', '考试任务管理', '36', 'javascript:;', 'fa fa-edit', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('41', '新建考试任务', '40', 'Education/eduexam', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('42', '已下发考试任务', '40', 'Education/examtask', '', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('43', '教学统计中心', '0', 'Teacount/personalstatistic', '', '2', '5', '1');
INSERT INTO `p_menu` VALUES ('44', '教学统计', '43', 'Teacount/personalstatistic', 'fa-bar-chart', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('45', '个人信息', '43', 'Teacount/personaldetails', 'fa-user', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('46', '修改密码', '43', 'Teacount/modifypassword', 'fa-key', '2', '1', '1');
INSERT INTO `p_menu` VALUES ('47', '我的学习', '0', 'Study/listunderway', 'fa fa-hourglass', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('48', '正在进行的学习', '47', 'Study/listunderway', 'fa fa-hourglass', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('49', '已经完成的学习', '47', 'Study/listfinished', 'fa fa-hourglass-3', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('50', '我的考试', '0', 'Exam/listunderway', 'fa fa-hourglass', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('51', '正在进行的考试', '50', 'Exam/listunderway', 'fa fa-hourglass', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('52', '已经完成的考试', '50', 'Exam/listfinished', 'fa fa-hourglass-3', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('53', '知识体系', '0', 'Book/lists', 'fa fa-archive', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('54', '全部课程', '53', 'Book/lists', 'fa fa-archive', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('55', '个人统计中心', '0', 'Personal/statistic', '', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('56', '个人统计', '55', 'Personal/statistic', 'fa-line-chart', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('57', '学习日志', '55', 'Personal/log', 'fa-newspaper-o', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('58', '个人信息', '55', 'Personal/information', 'fa-user', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('59', '修改密码', '55', 'Personal/changepassword', 'fa-key', '3', '1', '1');
INSERT INTO `p_menu` VALUES ('60', '人员管理', '0', 'User/teacher', '', '1', '4', '1');
INSERT INTO `p_menu` VALUES ('61', '教员管理', '60', 'javascript:;', 'fa-user', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('62', '所有教员', '61', 'User/teacher', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('63', '新建教员', '61', 'User/addteacher', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('64', '班级管理', '60', 'javascript:;', 'fa-sitemap', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('65', '所有班级', '64', 'User/classes', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('66', '新建班级', '64', 'User/addclass', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('67', '学员管理', '60', 'javascript:;', 'fa-user', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('68', '所有学员', '67', 'User/student', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('69', '新建学员', '67', 'User/addstudent', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('70', '个人中心', '0', 'Profile/info', '', '1', '5', '1');
INSERT INTO `p_menu` VALUES ('71', '个人信息', '70', 'Profile/info', 'fa-user', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('72', '修改密码', '70', 'Profile/modifypassword', 'fa-key', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('73', '系统日志', '70', 'Profile/systemlog', 'fa-newspaper-o', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('74', '知识体系管理', '0', 'Adminsubject/mysystem', '', '1', '2', '0');
INSERT INTO `p_menu` VALUES ('75', '体系管理', '74', 'javascript:;', 'fa-archive', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('76', '全部体系', '75', 'Adminsubject/mysystem', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('77', '课程管理', '74', 'javascript:;', 'fa-book', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('78', '全部课程', '77', 'Adminsubject/mybook', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('79', '试卷管理', '74', 'javascript:;', 'fa-copy', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('80', '全部试卷', '79', 'Adminsubject/myexam', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('81', '题目管理', '74', 'javascript:;', 'fa-question-circle', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('82', '所有题目', '81', 'Adminsubject/questionlist', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('83', '工具库管理', '74', 'javascript:;', 'fa-wrench', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('84', '所有工具', '83', 'Adminsubject/toollist', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('85', '分类管理', '83', 'Adminsubject/toolcate', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('86', '添加工具', '83', 'Adminsubject/addtool', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('87', '实训内容管理', '0', 'Admintrain/ctflist', '', '1', '3', '1');
INSERT INTO `p_menu` VALUES ('88', 'CTF实训管理', '87', 'Admintrain/ctflist', 'fa-newspaper-o', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('89', '场景管理', '87', 'javascript:;', 'fa-object-ungroup', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('90', '场景模板管理', '89', 'Admintrain/scenelist', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('91', '场景模板制作', '89', 'Admintrain/scenecreate', '', '1', '1', '1');
INSERT INTO `p_menu` VALUES ('92', '虚拟机模板管理', '89', 'Admintrain/vmlist', '', '1', '1', '1');";
                if(!empty($sql)){
                    $sql=explode("\r\n",$sql);
                    foreach($sql as $k=>$v){
                         $res= $this->db->query($v);
                        //echo $v;
                    }
                }
    }

}