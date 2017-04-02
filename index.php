<?php
header("Content-Type:text/html; charset=utf-8");

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

function get_itemdb_list($input_itemdb_file_path){
    // 22 counts:1 array
    // file add everyone account
    $itemdb = file($input_itemdb_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    $itemdb_list = null;
    foreach ($itemdb as $line)
    {
        if(strpos($line,'//')===0)
        {
            continue;
        }else{
            $p_pre = substr($line,0,strpos($line,',{'));
            $list_id = explode(',',$p_pre)[0];
            $itemdb_list[$list_id] = explode(',',$p_pre);

            $pd = substr($line,strpos($line,',{')+2,-1);
            $i = 19;
            foreach (explode('},{',$pd) as $value)
            {
                $itemdb_list[$list_id][$i++] = $value;
            }
        }
    }
    // return array[][21]
    return $itemdb_list;
}

//to big5 frist
function get_idnum2itemdisplaynametable_list($input_idnum2itemdisplaynametable_file_path){
    $idnum2itemdisplaynametable_changed_list_output = null;
    $idnum2itemdisplaynametable = file($input_idnum2itemdisplaynametable_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($idnum2itemdisplaynametable as $value)
    {
        if(strpos($value,'//')===0)
        {
            continue;
        }else{
            $value = mb_convert_encoding($value,"big5","ascii,utf-8,gb2312");
            $substr_list = substr($value,0,-1);
            $idnum2itemdisplaynametable_changed_list_output[explode('#',$substr_list)[0]] = explode('#',$substr_list);
        }
    }
    // return array[][1]
    return $idnum2itemdisplaynametable_changed_list_output;
}

function from_itemdb($input_itemdb_list = array(),$input_itemdb_language_list = array()){
    error_reporting(0);
    $itemdb_changed_list_output = null;
    foreach($input_itemdb_list as $id => $value)
    {
        if($input_itemdb_language_list[$id]===null)
        {
            $itemdb_changed_list_output[$id] = $input_itemdb_list[$id];
        }else{
            foreach ($input_itemdb_list[$id] as $key => $item)
            {
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

function from_idnum2itemdisplaynametable($input_itemdb_list = array(),$input_idnum2itemdisplaynametable_language_list = array()){
    error_reporting(0);
    $itemdb_changed_list_output = null;
    foreach ($input_itemdb_list as $key => $value)
    {
        if($input_idnum2itemdisplaynametable_language_list[$key]===null)
        {
            $itemdb_changed_list_output[$key] = $input_itemdb_list[$key];
        }else{
            foreach ($input_itemdb_list[$key] as $id => $item)
            {
                switch ($id) {
                    case 2:
                        $itemdb_changed_list_output[$key][$id] = str_replace(",","。",$input_idnum2itemdisplaynametable_language_list[$key][1]);
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

function output_itemdb_file($input_item_db = array(),$output_path = ""){
    $save_file = fopen($output_path,'w');
    $title = "// ID,AegisName,Name,Type,Buy,Sell,Weight,ATK[:MATK],DEF,Range,Slots,Job,Class,Gender,Loc,wLV,eLV[:maxLevel],Refineable,View,{ Script },{ OnEquip_Script },{ OnUnequip_Script }\r\n";
    fwrite($save_file,$title);
    foreach ($input_item_db as $id => $item)
    {
        $line_words = null;
        foreach ($input_item_db[$id] as $key => $value)
        {
            $line_words .= ($key<19)?"{$value}":"{{$value}}";
            $line_words .= ($key<21)?",":"\r\n";
        }
        fwrite($save_file,$line_words);
    }
    fclose($save_file);
    return true;
}
//output_itemdb_file(from_idnum2itemdisplaynametable(get_itemdb_list("C:\\my\\rathena\\db\\re\\item_db.txt"),get_idnum2itemdisplaynametable_list("D:\\OpenKoreTW20170328\\tables\\twRO\\items.txt")),"D:\\item_db.txt");

function get_mobdb_list($input_mobdb_file_path){
    //56 counts:1 array
    $mobdb = file($input_mobdb_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    $mobdb_list = array();
    foreach ($mobdb as $line)
    {
        if(strpos($line,'//')===0)
        {
            continue;
        }else{
            $mobdb_list[explode(',',$line)[0]] = explode(',',$line);
        }
    }
    //return array[57]
    return $mobdb_list;
}

//to big5 frist
function get_monsters_list($input_monsters_file_path){
    $monsters_list = array();
    $monsters = file($input_monsters_file_path,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($monsters as $line)
    {
        if(strpos($line,'//')===0)
        {
            continue;
        }else{
            $line = mb_convert_encoding($line,"big5","ascii,utf-8,gb2312");
            $monsters_list[explode(' ',$line)[0]] = explode(' ',$line);
        }
    }
    //return array[2]
    return $monsters_list;
}

function from_mobdb($input_mobdb_list = array(),$input_mobdb_language_list = array()){
    error_reporting(0);
    $mobdb_changed_list_output = null;
    foreach($input_mobdb_list as $id => $value){
        if($input_mobdb_language_list[$id]===null){
            $mobdb_changed_list_output[$id] = $input_mobdb_list[$id];
        }else {
            foreach ($input_mobdb_list[$id] as $key => $item) {
                switch ($key) {
                    case 2:
                        $mobdb_changed_list_output[$id][$key] = $input_mobdb_language_list[$id][$key];
                        break;
                    default:
                        $mobdb_changed_list_output[$id][$key] = $input_mobdb_list[$id][$key];
                        break;
                }
            }
        }
    }
    // return array[][57]
    return $mobdb_changed_list_output;
}

function from_monsters($input_mobdb_list = array(),$input_monsters_language_list = array()){
    error_reporting(0);
    $monsters_changed_list_output = null;
    foreach ($input_mobdb_list as $key => $value){
        if($input_monsters_language_list[$key]===null){
            $monsters_changed_list_output[$key] = $input_mobdb_list[$key];
        }else{
            foreach ($input_mobdb_list[$key] as $id => $item){
                switch ($id) {
                    case 2:
                        $monsters_changed_list_output[$key][$id] = str_replace(",","。",$input_monsters_language_list[$key][1]);
                        break;
                    default:
                        $monsters_changed_list_output[$key][$id] = $input_mobdb_list[$key][$id];
                        break;
                }
            }
        }
    }
    // return array[][21]
    return $monsters_changed_list_output;
}

function output_mobdb_file($input_mob_db = array(),$output_path = ""){
    $save_file = fopen($output_path,'w');
    $title = "// ID,Sprite_Name,kROName,iROName,LV,HP,SP,EXP,JEXP,Range1,ATK1,ATK2,DEF,MDEF,STR,AGI,VIT,INT,DEX,LUK,Range2,Range3,Scale,Race,Element,Mode,Speed,aDelay,aMotion,dMotion,MEXP,MVP1id,MVP1per,MVP2id,MVP2per,MVP3id,MVP3per,Drop1id,Drop1per,Drop2id,Drop2per,Drop3id,Drop3per,Drop4id,Drop4per,Drop5id,Drop5per,Drop6id,Drop6per,Drop7id,Drop7per,Drop8id,Drop8per,Drop9id,Drop9per,DropCardid,DropCardper\r\n";
    fwrite($save_file,$title);
    foreach ($input_mob_db as $id => $item){
        $line_words = null;
        foreach ($input_mob_db[$id] as $key => $value){
            $line_words .= $value;
            $line_words .= ($key<56)?",":"\r\n";
        }
        fwrite($save_file,$line_words);
    }
    fclose($save_file);
    return true;
}
//output_mobdb_file(from_monsters(get_mobdb_list("C:\\my\\rathena\\db\\re\\mob_db.txt"),get_monsters_list("D:\\OpenKoreTW20170328\\tables\\twRO\\monsters.txt")),"D:\\mob_db.txt");

function get_mobs_file_list($dir_path = ""){
    if(is_dir($dir_path))
    {
        $opendir = opendir($dir_path);
        chdir($dir_path);
        while(($file = readdir($opendir)) !== false)
        {
            if(is_dir($file) && basename($file)!=="." && basename($file)!=="..")
            {
                foreach (get_mobs_file_list($file) as $value)
                {
                    $output_file_list[] = $value;
                }
            }else{
                if(basename($file)!=="." && basename($file)!=="..")
                {
                    $output_file_list[] = getcwd() . "\\" . $file;
                }
            }
        }
        chdir("../");
        closedir($opendir);
    }
    return $output_file_list;
}
//get_mobs_file_list("C:\\my\\rathena\\npc\\re\\mobs\\");

function change_mobs_name_from_monster_list($input_mobs_file_list = array(),$input_monsters_language_list = array()){
    error_reporting(0);
    $change_mobs_list = array();
    foreach ($input_mobs_file_list as $file)
    {
        $linecount = 0;
        $mobs_file = file($file,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        foreach ($mobs_file as $line)
        {
            if(strpos($line,'//')===0)
            {
                $change_mobs_list[] = $line;
            }else{
                $temp = explode("\t",$line);
                $s = $input_monsters_language_list[explode(",",$temp[3])[0]][1];
                if($s!==null)
                {
                    $temp[2] = $s;
                }
                $marge = "";
                foreach ($temp as $item)
                {
                    $marge .= $item."\t";
                }
                $change_mobs_list[] = substr($marge,0,-1);
            }
            $linecount++;
        }
        $fw = fopen($file,"w");
        foreach ($change_mobs_list as $w)
        {
            fwrite($fw,$w."\r\n");
        }
        fclose($fw);
        echo $file." aleady changed!(".$linecount.",".count($change_mobs_list).")<br>";
        $change_mobs_list = null;
    }
}
//change_mobs_name_from_monster_list(get_mobs_file_list("C:\\my\\rathena\\npc\\re\\mobs\\"),get_monsters_list("D:\\OpenKoreTW20170328\\tables\\twRO\\monsters.txt"));