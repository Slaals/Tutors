<?php

class ProgramManagerModel {

	public function getData() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT DISTINCT ec.id, ec.prenom, ec.nom, ec.bureau, lp.libelle
								FROM enseignant_chercheur AS ec
								LEFT JOIN habilitation AS h ON ( h.id_enseignant_chercheur = ec.id )
								LEFT JOIN liste_programme AS lp ON ( lp.id = h.id_programme )
								ORDER BY ec.id'
		);
		$row = $query->fetchAll();
		$prog = array();
		$id = $row[0]->id;
		$data = array();

		//Mise en conformité du tableau
		for ($i = 0; $i < count($row); $i++) {
			if ($id == $row[$i]->id) {
				$prog[] = $row[$i]->libelle;
			} else {
				$row[$i - 1]->libelle = $prog;
				$data[] = $row[$i - 1];
				unset($prog);
				$prog[] = $row[$i]->libelle;
			}
			$id = $row[$i]->id;
		}

		if (count($row) > 0) {
			$row[count($row) - 1]->libelle = $prog;
			$data[] = $row[count($row) - 1];

			for ($i = 0; $i < count($data); $i++) {
				unset($data[$i]->id);
			}
		}

		return $data;
	}

	public function getStudentAndCounsellor($program) {
		$db = Database::getInstance();
		$query = $db->query('	SELECT	etu.prenom AS etu_prenom, 
										etu.nom AS etu_nom, 
										CONCAT(p.libelle, etu.semestre) AS formation, 
										ec.nom AS ec_nom
						        FROM etudiant AS etu
						        LEFT JOIN conseiller AS c ON    (c.id_etudiant=etu.id)
						        LEFT JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
						        LEFT JOIN liste_programme AS p ON   (p.id=etu.id_programme)
						        WHERE p.libelle = \'' . $program . '\''
		);
		$row = $query->fetchAll();
		return $row;
	}

	public function addAuthorization($teacher_name, $teacher_surname, $label_authorization) {
		$db = Database::getInstance();
		$id_teacher = self::getAcademicResearcherIdByNameSurname($teacher_name, $teacher_surname);

		$id_authorization = self::getIdProgramByLabel($label_authorization);

		$db->exec('INSERT INTO habilitation(id_enseignant_chercheur, id_programme) VALUES(
																						"' . $id_teacher . '",
																						"' . $id_authorization . '")'
		);
	}

	public function addHabilitationByProgram($label_authorization) {
		$db = Database::getInstance();
		$st = $db->prepare('	INSERT INTO habilitation(id_enseignant_chercheur, id_programme) 
								SELECT * FROM (	SELECT ec.id AS ec_id, lp.id AS lp_id 
								FROM 	enseignant_chercheur AS ec, 
										liste_programme AS lp
								WHERE lp.libelle=\'' . $label_authorization . '\'
								AND NOT EXISTS (
									SELECT h.id_enseignant_chercheur FROM habilitation AS h
									WHERE id_enseignant_chercheur = ec.id
                                    AND h.id_programme = lp.id
									)
								) AS tmp'
		);

		$st->execute();
	}

	public function deleteAuthorization($teacher_name, $teacher_surname, $label_authorization) {
		$db = Database::getInstance();

		$id_teacher = self::getAcademicResearcherIdByNameSurname($teacher_name, $teacher_surname);

		$id_authorization = self::getIdProgramByLabel($label_authorization);

		$st = $db->prepare('DELETE FROM habilitation WHERE id_enseignant_chercheur=' . $id_teacher . ' AND id_programme=' . $id_authorization);
		$st->execute();
	}

	function getAcademicResearcherIdByNameSurname($name, $surname) {
		$db = Database::getInstance();
		$query = $db->query('	SELECT id FROM enseignant_chercheur
								WHERE nom="' . $name . '"
								AND prenom="' . $surname . '"'
		);
		$id_teacher = $query->fetch();

		return $id_teacher->id;
	}

	function getIdProgramByLabel($label) {
		$db = Database::getInstance();
		$query = $db->query('SELECT id FROM liste_programme WHERE libelle="' . $label . '"');
		$id_authorization = $query->fetch();

		return $id_authorization->id;
	}

}

?>