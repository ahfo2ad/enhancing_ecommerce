<?php

session_start();

$pageTitle = "items";

if (isset($_SESSION["Username"])) {

    include "initialize.php";

    $do = (isset($_GET["do"])) ? $_GET["do"] : "manage";

    if ($do == "manage") {

        $stmt = $db->prepare("SELECT items.*, categories.Name AS categ_name, users.Username FROM items
                                    INNER JOIN categories ON categories.ID = items.Category_ID
                                    INNER JOIN users ON users.UserID = items.User_ID
                                    ORDER BY itemID DESC");

        $stmt->execute();
        $itms = $stmt->fetchAll();

        if (!empty($itms)) {

?>

            <div class="container users-page">
                <h1 class="text-center"> Manage Items </h1>
                <div class="table-responsive">
                    <table class="main table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td>Images</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category Name</td>
                            <td>Username</td>
                            <td>control</td>
                        </tr>
                        <?php

                        foreach ($itms as $itm) {

                            echo "<tr>";
                            echo "<td>" . $itm["itemID"] . "</td>";
                            // echo "<td><img src='uploads/items/" . $itm["Image"] . "'></td>";
                            echo "<td>";
                                if(! empty($itm['Image'])) {

                                    echo "<img src='uploads/items/" . $itm['Image'] . "'>";
                                }
                                else {

                                    echo "<img src='1.png'>";
                                }
                            echo "</td>";
                            echo "<td>" . $itm["Name"] . "</td>";
                            echo "<td>" . $itm["Description"] . "</td>";
                            echo "<td>" . $itm["Price"] . "</td>";
                            echo "<td>" . $itm["Date"] . "</td>";
                            echo "<td>" . $itm["categ_name"] . "</td>";
                            echo "<td>" . $itm["Username"] . "</td>";
                            echo "<td>
                                            <a href='items.php?do=edit&itemid=" . $itm["itemID"] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=delete&itemid=" . $itm["itemID"] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";
                            // check  if member RegisterStatus = 0 or not

                            if ($itm["approving"] == 0) {

                                echo "<a href='items.php?do=approve&itemid=" . $itm["itemID"] . "' 
                                            class='btn btn-info activate'> <i class='fa fa-check'></i> Approve</a>";
                            }
                            echo  "</td>";
                            echo "</tr>";
                        }

                        ?>

                    </table>
                </div>
                <a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item </a>
            </div>

        <?php
        } else {

            echo '<div class="container">';
            echo '<div class="alert alert-info" role="alert">There\'s no data to show</div>';
            echo '<a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item </a>';
            echo '</div>';
        }

        ?>

    <?php

    } elseif ($do == "add") { ?>

        <!-- add new item  -->

        <div class="container users-page item-page">
            <h1 class="text-center"> add item </h1>
            <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
                <!-- start category field name  -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" required="required" placeholder="item name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" required="required" placeholder="description of the item">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="price" required="required" placeholder="item price">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="country" required="required" placeholder="country of made">
                    </div>
                </div>
                <!-- start images  -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">images</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" class="form-control" name="Image" required="required">
                    </div>
                </div>
                <!-- end images  -->
                <!-- start status  -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
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
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">..</option>
                            <?php

                            // old function

                            // $stmt2 = $db->prepare("SELECT * FROM categories");
                            // $stmt2->execute();
                            // $categs = $stmt2->fetchAll();

                            // new global getall function

                            $maincategs = getall("*", "categories", "WHERE Parent = 0", "", "ID");

                            foreach ($maincategs as $categ) {

                                echo '<option value="' . $categ["ID"] . '">' . $categ["Name"] . '</option>';

                                // showing child categories

                                $childcategs = getall("*", "categories", "WHERE Parent = {$categ['ID']}", "", "ID");

                                foreach ($childcategs as $childcateg) {

                                    echo '<option value="' . $childcateg["ID"] . '"> - ' . $childcateg["Name"] . '</option>';
                                }
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <!-- end category  -->
                <!-- start user  -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">User</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="user">
                            <option value="0">..</option>
                            <?php

                            // old function

                            // $stmt = $db->prepare("SELECT * FROM users");
                            // $stmt->execute();
                            // $users = $stmt->fetchAll();

                            // new global getall function

                            $users = getall("*", "users", "", "", "UserID", "ASC");

                            foreach ($users as $user) {

                                echo '<option value="' . $user["UserID"] . '">' . $user["Username"] . '</option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <!-- end user  -->
                <!-- start tags  -->

                <div class="form-group">
                    <label class="col-sm-2 control-label">tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" data-role="tagsinput" class="form-control" name="tags" placeholder="separeted by comma ( , )">
                    </div>
                </div>
                <!-- end tags  -->

                <!-- end form code  -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 col-md-2">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>


        <?php

    } elseif ($do == "insert") {   // insert item page

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            echo '<h1 class="text-center"> update items</h1>';
            echo "<div class='container'>";

            //upload

            $itemimgName = $_FILES["Image"]["name"];
            $itemimgType = $_FILES["Image"]["type"];
            $itemimgTmp  = $_FILES["Image"]["tmp_name"];
            $itemimgSize = $_FILES["Image"]["size"];

                // allowed imag extensions

            $imgExtensions = array("png", "jpeg", "jpg", "gif");

            $convertname = explode('.', $itemimgName);

            $filteredname = strtolower(end($convertname));



            $name            = $_POST["name"];
            $description     = $_POST["description"];
            $price           = $_POST["price"];
            $country         = $_POST["country"];
            $status          = $_POST["status"];
            $categ           = $_POST["category"];
            $user            = $_POST["user"];
            $tags            = $_POST["tags"];


            // validate the form

            $formErrors = array();

            if (empty($name)) {

                $formErrors[] = 'name can\'t be empty';
            }
            if (empty($description)) {

                $formErrors[] = 'description can\'t be empty';
            }
            if (empty($price)) {

                $formErrors[] = 'price can\'t be empty';
            }
            if (empty($country)) {

                $formErrors[] = 'country can\'t be empty';
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
            if ($status == 0) {

                $formErrors[] = 'status can\'t be zero (0)';
            }
            if ($user == 0) {

                $formErrors[] = 'user can\'t be zero (0)';
            }
            if ($categ == 0) {

                $formErrors[] = 'category can\'t be zero (0)';
            }
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }

            // uppdate database auto
            
            if (empty($formErrors)) {

                    // check the image uploaded

                $provement = rand(0, 9000000000) . "=" . $itemimgName;

                move_uploaded_file($itemimgTmp, 'uploads\items\\' . $provement);
                

                // insert item data in the database

                $stmt = $db->prepare("INSERT INTO items( Name, Description, Price, Date, Country_Made, Image, Status, Category_ID, User_ID, tags) 

                                            VALUES(:iname, :idesc, :iprice, now(), :icountry, :iitmimg, :istat, :icatid, :iuserid, :itags ) ");

                $stmt->execute(array(

                    "iname"        => $name,
                    "idesc"        => $description,
                    "iprice"       => $price,
                    "icountry"     => $country,
                    "iitmimg"      => $provement,
                    "istat"        => $status,
                    "icatid"       => $categ,
                    "iuserid"      => $user,
                    "itags"        => $tags

                ));

                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                redirect($themsg, "back");
            }
        } else {

            echo '<div class="container users-page">';

            // calling redirect function 

            $themsg = '<div class="alert alert-danger" role="alert">sorry you aren\'t allowed to be here directly </div>';

            redirect($themsg);

            echo '</div>';
        }

        echo "</div>";
    } elseif ($do == "edit") {

        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;   // if function shortly in one row

        $stmt = $db->prepare("SELECT * FROM items WHERE itemID = ?");

        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {

        ?>
            <!-- start html code  -->

            <div class="container users-page item-page">
                <h1 class="text-center"> edit item </h1>
                <form class="form-horizontal" action="?do=update" method="POST">
                    <!-- hidden input for id field  -->
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                    <!-- start category field name  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" required="required" placeholder="item name" value="<?php echo $item["Name"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" required="required" placeholder="description of the item" value="<?php echo $item["Description"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="price" required="required" placeholder="item price" value="<?php echo $item["Price"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="country" required="required" placeholder="country of made" value="<?php echo $item["Country_Made"] ?>">
                        </div>
                    </div>
                    <!-- start status  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="1" <?php if ($item["Status"] == 1) {
                                                        echo "selected";
                                                    } ?>>New</option>
                                <option value="2" <?php if ($item["Status"] == 2) {
                                                        echo "selected";
                                                    } ?>>like new</option>
                                <option value="3" <?php if ($item["Status"] == 3) {
                                                        echo "selected";
                                                    } ?>>used</option>
                                <option value="4" <?php if ($item["Status"] == 4) {
                                                        echo "selected";
                                                    } ?>>old</option>
                            </select>
                        </div>
                    </div>
                    <!-- end status  -->
                    <!-- start category  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">
                                <?php

                                $stmt2 = $db->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $categs = $stmt2->fetchAll();
                                foreach ($categs as $categ) {

                                    echo '<option value="' . $categ["ID"] . '"';
                                    if ($item["Category_ID"] == $categ["ID"]) {
                                        echo "selected";
                                    }
                                    echo '>' . $categ["Name"] . '</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end category  -->
                    <!-- start user  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">User</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="user">
                                <?php

                                $stmt = $db->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {

                                    echo '<option value="' . $user["UserID"] . '"';
                                    if ($item["User_ID"] == $user["UserID"]) {
                                        echo "selected";
                                    }
                                    echo '>' . $user["Username"] . '</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end user  -->
                    <!-- start tags  -->

                    <div class="form-group">
                        <label class="col-sm-2 control-label">tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" data-role="tagsinput" class="form-control" name="tags" placeholder="separate with comma ( , )" value="<?php echo $item["tags"] ?>">
                        </div>
                    </div>
                    <!-- end tags  -->

                    <!-- end form code  -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 col-md-2">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </form>

                <!-- show comments for items  -->

                <?php

                $stmt = $db->prepare("SELECT comments.*, users.Username AS username FROM comments
                                    -- INNER JOIN items ON items.itemID = comments.itemID
                                    INNER JOIN users ON users.UserID = comments.User_ID
                                    WHERE itemID = ? ");
                $stmt->execute(array($itemid));
                $row = $stmt->fetchAll();

                // check if there is comments for that item or not 

                if (!empty($row)) {

                ?>

                    <h1 class="text-center"> Comments for <?php echo $item["Name"] ?> </h1>
                    <div class="table-responsive">
                        <table class="main table table-bordered text-center">
                            <tr>

                                <td>comment</td>
                                <td>Adding date</td>
                                <td>username</td>
                                <td>control</td>
                            </tr>
                            <?php

                            foreach ($row as $rw) {

                                echo "<tr>";

                                echo "<td>" . $rw["comment"] . "</td>";
                                echo "<td>" . $rw["comment_Date"] . "</td>";
                                echo "<td>" . $rw["username"] . "</td>";
                                echo "<td>
                                            <a href='comments.php?do=edit&commentid=" . $rw["comment_ID"] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>
                                            <a href='comments.php?do=delete&commentid=" . $rw["comment_ID"] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";

                                // check  if comment Status = 0 or not

                                if ($rw["status"] == 0) {

                                    echo "<a href='comments.php?do=approve&commentid=" . $rw["comment_ID"] . "' class='btn btn-info activate'> <i class='fa fa-check'></i> Approve</a>";
                                }
                                echo  "</td>";
                                echo "</tr>";
                            }

                            ?>

                        </table>
                    </div>

                <?php } ?>

            </div>


<?php
        } else {

            //redirect function

            echo '<div class="container users-page">';

            $themsg = '<div class="alert alert-danger" role="alert">sorry no id here like that</div>';

            redirect($themsg);

            echo '</div>';
        }
    } elseif ($do == "update") {

        echo '<h1 class="text-center"> update item</h1>';
        echo "<div class='container'>";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id             = $_POST["itemid"];
            $name           = $_POST["name"];
            $description    = $_POST["description"];
            $price          = $_POST["price"];
            $country        = $_POST["country"];
            $status         = $_POST["status"];
            $category       = $_POST["category"];
            $user           = $_POST["user"];
            $tags           = $_POST["tags"];



            // validate the form

            $formErrors = array();

            if (empty($name)) {

                $formErrors[] = 'name can\'t be empty';
            }
            if (empty($description)) {

                $formErrors[] = 'description can\'t be empty';
            }
            if (empty($price)) {

                $formErrors[] = 'price can\'t be empty';
            }
            if (empty($country)) {

                $formErrors[] = 'country can\'t be empty';
            }
            if ($status == 0) {

                $formErrors[] = 'status can\'t be zero (0)';
            }
            if ($category == 0) {

                $formErrors[] = 'category can\'t be zero (0)';
            }
            if ($user == 0) {

                $formErrors[] = 'user can\'t be zero (0)';
            }
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }

            // uppdate database auto

            if (empty($formErrors)) {

                $stmt = $db->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, 
                                            Country_Made = ?, Status = ?, Category_ID = ?, User_ID = ?, tags = ?  WHERE itemID = ?");
                $stmt->execute(array($name, $description, $price, $country, $status, $category, $user, $tags, $id));

                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                // calling redirect functon and the seconds will be 3s for the default
                redirect($themsg, "back");
            }
        } else {
            // calling redirect functon and rhe seconds will be 3s for the default

            $themsg = '<div class="alert alert-danger" role="alert">sorry u r not allowed here</div>';

            redirect($themsg);
        }

        echo "</div>";
        
    } elseif ($do == "delete") {

        // delete mamber from database page

        echo '<h1 class="text-center"> Delete Item</h1>';
        echo "<div class='container'>";

        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;   // if function shortly in one row

        // code for checkitem function

        $check = checkItem("itemID", "items", $itemid);

        if ($check > 0) {

            $stmt = $db->prepare("DELETE FROM items WHERE itemID = :itmid");
            $stmt->bindparam(":itmid", $itemid);
            $stmt->execute();

            // redirect function
            $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Deleted</div>";
            redirect($themsg, "back");
        } else {

            //  redirect function
            $themsg = '<div class="alert alert-danger" role="alert">not exist item id</div>';

            redirect($themsg);
        }

        echo "</div>";
    } elseif ($do == "approve") {

        // approve item from database page

        echo '<h1 class="text-center"> Approving items</h1>';
        echo "<div class='container'>";

        $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;   // if function shortly in one row

        // code for checkitem function


        $check = checkItem("itemID", "items", $itemid);

        if ($check > 0) {

            $stmt = $db->prepare("UPDATE items SET approving = 1 WHERE itemID = ? ");
            $stmt->execute(array($itemid));

            // redirect function
            $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Approved</div>";
            redirect($themsg, "back", 2);
        } else {

            //  redirect function
            $themsg = '<div class="alert alert-danger" role="alert">not exist ID item</div>';

            redirect($themsg);
        }

        echo "</div>";
    }

    include $temp_file . "footer.php";
} else {

    header("location: index.php");
    exit();
}

?>