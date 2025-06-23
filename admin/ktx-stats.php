<?php
require_once('../connect.php');

header('Content-Type: application/json');

$sql = "SELECT k.id, k.name, SUM(rb.total) as total FROM ktx k 
left JOIN room r on r.ktx_id=k.id
left JOIN room_bill rb on rb.room_id=r.id
where k.status>0 
GROUP by k.id
UNION
SELECT k.id, k.name, SUM(ub.room_fee) FROM ktx k 
LEFT JOIN room r on r.ktx_id=k.id
LEFT JOIN contract c on c.room_id=r.id
LEFT JOIN users_bill ub on ub.users_id=c.users_id
where k.status>0
GROUP by k.id  ";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $ok=1;
    foreach($data as $key=> $i){
        if($i['id']==$row['id']){
            $data[$key]['total']+=(int)$row['total'];
            $ok=0; break;
        }
    }
    if($ok==1){
        $data[] = [
        'id' => $row['id'],
        'ktx' => $row['name'],
        'total' => (int)$row['total']
        ];
    }
    
}

echo json_encode($data);
