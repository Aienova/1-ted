<?php

require_once __DIR__ . '/connectionDatabase.php';
require_once __DIR__ . '/../entity/candidate/identity.php';
require_once __DIR__ . '/../entity/candidate/skill.php';
require_once __DIR__ . '/../entity/candidate/personality.php';
require_once __DIR__ . '/../entity/candidate/experience.php';
require_once __DIR__ . '/../entity/candidate/mission.php';
require_once __DIR__ . '/../entity/candidate/social.php';
require_once __DIR__ . '/../entity/candidate/formation.php';
require_once __DIR__ . '/../entity/candidate/candidate.php';
require_once __DIR__ . '/../entity/candidate/demand.php';
require_once __DIR__ . '/../entity/candidate/knowledge.php';
require_once __DIR__ . '/../entity/candidate/language.php';
require_once __DIR__ . '/../entity/candidate/hobbie.php';
require_once __DIR__ . '/../entity/candidate/degree.php';
require_once __DIR__ . '/../entity/candidate/languageLevel.php';
require_once __DIR__ . '/../entity/job/job.php';
require_once __DIR__ . '/../entity/job/company.php';
require_once __DIR__ . '/../entity/job/contact.php';
require_once __DIR__ . '/../entity/job/requirement.php';
require_once __DIR__ . '/../entity/other/account.php';
require_once __DIR__ . '/../entity/other/preference.php';
require_once __DIR__ . '/../entity/other/application.php';
require_once __DIR__ . '/../entity/other/suggestion.php';
require_once __DIR__ . '/../entity/other/country.php';
require_once __DIR__ . '/../entity/other/activity.php';

/**
 * Get all data from a table
 * @param string $table Table name
 * @param bool $asObject Convert to objects if true, return raw data if false
 * @return array Array of data or objects
 */
function getAllData($table, $asObject = true) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Prepare SQL query
        $sql = "SELECT * FROM $table";
        $stmt = $pdo->prepare($sql);
        
        // Execute query
        $stmt->execute();
        
        // Fetch all data
        $results = $stmt->fetchAll();
        
        // Convert to objects if requested
        if ($asObject && !empty($results)) {
            $objects = [];
            foreach ($results as $data) {
                $obj = mapToObject($table, $data);
                if ($obj !== null) {
                    $objects[] = $obj;
                }
            }
            return $objects;
        }
        
        return $results;
        
    } catch (PDOException $e) {
        error_log("Get all data error from table $table: " . $e->getMessage());
        return [];
    }
}

/**
 * Get data by ID from a table
 * @param string $table Table name
 * @param int $id Record ID
 * @param bool $asObject Convert to object if true, return raw data if false
 * @return object|array|null Object, array, or null if not found
 */
function getDataById($table, $id, $asObject = true) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Prepare SQL query
        $sql = "SELECT * FROM $table WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Bind ID parameter
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        // Execute query
        $stmt->execute();
        
        // Fetch data
        $data = $stmt->fetch();
        
        // Return null if not found
        if (!$data) {
            return null;
        }
        
        // Convert to object if requested
        if ($asObject) {
            return mapToObject($table, $data);
        }
        
        return $data;
        
    } catch (PDOException $e) {
        error_log("Get data by ID error from table $table for ID $id: " . $e->getMessage());
        return null;
    }
}

/**
 * Generic getData function
 * @param string $table Table name
 * @param int|null $id Record ID (optional, if null returns all data)
 * @param bool $asObject Convert to object(s) if true
 * @return mixed Object, array of objects, array of data, or null
 */
function getData($table, $id = null, $asObject = true) {
    if ($id === null) {
        return getAllData($table, $asObject);
    }
    return getDataById($table, $id, $asObject);
}

/**
 * Map database data to corresponding entity object
 * @param string $table Table name
 * @param array $data Database row data
 * @return object|null Entity object or null if table not mapped
 */
function mapToObject($table, $data) {
    switch ($table) {
        case 'identity':
            $languageIds = isset($data['language_ids']) ? json_decode($data['language_ids'], true) : [];
            $hobbieIds = isset($data['hobbie_ids']) ? json_decode($data['hobbie_ids'], true) : [];
            // Load Degree object if degree_id exists
            $degree = null;
            if (isset($data['degree_id']) && $data['degree_id']) {
                $degree = getData('degree', $data['degree_id'], true);
            }
            return new Identity(
                $data['id'] ?? null,
                $data['last_name'] ?? '',
                $data['first_name'] ?? '',
                $data['birthdate'] ?? '',
                $degree,
                $data['job_title'] ?? '',
                $data['phone'] ?? '',
                $data['location'] ?? '',
                $data['email'] ?? '',
                $data['vehicule'] ?? '',
                $data['description'] ?? '',
                $languageIds,
                $hobbieIds
            );
            
        case 'language':
        case 'languages':
            // Load LanguageLevel object if level_id exists
            $level = null;
            if (isset($data['level_id']) && $data['level_id']) {
                $level = getData('language_level', $data['level_id'], true);
            }
            return new Language(
                $data['id'] ?? null,
                $data['language_name'] ?? '',
                $level,
                isset($data['is_native']) ? (bool)$data['is_native'] : false
            );
            
        case 'hobbie':
        case 'hobbies':
            return new Hobbie(
                $data['id'] ?? null,
                $data['title'] ?? ''
            );
            
        case 'degree':
        case 'degrees':
            return new Degree(
                $data['id'] ?? null,
                $data['title'] ?? ''
            );
            
        case 'language_level':
        case 'language_levels':
            return new LanguageLevel(
                $data['id'] ?? null,
                $data['title'] ?? ''
            );
            
        case 'skill':
        case 'skills':
            return new Skill(
                $data['id'] ?? null,
                $data['title'] ?? '',
                $data['year'] ?? '',
                isset($data['favorite']) ? (bool)$data['favorite'] : false
            );
            
        case 'personality':
        case 'personalities':
            return new Personality(
                $data['id'] ?? null,
                $data['trait'] ?? ''
            );
            
        case 'experience':
        case 'experiences':
            $missions = isset($data['missions']) ? json_decode($data['missions'], true) : [];
            return new Experience(
                $data['id'] ?? null,
                $data['start_date'] ?? '',
                $data['end_date'] ?? '',
                $data['current'] ?? false,
                $data['position'] ?? '',
                $data['company'] ?? '',
                $data['location'] ?? '',
                $missions
            );
            
        case 'mission':
        case 'missions':
            return new Mission(
                $data['id'] ?? null,
                $data['title'] ?? ''
            );
            
        case 'knowledge':
        case 'knowledges':
            return new Knowledge(
                $data['id'] ?? null,
                $data['title'] ?? ''
            );
            
        case 'social':
            return new Social(
                $data['id'] ?? null,
                $data['linkedin'] ?? '',
                $data['twitter'] ?? '',
                $data['github'] ?? '',
                $data['website'] ?? '',
                $data['instagram'] ?? '',
                $data['behance'] ?? '',
                $data['facebook'] ?? '',
                $data['other'] ?? ''
            );
            
        case 'formation':
        case 'formations':
            $knowledge = isset($data['knowledge']) ? json_decode($data['knowledge'], true) : [];
            return new Formation(
                $data['id'] ?? null,
                $data['start_date'] ?? '',
                $data['end_date'] ?? '',
                $data['current'] ?? false,
                $data['title'] ?? '',
                $data['school'] ?? '',
                $data['location'] ?? '',
                $knowledge
            );
            
        case 'demand':
        case 'demands':
            return new Demand(
                $data['id'] ?? null,
                $data['contract_type'] ?? '',
                $data['salary'] ?? 0,
                $data['work_condition'] ?? ''
            );
            
        case 'candidate':
        case 'candidates':
            $skillIds = isset($data['skill_ids']) ? json_decode($data['skill_ids'], true) : [];
            $personalityIds = isset($data['personality_ids']) ? json_decode($data['personality_ids'], true) : [];
            $experienceIds = isset($data['experience_ids']) ? json_decode($data['experience_ids'], true) : [];
            $formationIds = isset($data['formation_ids']) ? json_decode($data['formation_ids'], true) : [];
            $missionIds = isset($data['mission_ids']) ? json_decode($data['mission_ids'], true) : [];
            return new Candidate(
                $data['id'] ?? null,
                $data['identity_id'] ?? null,
                $data['country_id'] ?? null,
                $data['activity_id'] ?? null,
                $skillIds,
                $personalityIds,
                $data['social_id'] ?? null,
                $data['demand_id'] ?? null,
                $experienceIds,
                $formationIds,
                $missionIds,
                $data['media_code'] ?? null,
                $data['approved'] ?? null
            );
            
        case 'contact':
        case 'contacts':
            return new Contact(
                $data['id'] ?? null,
                $data['first_name'] ?? '',
                $data['last_name'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? ''
            );
            
        case 'company':
        case 'companies':
            $company = new Company(
                $data['id'] ?? null,
                $data['name'] ?? ''
            );
            $company->setCustomerNumber($data['customer_number'] ?? null);
            $company->setNationRegisterNumber($data['nation_register_number'] ?? null);
            $company->setDescription($data['description'] ?? '');
            $company->setDomain($data['domain'] ?? '');
            $company->setCapacity($data['capacity'] ?? null);
            $company->setHqLocation($data['hq_location'] ?? '');
            $company->setCountry($data['country_id'] ?? null);
            $company->setRecruiting($data['recruiting'] ?? true);
            $company->setContactId($data['contact_id'] ?? null);
            return $company;
            
        case 'requirement':
        case 'requirements':
            return new Requirement(
                $data['id'] ?? null,
                $data['description'] ?? '',
                $data['degree'] ?? null,
                $data['experience_years'] ?? null
            );
            
        case 'job':
        case 'jobs':
            return new Job(
                $data['id'] ?? null,
                $data['title'] ?? '',
                $data['description'] ?? '',
                $data['location'] ?? null,
                $data['activity_id'] ?? null,
                $data['country_id'] ?? null,
                $data['salary'] ?? null,
                $data['contract_type'] ?? null,
                $data['start_date'] ?? null,
                $data['length'] ?? null,
                $data['company_id'] ?? null,
                $data['work_condition'] ?? null,
                $data['need_vehicle'] ?? null,
                $data['contact_id'] ?? null,
                $data['requirement_id'] ?? null,
                $data['open'] ?? null,
                $data['approved'] ?? null
            );
            
        case 'account':
        case 'accounts':
            return new Account(
                $data['id'],
                $data['firstname'],
                $data['lastname'],
                $data['login'],
                $data['telephone'],
                $data['company_name'],
                $data['company_siret'],
                $data['activity_id'] ?? null,
                $data['email'],
                $data['password_hash'],
                $data['role'],
                $data['created_at'],
                $data['country_id'] ?? null,
                $data['last_connection'] ?? null,
                $data['token'] ?? null,
                $data['candidate_id'] ?? null,
                $data['company_id'] ?? null,
                null, // preference object, loaded separately if needed
                $data['forget_code'] ?? null
            );
            
        case 'preference':
        case 'preferences':
            return new Preference(
                $data['id'],
                $data['account_id'],
                $data['keywords'],
                $data['maximum_age'] ?? null,
                $data['minimum_experience'] ?? null,
                $data['minimum_salary'] ?? null,
                $data['maximum_salary'] ?? null,
                $data['locations'] ?? null,
                $data['suggestion_activated'] ?? false
            );
            
        case 'application':
        case 'applications':
            $application = new Application(
                $data['id'],
                $data['candidate_id'],
                $data['job_id'],
                $data['status'] ?? '',
                $data['recruiter_validation'] ?? false,
                $data['candidate_validation'] ?? false,
                $data['comment'] ?? ''
            );
            
            // Charger les relations
            if (isset($data['candidate_id']) && $data['candidate_id']) {
                $candidate = getData('candidate', $data['candidate_id'], true);
                $application->setCandidate($candidate);
            }
            
            if (isset($data['job_id']) && $data['job_id']) {
                $job = getData('job', $data['job_id'], true);
                $application->setJob($job);
            }
            
            return $application;
            
        case 'suggestion':
        case 'suggestions':
            return new Suggestion(
                $data['id'],
                $data['job_id'] ?? null,
                $data['candidate_id'] ?? null,
                $data['company_id'] ?? null
            );
            
        case 'country':
        case 'countries':
            return new Country(
                $data['id'],
                $data['name'],
                $data['code'] ?? null,
                $data['flag_url'] ?? null,
                $data['default_language'] ?? null,
                $data['currency_symbol'] ?? null,
                $data['fr_name'] ?? null,
                $data['en_name'] ?? null,
                $data['de_name'] ?? null,
                $data['es_name'] ?? null,
                $data['it_name'] ?? null,
                $data['nl_name'] ?? null,
                $data['pt_name'] ?? null
            );
            
        case 'activity':
        case 'activities':
            return new Activity(
                $data['id'],
                $data['name'],
                $data['fr_name'] ?? null,
                $data['en_name'] ?? null,
                $data['de_name'] ?? null,
                $data['es_name'] ?? null,
                $data['it_name'] ?? null,
                $data['nl_name'] ?? null,
                $data['pt_name'] ?? null,
                $data['description'] ?? null
            );
            
        default:
            // Return raw data if no mapping exists
            return (object) $data;
    }
}

/**
 * Get data with custom WHERE conditions
 * @param string $table Table name
 * @param array $conditions Associative array of column => value
 * @param bool $asObject Convert to objects if true
 * @return array Array of results
 */
function getDataWhere($table, $conditions, $asObject = true) {
    try {
        // Get database connection
        $pdo = getDatabaseConnection();
        
        // Build WHERE clause
        $whereParts = [];
        foreach ($conditions as $column => $value) {
            $whereParts[] = "$column = :$column";
        }
        $whereClause = implode(' AND ', $whereParts);
        
        // Prepare SQL query
        $sql = "SELECT * FROM $table WHERE $whereClause";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        
        // Execute query
        $stmt->execute();
        
        // Fetch all data
        $results = $stmt->fetchAll();
        
        // Convert to objects if requested
        if ($asObject && !empty($results)) {
            $objects = [];
            foreach ($results as $data) {
                $obj = mapToObject($table, $data);
                if ($obj !== null) {
                    $objects[] = $obj;
                }
            }
            return $objects;
        }
        
        return $results;
        
    } catch (PDOException $e) {
        error_log("Get data with conditions error from table $table: " . $e->getMessage());
        return [];
    }
}

// Point d'entrée pour les requêtes HTTP GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['table'])) {
    header('Content-Type: application/json');
    
    $table = $_GET['table'];
    $allowedTables = ['degree', 'language', 'personality', 'hobbie', 'skill', 'mission', 'knowledge', 'contact', 'company', 'requirement', 'job', 'account', 'preference', 'application', 'suggestion', 'country', 'activity'];
    
    if (!in_array($table, $allowedTables)) {
        echo json_encode([
            'success' => false,
            'error' => 'Table non autorisée'
        ]);
        exit;
    }
    
    try {
        // Récupérer les données sans conversion en objets
        $data = getAllData($table, false);
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Erreur: ' . $e->getMessage()
        ]);
    }
    exit;
}
