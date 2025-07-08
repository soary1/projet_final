<?php
require_once __DIR__ . '/../db.php';

class Remboursement {

    public static function all(): array {
        return getDB()->query("
            SELECT r.*, u.nom AS nom_client
            FROM banque_remboursement r
            JOIN banque_pret p ON r.id_pret = p.id
            JOIN banque_client c ON p.id_client = c.id
            JOIN banque_utilisateur u ON c.id_utilisateur = u.id
            ORDER BY r.date_echeance ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function byPret(int $idPret): array {
        $st = getDB()->prepare("SELECT * FROM banque_remboursement WHERE id_pret = ? ORDER BY date_echeance ASC");
        $st->execute([$idPret]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(int $idPret, string $datePaiement): bool {
    $db = getDB();

    // 1. Récupérer les infos du prêt
    $stmt = $db->prepare("
        SELECT p.date_demande, p.montant AS montant_pret,
               tp.taux_interet, tp.duree_mois, tp.delai_defaut
        FROM banque_pret p
        JOIN banque_type_pret tp ON tp.id = p.id_type_pret
        WHERE p.id = ?
    ");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pret) return false;

    $montant = (float) $pret['montant_pret'];
    $tauxAnnuel = (float) $pret['taux_interet'];
    $duree = (int) $pret['duree_mois'];
    $delai = (int) $pret['delai_defaut'];
    $dateDemande = new DateTime($pret['date_demande']);

    // 2. Calcul de la mensualité (annuité constante)
    $tauxMensuel = $tauxAnnuel / 100 / 12;
    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$duree));
    $mensualite = round($mensualite, 2);

    // 3. Compter le nombre de remboursements déjà effectués
    $stmt = $db->prepare("SELECT COUNT(*) FROM banque_remboursement WHERE id_pret = ?");
    $stmt->execute([$idPret]);
    $remboursementsExistants = (int) $stmt->fetchColumn();

    // 4. Vérifier si tout est déjà payé
    if ($remboursementsExistants >= $duree) {
        return false; // plus rien à rembourser
    }

    // 5. Calculer la prochaine date d’échéance
    $dateEcheance = $dateDemande->modify("+$delai days")->modify("+{$remboursementsExistants} months");
    $dateEcheanceStr = $dateEcheance->format('Y-m-d');

    // 6. Insérer la prochaine échéance
    $st = $db->prepare("
        INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance)
        VALUES (?, ?, ?, ?)
    ");
    return $st->execute([$idPret, $mensualite, $datePaiement, $dateEcheanceStr]);
}

public static function rembourserNmois(int $idPret, int $nbMois): bool {
    $db = getDB();

    $stmt = $db->prepare("
        SELECT p.date_demande, p.montant AS montant_pret,
               tp.taux_interet, tp.duree_mois, tp.delai_defaut
        FROM banque_pret p
        JOIN banque_type_pret tp ON tp.id = p.id_type_pret
        WHERE p.id = ?
    ");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pret) return false;

    $montant = (float) $pret['montant_pret'];
    $tauxAnnuel = (float) $pret['taux_interet'];
    $duree = (int) $pret['duree_mois'];
    $delai = (int) $pret['delai_defaut'];
    $dateDemande = new DateTime($pret['date_demande']);

    // Mensualité
    $tauxMensuel = $tauxAnnuel / 100 / 12;
    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$duree));
    $mensualite = round($mensualite, 2);

    // Récupérer les échéances déjà payées
    $stmt = $db->prepare("SELECT date_echeance FROM banque_remboursement WHERE id_pret = ?");
    $stmt->execute([$idPret]);
    $existantes = array_map(fn($d) => (new DateTime($d))->format('Y-m-d'), $stmt->fetchAll(PDO::FETCH_COLUMN));

    $dateDepart = $dateDemande->modify("+$delai days");

    $insert = $db->prepare("
        INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance)
        VALUES (?, ?, ?, ?)
    ");

    $nbAjoute = 0;
    for ($i = 0, $mois = 0; $i < $duree && $nbAjoute < $nbMois; $i++) {
        $echeance = clone $dateDepart;
        $echeance->modify("+$i months");
        $dateStr = $echeance->format('Y-m-d');

        if (in_array($dateStr, $existantes)) continue;

        $insert->execute([
            $idPret,
            $mensualite,
            $dateStr,
            $dateStr
        ]);

        $nbAjoute++;
    }

    return true;
}

public static function rembourserTout(int $idPret): bool {
    $db = getDB();

    // Récupérer les infos du prêt
    $stmt = $db->prepare("
        SELECT p.date_demande, p.montant AS montant_pret,
               tp.taux_interet, tp.duree_mois, tp.delai_defaut
        FROM banque_pret p
        JOIN banque_type_pret tp ON tp.id = p.id_type_pret
        WHERE p.id = ?
    ");
    $stmt->execute([$idPret]);
    $pret = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pret) return false;

    $montant = (float) $pret['montant_pret'];
    $tauxAnnuel = (float) $pret['taux_interet'];
    $duree = (int) $pret['duree_mois'];
    $delai = (int) $pret['delai_defaut'];
    $dateDemande = new DateTime($pret['date_demande']);

    // Calcul de la mensualité (annuité constante)
    $tauxMensuel = $tauxAnnuel / 100 / 12;
    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$duree));
    $mensualite = round($mensualite, 2);

    // Récupérer les dates d'échéance déjà existantes
    $stmt = $db->prepare("
        SELECT date_echeance FROM banque_remboursement
        WHERE id_pret = ?
    ");
    $stmt->execute([$idPret]);
    $remboursementsExistants = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $datesExistantes = array_map(function ($d) {
        return (new DateTime($d))->format('Y-m-d');
    }, $remboursementsExistants);

    $dateEcheance = $dateDemande->modify("+$delai days");

    $insert = $db->prepare("
        INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance)
        VALUES (?, ?, ?, ?)
    ");

    for ($i = 0; $i < $duree; $i++) {
        $echeance = clone $dateEcheance;
        $echeance->modify("+$i months");
        $dateStr = $echeance->format('Y-m-d');

        // 💡 Sauter si déjà existant
        if (in_array($dateStr, $datesExistantes)) continue;

        $insert->execute([
            $idPret,
            $mensualite,
            $dateStr,     // simulé payé le jour d’échéance
            $dateStr
        ]);
    }

    return true;
}



    public static function delete(int $id): void {
        $st = getDB()->prepare("DELETE FROM banque_remboursement WHERE id = ?");
        $st->execute([$id]);
    }

    public static function getRetards(): array {
        return getDB()->query("
            SELECT r.*, DATEDIFF(r.date_paiement, r.date_echeance) AS jours_retard,
                   u.nom AS nom_client
            FROM banque_remboursement r
            JOIN banque_pret p ON r.id_pret = p.id
            JOIN banque_client c ON p.id_client = c.id
            JOIN banque_utilisateur u ON c.id_utilisateur = u.id
            WHERE r.date_paiement > r.date_echeance
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getInteretsParMois($dateDebut, $dateFin) {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT
                DATE_FORMAT(r.date_paiement, '%Y-%m') AS mois_paiement,
                SUM( (p.montant * t.taux_interet / 100) / t.duree_mois ) AS interet_mensuel_gagne,
                COUNT(*) AS nb_remboursements,
                SUM(CASE WHEN r.date_paiement > r.date_echeance THEN 1 ELSE 0 END) AS nb_retards
            FROM banque_remboursement r
            JOIN banque_pret p ON r.id_pret = p.id
            JOIN banque_type_pret t ON p.id_type_pret = t.id
            WHERE r.date_paiement BETWEEN ? AND ?
            GROUP BY mois_paiement
            ORDER BY mois_paiement ASC
        ");
        $stmt->execute([$dateDebut, $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getInteretsPrevusEtReels($dateDebut, $dateFin) {
        $db = getDB();

        $stmt = $db->prepare("
            SELECT 
                DATE_FORMAT(r.date_paiement, '%Y-%m') AS mois,
                SUM( (p.montant * t.taux_interet / 100) / t.duree_mois ) AS interet_reel,
                COUNT(*) AS nb_remboursements,
                SUM(CASE WHEN r.date_paiement > r.date_echeance THEN 1 ELSE 0 END) AS nb_retards
            FROM banque_remboursement r
            JOIN banque_pret p ON r.id_pret = p.id
            JOIN banque_type_pret t ON p.id_type_pret = t.id
            WHERE r.date_paiement BETWEEN ? AND ?
            GROUP BY mois
            ORDER BY mois ASC
        ");
        $stmt->execute([$dateDebut, $dateFin]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter l’intérêt prévu = intérêt réel si payé
        foreach ($data as &$ligne) {
            $ligne['interet_prevu'] = $ligne['interet_reel'];
        }

        return $data;
    }
}
