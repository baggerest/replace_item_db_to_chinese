<?php
header("Content-Type:text/html; charset=utf-8");

$tag_itemdb = get_itemdb_list("E:\\rathena\\db\\re\\item_db.txt");
$language_itemdb = get_itemdb_list("E:\\rAthenaCN1.5.9_cht\\db\\re\\item_db.txt");
$itemdb_out_list = from_itemdb($tag_itemdb,$language_itemdb);
if(output_itemdb_file($itemdb_out_list,"D:\\item_db.txt")){
    echo "完成";
    foreach ($itemdb_out_list as $id => $value){
        $w = $itemdb_out_list[$id][2];
        echo "[".$w.":".isCh($w)."]";
    }
}

function unicode_to_ch($data = '["\u****"]'){
    //return chinese
    return json_decode($data)[0];
}

function ch_to_unicode($data = 'array | string'){
    //return '["\u****"]'
    return '['.json_encode($data).']';
}

function isCh($input_string){
    // return 1:0
    return preg_match("/[\x{4e00}-\x{9fa5}]/u", $input_string);
}

// to utf8 frist
function get_itemdb_list($input_itemdb_file_path){
    // 22 counts:1 array
    // file add everyone account
    $itemdb = file($input_itemdb_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    $itemdb_list = null;
    foreach ($itemdb as $line){
        if(strpos($line,'//')===0){
            continue;
        }else{
            $line = mb_convert_encoding($line,'utf-8','big5,gb2312');
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
    // return array[][21]
    return $itemdb_list;
}

// to utf8 frist
function get_idnum2itemdisplaynametable_list($input_idnum2itemdisplaynametable_file_path){
    $idnum2itemdisplaynametable_changed_list_output = null;
    $idnum2itemdisplaynametable = file($input_idnum2itemdisplaynametable_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($idnum2itemdisplaynametable as $value){
        if(strpos($value,'//')===0){
            continue;
        }else{
            $value = mb_convert_encoding($value,'utf-8','big5,gb2312');
            $substr_list = substr($value,0,-1);
            $idnum2itemdisplaynametable_changed_list_output[] = explode('#',$substr_list);
        }
    }
    // return array[][1]
    return $idnum2itemdisplaynametable_changed_list_output;
}


//$tag_itemdb = get_itemdb_list("D:\\GitHub\\rathena\\db\\re\\item_db.txt");
//$language_itemdb = get_itemdb_list("D:\\downloaded\\rAthenaCN_cht\\db\\re\\item_db.txt");
//foreach (from_itemdb($tag_itemdb,$language_itemdb) as $i => $item){
//    foreach ($item as $j => $value){
//        if($j===0)echo $a++.")";
//        echo ($j<19)?"{$value}":"{{$value}}";
//        echo ($j<21)?",":"";
//    }
//    echo "<br>";
//}
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
    // return array[][21]
    return $itemdb_changed_list_output;
}

//$tag_itemdb = get_itemdb_list("D:\\GitHub\\rathena\\db\\re\\item_db.txt");
//$language_idnum2itemdisplaynametable = get_idnum2itemdisplaynametable_list("D:\\downloaded\\data\\idnum2itemdisplaynametable.txt");
//foreach (from_idnum2itemdisplaynametable($tag_itemdb,$language_idnum2itemdisplaynametable) as $i => $item){
//    foreach ($item as $j => $value){
//        if($j===0)echo $a++.")";
//        echo ($j<19)?"{$value}":"{{$value}}";
//        echo ($j<21)?",":"";
//    }
//    echo "<br>";
//}
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
    // return array[][21]
    return $itemdb_changed_list_output;
}

// output utf8 frist
function output_itemdb_file($input_item_db = array(),$output_path = ""){
    $save_file = fopen($output_path,'w');
    $title = "// ID,AegisName,Name,Type,Buy,Sell,Weight,ATK[:MATK],DEF,Range,Slots,Job,Class,Gender,Loc,wLV,eLV[:maxLevel],Refineable,View,{ Script },{ OnEquip_Script },{ OnUnequip_Script }\r\n";
    fwrite($save_file,$title);
    foreach ($input_item_db as $id => $item){
        $line_words = null;
        foreach ($input_item_db[$id] as $key => $value){
            $line_words .= ($key<19)?"{$value}":"{{$value}}";
            $line_words .= ($key<21)?",":"\r\n";
        }
        fwrite($save_file,$line_words);
    }
    fclose($save_file);
    return true;
}