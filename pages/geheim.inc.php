<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Contains details of page information
	returns the built html
	Class Name convention: <pagename>Page
	Must contain iPage interface implementation ie getHtml()
	Called by content.inc.php
*/
class GeheimPage extends Core implements iPage {

	public function getHtml() {
		if(defined('ACTION')) {			// process the action obtained is existent
			switch(ACTION) {
				// get html for the required action
				case "create"	: return $this->create(); break;
				case "read"		: return $this->read(); break;
				case "update"	: return $this->update();break;
				case "delete"	: return $this->delete();
			}
		} else { // no ACTION so normal page
			$table 	= $this->getData();		// get users from database in tableform
			$button = $this->addButton("/create", "Toevoegen");	// add "/add" button. This is ACTION button
			// first show button, then table
			$html = $button . "<br />" . $table;
			return $html;
		}
	}

	// show button with the PAGE $p_sAction and the tekst $p_sActionText
	private function addButton($p_sAction, $p_sActionText) {
		// calculate url and trim all parameters [0..9]
		$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
		// create new link with PARAM for processing in new page request
		$url = $url . $p_sAction;
		$button = "<button onclick='location.href = \"$url\";'>$p_sActionText</button>";
		return $button;
	}

	private function getData(){
		// execute a query and return the result
		$sql='SELECT * FROM `tb_vacature` ORDER BY vac_id DESC';
		$result = $this->createTable(Database::getData($sql));

		//TODO: generate JSON output like this for webservices in future
		/*
			$data = Database::getData($sql);
			$json = Database::jsonParse($data);
			$array = Database::jsonParse($json);

			echo "<br />result: ";  print_r(Database::getData($sql));
			echo "<br /><br />json :" . $json;
			echo "<br /><br />array :"; print_r($array);
		*/

		return $result;
	} // end function getData()

	private function createTable($p_aDbResult){ // create html table from dbase result
		$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
		$table = "<table border='1'>";
			$table .= "	<th>vac_id</th>
						<th>vac_tekst</th>
						<th>vac_titel</th>
						<th>wtv_id</th>
						<th>delete</th>
						<th>update</th>";
			// now process every row in the $dbResult array and convert into table
			foreach ($p_aDbResult as $row){
				$table .= "<tr>";
					foreach ($row as $col) {
						$table .= "<td>" . $col . "</td>";
					}
					// calculate url and trim all parameters [0..9]
					$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
					//create new link with parameter (== delete user)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/delete/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
					// create new link with parameter (== update)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/update/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
				$table .= "</tr>";
				
			} // foreach
		$table .= "</table>";
		return $table;
	} //function

	// [C]rud action
	// based on sent form 'frmAddUser' fields
	private function create() {
		// use variabel field  from form for processing -->
		if(isset($_POST['frmAddVacature'])) { // in this case the form is returned
			return $this->processFormAddVacature();
		} // ifisset
		else {								// in this case the form is made
			return $this->addForm();
		} //else
	}


	//Form voor updaten van vacature
	private function addForm1() { // processed in $this->processFormAddUser()
		$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
		// heredoc statement. Everything between 2 HTML labels is put into $html
		$html = <<<HTML
			<fieldset>
				<legend>Update de vacature</legend>
					<form action="$url" enctype="multipart/formdata" method="post">
						<label>vacature id</label>
						<input type="text" name="vac_id" id="" value="" placeholder="vac_id" />

						<label>vacature tekst</label>
						<input type="text" name="vac_tekst" id="" value="" placeholder="vac_tekst" />

						<label>vacature titel</label>
						<input type="text" name="vac_titel" id="" value="" placeholder="vac_titel" />

						<label>wtv id</label>
						<input type="text" name="wtv_id" id="" value="" placeholder="wtv_id" />

						<label></label>
						<!-- add hidden field for processing -->
						<input type="hidden" name="frmUpdateVacature" value="frmUpdateVacature" />
						<input type="submit" name="submit" value="Voeg toe" />
					</form>
			</fieldset>
HTML;
		return $html;
	}

	//Form voor aanmaken van vacature
	private function addForm() { // processed in $this->processFormAddUser()
		$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
		// heredoc statement. Everything between 2 HTML labels is put into $html
		$html = <<<HTML
			<fieldset>
				<legend>Voeg een nieuwe vacature toe</legend>
					<form action="$url" enctype="multipart/formdata" method="post">
						<label>vacature id</label>
						<input type="text" name="vac_id" id="" value="" placeholder="vac_id" />

						<label>vacature tekst</label>
						<input type="text" name="vac_tekst" id="" value="" placeholder="vac_tekst" />

						<label>vacature titel</label>
						<input type="text" name="vac_titel" id="" value="" placeholder="vac_titel" />

						<label>wtv id</label>
						<input type="text" name="wtv_id" id="" value="" placeholder="wtv_id" />

						<label></label>
						<!-- add hidden field for processing -->
						<input type="hidden" name="frmAddVacature" value="frmAddVacature" />
						<input type="submit" name="submit" value="Voeg toe" />
					</form>
			</fieldset>
HTML;
		return $html;
	} 
	
	// Verwerken informatie van toevoegen vacature
	private function processFormAddVacature() {
		$vac_id 	= $_POST['vac_id'];
		$vac_tekst	= $_POST['vac_tekst'];
		$vac_titel	= $_POST['vac_titel'];
		$wtv_id		= $_POST['wtv_id'];
		// create insert query with all info above
		$sql = "INSERT INTO `tb_vacature`(`vac_id`, `vac_tekst`, `vac_titel`, `wtv_id`) VALUES ('$vac_id', '$vac_tekst', '$vac_titel', '$wtv_id')";

		Database::getData($sql);
		/*
			echo "<br />";
			echo $hash . "<br />";
			echo $uuid . "<br />";
			echo $hashDate . "<br />";
		*/
		return "Vacature is toegevoegd.";
	} 

	// Verwerken informatie van updaten vacature
	private function processFormUpdateVacature() {
		$vac_id 	= $_POST['vac_id'];
		$vac_tekst	= $_POST['vac_tekst'];
		$vac_titel	= $_POST['vac_titel'];
		$wtv_id		= $_POST['wtv_id'];
		// create update query with all info above
		$sql = "
		UPDATE tb_vacature
		SET vac_id = '$vac_id', vac_tekst = '$vac_tekst', vac_titel = '$vac_titel', wtv_id = '$wtv_id'
		WHERE vac_id= '$vac_id';
		";

		Database::getData($sql);
		/*
			echo "<br />";
			echo $hash . "<br />";
			echo $uuid . "<br />";
			echo $hashDate . "<br />";
		*/
		return "Vacature is gewijzigd.";
	} 
	
	
	
	
	//function


		//cr[U]d action
	private function update() {
		// present form with all user information editable and process
		$button = $this->addButton("/../..", "Terug");	
		// first show button, then table
		if(isset($_POST['frmUpdateVacature'])) { // in this case the form is returned
			return $this->processFormUpdateVacature();
		}
		else{
			return $this->addForm1();
		}
		return $button ."<br>Deze gebruiker moet worden aangepast " . PARAM;
	}


	//cru[D] action
	private function delete() {
		// remove selected record based om uuid in PARAM
		$sql='DELETE FROM tb_vacature WHERE vac_id="' . PARAM. '"';
		
		
		$result = Database::getData($sql);
		$button = $this->addButton("/../../..", "Terug");	// add "/add" button. This is ACTION button
		// first show button, then table

		return $button ."<br>Deze vacature is verwijderd " . PARAM;
	}

	
}// class gebruikerPage


?>