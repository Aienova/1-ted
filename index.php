<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - Apportage d'affaire</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<form action="declassified.php" method="post">
    <div class="cv-container">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <div class="profile-photo"><input type="file" name="profile_photo" accept="image/*"></div>
            </div>
            <div class="header-right">
                <h1 class="title"><input type="text" name="name" placeholder="Nom Prénom"></h1>
                <h1 class="title"><input type="text" name="position" placeholder="LE POSTE"></h1>
            </div>
        </header>

        <!-- Info Section -->
        <section class="info-section">
            <div class="personal-info">
                <p><strong>Age :</strong> <input type="text" name="age" placeholder="Age"></p>
                <p><strong>Sexe :</strong> <input type="text" name="gender" placeholder="Sexe"></p>
                <p><strong>Niveau d'étude :</strong> <input type="text" name="education_level" placeholder="Niveau d'étude"></p>
                <p><strong>Langue(s) :</strong> <input type="text" name="languages" placeholder="Langue(s)"></p>
            </div>
            <div class="job-info">
                <p><strong>Localisation :</strong> <input type="text" name="location" placeholder="Localisation"></p>
                <p><strong>Téléphone :</strong> <input type="text" name="phone" placeholder="Téléphone"></p>
                <p><strong>Email :</strong> <input type="text" name="email" placeholder="Email"></p>
                <p><strong>Véhicule :</strong> <input type="text" name="vehicle" placeholder="Véhicule"></p>
                <p><strong>Prétention :</strong> <input type="text" name="expectation" placeholder="Prétention"></p>
                <p><strong>Type de contract :</strong> <input type="text" name="contract_type" placeholder="Type de contract"></p>
            </div>
        </section>

        <!-- Description and Skills -->
        <section class="main-content">
            <div class="description">
                <h2>Description :</h2>
                <p><textarea name="description" placeholder="Description"></textarea></p>
            </div>
            <div class="skills">
                <h2>Compétences clés :</h2>
                <ul>
                    <li><input type="text" name="skill1" placeholder="Compétence 1"></li>
                    <li><input type="text" name="skill2" placeholder="Compétence 2"></li>
                    <li><input type="text" name="skill3" placeholder="Compétence 3"></li>
                    <li><input type="text" name="skill4" placeholder="Compétence 4"></li>
                </ul>
            </div>
        </section>

        <!-- Experience and Formation -->
        <section class="experience-formation">
            <div class="experience">
                <h2>Experiences professionnels</h2>
                <div class="timeline-item">
                    <p class="period"><span class="date"><input type="number" name="start-period" placeholder="2026"> - <input type="number" name="end-period" placeholder="2026"></span> <input type="text" name="position_occupied" placeholder="Poste occupée"></p>
                    <p><strong>Missions :</strong> <input type="text" name="missions" placeholder="Missions"></p>
                </div>
            </div>
            <div class="formation">
                <h2>Formations</h2>
                <div class="timeline-item">
                    <p class="period"><span class="date"><input type="number" name="start-period-formation" placeholder="2025"> - <input type="number" name="end-period-formation" placeholder="2026"></span> <input type="text" name="status" placeholder="Formation suivi"></p>
                    <p><strong>Missions :</strong> <input type="text" name="learning" placeholder="Connaissances"></p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-logo">
                <img src="image/aienova.png" alt="AIEE NOVA Logo">
            </div>
            <div class="footer-text">
                <p>Pour plus d'informations sur ce profil, n'hésitez pas à consulter ce Qr Code, pour voir ses réseaux sociaux ou son site web : </p>
            </div>
            <div class="footer-qr">
                <input type="file" name="qr_code" accept="image/*">
        </footer>
    </div>
    </form>
</body>
</html>
