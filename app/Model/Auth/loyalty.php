<?php

class LoyaltySystem
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function earnPoints($userId, $points)
    {
    // Code pour attribuer des points à un utilisateur
    $query = $this->pdo->prepare("UPDATE users SET pts_fidelite = pts_fidelite + :points WHERE id_user = :user_id");
    $query->execute([
        'user_id' => $userId,
        'points' => $points,
    ]);
    }
    
    
    public function processOrder($userId, $orderAmount)
    {
        // Calcul du nombre de points à attribuer pour chaque 10 euros d'achat
        $pointsEarned = floor($orderAmount / 10);

        // Vérification pour s'assurer que l'utilisateur a gagné des points
        if ($pointsEarned > 0) {
            $this->earnPoints($userId, $pointsEarned);
        }
    }

    


    public function redeemPoints($userId, $points)
    {
        // Code pour utiliser les points par l'utilisateur
        $query = $this->pdo->prepare("UPDATE users SET pts_fidelite = pts_fidelite - :points WHERE user_id = :user_id AND pts_fidelite >= :points");
        $query->execute([
            'user_id' => $userId,
            'points' => $points,
        ]);

        $affectedRows = $query->rowCount();

        if ($affectedRows > 0) {
            // L'utilisateur avait suffisamment de points, récompensez-le ici
            $this->giveReward($userId, $points);
        }
    }

    private function giveReward($userId, $points)
    {
        // Code pour attribuer une récompense à l'utilisateur
        // Dans cet exemple, chaque point vaut 1 euro
        $rewardAmount = $points;
        $query = $this->pdo->prepare("UPDATE users SET account_balance = account_balance + :reward_amount WHERE id = :user_id");
        $query->execute([
            'user_id' => $userId,
            'reward_amount' => $rewardAmount,
        ]);
    }
}

// Exemple d'utilisation
$loyaltySystem = new LoyaltySystem($pdo);

// Exemple : un utilisateur avec l'ID 1 effectue une commande de 35 euros
$loyaltySystem->processOrder(1, 35);

// Vous pouvez appeler processOrder pour chaque commande effectuée sur votre site pour mettre à jour les points de fidélité.

?>
