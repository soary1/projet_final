<?php
require_once __DIR__ . '/../db.php';

class Fond
{
    public static function getDisponibleAtDate(string $date): float
    {
        $pdo = getDB();

        // Total des fonds disponibles jusqu'à cette date
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) as total FROM banque_fond WHERE date_ajout <= ?");
        $stmt->execute([$date]);
        $totalFond = $stmt->fetch()['total'];

        // Total des prêts validés jusqu'à cette date
        $stmt2 = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) as total FROM banque_pret WHERE statut = 'valide' AND date_demande <= ?");
        $stmt2->execute([$date]);
        $totalPret = $stmt2->fetch()['total'];

        // Retourne la disponibilité réelle
        return $totalFond - $totalPret;
    }
}
