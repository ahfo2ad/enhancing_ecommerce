<?php

session_start();

$pageTitle = "categories";

if (isset($_SESSION["Username"])) {

    include "initialize.php";

    $do = (isset($_GET["do"])) ? $_GET["do"] : "manage";

    if ($do == "manage") {

        $sort = "ASC";

        $sort_array = array("ASC", "DESC");
        if (isset($_GET["sort"]) && in_array($_GET["sort"], $sort_array)) {

            $sort = $_GET["sort"];
        }

        // $order-type = "Ordering";

        // $order-type_array = array("Ordering", "ID");
        // if(isset($_GET["sortid"]) && in_array($_GET["order-type"], $order-type_array)) {

        //     $order-type = $_GET["order-type"];
        // }

        $catgstmt = $db->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
        // $catgstmt = $db->prepare("SELECT * FROM categories ORDER BY ID $sortid");

        $catgstmt->execute();
        $catgrow = $catgstmt->fetchAll();

?>

        <div class="container categories-page">
            <h1 class="text-center"> Manage categories </h1>
            <div class="panel panel-default">

                <div class="panel-heading">
                    <i class="fas fa-th-large"></i> manage categories
                    <div class="ordering pull-right">Filter :
                        <a class="<?php if ($sort == "ASC") {
                                        echo 'active';
                                    } ?>" href="?sort=ASC">Asc <i class="fas fa-sort-amount-down-alt"></i></a> |
                        <a class="<?php if ($sort == "DESC") {
                                        echo 'active';
                                    } ?>" href="?sort=DESC">Desc <i class="fas fa-sort-amount-down"></i></a>
                        | View :
                        <!-- <span class="active" data-view="full">full</span> | -->
                        <span class="active" data-view="full"><i class="fas fa-bars"></i></span> |
                        <span data-view="classic"><i class="fas fa-minus"></i></span>
                        <!-- <span data-view="classic">Classic</span> -->
                    </div>
                </div>
                <div class="panel-body">

                    <?php

                    foreach ($catgrow as $cat) {

                        echo "<div class='categ'>";
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='categories.php?do=edit&categid=" . $cat['ID'] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>";
                        echo "<a href='categories.php?do=delete&categid=" . $cat['ID'] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";
                        echo "</div>";
                        echo "<h3>" . $cat["Name"] . "</h3>";
                        echo "<div class='full-view'>";
                        echo "<p>";
                        if ($cat["Description"] == "") {

                            echo "this category has no description";
                        } else {

                            echo $cat["Description"];
                        }
                        echo "</p>";

                        if ($cat["Visibility"] == 1) {

                            echo "<span class='visibility'><i class='far fa-eye-slash'></i> Hidden</span>";
                        } else {
                            echo "<span class='visibility'><i class='far fa-eye'></i> Visible</span>";
                        }
                        if ($cat["Allow_Comment"] == 1) {

                            echo "<span class='comm'><i class='fas fa-close'></i> Comment Disabled</span>";
                        } else {
                            echo "<span class='comm'><i class='fas fa-universal-access'></i> Comment Enabled</span>";
                        }
                        if ($cat["Allow_Ads"] == 1) {

                            echo "<span class='advertises'><i class='fas fa-close'></i> Ads Disabled</span>";
                        } else {
                            echo "<span class='advertises'><i class='fas fa-ad'></i> Ads Enabled</span>";
                        }

                        echo "</div>";

                        // new global getall function

                        $childcatgrs = getall("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID");

                        if (!empty($childcatgrs)) { 

                            echo'<h4 class="subcat">Sub Categories</h4>';
                                        echo '<ul class="list-unstyled listed">';
                                            foreach($childcatgrs as $childcat) {
                                                
                                                echo "<li class='showlinks'>
                                                    <a href='categories.php?do=edit&categid=" . $childcat['ID'] . "'>" . $childcat['Name'] . "</a>
                                                    <a href='categories.php?do=delete&categid=" . $childcat['ID'] . "' class='show-delete confirm'> <i class='fa fa-close'></i> Delete </a>
                                                </li>";
                                                
                                            }
                                        echo '</ul>';
                            
                        }

                        echo "</div>";
                    }

                    ?>

                </div>
            </div>
            <a href="categories.php?do=add" class="btn btn-primary cat-butn"><i class="fa fa-plus"></i> Add New Category </a>
        </div>

    <?php
    } elseif ($do == "add") { ?>

        <!-- add new category  -->

        <div class="container users-page">
            <h1 class="text-center"> add category </h1>
            <form class="form-horizontal" action="?do=insert" method="POST">
                <!-- start category field name  -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" required="required" autocomplete="off" placeholder="category name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" placeholder="description of the category">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">parent</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent" title="choose main category if it is a parent category">
                            <option value="0">main category</option> <!-- choose main category if it is parent category -->
                            <?php

                            // new global getall function

                            $selectcats = getall("*", "categories", "WHERE Parent = 0", "", "ID");

                            foreach ($selectcats as $selectcat) {

                                echo "<option value='" . $selectcat["ID"] . "'>" . $selectcat["Name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="ordering" placeholder="order no">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">visibility</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="visible" id="v0" value="0" checked>
                            <label for="v0">yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visible" id="v1" value="1">
                            <label for="v1">no</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">allow comment</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="comment" id="com0" value="0" checked>
                            <label for="com0">yes</label>
                        </div>
                        <div>
                            <input type="radio" name="comment" id="com1" value="1">
                            <label for="com1">no</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">allow ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="ads" id="ads0" value="0" checked>
                            <label for="ads0">yes</label>
                        </div>
                        <div>
                            <input type="radio" name="ads" id="ads1" value="1">
                            <label for="ads1">no</label>
                        </div>
                    </div>
                </div>
                <!-- end form code  -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10 col-md-2">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>


        <?php
    } elseif ($do == "insert") {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            echo '<h1 class="text-center"> insert category</h1>';
            echo "<div class='container'>";

            // get variable data from the form

            $name         =     $_POST["name"];
            $descripe     =     $_POST["description"];
            $parent       =     $_POST["parent"];
            $order        =     $_POST["ordering"];
            $visible      =     $_POST["visible"];
            $comment      =     $_POST["comment"];
            $ads          =     $_POST["ads"];

            $formErrors = array();

            if (empty($name)) {

                $formErrors[] = 'category name can\'t be empty';
            }

            foreach ($formErrors as $error) {

                echo '<div class="alert alert-ganger">' . $error . '</div>';
            }


            // check if the category is exists or not

            if (empty($formErrors)) {

                $check = checkItem("Name", "categories", $name);

                if ($check == 1) {


                    $themsg = '<div class="alert alert-danger" role="alert">This category is already exists </div>';

                    redirect($themsg, "back", 2);
                } else {


                    // insert category data in the database

                    $stmt = $db->prepare("INSERT INTO categories( Name, Description, Parent, Ordering, Visibility, Allow_Comment, Allow_Ads)

                                            VALUES(:catname, :catdesc, :catparnt, :catorder, :catvisible, :catcomment, :catads) ");

                    $stmt->execute(array(
                        "catname"        => $name,
                        "catdesc"        => $descripe,
                        "catparnt"       => $parent,
                        "catorder"       => $order,
                        "catvisible"     => $visible,
                        "catcomment"     => $comment,
                        "catads"         => $ads
                    ));

                    $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                    redirect($themsg, "back", 2);
                }
            }
            // for secure category name

            $themsg = '<div class="alert alert-danger" role="alert">This category name is already exists </div>';

            redirect($themsg, "back");
        } else {

            echo '<div class="container users-page">';

            // calling redirect function 

            $themsg = '<div class="alert alert-danger" role="alert">sorry you aren\'t allowed to be here directly </div>';

            redirect($themsg, "back", 2);

            echo '</div>';
        }

        echo "</div>";
    } elseif ($do == "edit") {    // edit page

        $categid = isset($_GET["categid"]) && is_numeric($_GET["categid"]) ? intval($_GET["categid"]) : 0;   // if function shortly in one row

        $stmt = $db->prepare("SELECT 
                                  *
                            FROM
                                  categories 
                            WHERE 
                                  ID = ? ");
        $stmt->execute(array($categid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {    ?>
            <!-- start html code  -->

            <div class="container users-page">
                <h1 class="text-center"> edit category </h1>
                <form class="form-horizontal" action="?do=update" method="POST">
                    <input type="hidden" name="categid" value="<?php echo $categid ?>">
                    <!-- start category field name  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" required="required" placeholder="category name" value="<?php echo $cat["Name"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="description of the category" value="<?php echo $cat["Description"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">parent</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent" title="choose main category if it is a parent category">
                                <option value="0">main category</option> <!-- choose main category if it is parent category -->
                                <?php

                                // new global getall function

                                $selectcats = getall("*", "categories", "WHERE Parent = 0", "", "ID");

                                foreach ($selectcats as $selectcat) {

                                    echo "<option value='" . $selectcat["ID"] . "'";

                                    if ($cat["Parent"] == $selectcat["ID"]) {
                                        echo "selected";
                                    }

                                    echo ">" . $selectcat["Name"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="ordering" placeholder="order no" value="<?php echo $cat["Ordering"] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">visibility</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="visible" id="v0" value="0" <?php if ($cat["Visibility"] == 0) {
                                                                                            echo "checked";
                                                                                        } ?>>
                                <label for="v0">yes</label>
                            </div>
                            <div>
                                <input type="radio" name="visible" id="v1" value="1" <?php if ($cat["Visibility"] == 1) {
                                                                                            echo "checked";
                                                                                        } ?>>
                                <label for="v1">no</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">allow comment</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="comment" id="com0" value="0" <?php if ($cat["Allow_Comment"] == 0) {
                                                                                            echo "checked";
                                                                                        } ?>>
                                <label for="com0">yes</label>
                            </div>
                            <div>
                                <input type="radio" name="comment" id="com1" value="1" <?php if ($cat["Allow_Comment"] == 1) {
                                                                                            echo "checked";
                                                                                        } ?>>
                                <label for="com1">no</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">allow ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="ads" id="ads0" value="0" <?php if ($cat["Allow_Ads"] == 0) {
                                                                                        echo "checked";
                                                                                    } ?>>
                                <label for="ads0">yes</label>
                            </div>
                            <div>
                                <input type="radio" name="ads" id="ads1" value="1" <?php if ($cat["Allow_Ads"] == 1) {
                                                                                        echo "checked";
                                                                                    } ?>>
                                <label for="ads1">no</label>
                            </div>
                        </div>
                    </div>
                    <!-- end form code  -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 col-md-2">
                            <input type="submit" value="Update Category" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </form>
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

        echo '<h1 class="text-center"> update profile</h1>';
        echo "<div class='container'>";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id         = $_POST["categid"];
            $name       = $_POST["name"];
            $desc       = $_POST["description"];
            $parent     = $_POST["parent"];
            $order      = $_POST["ordering"];
            $visible    = $_POST["visible"];
            $comment    = $_POST["comment"];
            $ads        = $_POST["ads"];


            $stmt = $db->prepare("UPDATE categories SET Name = ?, Description = ?, Parent = ?, 
                    Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?  WHERE ID = ?");
            $stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads, $id));

            $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

            // calling redirect functon and rhe seconds will be 3s for the default
            redirect($themsg, "back");
        } else {
            // calling redirect functon and rhe seconds will be 3s for the default

            $themsg = '<div class="alert alert-danger" role="alert">sorry u r not allowed here</div>';

            redirect($themsg);
        }

        echo "</div>";
    } elseif ($do == "delete") {

        // delete category from database page

        echo '<h1 class="text-center"> Delete profile</h1>';
        echo "<div class='container'>";

        $categid = isset($_GET["categid"]) && is_numeric($_GET["categid"]) ? intval($_GET["categid"]) : 0;   // if function shortly in one row


        $check = checkItem("ID", "categories", $categid);

        if ($check > 0) {

            $stmt = $db->prepare("DELETE FROM categories WHERE ID = :cid");
            $stmt->bindparam(":cid", $categid);
            $stmt->execute();

            // redirect function
            $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Deleted</div>";
            redirect($themsg, "back");
        } else {

            //  redirect function
            $themsg = '<div class="alert alert-danger" role="alert">not exist member</div>';

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