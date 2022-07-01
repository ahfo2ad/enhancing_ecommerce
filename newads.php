<?php   

    session_start();

    $pageTitle = "NewAds";

    include "initialize.php";

    if(isset($_SESSION["user"])) {
        // print_r($_SESSION);

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $formErrors = array();

            //upload

            // $img = $_FILES["Image"];
            // print_r($img);

            // echo $_FILES["Image"]["name"] . "<br>";
            // echo $_FILES["Image"]["type"] . "<br>";
            // echo $_FILES["Image"]["tmp_name"] . "<br>";
            // echo $_FILES["Image"]["size"] . "<br>";

            $itemimgName = $_FILES["Image"]["name"];
            $itemimgType = $_FILES["Image"]["type"];
            $itemimgTmp  = $_FILES["Image"]["tmp_name"];
            $itemimgSize = $_FILES["Image"]["size"];

                // allowed imag extensions

            $imgExtensions = array("png", "jpeg", "jpg", "gif");

            $convertname = explode('.', $itemimgName);

            $filteredname = strtolower(end($convertname));

            $name        = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
            $price       = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
            $country     = filter_var($_POST["country"], FILTER_SANITIZE_STRING);
            $status      = filter_var($_POST["status"], FILTER_SANITIZE_NUMBER_INT);
            $category    = filter_var($_POST["category"], FILTER_SANITIZE_NUMBER_INT);
            $tags        = filter_var($_POST["tags"], FILTER_SANITIZE_STRING);

            if(empty($name)) {

                $formErrors[] = "name can't be empty";
            }
            if(empty($description)) {

                $formErrors[] = "description can't be empty";
            }
            if(empty($price)) {

                $formErrors[] = "price can't be empty";
            }
            if(empty($country)) {

                $formErrors[] = "country can't be empty";
            }
            if(! empty($itemimgName) && ! in_array($filteredname, $imgExtensions)) {
                    
                $formErrors[] = 'image extension not allowed';
            }
            if(empty($itemimgName)) {
                
                $formErrors[] = 'item image can\'t be empty';
            }
            if($itemimgSize > 4194304) {
                
                $formErrors[] = 'image can\'t be more than 4MB';
            }
            if($status == 0) {

                $formErrors[] = "status can't be empty";
            }
            if($category == 0) {

                $formErrors[] = "category can't be empty";
            }

            // uppdate database auto

            if(empty($formErrors)) {   
                
                
                // check the image uploaded

                $provement = rand(0, 9000000000) . "=" . $itemimgName;

                move_uploaded_file($itemimgTmp, 'admin-control\uploads\items\\' . $provement);
                

                // insert item data in the database

                $stmt = $db->prepare("INSERT INTO items( Name, Description, Price, Date, Country_Made, Image, Status, Category_ID, tags, User_ID) 

                                        VALUES(:iname, :idesc, :iprice, now(), :icountry, :iadsimg, :istat, :icatid, :itag, :iuserid ) ");

                $stmt->execute(array(

                    "iname"        => $name, 
                    "idesc"        => $description,
                    "iprice"       => $price, 
                    "icountry"     => $country,
                    "iadsimg"      => $provement,
                    "istat"        => $status,
                    "icatid"       => $category,
                    "itag"         => $tags,
                    "iuserid"      => $_SESSION["normid"]

                    ));

                if($stmt) {

                    // echo "item added successfully";

                    $msgsucces = "item added successfully";

                    // echo '<div class="alert alert-success alert-dismissible">';
                    //     echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                    //     echo '<strong>Success! </strong>' . "item added successfully";
                    // echo '</div>';
                }              

            }
        }
    
?>

    <h1 class="text-center">create new item</h1>
    <div class="ads">
        <div class="container">
            <div class="card">
                <div class="card-header bg-secondary">My Item Ads</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal adsform" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" enctype="multipart/form-data">
                                <!-- start category field name  -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" data-class=".live-name" name="name" required="required" placeholder="item name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" data-class=".live-descripe" name="description" required="required" placeholder="description of the item">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" data-class=".live-price" name="price" required="required" placeholder="item price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control" name="country" required="required" placeholder="country of made">
                                    </div>
                                </div>
                                <!-- start images  -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">images</label>
                                    <div class="col-sm-10 col-md-6">
                                        <input type="file" class="form-control live" data-class=".live-Image" name="Image" required="required">
                                    </div>
                                </div>
                                <!-- end images  -->
                                    <!-- start status  -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status" required="required" title="must choose one">
                                            <option value="0">..</option>
                                            <option value="1">New</option>
                                            <option value="2">like new</option>
                                            <option value="3">used</option>
                                            <option value="4">old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- end status  -->
                                <!-- start category  -->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Category</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category" required="required">
                                            <option value="0">..</option>
                                            <?php

                                                    // new global getall function
                                                $categs = getall("*", "categories", "", "", "ID");

                                                    //old function
                                                    
                                                // $stmt2 = $db->prepare("SELECT * FROM categories");
                                                // $stmt2->execute();
                                                // $categs = $stmt2->fetchAll();
                                                foreach($categs as $categ) {

                                                    echo '<option value="' . $categ["ID"] . '">' . $categ["Name"] . '</option>';
                                                }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- end category  -->
                                <!-- start tags  -->

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" data-role="tagsinput" class="form-control" name="tags" 
                                        placeholder="separeted by comma ( , )">
                                    </div>
                                </div>
                                <!-- end tags  -->
                            
                                <!-- end form code  -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10 col-md-9">
                                        <input type="submit" value="Add Item" class="btn btn-primary btn-block">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail box-item live-preview">
                                <span class="price">$<span class="live-price">0</span></span>
                                <img class="img-responsive" src="1.png">
                                <!-- <img src='admin-control/uploads/items/" . $categ['Image'] . "'> -->
                                
                                <div class="caption">
                                    <h3 class="live-name">Name</h3>
                                    <p class="live-descripe">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- start errors loop -->
                    <?php

                        if(! empty($formErrors)) {

                            foreach($formErrors as $error) {

                                echo '<div class="alert alert-danger alert-dismissible">';
                                    echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                    echo '<strong>Error! </strong>' . $error;
                                echo '</div>';

                            }
                        }
                        // if success 
                        if(isset($msgsucces)) {

                            echo '<div class="alert alert-success alert-dismissible">';
                                echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                echo '<strong>Success! </strong>' . $msgsucces;
                            echo '</div>';

                        }

                    ?>
                    <!-- end errors loop -->
                </div>
            </div>
        </div>
    </div>
    

<?php  

    }
    else {

        header("location: login.php");

        exit();
    }
    
    include $temp_file . "footer.php";
?>