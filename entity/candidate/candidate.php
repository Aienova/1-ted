<?php

require_once __DIR__ . '/../../function/getData.php';

class Candidate {
    private $id;
    private $identityId;
    private $country; // ID du pays de résidence du candidat (optionnel)
    private $activity; // ID de l'activité professionnelle du candidat (optionnel)
    private $skillIds;
    private $personalityIds;
    private $socialId;
    private $demandId;
    private $experienceIds;
    private $formationIds;
    private $missionIds;
    private $mediaCode;
    private $approved;

    private $priority; // Champ de priorité du niveau de priorité du candidat, 0 par défaut

    private $seniority; // Ancienneté du candidat exemple "10" entier

    private $dateAvailability; // Date de disponibilité du candidat au format "YYYY-MM-DD"

    private $banned; // Champ pour indiquer si le candidat est banni, false par défaut

    private $banlength; // Durée du bannissement en jours , null par défaut, mettre -1 pour bannissement à vie

    private $banEndDate; // Date de fin du bannissement au format "YYYY-MM-DD" (optionnel), null par défaut, mettre 9999-12-31 pour bannissement à vie

    private $banReason; // Raison du bannissement (optionnel), null par défaut
    private $createdAt;
    private $updatedAt;
    private $startDatePriority; // Champ pour indiquer la date de début de la priorité du candidat, null par défaut, format "YYYY-MM-DD"

    private $endDatePriority; // Champ pour indiquer la date de fin de la priorité du candidat, null par défaut, format "YYYY-MM-DD"


    private $lengthPriority; // Champ de priorité de la durée du poste, 0 par défaut

    private $avatarUrl; // URL de l'avatar du candidat (optionnel)
    
    public function __construct($id = null, $identityId = null, $country = null, $activity = null, $skillIds = [], $personalityIds = [], $socialId = null, $demandId = null, $experienceIds = [], $formationIds = [], $missionIds = [], $mediaCode = null, $approved = null, $avatarUrl = null) {
        $this->id = $id;
        $this->identityId = $identityId;
        $this->country = $country;
        $this->activity = $activity;
        $this->socialId = $socialId;
        $this->avatarUrl = $avatarUrl;
        $this->demandId = $demandId;
        
        // Générer le mediaCode si non fourni
        $this->mediaCode = $mediaCode ?: $this->generateUniqueMediaCode();
        
        // Décoder JSON si c'est une chaîne
        if (is_string($skillIds)) {
            $this->skillIds = json_decode($skillIds, true) ?: [];
        } else {
            $this->skillIds = $skillIds;
        }
        
        if (is_string($personalityIds)) {
            $this->personalityIds = json_decode($personalityIds, true) ?: [];
        } else {
            $this->personalityIds = $personalityIds;
        }
        
        if (is_string($experienceIds)) {
            $this->experienceIds = json_decode($experienceIds, true) ?: [];
        } else {
            $this->experienceIds = $experienceIds;
        }
        
        if (is_string($formationIds)) {
            $this->formationIds = json_decode($formationIds, true) ?: [];
        } else {
            $this->formationIds = $formationIds;
        }
        
        if (is_string($missionIds)) {
            $this->missionIds = json_decode($missionIds, true) ?: [];
        } else {
            $this->missionIds = $missionIds;
        }
        
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
        $this->approved = $approved;
        $this->priority = 0;
        $this->seniority = null;
        $this->dateAvailability = null;
        $this->banned = false;
        $this->banlength = null;
        $this->banEndDate = null;
        $this->banReason = null;
        $this->startDatePriority = null;
        $this->endDatePriority = null;
        $this->lengthPriority = 0;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getIdentityId() {
        return $this->identityId;
    }
    
    public function getAvatarUrl() {
        return $this->avatarUrl;
    }

    public function getSkillIds() {
        return $this->skillIds;
    }
    
    public function getPersonalityIds() {
        return $this->personalityIds;
    }
    
    public function getSocialId() {
        return $this->socialId;
    }
    
    public function getDemandId() {
        return $this->demandId;
    }
    
    public function getExperienceIds() {
        return $this->experienceIds;
    }
    
    public function getFormationIds() {
        return $this->formationIds;
    }
    
    public function getMissionIds() {
        return $this->missionIds;
    }


    public function getCountry() {
        return $this->country;
    }

    public function getActivity() {
        return $this->activity;
    }

    public function getMediaCode() {
        return $this->mediaCode;
    }

    public function isApproved() {
        return $this->approved;
    }
    
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getSeniority() {
        return $this->seniority;
    }

    public function getDateAvailability() {
        return $this->dateAvailability;
    }

    public function isBanned() {
        return $this->banned;
    }

    public function getBanlength() {
        return $this->banlength;
    }

    public function getBanEndDate() {
        return $this->banEndDate;
    }

    public function getBanReason() {
        return $this->banReason;
    }

    public function getStartDatePriority() {
        return $this->startDatePriority;
    }

    public function getEndDatePriority() {
        return $this->endDatePriority;
    }

    public function getLengthPriority() {
        return $this->lengthPriority;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }
    
    public function setSkillIds($skillIds) {
        $this->skillIds = $skillIds;
    }
    
    public function setPersonalityIds($personalityIds) {
        $this->personalityIds = $personalityIds;
    }
    
    public function setSocialId($socialId) {
        $this->socialId = $socialId;
    }

    public function setAvatarUrl($avatarUrl) {
        $this->avatarUrl = $avatarUrl;
    }

    public function setDemandId($demandId) {
        $this->demandId = $demandId;
    }
    
    public function setExperienceIds($experienceIds) {
        $this->experienceIds = $experienceIds;
    }
    
    public function setFormationIds($formationIds) {
        $this->formationIds = $formationIds;
    }
    
    public function setMissionIds($missionIds) {
        $this->missionIds = $missionIds;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function setActivity($activity) {
        $this->activity = $activity;
    }

    public function setMediaCode() {
        $this->mediaCode = $this->generateUniqueMediaCode();
    }

    public function setApproved($approved) {
        $this->approved = $approved;
    }

    public function setPriority($priority) {
        $this->priority = $priority ?? 0;
    }

    public function setSeniority($seniority) {
        $this->seniority = $seniority;
    }

    public function setDateAvailability($dateAvailability) {
        $this->dateAvailability = $dateAvailability;
    }

    public function setBanned($banned) {
        $this->banned = $banned ?? false;
    }

    public function setBanlength($banlength) {
        $this->banlength = $banlength;
    }

    public function setBanEndDate($banEndDate) {
        $this->banEndDate = $banEndDate;
    }

    public function setBanReason($banReason) {
        $this->banReason = $banReason;
    }

    public function setStartDatePriority($startDatePriority) {
        $this->startDatePriority = $startDatePriority;
    }

    public function setEndDatePriority($endDatePriority) {
        $this->endDatePriority = $endDatePriority;
    }

    public function setLengthPriority($lengthPriority) {
        $this->lengthPriority = $lengthPriority ?? 0;
    }

    /**
     * Génère un code média unique de 12 caractères
     * Mélange des lettres aléatoires avec des chiffres extraits de la date/heure de création
     * @return string Code unique de 12 caractères
     */
    private function generateUniqueMediaCode() {
        // Extraire tous les chiffres de la date/heure de création (YmdHis)
        $timestamp = date('YmdHis'); // Ex: 20260203143052
        $digits = str_split($timestamp); // Tableau de chiffres
        
        // Générer des lettres aléatoires (majuscules et minuscules)
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetters = [];
        for ($i = 0; $i < 6; $i++) {
            $randomLetters[] = $letters[random_int(0, strlen($letters) - 1)];
        }
        
        // Sélectionner 6 chiffres aléatoires du timestamp
        shuffle($digits);
        $selectedDigits = array_slice($digits, 0, 6);
        
        // Mélanger les lettres et les chiffres
        $characters = array_merge($randomLetters, $selectedDigits);
        shuffle($characters);
        
        // Prendre les 12 premiers caractères pour garantir la longueur
        $mediaCode = implode('', array_slice($characters, 0, 12));
        
        return $mediaCode;
    }
    
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
    
    // Methods to add IDs (for one-to-many relationships)
    public function addSkillId($skillId) {
        if (!in_array($skillId, $this->skillIds)) {
            $this->skillIds[] = $skillId;
        }
    }
    
    public function addPersonalityId($personalityId) {
        if (!in_array($personalityId, $this->personalityIds)) {
            $this->personalityIds[] = $personalityId;
        }
    }
    
    public function addExperienceId($experienceId) {
        if (!in_array($experienceId, $this->experienceIds)) {
            $this->experienceIds[] = $experienceId;
        }
    }
    
    public function addFormationId($formationId) {
        if (!in_array($formationId, $this->formationIds)) {
            $this->formationIds[] = $formationId;
        }
    }
    
    public function addMissionId($missionId) {
        if (!in_array($missionId, $this->missionIds)) {
            $this->missionIds[] = $missionId;
        }
    }
    
    // Methods to remove IDs
    public function removeSkillId($skillId) {
        $key = array_search($skillId, $this->skillIds);
        if ($key !== false) {
            unset($this->skillIds[$key]);
            $this->skillIds = array_values($this->skillIds);
        }
    }
    
    public function removePersonalityId($personalityId) {
        $key = array_search($personalityId, $this->personalityIds);
        if ($key !== false) {
            unset($this->personalityIds[$key]);
            $this->personalityIds = array_values($this->personalityIds);
        }
    }
    
    public function removeExperienceId($experienceId) {
        $key = array_search($experienceId, $this->experienceIds);
        if ($key !== false) {
            unset($this->experienceIds[$key]);
            $this->experienceIds = array_values($this->experienceIds);
        }
    }
    
    public function removeFormationId($formationId) {
        $key = array_search($formationId, $this->formationIds);
        if ($key !== false) {
            unset($this->formationIds[$key]);
            $this->formationIds = array_values($this->formationIds);
        }
    }
    
    public function removeMissionId($missionId) {
        $key = array_search($missionId, $this->missionIds);
        if ($key !== false) {
            unset($this->missionIds[$key]);
            $this->missionIds = array_values($this->missionIds);
        }
    }
    
    // Methods to retrieve full objects from database
    
    /**
     * Get the full Identity object from database
     * @return Identity|null
     */
    public function getIdentity() {
        if ($this->identityId === null) {
            return null;
        }
        return getData('identity', $this->identityId, true);
    }

    /**
     * Get the full Country object from database
     * @return Country|null
     */
    public function getCountryObject() {
        if ($this->country === null) {
            return null;
        }
        return getData('country', $this->country, true);
    }

    /**
     * Get the full Activity object from database
     * @return Activity|null
     */
    public function getActivityObject() {
        if ($this->activity === null) {
            return null;
        }
        return getData('activity', $this->activity, true);
    }
    
    /**
     * Get all Skill objects from database
     * @return array Array of Skill objects
     */
    public function getSkills() {
        if (empty($this->skillIds)) {
            return [];
        }
        
        $skills = [];
        foreach ($this->skillIds as $skillId) {
            $skill = getData('skill', $skillId, true);
            if ($skill !== null) {
                $skills[] = $skill;
            }
        }
        return $skills;
    }
    
    /**
     * Get all Personality objects from database
     * @return array Array of Personality objects
     */
    public function getPersonalities() {
        if (empty($this->personalityIds)) {
            return [];
        }
        
        $personalities = [];
        foreach ($this->personalityIds as $personalityId) {
            $personality = getData('personality', $personalityId, true);
            if ($personality !== null) {
                $personalities[] = $personality;
            }
        }
        return $personalities;
    }
    
    /**
     * Get the full Social object from database
     * @return Social|null
     */
    public function getSocial() {
        if ($this->socialId === null) {
            return null;
        }
        return getData('social', $this->socialId, true);
    }
    
    /**
     * Get the full Demand object from database
     * @return Demand|null
     */
    public function getDemand() {
        if ($this->demandId === null) {
            return null;
        }
        return getData('demand', $this->demandId, true);
    }
    
    /**
     * Get all Experience objects from database
     * @return array Array of Experience objects
     */
    public function getExperiences() {
        if (empty($this->experienceIds)) {
            return [];
        }
        
        $experiences = [];
        foreach ($this->experienceIds as $expId) {
            $experience = getData('experience', $expId, true);
            if ($experience !== null) {
                $experiences[] = $experience;
            }
        }
        return $experiences;
    }
    
    /**
     * Get all Formation objects from database
     * @return array Array of Formation objects
     */
    public function getFormations() {
        if (empty($this->formationIds)) {
            return [];
        }
        
        $formations = [];
        foreach ($this->formationIds as $formId) {
            $formation = getData('formation', $formId, true);
            if ($formation !== null) {
                $formations[] = $formation;
            }
        }
        return $formations;
    }
    
    /**
     * Get all Mission objects from database
     * @return array Array of Mission objects
     */
    public function getMissions() {
        if (empty($this->missionIds)) {
            return [];
        }
        
        $missions = [];
        foreach ($this->missionIds as $missionId) {
            $mission = getData('mission', $missionId, true);
            if ($mission !== null) {
                $missions[] = $mission;
            }
        }
        return $missions;
    }
}
