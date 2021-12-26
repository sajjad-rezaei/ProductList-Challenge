<?php

//make sure all service up

echo shell_exec("mv ./public/index.php ./public/indexB.php");
echo shell_exec("echo 'installing in progress please refresh page some second later' >  ./public/index.php");

sleep("5");


echo shell_exec("composer install");
echo shell_exec("php bin/console doctrine:database:create");
echo shell_exec("php bin/console doctrine:schema:update --force");


$servername = "mysql8-service";
$username = "root";
$password = "12345";
$dbName = "productList";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";


    $stmt = $conn->prepare("SELECT * FROM product");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    if(empty($result) AND !$result){
        //read the json file
        $jsonProduct = file_get_contents("./install/products.json");
        $products = json_decode($jsonProduct , true);
        $sql = "";
        foreach ($products['products'] AS $product){
            //lets see if product is exist
            $catName = $product['category'];

            $stmt = $conn->prepare("SELECT * FROM category WHERE name = ? LIMIT 1");
            $stmt->execute([$catName]);
            $row = $stmt->fetch();
            $catId = 0;

            if(!$row and empty($row)){
                //we dont have the category lets insert
                $discount = 0;
                if($catName == "boots")
                    $discount = 30;
                $result = $conn->exec("INSERT INTO category (name , discount) VALUES ('{$catName}',{$discount})");

                if($result)
                    $catId = $conn->lastInsertId();
            }else
                //we have the category before
                $catId = $row['id'];

            $productName = $product['name'];
            $SKU = $product['sku'];
            $price = $product['price'];
            $discount = 0;
            if($SKU == "000003")
                $discount = 15;
            //add insert query
            $sql .= " ('{$productName}', '{$SKU}', '{$price}',{$discount},{$catId}),";

        }

        $result = false;
        if(!empty($sql)){
            //remove the last , from string
            $sql = rtrim($sql , ",");
            //concat the insert statement to fit multi insert
            $sql = "INSERT INTO product (name, sku, price , discount , category_id)
                VALUES " .$sql;
            //add data to database
            $result = $conn->exec($sql);
        }


        //lets insert product to db

    }




    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
echo shell_exec("mv ./public/index.php ./public/index_install.php");
echo shell_exec("rm ./public/index_install.php");
echo shell_exec("mv  ./public/indexB.php ./public/index.php");

echo "\n" . "*************** Done ***************";



