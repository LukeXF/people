<?php
require( "config.php" );

class Import {
	var $name;

	function getJsonFile( $file ) {
		$string = file_get_contents( $file );

		$content = json_decode( $string, true );

		$amountOfPeople = count($content);
		if ($amountOfPeople > 0) {
			$this->message("Found " . $amountOfPeople . " people to add to the database.", "info");
		}

		$i = 1;
		foreach ( $content as $person ) {

			if ( $i < 10000 ) {

				// $this->debug( $person );
				$person['counter'] = $i;
				$this->insert( $person );
			}

			if ($i == $amountOfPeople) {
				$this->message("Finished adding " . $amountOfPeople . " check above for status of each user.", "info");
			}
			$i ++;
		}
		//var_dump($content);
	}


	public function sqlSetup() {
		global $config;
		try {
			$sql = new PDO( 'mysql:host=' . $config['host'] . ';dbname=' . $config['data'], $config['user'], $config['pass'] );
			$sql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			return $sql;
		} catch ( PDOException $e ) {
			die( "Error setting up PDO:<hr>" . $e );
		}
	}

	private function checkIfExists( $person_id, $tag_value = null ) {

		$sql = $this->sqlSetup();

		if ($tag_value) {
			$query = $sql->prepare( "
           		SELECT * FROM `tags` WHERE `tag_person_id` = :id AND `tag_value` = :tag_value
        	" );

			$query->bindParam( ':id', $person_id, PDO::PARAM_STR );
			$query->bindParam( ':tag_value', $tag_value, PDO::PARAM_STR );
		} else {
			$query = $sql->prepare( "
            	SELECT * FROM `people` WHERE `people_id` = :id
        	" );

			$query->bindParam( ':id', $person_id, PDO::PARAM_STR );
		}


		$query->execute();
		$row = $query->fetch( PDO::FETCH_ASSOC );

		if ( $row ) {
			return true;
		} else {
			return false;
		}

	}

	public function insert( $data ) {

		if ( isset( $data['_id'] ) && ( $this->checkIfExists( $data['_id'] ) == false ) ) {

			$sql = $this->sqlSetup();

			$data['tags_id'] = null;

			if ( ! is_int( $data['balance'] ) ) {
				$currency = substr( $data['balance'], 0, 1 );
				$balance  = substr( $data['balance'], 1 );
				$balance  = (float) str_replace( ',', '', $balance );
			} else {
				$currency = "$";
				$balance  = $data['balance'];
			}


			$fullName = explode( " ", $data['name'] );

			$firstName = $fullName[0];
			$lastName  = end( $fullName );


			$query = $sql->prepare( "
            INSERT INTO `people`(
            `people_id`, `people_isActive`, `people_currency`, `people_balance`, `people_picture`, `people_age`, `people_eyeColor`, 
            `people_first_name`, `people_last_name`, `people_gender`, `people_company`, `people_email`, `people_phone`, `people_address`, 
            `people_about`, `people_registered`, `people_latitude`, `people_longitude`, `people_tags_id`) 
            VALUES (
            :people_id, :people_isActive, :people_currency, :people_balance, :people_picture, :people_age, :people_eyeColor, 
            :people_first_name, :people_last_name, :people_gender, :people_company, :people_email, :people_phone, :people_address, 
            :people_about, :people_registered, :people_latitude, :people_longitude, :people_tags_id
            )
        " );

			$query->bindParam( ':people_id', $data['_id'], PDO::PARAM_STR );
			$query->bindParam( ':people_isActive', $data['isActive'], PDO::PARAM_BOOL );
			$query->bindParam( ':people_currency', $currency, PDO::PARAM_INT );
			$query->bindParam( ':people_balance', $balance, PDO::PARAM_INT );
			$query->bindParam( ':people_picture', $data['picture'], PDO::PARAM_STR );
			$query->bindParam( ':people_age', $data['age'], PDO::PARAM_STR );
			$query->bindParam( ':people_eyeColor', $data['eyeColor'], PDO::PARAM_STR );
			$query->bindParam( ':people_first_name', $firstName, PDO::PARAM_STR );
			$query->bindParam( ':people_last_name', $lastName, PDO::PARAM_STR );
			$query->bindParam( ':people_gender', $data['gender'], PDO::PARAM_STR );
			$query->bindParam( ':people_company', $data['company'], PDO::PARAM_STR );
			$query->bindParam( ':people_email', $data['email'], PDO::PARAM_STR );
			$query->bindParam( ':people_phone', $data['phone'], PDO::PARAM_STR );
			$query->bindParam( ':people_address', $data['address'], PDO::PARAM_STR );
			$query->bindParam( ':people_about', $data['about'], PDO::PARAM_STR );
			$query->bindParam( ':people_registered', $data['registered'], PDO::PARAM_STR );
			$query->bindParam( ':people_latitude', $data['latitude'], PDO::PARAM_INT );
			$query->bindParam( ':people_longitude', $data['longitude'], PDO::PARAM_INT );
			$query->bindParam( ':people_tags_id', $data['tags_id'], PDO::PARAM_STR );

			if ( $query->execute() ) {
				$this->message("#" . $data['counter'] . " " . $firstName . " " . $lastName . " has been added to the database. id(" . $data['_id'] . ")", "success");
				$this->insertTags($data['_id'], $data['tags']);
			} else {
				$this->message("#" . $data['counter'] . " " . $data['name'] . " has NOT been added to the database due to an error. id(" . $data['_id'] . ")", "danger");
				$this->debug( $query->errorInfo() );
			}
		} else {
			$this->message("#" . $data['counter'] . " " . $data['name'] . " already exists in the database. id(" . $data['_id'] . ")", "warning");

			$this->insertTags($data['_id'], $data['tags']);

		}
	}

	public function insertTags( $personId, $personTags ) {

		foreach ($personTags as $tag) {
			$result = $this->checkIfExists( $personId, $tag);


			if ($result) {
				$this->message($tag . " has NOT been added to the tag table as it already exists for that person.", "warning", 1);
			} else {
				$this->insertTag($personId, $tag);
			}
		}
	}

	public function insertTag($personId, $tag) {
		$sql = $this->sqlSetup();

		$query = $sql->prepare( "
            INSERT INTO `tags`(`tag_person_id`, `tag_value`) 
            VALUES (:tag_person_id, :tag_value)
        " );

		$query->bindParam( ':tag_person_id', $personId, PDO::PARAM_STR );
		$query->bindParam( ':tag_value', $tag, PDO::PARAM_STR );

		if ( $query->execute() ) {
			$this->message($tag . " has been added to the tag table.", "success", 1);
		} else {
			$this->message($tag . " has NOT been added to the tag table due to an error.", "danger", 1);
			$this->debug( $query->errorInfo() );
		}
	}

	public function debug($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	private function message($message, $warningType, $indent = 0) {

		if ($indent == 1) {
			$display = 'style="display: none"';
		} else {
			$display = "";
		}

		echo '
		<div class="col-sm-' . (12 - $indent) . ' col-sm-offset-' . $indent . ' colLevel-' . $indent . '" ' . $display . '>
			<div class="level-' . $indent . ' alert alert-' . $warningType . '">
		       <strong>' . $warningType . '</strong> ' . $message . '
			</div>
		</div>';
	}
}

?>