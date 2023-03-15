<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include_once('connection.php');

//get page number
if (isset($_GET['page_no']) && $_GET['page_no'] !== ""){
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

//total rows
$total_records_per_page = 5;

//get page offset
$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db_demo.employee") or die(mysqli_error($conn));
$records = mysqli_fetch_array($result_count);
$total_records = $records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);

$sql="SELECT * FROM db_demo.employee LIMIT $offset, $total_records_per_page";

$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <div style="margin-left:10px;margin-right:10px;display:flex;flex-direction:row;justify-content:flex-start;align-items:baseline">

    <!-- Preloading -->
    </div>
    <style>
        .wrapper{
            width: 900px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
       
        
    </script>
</head>
<body class="text-bg-success">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        
                        <div class="d-flex align-items-end flex-column">
                        
                        
                        <nav class="navbar">
                        
                        <div class="">
                        <h2 class="d-inline">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                        </nav>
                        <div class="collapse" id="navbarToggleExternalContent">
                            <div class=" p-4">
                            <p class="">
                                <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                                <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
                            </p>
                            </div>
                        </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3 clearfix">
                        <h2 class="pull-left">Employee Details</h2>
                        <!-- <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Employee</a> -->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary pull-right py-2 px-3" data-bs-toggle="modal" data-bs-target="#addEmployee">
                            <i class="fa fa-plus"></i> Add new employee 
                        </button>
                    </div>
                    <?php 
                    require_once "config.php";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo '<table class="bg-white table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Salary</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['salary'] . "</td>";
                                        echo "<td class=''>";        
                                            echo '<div class="d-flex justify-content-around">';
                                            echo '<form action="read.php" method="POST"> <input class="d-none" id="id" name="id" value="'. $row['id'] .'"></input><button type="submit"  title="View Record" data-toggle="tooltip" class="border-0 bg-transparent text-primary"><span class="fa fa-eye"></span></button> </form>';

                                            echo '<form action="edit.php" method="POST"> <input class="d-none" id="id" name="id" value="'. $row['id'] .'"></input><button type="submit"  title="View Record" data-toggle="tooltip" class="border-0 bg-transparent text-primary"><span class="fa fa-pencil"></span></button> </form>';
                                            
                                            echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo '</div>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    
                    $mysqli->close() ?>
                    
                    <div style="display:flex;flex-direction:row;justify-content:space-between;align-items:baseline">
                        <div class="p-10">
                        <strong>Page <?= $page_no; ?> of <?=$total_no_of_pages; ?></strong>
                        </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">

                            <li class="page-item">
                                <a class="page-link <?= ($page_no <=1)? 'disabled': '';?>"<?=($page_no > 1)? 'href=?page_no='.$previous_page : ''; ?>>Previous</a>
                            </li>


                            <?php for($counter = 1; $counter <=$total_no_of_pages; $counter++) {?>


                                <?php if($page_no != $counter) {?>
                                   
                                    <li class="page-item"><a class="page-link" href="?page_no=<?=$counter; ?>"><?= $counter; ?></a></li>
                                <?php } else { ?>
                                    <li class="page-item" ><a class="page-link active">  <?= $counter; ?></a></li>
                                    
                                <?php } ?>
                                <?php } ?>

                            <li class="page-item">
                                <a class="page-link <?= ($page_no >= $total_no_of_pages)? 'disabled': '';?>"<?=($page_no < $total_no_of_pages)? 'href=?page_no='.$next_page : ''; ?>>Next</a>
                            </li>
                        </ul>
                    </nav>
                        
                    </div>
                    

                </div>
            </div>        
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>



<!-- Start Add Employee Modal -->
<!-- PALITAN YUNG ID PARA SA TARGETING NG BUTTON!! -->
<div class="modal fade text-black" id="addEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Modal Heading -->
        <h1 class="modal-title fs-5" id="addEmployeeLabel">Create Record</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Start Modal Body -->
            <div class="row">
                <div class="col-md-12 ">
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <!-- Form Start -->
                    <form action="<?php echo htmlspecialchars("create.php"); ?>" method="post" novalidate class="needs-validation ">

                        <div class="form-group mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                            <span class="invalid-feedback">Name is required!</span>
                        </div>

                        <div class="form-group mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required></textarea>
                            <span class="invalid-feedback">Address is required</span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control " required>
                            <span class="invalid-feedback">Please provide a salary</span>
                        </div>
                        
                        <div class="w-100 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary"></input>    
                        </div>
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        <!-- End Modal Body -->
      </div>
    </div>
  </div>
</div>
<!-- End Add Employee Modal -->

<!-- Start Edit Employee Modal -->
    <div class="edit-modal position-absolute z-2 min-vw-100 min-vh-100 bg-black top-0 opacity-50 d-none">

    </div>
    <div class="edit-modal card text-black w-50 position-absolute top-50 start-50 translate-middle z-3 d-none">
      <div class="card-header">
        View Record
      </div>
      <div class="card-body">
        <!-- Form Start -->
        <form action="<?php echo htmlspecialchars("create.php"); ?>" method="post" novalidate class="needs-validation ">

<div class="form-group mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
    <span class="invalid-feedback">Name is required!</span>
</div>

<div class="form-group mb-3">
    <label>Address</label>
    <textarea name="address" class="form-control" required></textarea>
    <span class="invalid-feedback">Address is required</span>
</div>

<div class="form-group mb-3">
    <label>Salary</label>
    <input type="text" name="salary" class="form-control " required>
    <span class="invalid-feedback">Please provide a salary</span>
</div>

<div class="w-100 d-flex justify-content-end">
    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
    <input type="submit" class="btn btn-primary"></input>    
</div>
</form>
<!-- Form End -->

        <button id="edit-modal-close" class="btn btn-primary">Back</button>
      </div>
    </div>
<!-- End Edit Employee Modal -->

<!-- Start View Employee Modal -->
    <div class="view-modal position-absolute z-2 min-vw-100 min-vh-100 bg-black top-0 opacity-50 d-none">

    </div>
    <div class="view-modal card text-black w-50 position-absolute top-50 start-50 translate-middle z-3 d-none">
      <div class="card-header">
        View Record
      </div>
      <div class="card-body">
        <h5 class="card-title">Name: <span id="name" class="fs-6 fw-normal card-text"></span></h5>
        <h5 class="card-title">Address: <span id="address" class="fs-6 fw-normal card-text">William</span></h5>
        <h5 class="card-title">Salary: <span id="salary" class="fs-6 fw-normal card-text">William</span></h5>

        <button id="view-modal-close" class="btn btn-primary">Back</button>
      </div>
    </div>
<!-- End View Employee Modal -->


<!-- Example starter JavaScript for disabling form submissions if there are invalid fields -->
<script>
const name = localStorage.getItem('name');
const address = localStorage.getItem('address');
const salary = localStorage.getItem('salary');


const nameEl = document.querySelector("#name");
const addressEl = document.querySelector("#address");
const salaryEl = document.querySelector("#salary");
const viewModalCloseBtn = document.querySelector("#view-modal-close");
const viewModal = document.querySelectorAll('.view-modal');
const viewOpen = localStorage.getItem('viewOpen') == 'true';
console.log(localStorage.getItem(viewOpen));

if(viewOpen == true){
viewModal.forEach((el)=>{
    el.classList.remove('d-none');
    el.classList.add('d-block');
    console.log(el.classList);
});
} else {
    viewModal.forEach((el)=>{
    el.classList.remove('d-block');
    el.classList.add('d-none');
    console.log(el.classList);
});
}

viewModalCloseBtn.addEventListener('click', ()=>{
    viewModal.forEach((el)=>{
    el.classList.remove('d-block');
    el.classList.add('d-none');
    console.log(el.classList);
    localStorage.setItem('viewOpen', false);
    console.log(localStorage.getItem('viewOpen'))
});
})

nameEl.innerHTML = name;
addressEl.innerHTML = address;
salaryEl.innerHTML = salary;


(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
</body>
</html>