<?php

class EducationServiceModel {

	public function getData() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT  etu.id AS id,
										etu.prenom AS etu_prenom, 
										etu.nom AS etu_nom, 
										CONCAT(p.libelle, etu.semestre) AS formation, 
										ec.nom AS ec_nom
						        FROM etudiant AS etu
						        LEFT JOIN conseiller AS c ON    (c.id_etudiant=etu.id)
						        LEFT JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
						        LEFT JOIN liste_programme AS p ON   (p.id=etu.id_programme)
						        ORDER BY etu.nom'
		);
		$row = $query->fetchAll();
		return $row;
	}

	public function getOrphan() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT 	etu.id,
										etu.nom, 
										etu.prenom, 
										CONCAT(lp.libelle, etu.semestre) AS formation 
								FROM etudiant AS etu
								LEFT OUTER JOIN conseiller AS c ON ( c.id_etudiant=etu.id)
								LEFT JOIN liste_programme AS lp ON ( lp.id=etu.id_programme)
								WHERE c.id_etudiant IS NULL'
		);
		$row = $query->fetchAll();

		return $row;
	}

	public function getStudentByProgram($program) {
		$db = Database::getInstance();
		$query = $db->query('	SELECT  etu.id AS etu_id,
										etu.prenom AS etu_prenom, 
										etu.nom AS etu_nom, 
										CONCAT(p.libelle, etu.semestre) AS formation, 
										ec.nom AS ec_nom
						        FROM etudiant AS etu
						        LEFT JOIN conseiller AS c ON (c.id_etudiant=etu.id)
						        LEFT JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
						        LEFT JOIN liste_programme AS p ON (p.id=etu.id_programme)
						        WHERE p.libelle=\'' . $program . '\''
		);
		$row = $query->fetchAll();

		return $row;
	}

	public function addStudent($id, $student_name, $student_surname, $program, $nb_semester) {
		$db = Database::getInstance();

		$st = $db->prepare('INSERT INTO etudiant(id, id_programme, nom, prenom, semestre) VALUES (
							' . $id . ',
			                ' . self::getProgramId($program) . ',
			                \'' . self::stdName($student_name) . '\',
			                \'' . self::stdSurname($student_surname) . '\',
			                ' . $nb_semester . '
			                )'
				);
		$st->execute();
	}

	public function alreadyExists($id) {
		$db = Database::getInstance();

		$query = $db->query('	SELECT id FROM etudiant
								WHERE id=' . $id
		);
		$row = $query->fetch();
		if ($row) {
			return true;
		} else {
			return false;
		}
	}

	public function addStudents($data) {
		$db = Database::getInstance();
		$data_affected = array();

		foreach ($data as $key => $value) {
			$name = self::stdName($value['nom']);
			$surname = self::stdSurname($value['prenom']);
			$id_program = self::getProgramId($value['programme']);

			if(!self::conformValues($name, $surname, $value['programme'], $value['semestre'], $value['numero'])) {
				$data_affected[$key] = array('nom' => $name,
												'prenom' => $surname,
												'affected' => false);
			} else {
				$st = $db->prepare('INSERT INTO etudiant(id, prenom, nom, id_programme, semestre)
								SELECT * FROM (SELECT 	' . $value['numero'] . ' AS id,
														\'' . $surname . '\' AS prenom,
														\'' . $name . '\' AS nom,
														' . $id_program . ' AS id_programme,
														'. $value['semestre'] . ' AS semestre) AS tmp
								WHERE NOT EXISTS (
								    SELECT etu.id FROM etudiant AS etu
								    WHERE etu.id=\'' . $value['numero'] . '\' 
								) LIMIT 1'
				);
				$st->execute();
				$affected = $st->rowCount();

				if ($affected == 1) {
					$data_affected[$key] = array('nom' => $name,
						'prenom' => $surname,
						'affected' => true);
				} else {
					$data_affected[$key] = array('nom' => $name,
												'prenom' => $surname,
												'affected' => false);
				}
			}

			
		}

		return $data_affected;
	}

	public function purgeStudent() {
		$db = Database::getInstance();
		$st = $db->prepare('DELETE FROM conseiller');
		$st->execute();

		$st = $db->prepare('DELETE FROM etudiant');
		$st->execute();

		return $this->getData();
	}

	public function deleteStudent($id) {
		$db = Database::getInstance();

		$st = $db->prepare('DELETE FROM conseiller WHERE id_etudiant=' . $id);
		$st->execute();

		$st = $db->prepare('DELETE FROM etudiant WHERE id=' . $id);
		$st->execute();

		return $this->getData();
	}

	public function formationTransfert($student_name, $student_surname, $formation_transfert) {
		$db = Database::getInstance();
		$query = $db->query('	SELECT id FROM liste_programme
								WHERE libelle="' . $formation_transfert . '"'
		);
		$id_formation_transfert = $query->fetch();

		$query = $db->query('	SELECT id FROM etudiant
										WHERE nom="' . $student_name . '"
										AND prenom="' . $student_surname . '"'
		);
		$id_student = $query->fetch();

		$query = $db->query('	SELECT 	ec.id,
										h.id_programme
								FROM etudiant AS etu
								LEFT JOIN conseiller AS c ON 			(c.id_etudiant=etu.id)
								LEFT JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
								LEFT JOIN habilitation AS h ON (h.id_enseignant_chercheur=ec.id)
								WHERE etu.id=' . $id_student->id
		);
		$id_conseilor = $query->fetchAll();

		$authorized = false;

		if ($id_conseilor) {
			foreach ($id_conseilor as $value) {
				if ($value->id_programme == $id_formation_transfert) {
					$authorized = true;
					break;
				}
			}

			$db->exec('UPDATE etudiant SET id_programme=' . $id_formation_transfert->id . ' WHERE id=' . $id_student->id);

			if (!$authorized) {
				$db->exec('DELETE FROM conseiller WHERE id_etudiant=' . $id_student->id);
				return $this->assignNewStudent($student_name, $student_surname);
			} else {
				return $this->getData();
			}
		} else {
			return $this->assignNewStudent($student_name, $student_surname);
		}
	}

	public function getFormation() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT libelle FROM liste_programme
								WHERE libelle <> "TC" '
		);
		$row = $query->fetchAll();

		return $row;
	}

	public function getAllFormation() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT libelle FROM liste_programme');
		$row = $query->fetchAll(PDO::FETCH_COLUMN);

		return $row;
	}

	public function assignNewStudent($student_id) {
		$db = Database::getInstance();

		$st = $db->prepare('INSERT INTO conseiller(id_enseignant_chercheur, id_etudiant)
							SELECT	ec.id AS ec_id, 
									etu.id AS etu_id
									FROM  	etudiant AS etu, 
											conseiller AS c
							RIGHT OUTER JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
							LEFT JOIN habilitation AS h ON (h.id_enseignant_chercheur=ec.id)
							WHERE etu.id="' . $student_id . '"
							AND etu.id_programme=h.id_programme
							GROUP BY(ec.id)
							ORDER BY SUM(CASE WHEN c.id_etudiant IS NULL THEN 0 ELSE 1 END) ASC
							LIMIT 1'
		);
		$st->execute();

		$affected = $st->rowCount();

		return $affected;
	}

	static function etuCompare($a, $b) {
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}

	public function assignNewStudents() {
		$db = Database::getInstance();
		$query = $db->query('	SELECT 	etu.id, 
										etu.id_programme,
										etu.nom AS student_name,
										etu.prenom AS student_surname
								FROM etudiant AS etu
								LEFT OUTER JOIN conseiller AS c ON (c.id_etudiant=etu.id)
								WHERE c.id_etudiant is NULL'
		);
		$student = $query->fetchAll();

		$query = $db->query('	SELECT 	SUM(CASE WHEN c.id_etudiant IS NULL THEN 0 ELSE 1 END) AS nbetu, 
										ec.id, 
										ec.nom AS academic_researcher_name,
										ec.prenom AS academic_researcher_surname
								FROM conseiller AS c
								RIGHT OUTER JOIN enseignant_chercheur AS ec ON (ec.id=c.id_enseignant_chercheur)
								GROUP BY(ec.id)
								ORDER BY nbetu'
		);
		$academic_researcher = $query->fetchAll();

		$query = $db->query('  	SELECT h.* 
						        FROM enseignant_chercheur AS ec
						        LEFT JOIN habilitation AS h ON (h.id_enseignant_chercheur=ec.id)'
		);
		$authorization = $query->fetchAll();

		$found = false;

		$assign_logs = array(array());
		$i = 0;

		foreach ($student as $student_val) {
			foreach ($academic_researcher as $academic_researcher_key => $academic_researcher_val) {
				foreach ($authorization as $authorization_val) {
					if ($authorization_val->id_enseignant_chercheur == $academic_researcher_val->id && $authorization_val->id_programme == $student_val->id_programme) {
						$academic_researcher_chosen = $academic_researcher_val->id;
						$academic_researcher_chosen_key = $academic_researcher_key;
						$found = true;
						break;
					}
				}
				if ($found) {
					break;
				}
			}
			if ($found) {
				$st = $db->prepare('INSERT INTO conseiller(id_enseignant_chercheur, id_etudiant) VALUES(' . $academic_researcher_chosen . ', ' . $student_val->id . ')');
				$st->execute();
				$assign_logs[$i]['student_name'] = $student_val->student_name;
				$assign_logs[$i]['student_surname'] = $student_val->student_surname;
				$assign_logs[$i]['academic_researcher_name'] = $academic_researcher[$academic_researcher_chosen_key]->academic_researcher_name;
				$assign_logs[$i]['academic_researcher_surname'] = $academic_researcher[$academic_researcher_chosen_key]->academic_researcher_surname;
				$academic_researcher[$academic_researcher_chosen_key]->nbetu += 1;
				usort($academic_researcher, array('EducationServiceModel', 'etuCompare'));
			} else {
				$assign_logs[$i]['student_name'] = $student_val->student_name;
				$assign_logs[$i]['student_surname'] = $student_val->student_surname;
				$assign_logs[$i]['academic_researcher_name'] = '';
				$assign_logs[$i]['academic_researcher_surname'] = '';
			}

			$academic_researcher_chosen = '';
			$found = false;
			$i++;
		}
		return $assign_logs;
	}


	public function conformValues($name = '', $surname = '', $formation = '', $semester = '', $id = 1) {
		$regex_name_surname = '/^[a-zA-ZÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜáàâäéèêëíìîïóòôöúùûüÇç]+-?[a-zA-ZÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜáàâäéèêëíìîïóòôöúùûüÇç]+$/i';
		$regex_formation = '/^[a-zéèêàâ]+$/i';
		$regex_semester = '/^[0-9]{1,2}$/';
		$regex_id = '/^[0-9]{1,6}$/';

		if($name != '' && $surname != '' && $formation != '' && $semester != '' &&  preg_match($regex_name_surname, $name) && 
			preg_match($regex_name_surname, $surname) && preg_match($regex_formation, $formation) && preg_match($regex_semester, $semester) && preg_match($regex_id, $id)) {
			return true;
		} else {
			return false;
		}
	}

	function getProgramId($label) {
		$db = Database::getInstance();
		$query = $db->query('SELECT id FROM liste_programme 
							WHERE libelle=\'' . $label . '\'');
		$row = $query->fetch();

		if ($row) {
			return $row->id;
		} else {
			return 1;
		}
	}

	function stdName($name) {
		return strtoupper($name);
	}

	function stdSurname($surname) {
		return strtoupper(substr($surname, 0, 1)) . strtolower(substr($surname, 1));
	}

}

?>