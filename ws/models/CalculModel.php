<?php
class CalculModel {

    /**
     * Calcul de l'intérêt simple total
     */
    public static function interetSimple(float $montant, float $tauxAnnuel, int $dureeMois): float {
        return $montant * ($tauxAnnuel / 100) * ($dureeMois / 12);
    }

    /**
     * Calcul des intérêts composés
     */
    public static function interetCompose(float $montant, float $tauxAnnuel, int $dureeMois): float {
        $tauxMensuel = $tauxAnnuel / 100 / 12;
        return $montant * (pow(1 + $tauxMensuel, $dureeMois) - 1);
    }

    /**
     * Montant total à rembourser avec intérêts simples
     */
    public static function montantTotalSimple(float $montant, float $tauxAnnuel, int $dureeMois): float {
        return $montant + self::interetSimple($montant, $tauxAnnuel, $dureeMois);
    }

    /**
     * Montant total à rembourser avec intérêts composés
     */
    public static function montantTotalCompose(float $montant, float $tauxAnnuel, int $dureeMois): float {
        return $montant + self::interetCompose($montant, $tauxAnnuel, $dureeMois);
    }

    /**
     * Mensualité simple : montant total / durée
     */
    public static function mensualiteSimple(float $montant, float $tauxAnnuel, int $dureeMois): float {
        $total = self::montantTotalSimple($montant, $tauxAnnuel, $dureeMois);
        return $total / $dureeMois;
    }

    /**
     * Mensualité composée (formule d'annuité constante)
     */
    public static function mensualiteComposee(float $montant, float $tauxAnnuel, int $dureeMois): float {
        $tauxMensuel = $tauxAnnuel / 100 / 12;
        return ($montant * $tauxMensuel) / (1 - pow(1 + $tauxMensuel, -$dureeMois));
    }

    /**
     * Calcul de l'assurance
     */
    public static function assuranceTotal(float $montant, float $tauxAssurance, int $dureeMois): float {
        return $montant * ($tauxAssurance / 100) * ($dureeMois / 12);
    }
}
