<?php
//header('Content-Type: text/html; charset=utf-8');

require_once('./config.php'); 

/**  Switch Case to Get Action from controller  **/
// CRUD
switch($_GET['action']){
    case 'add_row' :
            add_row();
            break;

    case 'get_data' :
            get_data();
            break;

    case 'edit_row' :
            edit_row();
            break;

    case 'delete_row' :              
            delete_row();
            break;

    case 'update_row' :
            update_row();
            break;

    case 'importCsv' :
            importCsv();
            break;
}

/**  Function to Add row  **/

function add_row() {
	// _GET_POST
    $data = json_decode(file_get_contents("php://input")); 
    //$id = $data->id;    // id + city form a primary key
    $inn_name = $data->inn_name;    
    $tel = $data->tel;
    $fax = $data->fax;
    $mobile=$data->mobile;
    $addr = $data->addr;
    $website=$data->website;
    $email=$data->email;
    $landload=$data->lanlord;
    $no_of_room=$data->no_of_room;
    $prices=$data->prices;
 
    // print_r($data);
    $sql = 'INSERT INTO crm (inn_name, tel, fax, addr) values ("' . $inn_name . '","' . $tel . '",' .$fax . ','.$addr.')';
   
    mysql_query($sql) or die('error adding row');    
};

/**  Function to Get row  **/
function get_data() {  
    //mysql_query('SET CHARACTER SET utf-8');  
    $result = mysql_query('SELECT * FROM crm WHERE 1') or die('error getting data:' .mysql_error());
    //$colNum = mysql_num_fileds($result);
    $data = array();
    $row=[];
    while($row = mysql_fetch_assoc($result)){
        //$data[]=array_map('utf8_encode', $row);   //json_encode only accept utf8 format    
        $data[]=$row;
    }
    if (mysql_num_rows($result) === 0)
        return false;
/*   
    while($rows = mysql_fetch_array($result)){
        $data[] = array(
                    "id"       => $rows['id'], // db table's col name
                    "inn_name"  => $rows['inn_name'],
                    "tel"     => $rows['tel'],
                    "fax"    => $rows['fax'],
                    "addr" => $rows['addr'],
                    'eng_addr' =>$rows['eng_addr'],  // col name
                    'jpn_addr' =>$rows['jpn_addr'],
                    'status' =>$rows['status'],
                    'website'=>$rows['email'],
                    'landlord'=>$rows['landlord'],
                    'type'=>$rows['type'],
                    'no_of_room'=>$rows['no_of_room'],
                    'prices'=>$rows['prices'],
                    'certificated_date'=>$rows['certificated_date'],
                    'remodel_date'=>$rows['remodel_date']
                    );
    }
*/     
    // echo json_encode( $data, JSON_UNESCAPED_UNICODE);
    echo json_encode($data);  
}


/**  Function to Delete row  **/

function delete_row() {
    $data = json_decode(file_get_contents("php://input"));     
    $index = $data->index;     
    //print_r($data)   ;
    $result = mysql_query("DELETE FROM crm WHERE id = ".$index);
    if($result)
        return true;
    return false;     
}

/**  Function to Edit row  **/

function edit_row() {
    $data = json_decode(file_get_contents("php://input"));     
    $index = $data->index; 
    $result = mysql_query('SELECT * from crm WHERE id='.$index) or die('error editing');
    $row = array();
    $row = mysql_fetch_assoc($result);  // thus we can get field names from controller
    // don't know why print_r is a must. otherwise won't work
    print(json_encode($row));
    return json_encode($row);  
}

/** Function to Update row **/

function update_row(){
    $data = json_decode(file_get_contents("php://input"));     
    
    $id = $data->id;
    $inn_name = $data->inn_name;    
    $tel = $data->tel;
    $fax = $data->fax;
    $mobile=$data->mobile;
    $addr = $data->addr;
    $website=$data->website;
    $email=$data->email;
    $landload=$data->lanlord;
    $no_of_room=$data->no_of_room;
    $prices=$data->prices;

    //print_r($data);
 
    $sql = "UPDATE `crm` set `inn_name`='".$inn_name."', `tel`='".$tel."',
            `fax`='".$fax."', `addr`='".$addr."', `mobile`='".$mobile."', `no_of_room`='".$no_of_room."',
            `website`='".$website."', `email`='".$email."', `landlord`='".$landlord."', `prices`='".$prices."'
            WHERE `id`=".$id;

    //$sql = sprintf("UPDATE crm set prod_name=%s, $prod_name=%s, prod_desc=%s, prod_price=%d, prod_quantity=%d WHERE id=%d")  
    //$sql = "UPDATE crm set prod_name='mark' where id=1";
    mysql_query($sql) or die('error updating:' . mysql_error());     
}

function importCsv(){
    $data = json_decode(file_get_contents("php://input")); 
    $filename = $data->filename;
    $city = $data->city;

    $sql = "CREATE TEMPORARY TABLE tmpCrm LIKE crm ";
    mysql_query($sql) or die('error cearting tmp table:' . mysql_error());  

    //$fd = fopen($filename, 'r') or die ('error opening csv file');
   
    //while(!feof($fd){ 
    //$csv=[];
    // import csv into table tmpCrm 
    $file = '/var/www/html/crm/test.tsv';
    $sql = "LOAD DATA LOCAL INFILE '{$file}' INTO TABLE tmpCrm FIELDS TERMINATED BY '\t' IGNORE 1 LINES";
        
    mysql_query($sql) or die('error infile');    
    /*  
    while($csv = fgetcsv($fd, 1000, ","))
    {              
        $sql = "INSERT INTO `tmpCrm` (`id`, `city`, `inn_name`, `eng_name`, `jpn_name`, `tel`, `fax`,\n"
            ."`mobile`, `addr`, `eng_addr`, `jpn_addr`, `status`, `website`, `email`, `landlord`, \n"
            ."`type`, `no_of_room`, `prices`, `traffic`, `certificated_date`, `remodel_date`, \n"
            ."`pix_upload_date`, `source`, `in_charge`, `approach`)\n"
            ."VALUES ($csv[0], $city, $csv[1], $csv[2],$csv[3],$csv[4],$csv[5],\n"
            ."$csv[6],$csv[7],$csv[8],$csv[9], $csv[10],$csv[11],$csv[12],$csv[13],\n"
            ."$csv[14],$csv[15],$csv[16],$csv[17],$csv[18],STR_TO_DATE($csv[19],'%m,%d,%Y'),\n"
            ."STR_TO_DATE($csv[20],'%m,%d,%Y'),STR_TO_DATE($csv[21],'%m,%d,%Y'),$csv[22], $csv[23])";
        mysql_query($sql) or die('error inserting tmpCrm table:' . mysql_error()); 
    }   
    fclose($fd);
    */
    // so complex! if conflict, crm always wins
$sql = <<<EOD
    CREATE TEMPORARY TABLE t1
    SELECT tmpCrm.id, tmpCrm.city,
    COALESCE(crm.inn_name, tmpCrm.inn_name) as inn_name,
    COALESCE(crm.eng_name, tmpCrm.eng_name) as eng_name,
    tmpCrm.jpn_name,
    COALESCE(crm.tel, tmpCrm.tel) as tel,
    COALESCE(crm.fax, tmpCrm.fax) as fax,
    COALESCE(crm.mobile, tmpCrm.mobile) as mobile,
    COALESCE(crm.addr, tmpCrm.addr) as addr,
    tmpCrm.eng_addr, tmpCrm.jpn_addr, tmpCrm.status,
    COALESCE(crm.website, tmpCrm.website) as website,
    COALESCE(crm.email, tmpCrm.email) as email,
    COALESCE(crm.landlord, tmpCrm.landlord) as landlord,
    tmpCrm.type,
    COALESCE(crm.no_of_room, tmpCrm.no_of_room) as no_of_room,
    tmpCrm.prices,
    COALESCE(crm.traffic, tmpCrm.traffic) as traffic,
    tmpCrm.certificated_date, tmpCrm.remodel_date, tmpCrm.pix_upload_date,
    tmpCrm.source, tmpCrm.in_charge, tmpCrm.approach
    FROM tmpCrm
    LEFT JOIN crm ON tmpCrm.id = crm.id and tmpCrm.city = crm.city
EOD;
    mysql_query($sql) or die('error resolving conflict:' . mysql_error());  

    // almost drive me crazy.
    $sql="CREATE TEMPORARY TABLE t2 SELECT * FROM t1 UNION SELECT * FROM crm";
    mysql_query($sql) or die('error union:' . mysql_error());   
    mysql_query("DELETE FROM `crm` WHERE 1") or die ('error del:' . mysql_error());
    $sql="INSERT INTO crm SELECT * FROM t2";
    mysql_query($sql) or die ('error MERGE:' . mysql_error());
}
  
function __destruct(){
    mysql_close($con);
    return true;
}

?>