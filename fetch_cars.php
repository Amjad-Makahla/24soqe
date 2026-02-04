<?php
include 'db.php';

$sql = "
  SELECT 
    cars.id,
    COALESCE(cars.brand, brands.name_ar) AS brand,
    COALESCE(cars.model, models.name_ar) AS model,
    cars.year,
    cars.price,
    cities.name_ar AS city,
    (SELECT image_path FROM car_images WHERE car_id = cars.id LIMIT 1) AS image
  FROM cars
  LEFT JOIN brands ON cars.brand_id = brands.id
  LEFT JOIN models ON cars.model_id = models.id
  LEFT JOIN cities ON cars.city_id = cities.id
  ORDER BY cars.created_at DESC
  LIMIT 15
";

$result = $conn->query($sql);

$cars = [];

while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cars, JSON_UNESCAPED_UNICODE);
