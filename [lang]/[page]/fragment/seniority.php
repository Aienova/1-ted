<script>


    function getSeniorityColor(seniority) {
        switch (seniority) {
            case 0: // Apprenti
                return 'style="background-color: #E0F7FA; color: #00796B;"';
            case 1: // Junior
                return 'style="background-color: #FFF3E0; color: #FF6F00;"';
            case 2: // Confirmé
                return 'style="background-color: #E8F5E9; color: #388E3C;"';
            case 3: // Senior
                return 'style="background-color: #F3E5F5; color: #6A1B9A;"';
            default:
                return 'style="background-color: #171616; color: #d6c9c9;"'; // Non spécifié ou valeur hors limites
        }
    }

    function getSeniority(seniority, language) {
        // Vérifier si seniority est null, undefined ou hors limites
        if (seniority === null || seniority === undefined || seniority < 0 || seniority > 3) {
            switch (language) {
                case 'fr':
                    return 'Non spécifié';
                case 'en':
                    return 'Not specified';
                case 'es':
                    return 'No especificado';
                case 'de':
                    return 'Nicht angegeben';
                case 'it':
                    return 'Non specificato';
                case 'pt':
                    return 'Não especificado';
                default:
                    return 'Not specified';
            }
        }

        let fr_seniority = ['Apprenti', 'Junior', 'Confirmé', 'Senior'];
        let en_seniority = ['Apprentice', 'Junior', 'Confirmed', 'Senior'];
        let es_seniority = ['Aprendiz', 'Junior', 'Confirmado', 'Senior'];
        let de_seniority = ['Lehrling', 'Junior', 'Bestätigt', 'Senior'];
        let it_seniority = ['Apprendista', 'Junior', 'Confermato', 'Senior'];
        let pt_seniority = ['Aprendiz', 'Júnior', 'Confirmado', 'Sênior'];

        switch (language) {
            case 'fr':
                return fr_seniority[seniority];
            case 'en':
                return en_seniority[seniority];
            case 'es':
                return es_seniority[seniority];
            case 'de':
                return de_seniority[seniority];
            case 'it':
                return it_seniority[seniority];
            case 'pt':
                return pt_seniority[seniority];
            default:
                return 'Langue non prise en charge';
        }
    }
</script>