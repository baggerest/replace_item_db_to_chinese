<?php
header("Content-Type:text/html;charset=big5");
$time1 = time();
$tag_itemdb = get_itemdb_list("D:\\GitHub\\rathena\\db\\re\\item_db.txt");
$language_itemdb = get_itemdb_list("D:\\downloaded\\rAthenaCN_cht\\db\\re\\item_db.txt");
foreach (from_itemdb($tag_itemdb,$language_itemdb) as $i => $item){
    foreach ($item as $j => $value){
        echo ($j<19)?"{$value}":"{{$value}}";
        echo ($j<21)?",":"";
    }
    echo "<br>";
}

function get_itemdb_list($input_itemdb_path){
    // 22 counts:1 array
    // file add everyone account
    $itemdb = file($input_itemdb_path,FILE_IGNORE_NEW_LINES);
    $itemdb_list = null;
    foreach ($itemdb as $line){
        if(strpos($line,'//')===0){
            continue;
        }else{
            $p_pre = substr($line,0,strpos($line,',{'));
            $list_id = explode(',',$p_pre)[0];
            $itemdb_list[$list_id] = explode(',',$p_pre);

            $pd = substr($line,strpos($line,',{')+2,-1);
            $i = 19;
            foreach (explode('},{',$pd) as $value){
                $itemdb_list[$list_id][$i++] = $value;
            }
        }
    }
    return $itemdb_list;
}

function from_itemdb($input_itemdb_list,$input_itemdb_language){
    error_reporting(0);
    $itemdb_cht_list_output = null;
    foreach($input_itemdb_list as $id => $value){
        if($input_itemdb_language[$id]===null){
            $itemdb_cht_list_output[$id] = $input_itemdb_list[$id];
        }else {
            foreach ($input_itemdb_list[$id] as $key => $item) {
                switch ($key) {
                    case 2:
                        $itemdb_cht_list_output[$id][$key] = $input_itemdb_language[$id][$key];
                        break;
                    default:
                        $itemdb_cht_list_output[$id][$key] = $input_itemdb_list[$id][$key];
                        break;
                }
            }
        }
    }
    return $itemdb_cht_list_output;
}

function from_idnum2itemdisplaynametable(){

}