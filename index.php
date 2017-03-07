<?php
/**
 * Created by PhpStorm.
 * User: Zachary
 * Date: 2017/3/6
 * Time: 上午 10:19
 */
//22個
header("Content-Type:text/html;charset=big5");

function item_db_read(){
    $itemdb_en = file("D:/GitHub/rathena/db/re/item_db.txt");
    $itemdb_en_list = null;
    foreach ($itemdb_en as $line){
        if(strpos($line,'//')===0){
            continue;
        }else{
            $p_pre = substr($line,0,strpos($line,',{'));
            $list_id = explode(',',$p_pre)[0];
            $itemdb_en_list[$list_id] = explode(',',$p_pre);

            $p_end = substr($line,strpos($line,',{')+2,-3);
            $i = 19;
            foreach (explode('},{',$p_end) as $value){
                $itemdb_en_list[$list_id][$i++] = $value;
            }
        }
    }

    $itemdb_cht = file("D:/item_db.txt");
    $itemdb_cht_list = null;
    foreach ($itemdb_cht as $line){
        if(strpos($line,'//')===0){
            continue;
        }else{
            $p_pre = substr($line,0,strpos($line,',{'));
            $list_id = explode(',',$p_pre)[0];
            $itemdb_cht_list[$list_id] = explode(',',$p_pre);

            $p_end = substr($line,strpos($line,',{')+2,-3);
            $i = 19;
            foreach (explode('},{',$p_end) as $value){
                $itemdb_cht_list[$list_id][$i++] = $value;
            }
        }
    }

    error_reporting(0);
    for($id=0;$id<40000;$id++){
        if($itemdb_en_list[$id]===null)continue;
        foreach ($itemdb_en_list[$id] as $item){
            echo "<font style=\"background-color:#00ffff\">{{$item}}</font>";
        }
        echo "<br>";
        foreach ($itemdb_cht_list[$id] as $item){
            echo "{{$item}}";
        }
        echo "<br>";
    }
}