<?php require_once "db/db.php";?>
<?php require_once "db/queries.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        textarea {
            resize: none;
        }
        .flex-even {
            flex: 1;
        }
        .center-absolute {
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
        }
        .activeColor {
            background:wheat;
        }
        .font-size-16{
            font-size:16px;
        }
        .btnCreateUsers{
            border-radius:8px;
        }
        .crudJumbotron {
            padding:1rem 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid pb-5">
        <div class="row pt-3">
            <div class="col-12">
                <div class="jumbotron crudJumbotron text-center">
                    <h1>Create, read, update and delete</h1>
                    <p class="lead">In computer programming, create, read (aka retrieve), update, and delete (CRUD) are the four basic functions of persistent storage. Alternate words are sometimes used when defining the four basic functions of CRUD, such as retrieve instead of read, modify instead of update, or destroy instead of delete. CRUD is also sometimes used to describe user interface conventions that facilitate viewing, searching, and changing information, often using computer-based forms and reports. </p>
                    <hr class="my-4">
                    <p>The term was likely first popularized by James Martin in his 1983 book Managing the Data-base Environment.[</p>
                    <p class="lead">
                        <a class="btn btn-primary" href="https://en.wikipedia.org/wiki/Create,_read,_update_and_delete" target="_blank" role="button">Learn more</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row pt-5 justify-content-center">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
              <button class="btn btn-info btn-block btnCreateUsers" data-toggle="modal" data-target="#userCreateModal">Create Users</button>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <?php echo showAllUsers();?>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <?php echo deletedUsers();?>
            </div>
        </div>
    </div>
    
<?php echo createUserModal();?>
<?php echo modalDetails();?>

<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="js/plugins/sweetalert.js"></script>
<script src="js/plugins/autosize.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

