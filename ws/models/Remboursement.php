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

    // 2. Calculer la mensualité (annuité constante)
    $tauxMensuel = $tauxAnnuel / 100 / 12;
    $mensualite = ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$duree));
    $mensualite = round($mensualite, 2);

    // 3. Calculer la date d’échéance
    $dateEcheance = $dateDemande->modify("+$delai days")->format('Y-m-d');

    // 4. Enregistrement
    $st = $db->prepare("
        INSERT INTO banque_remboursement (id_pret, montant, date_paiement, date_echeance)
        VALUES (?, ?, ?, ?)
    ");
    return $st->execute([$idPret, $mensualite, $datePaiement, $dateEcheance]);
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
}
