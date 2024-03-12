<?php

	class Epms{
		private $dbHandler;
		private $employee;
		
		/**
		*	Constructor. Class dependant on DBHandler.
		**/
		public function __construct(DBHandler $dbHandler){
			$this->dbHandler = $dbHandler;
		}
		
		public function generateEID(){
			return rand(10000, 99999);
		}
		
		public function formatPhoneNumber($phoneNumber){
			return substr($phoneNumber, 0, 3) . '-' . substr($phoneNumber, 3, 3) . '-' . substr($phoneNumber, 6);
		}
		
		public function formatSSN($SSN){
			return substr($SSN, 0, 3) . '-' . substr($SSN, 3, 2) . '-' . substr($SSN, 5);
		}
		
		public function getEmployees(){
			return $this->dbHandler->run('SELECT * 
											FROM `employees` 
												INNER JOIN `persons` 
											ON `employees`.`EID` =  `persons`.`EID`
												INNER JOIN `locations`
											ON `employees`.`LID` = `locations`.`LID`
											ORDER BY `hire_date` DESC')->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function addEmployee(Employee $employee){
			$this->dbHandler->run('INSERT INTO `employees` 
										(`EID`, `hire_date`, `LID`, `pay_rate`) 
									VALUES 
										(:EID, :hireDate, :LID, :payRate)', 
								array(
									'EID' => $employee->EID, 
									'hireDate' => $employee->hireDate, 
									'LID' => $employee->LID, 
									'payRate' => $employee->payRate
									));
			$this->dbHandler->run('INSERT INTO `persons` 
										(`SSN`, `EID`, `first_name`, `last_name`, `sex`, `birth_date`,
										`contact_number`, `address`, `email`)
									VALUES 
										(:SSN, :EID, :firstName, :lastName, :sex, :birthDate,
										:contactNumber, :address, :email)', 
								array(
									'SSN' => $employee->SSN, 
									'EID' => $employee->EID, 
									'firstName' => $employee->firstName, 
									'lastName' => $employee->lastName,
									'sex' => $employee->sex,
									'birthDate' => $employee->birthDate,
									'contactNumber' => $employee->contactNumber,
									'address' => $employee->address,
									'email' => $employee->email
									));
		}
		
		public function deleteEmployee($eid){
			$this->dbHandler->run('DELETE FROM `employees`
									WHERE `EID` = :EID',
								array(
									'EID' => $eid
								));
								
			$this->dbHandler->run('DELETE FROM `persons`
									WHERE `EID` = :EID',
								array(
									'EID' => $eid
								));
		}
		
		public function editEmployee($eid, Employee $employee){
			$this->dbHandler->run('UPDATE `employees` 
										SET `EID` = :updatedEID, `hire_date` = :hireDate, `LID` = :LID, `pay_rate` = :payRate
									WHERE 
										`EID` = :EID', 
								array(
									'updatedEID' => $employee->EID, 
									'hireDate' => $employee->hireDate, 
									'LID' => $employee->LID, 
									'payRate' => $employee->payRate,
									'EID' => $eid
									));
									
			$this->dbHandler->run('UPDATE `persons` 
										SET `SSN` = :SSN, `EID` = :updatedEID, `first_name` = :firstName, `last_name` = :lastName,
											`sex` = :sex, `birth_date` = :birthDate, `contact_number` = :contactNumber,
											`address` = :address, `email` = :email
									WHERE 
										`EID` = :EID', 
								array(
									'SSN' => $employee->SSN, 
									'updatedEID' => $employee->EID, 
									'firstName' => $employee->firstName, 
									'lastName' => $employee->lastName,
									'sex' => $employee->sex,
									'birthDate' => $employee->birthDate,
									'contactNumber' => $employee->contactNumber,
									'address' => $employee->address,
									'email' => $employee->email,
									'EID' => $eid
									));						
		}
		
		/**
		*	Search all columns in the `persons`, `employees`, and `locations` tables for searchQuery parameter.
		*	Syntax is messy as there is no way to bind a same named variable using PDO.
		*/
		public function searchEmployees($searchQuery){
			return $this->dbHandler->run('SELECT * 
											FROM `employees` 
												INNER JOIN `persons` 
											ON `employees`.`EID` =  `persons`.`EID`
												INNER JOIN `locations`
											ON `employees`.`LID` = `locations`.`LID`
											WHERE 
												`SSN` LIKE :searchQuery1
											OR `employees`.`EID` LIKE :searchQuery2
											OR `first_name` LIKE :searchQuery3
											OR `last_name` LIKE :searchQuery4
											OR `birth_date` LIKE :searchQuery5
											OR `contact_number` LIKE :searchQuery6
											OR `address` LIKE :searchQuery7
											OR `email` LIKE :searchQuery8
											OR `hire_date` LIKE :searchQuery9
											OR `pay_rate` LIKE :searchQuery10
											OR `city` LIKE :searchQuery11
											OR CONCAT(`first_name`, " ", `last_name`) LIKE :searchQuery12',
									array(
										'searchQuery1' =>  "%$searchQuery%",
										'searchQuery2' =>  "%$searchQuery%",
										'searchQuery3' =>  "%$searchQuery%",
										'searchQuery4' =>  "%$searchQuery%",
										'searchQuery5' =>  "%$searchQuery%",
										'searchQuery6' =>  "%$searchQuery%",
										'searchQuery7' =>  "%$searchQuery%",
										'searchQuery8' =>  "%$searchQuery%",
										'searchQuery9' =>  "%$searchQuery%",
										'searchQuery10' =>  "%$searchQuery%",
										'searchQuery11' =>  "%$searchQuery%",
										'searchQuery12' =>  "%$searchQuery%"
									))->fetchAll(PDO::FETCH_ASSOC);
		}
	}

?>