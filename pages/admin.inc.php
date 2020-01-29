<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Contains details of page information
	returns the built html
	Must contain iPage interface implementation ie getHtml()
	Called by content.inc.php
*/
class adminPage extends Core implements iPage {

	public function getHtml() {
		if(defined('ACTION')) {			// process the action obtained is existent
			switch(ACTION) {
				// get html for the required action
				case "sollicitatie"	: return $this->create(); 	break;
				case "read"			: return $this->read(); 	break;
				case "update"		: return $this->update();	break;
				case "delete"		: return $this->delete();	break;
				case "addVote"      : return $this->addVote();
			}
		} else { // no ACTION so normal page
			$table 	= $this->getData();		// get sollicitaties from database in tableform
			
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
		

	private function getData(){
		// execute a query and return the result
		$sql='SELECT * FROM `tb_soll` WHERE status = 0 OR status = 1 ORDER BY punten DESC';
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
			$table .= "	<th>naamid</th>
						<th>naam</th>
						<th>adres</th>
						<th>gebdatum</th>
						<th>mail</th>
						<th>vac_id</th>
						<th>status</th>
						<th>punten</th>
						<th>stemmen</th>
						<th>verwijder</th>";
			// now process every row in the $dbResult array and convert into table
			foreach ($p_aDbResult as $row){
				$table .= "<tr>";
					foreach ($row as $col) {
						$table .= "<td>" . $col . "</td>";
					}
					// calculate url and trim all parameters [0..9]
					$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
					// create new link with parameter (== edit user link!)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/addVote/" . $row["naamid"] 		// add ACTION and PARAM to the link
							
							. ">$image</a></td>";			// link to edit icon
					//create new link with parameter (== delete user)
					$table 	.= "<td><a href="
							. $url 							// current menu
							. "/delete/" . $row["naamid"] 	// add ACTION and PARAM to the link
							. ">$image</a></td>";			// link to delete icon
				$table .= "</tr>";
				
			} // foreach
		$table .= "</table>";
		return $table;
	} //function

		//cru[D] action
	private function delete() {
		// remove selected record based om uuid in PARAM
		$sql='DELETE FROM tb_soll WHERE naamid="' . PARAM. '"';		
		$result = Database::getData($sql);
		$button = $this->addButton("/../..", "Terug");	// add "/add" button. This is ACTION button
		// first show button, then table

		return $button ."<br>Deze sollicitatie is verwijderd " . PARAM;
	}

	//cru[D] action
	private function addVote() {
		// count selected record based om votes in PARAM
		$sql='UPDATE tb_soll SET punten = punten + 1 WHERE naamid="' . PARAM. '"';		
		$result = Database::getData($sql);
		$button = $this->addButton("/../../..", "Terug");	// add "/add" button. This is ACTION button
		// first show button, then table

		return $button ."<br>U heeft een stem uitgrebracht"	. PARAM;
	}
}// class gebruikerPage
?>