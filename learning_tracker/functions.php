<?php
function getAllTopics($pdo, $filter = null) {
    $sql = "SELECT * FROM belajar";
    
    if ($filter && in_array($filter, ['Belum Mulai', 'Sedang Belajar', 'Selesai'])) {
        $sql .= " WHERE status = :status";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['status' => $filter]);
    } else {
        $stmt = $pdo->query($sql);
    }
    
    return $stmt->fetchAll();
}

function addTopic($pdo, $topik, $status) {
    $sql = "INSERT INTO belajar (topik, status) VALUES (:topik, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['topik' => $topik, 'status' => $status]);
}

function updateTopic($pdo, $id, $topik, $status) {
    $sql = "UPDATE belajar SET topik = :topik, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id, 'topik' => $topik, 'status' => $status]);
}

function deleteTopic($pdo, $id) {
    $sql = "DELETE FROM belajar WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}
?>