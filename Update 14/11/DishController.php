<?php

class DishController extends Controller{
    private $editID;
    # hiện thông tin món ăn 
    public function show(){
        $dish = $this->callmodel("DishDB");
        $dish = $dish->getDish($_GET['Id']);
        $this->callview("Home",["page"=>"Dish","dish"=>$dish]);
    }
    # thêm món ăn
    public function addDish() {
        if (isset($_POST['btnnewdish'])) {
            // lấy dữ liệu
            $name = $_POST['dishname'];
            $price = (int)$_POST['price'];
            $description = $_POST['description'];
            $type = $_POST['type'];
            // xử lí ảnh
            $file = $_FILES['image']['name'];
            $target_dir = "./public/img/dish/";
            $target_file = $target_dir . basename($file);
            $canUpload = true;
            $result = true;
            if (file_exists($target_file))
            {
                $canUpload = false;
            }
            // đưa vào DB
            if ($canUpload) {
                $dishDB = $this->callmodel("DishDB");
                $result = $dishDB->addDish($name, $price, $description, $type, $file);
                if ($result) {
                    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                }
            }
            else {
                $result = false;
            }
            // hiện kết quả
            $this->callview("Home", ['page' => "addDish", 'result' => $result]);
        }
    }
    # xóa món ăn
    public function removeDish() {
        $id = (int)$_GET['Id'];
        $dishDB = $this->callmodel("DishDB");
        // xóa ảnh
        $img = $dishDB->getImg($id);
        if (file_exists("./public/img/dish/".$img)) {
            unlink("./public/img/dish/".$img);
        }
        // xóa khỏi database
        $dishDB->removeDish($id);
        header('Location: index.php');
    }
    # sửa món ăn
    public function editDish() {
        $id = $_SESSION['editId'];
        $dishDB = $this->callmodel("DishDB");
        // xóa ảnh
        $img = $dishDB->getImg($id);
        unlink("./public/img/dish/".$img);
        if (isset($_POST['btneditdish'])) {
            // lấy dữ liệu
            $name = $_POST['dishname'];
            $price = (int)$_POST['price'];
            $description = $_POST['description'];
            $type = $_POST['type'];
            // xử lí ảnh
            $file = $_FILES['image']['name'];
            $target_dir = "./public/img/dish/";
            $target_file = $target_dir . basename($file);
            $canUpload = true;
            $result = true;
            if (file_exists($target_file))
            {
                $canUpload = false;
            }
            // đưa vào DB
            if ($canUpload) {
                $dishDB = $this->callmodel("DishDB");
                $result = $dishDB->editDish($id, $name, $price, $description, $type, $file);
                if ($result) {
                    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                }
            }
            else {
                $result = false;
            }
            // hiện kết quả
            var_dump($canUpload);
            $this->callview("Home", ['page' => "editDish", 'result' => $result]);
        }
    }
}

?>