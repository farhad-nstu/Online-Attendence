<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/Database.php');
?>


<?php

    /**
     * 
     */
    class Student {
    	private $db;
    	
    	public function __construct(){
    		$this->db = new Database();
    	}

    	public function getStudents(){
    		$query = "SELECT * FROM tbl_student";
    		$result = $this->db->select($query);
    		return $result;

    	}

        

        public function insertStudent($name, $roll){
        	$name = mysqli_real_escape_string($this->db->link, $name);
        	$roll = mysqli_real_escape_string($this->db->link, $roll);
        	if (empty($name) || empty($roll)) {
        		$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Field must not be empty ! </div>";
        		return $msg;
        	} else{
        		$att_query = "INSERT INTO tbl_student(name, roll) VALUES('$name', '$roll')";
        		$stu_insert = $this->db->insert($att_query);

        		$att_query = "INSERT INTO tbl_attendence(roll) VALUES('$roll')";
        		$stu_insert = $this->db->insert($att_query);

        		if ($stu_insert) {
        			$msg = "<div class='alert alert-success'><strong>Success ! </strong>Student Data Inserted Successfully ! </div>";
        		    return $msg;
        		} else{
        			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Student Data Not Inserted ! </div>";
        		    return $msg;
        		}

        	}
        }



        



        public function insertAttendance($cur_date, $attend = array()) {
        	$query = "SELECT DISTINCT attend_time FROM tbl_attendence";
        	$getdata = $this->db->select($query);

        	while ($result = $getdata->fetch_assoc()) {
        		$db_date = $result['attend_time'];
        		if ($cur_date == $db_date) {
        			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Attendance already taken ! </div>";
        		    return $msg;
        		}
        	}


        	foreach ($attend as $atn_key => $atn_value) {
        		if ($atn_value == "present" ) {
        			$stu_query = "INSERT INTO tbl_attendence(roll, attend, attend_time) VALUES('$atn_key', 'present', now())";
        			$data_insert = $this->db->insert($stu_query);
        		} elseif ($atn_value == "absent") {
        			$stu_query = "INSERT INTO tbl_attendence(roll, attend, attend_time) VALUES('$atn_key', 'absent', now())";
        			$data_insert = $this->db->insert($stu_query);
        		}
        	}

        	if ($data_insert) {
        			$msg = "<div class='alert alert-success'><strong>Success ! </strong>Attendance Data Inserted Successfully ! </div>";
        		    return $msg;
        		} else{
        			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Attendance already taken today ! </div>";
        		    return $msg;
        		}
        }



        public function getDateList(){
        	$query = "SELECT DISTINCT attend_time FROM tbl_attendence";
        	$result = $this->db->select($query);
        	return $result;
        }


        public function getAllData($dt){
        	$query = "SELECT tbl_student.name, tbl_attendence.* 
        	FROM tbl_student
        	INNER JOIN tbl_attendence
        	ON tbl_student.roll = tbl_attendence.roll
        	WHERE attend_time = '$dt'";
        	$result = $this->db->select($query);
        	return $result;
        }




        public function updateAttendance($dt, $attend){
        	foreach ($attend as $atn_key => $atn_value) {
        		if ($atn_value == "present" ) {

        			$query = "UPDATE tbl_attendence SET attend = 'present'

                     WHERE roll = '".$atn_key."' AND attend_time = '".$dt."'

        			";
        			$data_update = $this->db->update($query);
        			
        		} elseif ($atn_value == "absent") {

        			$query = "UPDATE tbl_attendence SET attend = 'absent'

                     WHERE roll = '".$atn_key."' AND attend_time = '".$dt."'

        			";
        			$data_update = $this->db->update($query);
        			
        		}
        	}

        	if ($data_update) {
        			$msg = "<div class='alert alert-success'><strong>Success ! </strong>Attendance Data Updated Successfully ! </div>";
        		    return $msg;
        		} else{
        			$msg = "<div class='alert alert-danger'><strong>Error ! </strong>Attendance Data Not Updated ! </div>";
        		    return $msg;
        		}
        }


    }

?>