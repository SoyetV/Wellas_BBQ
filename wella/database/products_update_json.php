<?php
    function CreateProductsJSONData($MenuType, $dir, $dbConn) {
        $data = "SELECT * FROM products WHERE Type='$MenuType'";
        $results = $dbConn->query($data);

        if ($results->num_rows > 0) {
            while ($product = $results->fetch_assoc()) {
                $products[] = $product;
            }
            $encoded_data = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($dir, $encoded_data);
        } else {
            file_put_contents($dir, json_encode([]));
        }
    }
?>