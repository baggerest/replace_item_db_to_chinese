<?php
header("Content-Type:text/html;charset=big5");
//$tag_itemdb = get_itemdb_list("D:\\GitHub\\rathena\\db\\re\\item_db.txt");
//$language_itemdb = get_itemdb_list("D:\\downloaded\\rAthenaCN_cht\\db\\re\\item_db.txt");

//$tag_itemdb = get_itemdb_list("D:\\GitHub\\rathena\\db\\re\\item_db.txt");
//$language_idnum2itemdisplaynametable = get_idnum2itemdisplaynametable_list("D:\\downloaded\\data\\idnum2itemdisplaynametable.txt");

/*
foreach (from_idnum2itemdisplaynametable($tag_itemdb,$language_idnum2itemdisplaynametable) as $i => $item){
    foreach ($item as $j => $value){
        if($j===0)echo $a++.")";
        echo ($j<19)?"{$value}":"{{$value}}";
        echo ($j<21)?",":"";
    }
    echo "<br>";
}
*/

function get_itemdb_list($input_itemdb_file_path){
    // 22 counts:1 array
    // file add everyone account
    $itemdb = file($input_itemdb_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
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
    // return array
    return $itemdb_list;
}

function get_idnum2itemdisplaynametable_list($input_idnum2itemdisplaynametable_file_path){
    $idnum2itemdisplaynametable_changed_list_output = null;
    $idnum2itemdisplaynametable = file($input_idnum2itemdisplaynametable_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($idnum2itemdisplaynametable as $value){
        if(strpos($value,'//')===0){
            continue;
        }else{
            $substr_list = substr($value,0,-1);
            $idnum2itemdisplaynametable_changed_list_output[] = explode('#',$substr_list);
        }
    }
    // return array
    return $idnum2itemdisplaynametable_changed_list_output;
}

function from_itemdb($input_itemdb_list = array(),$input_itemdb_language_list = array()){
    error_reporting(0);
    $itemdb_changed_list_output = null;
    foreach($input_itemdb_list as $id => $value){
        if($input_itemdb_language_list[$id]===null){
            $itemdb_changed_list_output[$id] = $input_itemdb_list[$id];
        }else {
            foreach ($input_itemdb_list[$id] as $key => $item) {
                switch ($key) {
                    case 2:
                        $itemdb_changed_list_output[$id][$key] = $input_itemdb_language_list[$id][$key];
                        break;
                    default:
                        $itemdb_changed_list_output[$id][$key] = $input_itemdb_list[$id][$key];
                        break;
                }
            }
        }
    }
    // return array
    return $itemdb_changed_list_output;
}

function from_idnum2itemdisplaynametable($input_itemdb_list = array(),$input_idnum2itemdisplaynametable_language_list = array()){
    error_reporting(0);
    $itemdb_changed_list_output = null;
    foreach ($input_itemdb_list as $key => $value){
        if($input_idnum2itemdisplaynametable_language_list[$key]===null){
            $itemdb_changed_list_output[$key] = $input_itemdb_list[$key];
        }else{
            foreach ($input_itemdb_list[$key] as $id => $item){
                switch ($id) {
                    case 2:
                        $itemdb_changed_list_output[$key][$id] = $input_idnum2itemdisplaynametable_language_list[$key][1];
                        break;
                    default:
                        $itemdb_changed_list_output[$key][$id] = $input_itemdb_list[$key][$id];
                        break;
                }
            }
        }
    }
    // return array
    return $itemdb_changed_list_output;
}