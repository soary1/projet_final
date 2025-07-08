<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Comparaison des Simulations</title>
</head>
<body>
    <h1>Comparaison des Simulations de Prêts</h1>

    <?php if (count($simulations) > 1): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Taux</th>
                    <th>Durée</th>
                    <th>Intérêt Mensuel</th>
                    <th>Intérêt Composé</th>
                    <th>Assurance</th>
                    <th>Délai défaut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($simulations as $index => $simulation): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($simulation['id_client']) ?></td>
                        <td><?= number_format($simulation['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= $simulation['taux_annuel'] ?>%</td>
                        <td><?= $simulation['duree_mois'] ?> mois</td>
                        <td><?= number_format($simulation['interet_mensuel'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($simulation['interet_total'], 0, ',', ' ') ?> Ar</td>
                        <td><?= $simulation['assurance'] ?>%</td>
                        <td><?= $simulation['delai_defaut'] ?> mois</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Pas assez de simulations sélectionnées pour la comparaison.</p>
    <?php endif; ?>
</body>
</html>
