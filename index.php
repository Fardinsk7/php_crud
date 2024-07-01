<?php
include 'Header.php';
include 'connection.php';


$total_page = 0;
$start = 0;
$per_page = 5;
if (isset($_GET['page-no'])) {
  $pagegot = $_GET['page-no'] - 1;
  $start = $pagegot * $per_page;
}
$sql = "SELECT u.user_id, u.name, u.email, u.gender, u.mobile, e.num_companies, e.num_years, e.num_months from users u join experience e where u.user_id = e.user_id LIMIT $start, $per_page";
$sql2 = "SELECT u.user_id, u.name, u.email, u.gender, u.mobile, e.num_companies, e.num_years, e.num_months from users u join experience e where u.user_id = e.user_id";

$result = $conn->query($sql);
$record = $conn->query($sql2)->num_rows;
$total_page = ceil($record / $per_page);



?>



<div class="container mt-5" id="main">
  <h1 class="mb-4">User Information</h1>
  <form class="row g-3" id="userForm" method="post">
    <div class="col-md-6">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="col-md-6">
      <label for="email" class="form-label">Email</label>
      <input type="text" class="form-control" id="email" name="email">
    </div>
    <div class="col-md-6">
      <label for="mobile" class="form-label">Mobile (Unique)</label>
      <input type="text" class="form-control" id="mobile" name="mobile">
    </div>
    <div class="col-md-6">
      <label for="gender" class="form-label">Gender</label>
      <select class="form-select" id="gender" name="gender">
        <option value="">Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>

    <!-- Experience Section -->
    <div class="col-md-12 mt-4">
      <h2>Experience</h2>
    </div>
    <div class="col-md-4">
      <label for="company" class="form-label">Companies Served</label>
      <input type="number" class="form-control" id="company" name="company[]">
    </div>
    <div class="col-md-4">
      <label for="years" class="form-label">No of Years</label>
      <input type="number" class="form-control" id="years" name="years[]">
    </div>
    <div class="col-md-4">
      <label for="months" class="form-label">No of Months</label>
      <input type="number" class="form-control" id="months" name="months[]">
    </div>

    <div class="col-12 mt-4">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>


<!-- Table -->
<div class="container">
  <h2 class="mt-4 mb-4">User Details</h2>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile No</th>
          <th>Gender</th>
          <th>Total Company Served</th>
          <th>Total Exp</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $totalCompanies = $row["num_years"] . " years" . ", " . $row["num_months"] . " months";
            echo '<tr>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['mobile']) . '</td>
                <td>' . htmlspecialchars($row['gender']) . '</td>
                <td>' . htmlspecialchars($row['num_companies']) . '</td>
                <td>' . htmlspecialchars($totalCompanies) . '</td>
                <td>
                  <button class="btn btn-sm btn-primary m-2 editBtn" data-bs-toggle="modal" data-bs-target="#editModal1" data-user-id-edit="' . htmlspecialchars($row['user_id']) . '">Edit</button>
                  <button class="btn btn-sm btn-danger deleteBtn" data-user-id="' . htmlspecialchars($row['user_id']) . '">Delete</button>
                </td>
              </tr>';
          }
        } else {
          echo "<tr><td colspan='7'> No records found</td><tr>";
        }
        ?>




      </tbody>
    </table>
  </div>

  <!-- Pagination Section -->
  <nav aria-label="...">
    <h6> Showing 1 of <?php
                      if (!isset($_GET['page-no'])) {
                        echo $total_page;
                      } else {
                        echo $_GET['page-no'];
                      }
                      ?></h6>
    <ul class="pagination">

      <?php
      if (isset($_GET['page-no']) && $_GET['page-no'] > 1) {

      ?>
        <li class="page-item ">
          <a class="page-link" href="?page-no=<?php echo $_GET['page-no'] - 1; ?>">Previous</a>
        </li>

      <?php } else { ?>

        <li class="page-item ">
          <a class="page-link disabled">Previous</a>
        </li>
      <?php
      }
      ?>
      <?php
      for ($i = 1; $i <= $total_page; $i++) { ?>
        <li class="page-item <?php if (isset($_GET['page-no']) && $_GET['page-no'] == $i) {
                                echo "active";
                              } else if (!isset($_GET['page-no']) && $i == 1) {
                                echo "active";
                              } else {
                                echo "";
                              } ?> "><a class="page-link" href="?page-no=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
      <?php  } ?>

      <?php

      if (!isset($_GET['page-no'])) {

      ?>
        <li class="page-item ">
          <a class="page-link" href="?page-no=2">Next</a>
        </li>
      <?php

      } else {
      ?>
        <?php if (isset($_GET['page-no']) && $_GET['page-no'] >= $total_page) {

        ?>
          <a class="page-link disabled">Next</a>
        <?php
        } else { ?>
          <li class="page-item ">
            <a class="page-link" href="?page-no=<?php echo $_GET['page-no'] + 1; ?>">Next</a>
          </li>
        <?php
        } ?>
      <?php
      }
      ?>

    </ul>
  </nav>
</div>

<!-- Edit Modals (hidden by default) -->
<div class="modal fade" id="editModal1" tabindex="-1" aria-labelledby="editModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel1">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="row g-3" id="editform" method="post">
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="Editname" name="name">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="Editemail" name="email">
          </div>
          <div class="col-md-6">
            <label for="mobile" class="form-label">Mobile </label>
            <input type="text" class="form-control" id="Editmobile" name="mobile">
          </div>
          <div class="col-md-6">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="Editgender" name="gender">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Pop up Experience Section -->
          <div class="col-md-12 mt-4">
            <h2>Experience</h2>
          </div>
          <div class="col-md-4">
            <label for="company" class="form-label">Companies Served</label>
            <input type="number" class="form-control" id="Editcompany" name="company[]">
          </div>
          <div class="col-md-4">
            <label for="years" class="form-label">No of Years</label>
            <input type="number" class="form-control" id="Edityears" name="years[]">
          </div>
          <div class="col-md-4">
            <label for="months" class="form-label">No of Months</label>
            <input type="number" value="0" class="form-control" id="Editmonths" name="months[]">
          </div>

          <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary">Update Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--  -->

</div>
</div>
</div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="script.js"></script>

<?php
include 'Footer.php';
?>