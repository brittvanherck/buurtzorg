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
class VacaturePage extends Core implements iPage {
	public function getHtml() {
		if(defined('ACTION')) {			// process the action obtained is existent
			switch(ACTION) {
				// get html for the required action
				case "solliciteer" : return $this->create(); break;
				case "read"		   : return $this->read(); break;
				case "update"	   : return $this->update();break;
				case "delete"	   : return $this->delete();
			}
		} else { // no ACTION so normal page
			$table 	= $this->getData();		// get users from database in tableform
			$form   = $this->addSollicitatie();				
			// first show button, then table
			$html = $table;
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
	
	private function createTable($p_aDbResult){ // create html table from dbase result
		$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
		$table = "<table border='1'>";
			$table .= "	<th>Vacature Nummer</th>
						<th>Informatie</th>
						<th>Vacature</th>
						<th>wtv_id</th>
						<th>Solliciteer</th>";
			// now process every row in the $dbResult array and convert into table
			foreach ($p_aDbResult as $row){
				$table .= "<tr>";
					foreach ($row as $col) {
						$table .= "<td>" . $col . "</td>";
					}
					// calculate url and trim all parameters [0..9]
					$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
					// create new link with parameter (== edit user link!)
					$table 	.= "<td>"
							//. $url 							// current menu
							. $button = $this->addButton("/solliciteer", "Solliciteer")	// add "/add" button. This is ACTION button
							//. "/read/" . $row["vac_id"] 	// add ACTION and PARAM to the link
							. "</a></td>";			// link to edit icon
				$table .= "</tr>";
				
			} // foreach
		$table .= "</table>";
		return $table;
	} //function

	private function getData(){
		// execute a query and return the result
		$sql='SELECT * FROM `tb_vacature` ORDER BY `vac_id`';
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
	
	// [C]rud action
	// based on sent form 'frmAddSollicitatie' fields
	private function create() {
		// use variabel field  from form for processing -->
		if(isset($_POST['frmAddSollicitatie'])) { // in this case the form is returned
			return $this->processFormAddSollicitatie();
		} // ifisset
		else {								// in this case the form is made
			return $this->addSollicitatie();
		} //else
	}

	private function addSollicitatie() { // processed in $this->processFormAddUser()
		$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
		// heredoc statement. Everything between 2 HTML labels is put into $html
		$html = <<<HTML
			<fieldset>
				<legend>Vul uw gegevens in.</legend>
					<form action="$url" enctype="multipart/formdata" method="post">
						<label>Voornaam</label>
						<input type="text" name="voornaam" id="" value="" placeholder="Voornaam" />

						<label>Tussenvoegsel</label>
						<input type="" name="tussenvoegsel" id="" value="" placeholder="Tussenvoegsel" />

						<label>Achternaam</label>
						<input type="text" name="achternaam" id="" value="" placeholder="Achternaam" />

						<label>Adres</label>
						<input type="text" name="adres" id="" value="" placeholder="Adres" />

						<label>Geboortedatum</label>
						<input type="date" name="gebdatum" id="" value="" placeholder="Geboortedatum" />

						<label>E-mailadres</label>
						<input type="text" name="mail" id="" value="" placeholder="E-mailadres" />

						<label></label>
						<!-- add hidden field for processing -->
						<input type="hidden" name="frmAddSollicitatie" value="frmAddSollicitatie" />
						<input type="submit" name="submit" value="Voeg toe" />
					</form>
			</fieldset>
HTML;
		return $html;
	} // function
	
	private function processFormAddSollicitatie() {
		$uuid 		   = $this->createUuid(); // in code
		// get transfered datafields from form "$this->addForm()"	
		$voornaam 	   = $_POST['voornaam'];
		$tussenvoegsel = $_POST['tussenvoegsel'];
		$achternaam    = $_POST['achternaam'];
		$vollNaam      = $voornaam . " " . $tussenvoegsel . " " . $achternaam;
		$adres	       = $_POST['adres'];
		$gebdatum      = $_POST['gebdatum'];
		$mail		   = $_POST['mail'];
		// create insert query with all info above
		$sql = "INSERT
					INTO tb_soll
						(naamid, naam, adres, gebdatum, mail)
							VALUES
								('$uuid', '$vollNaam', '$adres', '$gebdatum', '$mail')";

		Database::getData($sql);
		/*
			echo "<br />";
			echo $hash . "<br />";
			echo $uuid . "<br />";
			echo $hashDate . "<br />";
		*/
		return "Sollicitatie is verstuurd.";
	} //function           
}