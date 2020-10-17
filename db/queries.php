<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch($_POST["action"]) {
        case "deleteUser":
            deleteUser();
        break;
        case "acticvateUser":
            activateUser();
        break;
        case "editUser":
            editUser();
        break;
        case "createUser":
            createUser();
        break;
    }
}

function createUser() {
    global $dbh;

    parse_str($_POST['userDetails'], $userarray);
    print_r($userarray);

    $fname = $userarray['fNameUserCreate'];
    $lname = $userarray['lNameUserCreate'];
    $email = $userarray['userEmailCreate'];
    $phone = $userarray['phoneUserCreate'];
    $address = $userarray['userAddressCreate'];
    $comments = $userarray['userCommentsCreate'];
    $active = 1;
  
    $sql = "INSERT INTO users (fname, lname, email, phone, address, comments, active, date)
             VALUES (:fname, :lname, :email, :phone, :address, :comments, :active, NOW())";

    $stmt= $dbh->prepare($sql);
    $stmt->bindParam(':fname', $fname);    
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);    
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);    
    $stmt->bindParam(':comments', $comments);
    $stmt->bindParam(':active', $active);
    $stmt->execute(); 

    print("Inserted $sql rows.\n");
}

function editUser() {
    global $dbh;

    parse_str($_POST['userDetails'], $searcharray);
    
    $fname = $searcharray['fNameUser'];
    $lname = $searcharray['lNameUser'];
    $email = $searcharray['userEmail_'];
    $phone = $searcharray['phoneUser'];
    $address = $searcharray['userAddress'];
    $comments = $searcharray['userComments'];
    $date = $searcharray['registerDate'];
    $id = $searcharray['param'];

    $sql = "UPDATE users
            SET fname = :fname,
                lname = :lname,
                email = :email,
                phone = :phone,
                address = :address,
                comments = :comments,
                date = :date
            WHERE `id` = :id
            LIMIT 1 ";

    $stmt = $dbh->prepare($sql);  
    //where clause                                 
    $stmt->bindParam(':id', $id);  
    //add vars to db      
    $stmt->bindParam(':fname', $fname);    
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);    
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);    
    $stmt->bindParam(':comments', $comments);
    $stmt->bindParam(':date', $date);
    $stmt->execute(); 

    /* Return number of rows that were effected */
    print("Updated $sql rows.\n");
}

function deleteUser() {
    global $dbh;
    $id = $_POST["userId"];
    $active=0;
   
    $count = $dbh->exec("UPDATE users
            SET active = $active
            WHERE `id` = $id
            LIMIT 1 ");

    /* Return number of rows that were deleted */
    print("Updated $count rows.\n");
}

function activateUser() {
    global $dbh;
    $id = $_POST["userId"];
    $active=1;
   
    $count = $dbh->exec("UPDATE users
            SET active = $active
            WHERE `id` = $id
            LIMIT 1 ");

    /* Return number of rows that were deleted */
    print("Updated $count rows.\n");
}

function deletedUsers() {
    global $dbh;
    $notActiveRows = intval($dbh->query('SELECT count(active) FROM users WHERE active=0')->fetchColumn());
    echo " <h3 class=\"text-center text-danger\"><span class=\"text-muted font-size-16\">Edit/Activate </span>Deleted Users</h3>";
    if ($notActiveRows > 0) {
        echo "<ul id='deletedUsersList' class='list-group'>";
        foreach($row = $dbh->query('SELECT * from users') as $row) {
            if ($row['active'] == "0") {
                echo "
                <li class=\"users-list-group-item list-group-item d-flex flex-wrap align-items-center justify-content-between\" data-toggle=\"modal\" data-target=\"#userDetailsModal{$row['id']}\" style=\"cursor:pointer;\">
                    <span class=\"mr-4\">{$row['id']}</span>
                    <span class=\"mr-1 flex-even\">{$row['fname']} {$row['lname']}</span>
                    <span class=\"mr-2 badge badge-pill badge-success flex-even\">{$row['email']}</span>
                    <span class=\"text-muted d-block ml-5 flex-even\">{$row['date']}</span>
                    <button data-toggle=\"tooltip\" data-placement=\"top\" title=\"activate user\" type=\"button\" class=\"btn btn-primary btn-sm align-self-start text-white ml-4 activateBtn\" id=\"activateUser{$row['id']}\" data-id=\"{$row['id']}\" name=\"btn-activate\">&uArr;</button>
                </li>";
            }
        }
        echo "</ul>";
    } else {
        echo '<p class=\'text-dark text-center lead\'>There are no deleted Users...</p>';
    }



}

function showAllUsers() {
    global $dbh;

    $totalRows = intval($dbh->query('select count(*) from users')->fetchColumn()); 
    $notActiveRows = intval($dbh->query('SELECT count(active) FROM users WHERE active=0')->fetchColumn());
   
    if ($totalRows !== $notActiveRows) {
        
        echo " <h3 class=\"text-center text-danger\"><span class=\"text-muted font-size-16\">Edit/Delete</span> Users</h3>
                <ul id='showUsersList' class='list-group'>";
            foreach($row = $dbh->query('SELECT * from users') as $row) {
                if ($row['active'] !== "0") {
                    echo "
                    <li class=\"users-list-group-item list-group-item d-flex flex-wrap align-items-center justify-content-between\" data-toggle=\"modal\" data-target=\"#userDetailsModal{$row['id']}\" style=\"cursor:pointer;\">
                        <span class=\"mr-4\">{$row['id']}</span>
                        <span class=\"mr-1 flex-even\">{$row['fname']} {$row['lname']}</span>
                        <span class=\"mr-2 badge badge-pill badge-success flex-even\">{$row['email']}</span>
                        <span class=\"text-muted d-block ml-5 flex-even\">{$row['date']}</span>
                        <button data-toggle=\"tooltip\" data-placement=\"top\" title=\"delete user\" type=\"button\" class=\"btn btn-danger btn-sm align-self-start text-white ml-4 deleteBtn\" id=\"deleteUser{$row['id']}\" data-id=\"{$row['id']}\" name=\"btn-delete\">&#10005</button>
                    </li>";
                }
            }
        echo "</ul>";
    } else {
        echo '
        <div class="alert alert-success text-center mt-4" role="alert">
            <h2 class="alert-heading mb-4">There are no contacts left</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia unde perferendis suscipit fuga facere, libero sit quisquam deserunt voluptate exercitationem iure neque, fugit voluptatum odio minus quibusdam iusto aspernatur totam omnis. Quas nam quod esse laborum ut maiores repellendus corrupti reiciendis in provident fuga vero id praesentium velit, temporibus eum..</p>
            <hr>
            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia unde perferendis suscipit fuga facere.</p>
        </div>';
    }
}

function modalDetails() {
    global $dbh;
    foreach($row = $dbh->query('SELECT * from users') as $row) {
        $id=$row['id'];
        $fname = !in_array($row['fname'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['fname'] : $row['fname'] = " - ";
        $lname = !in_array($row['lname'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['lname'] : $row['lname'] = " - ";
        $email = !in_array($row['email'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['email'] : $row['email'] = " - ";
        $phone = !in_array($row['phone'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['phone'] : $row['phone'] = " - ";
        $address = !in_array($row['address'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['address'] : $row['address'] = " - ";
        $comments = !in_array($row['comments'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['comments'] : $row['comments'] = " - ";
        $date = !in_array($row['date'], ['', ' ','undefined',null,false, 'false', 'null']) ? $row['date'] : $row['date'] = " - ";

        echo "
        <div class='modal fade' id='userDetailsModal{$id}' tabindex='-1' aria-labelledby='userDetailsModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='userDetailsModalLabel'>Full User Details</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class='container-fluid position-relative'>
                            <div class='row'>
                                <div class='col-12'>
                                    <form class='pb-4 formInvisible invisible'>
                                        <div class='row'>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='fNameUser'>First Name</label>
                                                <input type='text' class='form-control' id='fNameUser{$id}' name='fNameUser' value='$fname' disabled>
                                            </div>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='lNameUser'>Last Name</label>
                                                <input type='text' class='form-control' id='lNameUser{$id}' name='lNameUser' value='$lname' disabled>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='userEmail'>Email</label>
                                                <input type='email' class='form-control' id='userEmail{$id}' name='userEmail 'aria-describedby='emailHelp' disabled value='$email'>
                                            </div>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='phoneUser'>Phone</label>
                                                <input type='number' class='form-control' id='phoneUser{$id}' name='phoneUser' value='$phone' disabled>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='userAddress'>Address</label>
                                                <input type='text' class='form-control' id='userAddress{$id}' name='userAddress' disabled value='$address'>
                                            </div>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='registerDate'>Register Date</label>
                                                <input type='text' class='form-control' id='registerDate{$id}' name='registerDate' value='$date' disabled>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-sm-12'>
                                                <label for='userComments'>Comments</label>
                                                <textarea type='text' class='form-control' id='userComments{$id}' name='userComments' disabled>$comments</textarea>
                                            </div>
                                        </div>
                                    </form>
                                    <div class='loaderModal position-absolute center-absolute'>
                                        <div class=\"d-flex justify-content-center\">
                                            <div class=\"spinner-border\" role=\"status\">
                                                <span class=\"sr-only\">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' data-id={$id} id='editBtn{$id}' class='btn btn-info editBtn'>Edit</button>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div></div>
        ";
    }
}

function createUserModal() { 
        echo "
        <div class='modal fade' id='userCreateModal' tabindex='-1' aria-labelledby='userCreateModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='userCreateModalLabel'>Create User</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class='container-fluid position-relative'>
                            <div class='row'>
                                <div class='col-12'>
                                    <div id=\"userCreatedAlert\" class=\"alert alert-success alert-dismissible fade show d-none\" role=\"alert\">
                                        <strong>Well done!</strong> User has been created
                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                        <span aria-hidden=\"true\">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class='col-12'>
                                    <form class='pb-4 formInvisible invisible'>
                                        <ul id=\"displayErrors\"></ul>
                                        <div class='row'>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='fNameUserCreate'>First Name</label>
                                                <input type='text' class='form-control' id='fNameUserCreate' name='fNameUserCreate'>
                                            </div>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='lNameUserCreate'>Last Name</label>
                                                <input type='text' class='form-control' id='lNameUserCreate' name='lNameUserCreate'>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='userEmailCreate'>Email</label>
                                                <input type='email' class='form-control' id='userEmailCreate' name='userEmailCreate' aria-describedby='emailHelp'>
                                            </div>
                                            <div class='col-md-6 col-sm-12'>
                                                <label for='phoneUserCreate'>Phone</label>
                                                <input type='number' class='form-control' id='phoneUserCreate' name='phoneUserCreate'>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-md-12 col-sm-12'>
                                                <label for='userAddressCreate'>Address</label>
                                                <input type='text' class='form-control' id='userAddressCreate' name='userAddressCreate'>
                                            </div>
                                        </div>
                                        <div class='row pt-3'>
                                            <div class='col-sm-12'>
                                                <label for='userCommentsCreate'>Comments</label>
                                                <textarea type='text' class='form-control' id='userCommentsCreate' name='userCommentsCreate'></textarea>
                                            </div>
                                        </div>
                                    </form>
                                    <div class='loaderModal position-absolute center-absolute'>
                                        <div class=\"d-flex justify-content-center\">
                                            <div class=\"spinner-border\" role=\"status\">
                                                <span class=\"sr-only\">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' name='reset-user-btn' class='btn btn-warning ResetBtn'>Reset</button>
                        <button type='submit' name='create-user-btn' class='btn btn-info saveBtn'>Save</button>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div></div>
        ";
}

?>