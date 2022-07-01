<nav class="navbar navbar-expand-md navbar-dark">
    <div class="container">
        <!-- Brand -->
        <li class="nav-item list-unstyled">
            <!-- <a class="navbar-brand nav-link logo" href="dashboard.php">MyStore</a> -->
            <a class="navbar-brand nav-link logo" href="../index.php" target="_blank">MyStore</a>
        </li>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><?php echo language("main-page") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php"><?php echo language("sections") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php"><?php echo language("items") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php"><?php echo language("members") ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comments.php"><?php echo language("comments") ?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Ahmed</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../index.php" target="_blank"><?php echo language("to-store") ?></a></li>
                        <li><a class="dropdown-item" href="users.php?do=edit&userid=<?php echo $_SESSION["ID"] ?>"><?php echo language("edit-data") ?></a></li>
                        <li><a class="dropdown-item" href="users.php?do=edit&userid=<?php echo $_SESSION["ID"] ?>"><?php echo language("changes") ?></a></li>
                        <li><a class="dropdown-item" href="logout.php"><?php echo language("exit") ?></a></li>
                        <li class="dropdown-item"> Dark Mode 
                            <span id="main">
                                <label class="switch nav-link">
                                    <input type="checkbox" onclick="darkLight()" id="checkBox">
                                    <span class="slider"></span>
                                </label>
                            </span>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>