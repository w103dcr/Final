<?php
	require_once('classes/person.php');

	class Employee extends Person{
		
		public $EID;
		public $hireDate;
		public $LID;
		public $payRate;
		
		public function __construct($EID, $SSN, $firstName, $lastName, $sex, $birthDate, $contactNumber, $address, $email, $hireDate, $LID, $payRate){
			$this->SSN = intval(str_replace('-', '', $SSN));
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->sex = $sex;
			$this->birthDate = $birthDate;
			$this->contactNumber = intval(str_replace('-', '', $contactNumber));
			$this->address = $address;
			$this->email = $email;
			
			$this->EID = $EID;
			$this->hireDate = $hireDate;
			$this->LID = $LID;
			$this->payRate = $payRate;
		}
	}

?>