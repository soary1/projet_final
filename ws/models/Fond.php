<?php
require_once __DIR__ . '/../db.php';

class Fond
{
    public static function getDisponibleAtDate(string $date): float
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) as total FROM banque_fond WHERE date_ajout <= ?");
        $stmt->execute([$date]);
        $totalFond = $stmt->fetch()['total'];

        $stmt2 = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) as total FROM banque_pret WHERE statut = 'valide' AND date_demande <= ?");
        $stmt2->execute([$date]);
        $totalPret = $stmt2->fetch()['total'];

        return $totalFond - $totalPret;
    }

    public static function getTotalFondsAvant(string $mois): float {
        $pdo = getDB();

        $stmt = $pdo->prepare("SELECT SUM(montant) as total FROM banque_fond WHERE DATE_FORMAT(date_ajout, '%Y-%m') <= ?");
        $stmt->execute([$mois]);
        return floatval($stmt->fetch()['total'] ?? 0);
    }

}
