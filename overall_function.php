<?php
class over {
    
    function over($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        $this->yearID = $_SESSION[$guid]['gibbonSchoolYearID'];
        $this->teacherID = $_SESSION[$guid]['gibbonPersonID'];
        
        if (isset($_POST['submit'])) {
            $this->awardSave();
        }
        $this->pointsList = $this->readPointsList($this->dbh, $this->yearID);
    }

    function mainForm() {

        echo "<p>&nbsp;</p>";
        echo "<h3>Overall House Points</h3>";
        
        echo "<table style='width:100%;font-size:14pt'>";
            echo "<tr>";
                echo "<th style='width:40%'>House</th>";
                echo "<th style='width:40%'>Points</th>";
            echo "</tr>";
                
            while ($row = $this->pointsList->fetch()) {
                echo "<tr>";
                    echo "<td>".$row['houseName']."</td>";
                    echo "<td>".$row['total']."</td>";
                echo "</tr>";
            }
        echo "</table>";
    }
    
    function readPointsList() {
        $data = array(
            'yearID' => $this->yearID
        );
        $sql = "SELECT gibbonHouse.gibbonHouseID AS houseID,
            gibbonHouse.name AS houseName,
            COALESCE(pointStudent.total + pointHouse.total, pointStudent.total, pointHouse.total, 0) AS total
            FROM gibbonHouse
            LEFT JOIN 
            (
                SELECT gibbonPerson.gibbonHouseID AS houseID,
                SUM(hpPointStudent.points) AS total
                FROM hpPointStudent
                INNER JOIN gibbonPerson
                ON hpPointStudent.studentID = gibbonPerson.gibbonPersonID
                GROUP BY gibbonPerson.gibbonHouseID

            ) AS pointStudent
            ON pointStudent.houseID = gibbonHouse.gibbonHouseID
            LEFT JOIN 
            (
                SELECT hpPointHouse.houseID,
                SUM(hpPointHouse.points) AS total
                FROM hpPointHouse
                GROUP BY hpPointHouse.houseID
            ) AS pointHouse
            ON pointHouse.houseID = gibbonHouse.gibbonHouseID

            ORDER BY total DESC";
        $rs = $this->dbh->prepare($sql);
        $rs->execute($data);
        return $rs;
    }
}