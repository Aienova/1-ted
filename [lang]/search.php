<div class="search-container">
    <form class="search-form" id="searchForm" method="GET" action="">
        <?php 
        // Récupérer les paramètres GET pour pré-remplir le formulaire
        $searchKeyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
        $searchLocation = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
        ?>
        <?php foreach ($searchFields as $field): ?>
            <?php if ($field['balise'] === 'h1'): ?>
                <h1 class="search-title"><?php echo htmlspecialchars($field['title']); ?></h1>
            <?php elseif ($field['balise'] === 'input'): ?>
                <div class="form-group">
                    <label for="<?php echo $field['name']; ?>">
                        <?php echo htmlspecialchars($field['contentInside']['label']); ?>
                    </label>
                    <?php 
                    // Déterminer la valeur à pré-remplir
                    $fieldValue = '';
                    if ($field['name'] === 'keywords' && $searchKeyword) {
                        $fieldValue = $searchKeyword;
                    } elseif ($field['name'] === 'location' && $searchLocation) {
                        $fieldValue = $searchLocation;
                    }
                    ?>
                    <input 
                        type="<?php echo $field['type']; ?>"
                        id="<?php echo $field['name']; ?>"
                        name="<?php echo $field['name']; ?>"
                        placeholder="<?php echo htmlspecialchars($field['contentInside']['placeholder']); ?>"
                        value="<?php echo $fieldValue; ?>"
                        <?php echo $field['contentInside']['required'] ? 'required' : ''; ?>
                    >
                </div>
            <?php elseif ($field['balise'] === 'select'): ?>
                <div class="form-group">
                    <label for="<?php echo $field['name']; ?>">
                        <?php echo htmlspecialchars($field['contentInside']['label']); ?>
                    </label>
                    <?php 
                    // Déterminer si on doit bloquer le select selon la page
                    $isOffersPage = ($currentPageName === 'offers');
                    $isCandidatesPage = ($currentPageName === 'candidates');
                    $shouldDisable = $isOffersPage || $isCandidatesPage;
                    ?>
                    <select 
                        id="<?php echo $field['name']; ?>"
                        name="<?php echo $field['name']; ?>"
                        <?php echo $field['contentInside']['required'] ? 'required' : ''; ?>
                        <?php echo $shouldDisable ? 'disabled' : ''; ?>
                        class="<?php echo $shouldDisable ? 'disabled-select' : ''; ?>"
                    >
                        <?php 
                        foreach ($field['contentInside']['options'] as $option): 
                            // Déterminer si cette option doit être sélectionnée
                            $isSelected = false;
                            if ($isOffersPage && $option['value'] === 'joboffer') {
                                $isSelected = true;
                            } elseif ($isCandidatesPage && $option['value'] === 'candidate') {
                                $isSelected = true;
                            }
                        ?>
                            <option value="<?php echo htmlspecialchars($option['value']); ?>" <?php echo $isSelected ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($shouldDisable): ?>
                        <?php 
                        // Ajouter un champ caché pour envoyer la valeur quand le select est disabled
                        $hiddenValue = $isOffersPage ? 'joboffer' : 'candidate';
                        ?>
                        <input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo $hiddenValue; ?>">
                    <?php endif; ?>
                </div>
            <?php elseif ($field['balise'] === 'button'): ?>
                <button type="submit" class="search-button">
                    <?php echo htmlspecialchars($field['contentInside']['label']); ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </form>
</div>